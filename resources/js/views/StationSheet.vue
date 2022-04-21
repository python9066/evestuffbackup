<template>
  <v-row class="pr-5 pl-5 pt-1" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="11">
          <v-card>
            <v-card-title>Stations</v-card-title>
            <v-card-text>
              <v-data-table
                :headers="headers"
                :items="filter_end"
                :item-class="itemRowBackground"
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
                :sort-by="['region_name']"
                :sort-desc="[false, true]"
                multi-sort
                class="elevation-5"
              >
                <template slot="no-data">
                  All Hostile Stations our reffed!!!!!!
                </template>

                <template
                  v-slot:[`item.alliance_ticker`]="{ item }"
                  class="d-inline-flex align-center"
                >
                  <span v-if="item.url">
                    <v-avatar size="35"><img :src="item.url" /></v-avatar>
                    <span :class="standingCheck(item)"
                      >{{ item.alliance_ticker }}
                    </span>
                  </span>
                  <span v-else-if="$can('super')">
                    <AddCorpTicker :station="item"></AddCorpTicker
                    ><AddAllianceTicker :station="item"></AddAllianceTicker>
                  </span>
                </template>
                <template
                  v-slot:[`item.system_name`]="{ item }"
                  class="d-inline-flex align-center"
                >
                  <v-btn :href="link(item)" target="_blank" icon color="green">
                    <v-icon> fas fa-map fa-xs</v-icon>
                  </v-btn>
                  <button
                    v-clipboard="item.system_name"
                    v-clipboard:success="Systemcopied"
                  >
                    {{ item.system_name }}
                  </button>
                </template>

                <template v-slot:[`item.station_status_name`]="{ item }">
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
    await this.$store.dispatch("getStationList");
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

      headers: [
        {
          text: "System",
          value: "system_name",
        },
        {
          text: "Constellation",
          value: "constellation_name",
        },
        {
          text: "Region",
          value: "region_name",
        },
        {
          text: "Alliance",
          value: "alliance_ticker",
        },
        {
          text: "Type",
          value: "item_name",
        },
        {
          text: "Name",
          value: "station_name",
        },
        {
          text: "Status",
          value: "station_status_name",
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
  },

  computed: {
    ...mapState(["stationList"]),
  },
  beforeDestroy() {},
};
</script>
