<template>
  <v-row class="pr-5 pl-5 pt-1" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="11">
          <v-card elevation="10" rounded="xl">
            <v-card-title class="primary">Stations</v-card-title>
            <v-card-text>
              <v-data-table
                :headers="headers"
                :items="filter_end"
                id="table"
                item-key="id"
                :height="height"
                fixed-header
                :expanded.sync="expanded"
                :loading="loadingt"
                :items-per-page="50"
                :footer-props="{
                  'items-per-page-options': [10, 20, 30, 50, 100, -1],
                }"
                :search="search"
                :sort-by="['system.region.region_name']"
                :sort-desc="[false, true]"
                multi-sort
              >
                <template slot="no-data">
                  All Hostile Stations our reffed!!!!!!
                </template>

                <template
                  v-slot:[`item.corp.alliance.ticker`]="{ item }"
                  class="d-inline-flex align-center"
                >
                  <span v-if="item.corp.alliance.ticker.url">
                    <v-avatar size="35"
                      ><img :src="item.corp.alliance.ticker.url"
                    /></v-avatar>
                    <span :class="standingCheck(item)"
                      >{{ item.corp.alliance.ticker }}
                    </span>
                  </span>
                  <span v-else-if="$can('super')">
                    <AddCorpTicker :station="item"></AddCorpTicker
                    ><AddAllianceTicker :station="item"></AddAllianceTicker>
                  </span>
                </template>
                <template
                  v-slot:[`item.system.system_name`]="{ item }"
                  class="d-inline-flex align-center"
                >
                  <v-btn :href="link(item)" target="_blank" icon color="green">
                    <v-icon> fas fa-map fa-xs</v-icon>
                  </v-btn>
                  <button
                    v-clipboard="item.system.system_name"
                    v-clipboard:success="Systemcopied"
                  >
                    {{ item.system.system_name }}
                  </button>
                </template>

                <template v-slot:[`item.status.name`]="{ item }">
                  <DoneButtonCoord :item="item"></DoneButtonCoord>
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                  <div class="d-inline-flex">
                    <RcStationMessage
                      class="mr-2"
                      :station="item"
                    ></RcStationMessage>
                    <div>
                      <Info :station="item" v-if="showInfo(item)"></Info>
                    </div>

                    <div v-if="$can('view_station_logs')">
                      <v-btn
                        @click="(expanded = [item]), (expanded_id = item.id)"
                        v-show="!expanded.includes(item)"
                        icon
                        class="pb-3"
                        color="blue"
                      >
                        <v-icon> faSvg fa-history</v-icon>
                      </v-btn>
                      <v-btn
                        @click="(expanded = []), (expanded_id = 0)"
                        v-show="expanded.includes(item)"
                        icon
                        class="pb-3"
                        color="error"
                      >
                        <v-icon> faSvg fa-history</v-icon>
                      </v-btn>
                    </div>
                  </div>
                </template>
                <template
                  v-slot:expanded-item="{ headers, item }"
                  inset
                  class="align-center"
                  height="100%"
                >
                  <td :colspan="headers.length" align="center">
                    <StationLogs :station="item"></StationLogs>
                  </td>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
import ApiL from "../service/apil";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  async created() {
    await this.$store.dispatch("getStationList").then((this.loadingt = true));
  },
  async mounted() {
    this.onResize();
  },
  title() {
    return `EveStuff - Stations`;
  },
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },

      expanded: [],
      expanded_id: 0,
      loadingt: true,

      headers: [
        {
          text: "System",
          value: "system.system_name",
        },
        {
          text: "Constellation",
          value: "system.constellation.constellation_name",
        },
        {
          text: "Region",
          value: "system.region.region_name",
        },
        {
          text: "Alliance",
          value: "corp.alliance.ticker",
        },
        {
          text: "Type",
          value: "item.item_name",
        },
        {
          text: "Name",
          value: "name",
        },
        {
          text: "Status",
          value: "status.name",
          align: "center",
        },
        {
          text: "",
          value: "actions",
        },
      ],
    };
  },

  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    standingCheck(item) {
      var standing = 0;
      if (item.corp.alliance) {
        standing = item.corp.alliance.standing;
      } else {
        standing = item.corp.standing;
      }
      if (standing > 0) {
        return "blue--text pl-3";
      } else if (standing < 0) {
        return "red--text pl-3";
      } else {
        return "white--text pl-3";
      }
    },
  },

  computed: {
    ...mapState(["stationList"]),

    filter_end() {
      return this.stationList;
    },

    height() {
      let num = this.windowSize.y - 370;
      return num;
    },
  },
  beforeDestroy() {},
};
</script>
