<template>
  <v-row class="pr-5 pl-5 pt-1" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12">
      <v-row no-gutters justify="center">
        <v-col cols="12">
          <v-card elevation="10" rounded="xl">
            <v-card-title class="primary"
              ><v-row no-gutters justify="space-between"
                ><v-col cols="5"> Stations </v-col
                ><v-col cols="1" class="d-flex justify-content-end"
                  ><SettingPannel
                    v-if="$can('view_coord_sheet')"
                  ></SettingPannel></v-col></v-row
            ></v-card-title>
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
                <template v-slot:[`header.system.webway[0].jumps`]="{ props }">
                  <v-row no-gutters>
                    <v-col>
                      <span class="myFont">Webway</span>
                    </v-col></v-row
                  >
                  <v-row no-gutters>
                    <v-col>
                      <v-menu
                        v-model="menu"
                        :close-on-content-click="false"
                        offset-y
                        :transition="false"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn text v-bind="attrs" v-on="on" x-small>
                            <span class="myFontSmall">{{
                              webwaySelect.text
                            }}</span>
                          </v-btn>
                        </template>

                        <v-card> this is a test </v-card>
                      </v-menu>
                    </v-col>
                  </v-row>
                </template>
                <template v-slot:[`item.system.webway[0].jumps`]="{ item }">
                  <SoloCampaginWebWay
                    v-if="item.system.webway[0]"
                    :jumps="item.system.webway[0].jumps"
                    :web="item.system.webway[0].webway"
                  ></SoloCampaginWebWay>
                </template>
                <template
                  v-slot:[`item.corp.ticker`]="{ item }"
                  class="d-inline-flex align-center"
                >
                  <span>
                    <v-avatar size="35"><img :src="item.corp.url" /></v-avatar>
                    <span :class="standingCheckCorp(item)"
                      >{{ item.corp.ticker }}
                    </span>
                  </span></template
                >
                <template
                  v-slot:[`item.corp.alliance.ticker`]="{ item }"
                  class="d-inline-flex align-center"
                >
                  <span v-if="item.corp.alliance">
                    <v-avatar size="35"
                      ><img :src="item.corp.alliance.url"
                    /></v-avatar>
                    <span :class="standingCheck(item)"
                      >{{ item.corp.alliance.ticker }}
                    </span>
                  </span>

                  <!-- <span v-else-if="$can('super')">
                    <AddCorpTicker :station="item"></AddCorpTicker
                    ><AddAllianceTicker :station="item"></AddAllianceTicker>
                  </span> -->
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
                  <StatusButton
                    v-if="$can('add_timer')"
                    :item="item"
                  ></StatusButton>
                  <v-chip v-else pill :color="pillColor(item)">
                    {{ buttontext(item) }}
                  </v-chip>
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                  <div class="d-inline-flex">
                    <div>
                      <AddTimerFromStationSheet
                        class="mr-2"
                        :item="item"
                      ></AddTimerFromStationSheet>
                    </div>
                    <div>
                      <RcStationMessage
                        class="mr-2"
                        :station="item"
                        :type="4"
                      ></RcStationMessage>
                    </div>
                    <div>
                      <StationSheetInfo
                        :station="item"
                        v-if="showInfo(item)"
                      ></StationSheetInfo>
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
                    <StationSheetLogs :station="item"></StationSheetLogs>
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
    await this.$store.dispatch("getWebwayStartSystems");
    await this.$store.dispatch("getStationList").then((this.loadingt = false));
    Echo.private("stationsheet")
      .listen("StationSheetUpdate", (e) => {
        if (e.flag.message != null) {
          this.$store.dispatch("updateStationList", e.flag.message);
        }

        if (e.flag.flag == 1) {
          this.$store.dispatch("getStationRegionLists");
        }

        if (e.flag.flag == 2) {
        }
        this.$store.dispatch("getStationList");
        if (e.flag.flag == 3) {
        }
      })
      .listen("StationDeadStationSheet", (e) => {
        this.$store.dispatch("deleteStationSheetNotification", e.flag.id);
      });
  },
  async mounted() {
    this.onResize();
    await this.$store.dispatch("getStationRegionLists");
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
      search: "",
      menu: false,
      webwaySelect: {
        text: "1DQ1-A",
        value: 30004759,
      },

      headers: [
        {
          text: "Webway",
          value: "system.webway[0].jumps",
          sortable: false,
        },
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
          text: "Corp",
          value: "corp.ticker",
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
          text: "Actions",
          value: "actions",
        },
      ],
    };
  },

  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    buttontext(item) {
      var ret = item.status.name.replace("Upcoming - ", "");
      return ret;
    },

    pillColor(item) {
      if (item.status.id == 4) {
        return "orange darken-1";
      }
      if (item.status.id == 18) {
        return "brown lighten-2";
      }
      if (item.status.id == 16) {
        return "green";
      }
      if (item.status.id == 7) {
        return "red";
      }
    },

    icons(item) {
      if (item.status.id == 4) {
        return "faSvg fa-check-circle";
      }
      if (item.status.id == 18) {
        return "faSvg fa-question-circle";
      }
      if (item.status.id == 16) {
        return "faSvg fa-exclamation-triangle";
      }
      if (item.status.id == 7) {
        return "faSvg fa-skull-crossbones";
      }
    },

    Systemcopied() {
      this.snack = true;
      this.snackColor = "success";
      this.snackText = "System Copied";
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

    standingCheckCorp(item) {
      var standing = 0;
      if (item.corp) {
        standing = item.corp.standing;
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

    showInfo(item) {
      if (
        item.item.id == 37534 ||
        item.item.id == 35841 ||
        item.item.id == 35840
      ) {
        return false;
      }
      if (item.added_from_recon == 1 && this.loadingt == false) {
        return true;
      } else {
        return false;
      }
    },

    link(item) {
      if (item.system.region.region_name == "Black Rise") {
        return (
          "https://evemaps.dotlan.net/map/Black_Rise/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Bleak Lands") {
        return (
          "https://evemaps.dotlan.net/map/The_Bleak_Lands/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Citadel") {
        return (
          "https://evemaps.dotlan.net/map/The_Citadel/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Cloud Ring") {
        return (
          "https://evemaps.dotlan.net/map/Cloud_Ring/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Cobalt Edge") {
        return (
          "https://evemaps.dotlan.net/map/Cobalt_Edge/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Etherium Reach") {
        return (
          "https://evemaps.dotlan.net/map/Etherium_Reach/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Forge") {
        return (
          "https://evemaps.dotlan.net/map/The_Forge/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Kalevala Expanse") {
        return (
          "https://evemaps.dotlan.net/map/The_Kalevala_Expanse/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Molden Heath") {
        return (
          "https://evemaps.dotlan.net/map/Molden_Heath/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Outer Passage") {
        return (
          "https://evemaps.dotlan.net/map/Outer_Passage/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Outer Ring") {
        return (
          "https://evemaps.dotlan.net/map/Outer_Ring/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Paragon Soul") {
        return (
          "https://evemaps.dotlan.net/map/Paragon_Soul/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Period Basis") {
        return (
          "https://evemaps.dotlan.net/map/Period_Basis/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Perrigen Falls") {
        return (
          "https://evemaps.dotlan.net/map/Perrigen_Falls/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Pure Blind") {
        return (
          "https://evemaps.dotlan.net/map/Pure_Blind/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Scalding Pass") {
        return (
          "https://evemaps.dotlan.net/map/Scalding_Pass/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Sinq Laison") {
        return (
          "https://evemaps.dotlan.net/map/Sinq_Laison/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "The Spire") {
        return (
          "https://evemaps.dotlan.net/map/The_Spire/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Vale of the Silent") {
        return (
          "https://evemaps.dotlan.net/map/Vale_of_the_Silent/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Verge Vendor") {
        return (
          "https://evemaps.dotlan.net/map/Verge_Vendor/" +
          item.system.system_name +
          "#const"
        );
      }
      if (item.system.region.region_name == "Wicked Creek") {
        return (
          "https://evemaps.dotlan.net/map/Wicked_Creek/" +
          item.system.system_name +
          "#const"
        );
      }
      return (
        "https://evemaps.dotlan.net/map/" +
        item.system.region.region_name +
        "/" +
        item.system.system_name +
        "#const"
      );
    },
  },

  computed: {
    ...mapState(["stationList"]),

    filter_end() {
      return this.stationList.filter(
        (f) => f.show_on_coord == 1 && f.standing <= 0
      );
    },

    height() {
      let num = this.windowSize.y - 370;
      return num;
    },
  },
  beforeDestroy() {
    Echo.leave("stationsheet");
  },
};
</script>
<style>
.myFont {
  font-family: "Roboto", sans-serif;
  font-size: 12px;
  text-transform: none;
  font-weight: 700;
  letter-spacing: 0.1px;
  line-height: 18px;
  tab-size: 4;
  color: rgba(255, 255, 255, 0.7);
}
.myFontSmall {
  font-family: "Roboto", sans-serif;
  font-size: 9px;
  text-transform: none;
  font-weight: 700;
  letter-spacing: 0.1px;
  line-height: 18px;
  tab-size: 4;
  color: rgba(255, 255, 255, 0.7);
}
</style>
