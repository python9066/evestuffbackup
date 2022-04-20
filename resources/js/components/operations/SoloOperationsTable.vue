<template>
  <v-col cols="12" class="pa-0">
    <v-row no-gutters>
      <v-col cols="12">
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          fixed-header
          :height="height"
          item-key="id"
          :items-per-page="50"
          :footer-props="{
            'items-per-page-options': [10, 20, 30, 50, 100, -1],
          }"
          class="elevation-24 rounded-xl full-width"
        >
          <template slot="no-data"> No Active or Upcoming Campaigns </template>
          <template v-slot:[`item.campaign[0].alliance.name`]="{ item }">
            <v-avatar size="35"
              ><img :src="item.campaign[0].alliance.url"
            /></v-avatar>
            <span
              v-if="item.campaign[0].alliance.standing > 0"
              class="blue--text pl-3"
              >{{ item.campaign[0].alliance.name }}
            </span>
            <span
              v-else-if="item.campaign[0].alliance.standing < 0"
              class="red--text pl-3"
              >{{ item.campaign[0].alliance.name }}
            </span>
            <span v-else class="pl-3">{{
              item.campaign[0].alliance.name
            }}</span>
          </template>

          <template v-slot:[`item.campaign[0].start_time`]="{ item }">
            <span v-if="item.campaign[0].status_id == 1">
              {{ item.campaign[0].start_time }}
            </span>
            <span
              v-else-if="
                item.campaign[0].status_id != 3 &&
                item.campaign[0].status_id != 4
              "
              class="d-flex full-width align-content-center"
            >
              <span>
                <v-icon
                  v-if="
                    item.campaign[0].defenders_score >
                      item.campaign[0].defenders_score_old &&
                    item.campaign[0].defenders_score_old > 0
                  "
                  small
                  left
                  dark
                  color="blue darken-4"
                >
                  fas fa-arrow-alt-circle-up
                </v-icon>
                <v-icon
                  v-if="
                    item.campaign[0].defenders_score <
                      item.campaign[0].defenders_score_old &&
                    item.campaign[0].defenders_score_old > 0
                  "
                  small
                  left
                  dark
                  color="blue darken-4"
                >
                  fas fa-arrow-alt-circle-down
                </v-icon>
                <v-icon
                  v-if="
                    item.campaign[0].defenders_score ==
                      item.campaign[0].defenders_score_old ||
                    item.campaign[0].defenders_score_old === null
                  "
                  small
                  left
                  dark
                  color="grey darken-3"
                >
                  fas fa-minus-circle
                </v-icon>
              </span>

              <v-progress-linear
                :color="barColor(item)"
                :value="barScoure(item)"
                height="20"
                rounded
                :active="barActive(item)"
                :reverse="barReverse(item)"
                :background-color="barBgcolor(item)"
                background-opacity="0.2"
              >
                <strong>
                  {{ item.campaign[0].defenders_score * 100 }} /
                  {{ item.campaign[0].attackers_score * 100 }}
                </strong>
              </v-progress-linear>
              <span>
                <v-icon
                  v-if="
                    item.campaign[0].attackers_score >
                      item.campaign[0].attackers_score_old &&
                    item.campaign[0].attackers_score_old > 0
                  "
                  small
                  right
                  dark
                  color="red darken-4"
                >
                  fas fa-arrow-alt-circle-up
                </v-icon>
                <v-icon
                  v-if="
                    item.campaign[0].attackers_score <
                      item.campaign[0].attackers_score_old &&
                    item.campaign[0].attackers_score_old > 0
                  "
                  small
                  right
                  dark
                  color="red darken-4"
                >
                  fas fa-arrow-alt-circle-down
                </v-icon>
                <v-icon
                  v-if="
                    item.campaign[0].attackers_score ==
                      item.campaign[0].attackers_score_old ||
                    item.campaign[0].attackers_score_old == null
                  "
                  small
                  right
                  dark
                  color="grey darken-3"
                >
                  fas fa-minus-circle
                </v-icon>
              </span>
            </span>
            <span
              v-else-if="
                item.campaign[0].status_id == 3 ||
                item.campaign[0].status_id == 4
              "
            >
              <p
                v-if="item.campaign[0].attackers_score == 0"
                class="text-md-center green--text"
              >
                {{ item.campaign[0].alliance.name }}
                <span class="font-weight-bold"> WON </span> the
                {{ item.campaign[0].event_type }} timer.
              </p>
              <p v-else class="text-md-center red--text">
                {{ item.campaign[0].alliance.name }}
                <span class="font-weight-bold"> LOST </span> the
                {{ item.campaign[0].event_type }} timer.
              </p>
            </span>
          </template>
          <template
            v-slot:[`item.campaign[0].system.webway[0].jumps`]="{ item }"
          >
            <SoloCampaginWebWay
              :item="item.campagin[0].system"
            ></SoloCampaginWebWay>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-col>
</template>
<script>
import Axios from "axios";
import { mapGetters, mapState } from "vuex";
export default {
  props: {
    windowSize: Object,
    filteredItems: Array,
  },
  data() {
    return {
      headers: [
        {
          text: "WebWay",
          value: "campaign[0].system.webway[0].jumps",
          sortable: false,
        },
        {
          text: "Region",
          value: "campaign[0].constellation.region.region_name",
          sortable: true,
        },

        {
          text: "Constellation",
          value: "campaign[0].constellation.constellation_name",
          sortable: true,
        },

        {
          text: "System",
          value: "campaign[0].system.system_name",
          sortable: true,
        },
        {
          text: "Alliance",
          value: "campaign[0].alliance.name",
          sortable: true,
        },

        {
          text: "ADM",
          value: "campaign[0].system.adm",
          sortable: true,
        },
        {
          text: "Ticker",
          value: "campaign[0].alliance.ticker",
          sortable: true,
        },

        {
          text: "Structure",
          value: "campaign[0].event_type",
          sortable: true,
        },

        {
          text: "Start/Progress",
          value: "campaign[0].start_time",
          sortable: true,
        },

        {
          text: "Countdown/Age",
          value: "campaign[0].structure.age",
          sortable: true,
        },
      ],
    };
  },
  mounted() {},
  methods: {
    barColor(item) {
      var d = item.campaign[0].defenders_score * 100;
      if (d > 50) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    barScoure(item) {
      var d = item.campaign[0].defenders_score * 100;
      var a = item.campaign[0].attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barActive(item) {
      if (item.campaign[0].status_id > 1) {
        return true;
      }
      return false;
    },

    barReverse(item) {
      var d = item.campaign[0].defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },

    barBgcolor(item) {
      var d = item.campaign[0].defenders_score * 100;
      var a = item.campaign[0].attackers_score * 100;

      if (d > 50) {
        return "red darken-4";
      }

      return "blue darken-4";
    },
  },
  computed: {
    height() {
      let num = this.windowSize.y - 262;
      return num;
    },
  },
};
</script>
