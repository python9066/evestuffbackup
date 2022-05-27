<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-data-table
        :headers="headers"
        :items="filteredItems"
        fixed-header
        :height="height"
        :search="search"
        item-key="id"
        :items-per-page="50"
        :sort-by="['campaign[0].start_time']"
        @click:row="rowClick($event)"
        :footer-props="{
          'items-per-page-options': [10, 20, 30, 50, 100, -1],
        }"
        class="elevation-24 rounded-xl full-width"
      >
        <template slot="no-data"> No Active or Upcoming Campaigns </template>
        <template v-slot:[`item.campaign[0].alliance.name`]="{ item }">
          <div class="d-flex flex-nowrap">
            <span v-if="item.campaign[0].priority == 1" class="rainbow-2 pr-2">
              <font-awesome-icon
                icon="fa-solid fa-wand-magic-sparkles"
                size="xl"
                transform="flip-h"
                class="fa-bounce"
                style="
                  --fa-bounce-start-scale-x: 1;
                  --fa-bounce-start-scale-y: 1;
                  --fa-bounce-jump-scale-x: 1;
                  --fa-bounce-jump-scale-y: 1;
                  --fa-bounce-land-scale-x: 1;
                  --fa-bounce-land-scale-y: 1;
                "
                v-if="item.campaign[0].priority == 1"
            /></span>
            <v-avatar size="35"
              ><img :src="item.campaign[0].alliance.url"
            /></v-avatar>
            <span
              v-if="item.campaign[0].alliance.standing > 0"
              class="blue--text pl-3"
              >{{ item.campaign[0].alliance.name }}
            </span>
            <span v-if="item.campaign[0].priority == 0">
              <span v-if="item.standing > 0" class="blue--text pl-3"
                >{{ item.alliance }}
              </span>
              <span v-else-if="item.standing < 0" class="red--text pl-3"
                >{{ item.alliance }}
              </span>
              <span v-else class="pl-3">{{ item.alliance }}</span></span
            >
            <span
              v-else-if="item.campaign[0].alliance.standing < 0"
              class="red--text pl-3"
              >{{ item.campaign[0].alliance.name }}
            </span>
            <span v-else class="pl-3">{{
              item.campaign[0].alliance.name
            }}</span>
            <span v-if="item.campaign[0].priority == 1" class="rainbow-2 pl-2">
              <font-awesome-icon
                icon="fa-solid fa-wand-magic-sparkles"
                size="xl"
                class="fa-bounce"
                bounce
                style="
                  --fa-bounce-start-scale-x: 1;
                  --fa-bounce-start-scale-y: 1;
                  --fa-bounce-jump-scale-x: 1;
                  --fa-bounce-jump-scale-y: 1;
                  --fa-bounce-land-scale-x: 1;
                  --fa-bounce-land-scale-y: 1;
                "
              />
            </span>
          </div>
        </template>

        <template v-slot:[`item.campaign[0].start_time`]="{ item }">
          <span
            v-if="
              item.campaign[0].status_id == 1 || item.campaign[0].status_id == 5
            "
          >
            {{ item.campaign[0].start_time }}
          </span>
          <span
            v-else-if="
              item.campaign[0].status_id != 3 && item.campaign[0].status_id != 4
            "
            class="d-flex full-width align-content-center"
          >
            <span>
              <span
                dark
                color="blue darken-4"
                v-if="
                  item.campaign[0].defenders_score >
                    item.campaign[0].defenders_score_old &&
                  item.campaign[0].defenders_score_old > 0
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-up" pull="left"
              /></span>
              <span
                dark
                color="blue darken-4"
                v-if="
                  item.campaign[0].defenders_score <
                    item.campaign[0].defenders_score_old &&
                  item.campaign[0].defenders_score_old > 0
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-down" pull="left"
              /></span>
              <span
                dark
                color="grey darken-3"
                v-if="
                  item.campaign[0].defenders_score ==
                    item.campaign[0].defenders_score_old ||
                  item.campaign[0].defenders_score_old === null
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-minus" pull="left"
              /></span>
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
              <span
                dark
                color="red darken-4"
                v-if="
                  item.campaign[0].attackers_score >
                    item.campaign[0].attackers_score_old &&
                  item.campaign[0].attackers_score_old > 0
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-up" pull="right"
              /></span>
              <span
                dark
                color="red darken-4"
                v-if="
                  item.campaign[0].attackers_score <
                    item.campaign[0].attackers_score_old &&
                  item.campaign[0].attackers_score_old > 0
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-down" pull="right"
              /></span>
              <span
                dark
                color="grey darken-3"
                v-if="
                  item.campaign[0].attackers_score ==
                    item.campaign[0].attackers_score_old ||
                  item.campaign[0].attackers_score_old == null
                "
              >
                <font-awesome-icon icon="fa-solid fa-circle-minus" pull="right"
              /></span>
            </span>
          </span>
          <span
            v-else-if="
              item.campaign[0].status_id == 3 || item.campaign[0].status_id == 4
            "
          >
            <p
              v-if="item.campaign[0].attackers_score == 0"
              class="text-md-center green--text"
            >
              {{ item.campaign[0].alliance.name }}
              <span class="font-weight-bold"> WON </span> the
              {{ itemType(item.campaign[0].event_type) }} timer.
            </p>
            <p v-else class="text-md-center red--text">
              {{ item.campaign[0].alliance.name }}
              <span class="font-weight-bold"> LOST </span> the
              {{ itemType(item.campaign[0].event_type) }} timer.
            </p>
          </span>
        </template>
        <template v-slot:[`item.campaign[0].system.webway[0].jumps`]="{ item }">
          <SoloCampaginWebWay
            v-if="item.campaign[0].system.webway[0]"
            :jumps="item.campaign[0].system.webway[0].jumps"
            :web="item.campaign[0].system.webway[0].webway"
          ></SoloCampaginWebWay>
        </template>
        <template v-slot:[`item.count`]="{ item }">
          <div class="d-inline-flex align-center">
            <CountDowntimer
              v-if="
                item.campaign[0].status_id == 1 ||
                item.campaign[0].status_id == 5
              "
              :start-time="moment.utc(item.campaign[0].start_time).unix()"
              :end-text="'Window Closed'"
              :interval="1000"
              @campaignStart="campaignStart(item)"
            >
              <template slot="countdown" slot-scope="scope">
                <span
                  v-if="
                    scope.props.hours == 0 &&
                    scope.props.days == 0 &&
                    $can('access_campaigns')
                  "
                  class="red--text pl-3"
                >
                  <v-chip
                    class="ma-2 ma"
                    filter
                    pill
                    :disabled="pillDisabled(item)"
                    color="light-blue lighten-1"
                  >
                    {{ scope.props.minutes }}:{{ scope.props.seconds }}
                  </v-chip>
                </span>
                <span v-else class="red--text pl-3"
                  >{{ scope.props.days }}:{{ scope.props.hours }}:{{
                    scope.props.minutes
                  }}:{{ scope.props.seconds }}</span
                >
              </template>
            </CountDowntimer>
            <div
              v-if="item.campaign[0].status_id == 2 && $can('access_campaigns')"
            >
              <v-chip
                class="ma-2 ma"
                filter
                pill
                :disabled="pillDisabled(item)"
                :color="pillColor(item)"
              >
                {{ item.campaign[0].status.name }}
              </v-chip>
            </div>
            <div
              v-else-if="
                item.campaign[0].status_id > 1 && !$can('access_campaigns')
              "
            >
              <span class="pl-5">{{ item.campaign[0].status.name }}</span>
            </div>

            <div
              class="d-flex full-width align-content-center"
              v-if="item.campaign[0].status_id == 2"
            >
              <VueCountUptimer
                :start-time="moment.utc(item.campaign[0].start_time).unix()"
                :end-text="'Campaign Started'"
                :interval="1000"
              >
                <template slot="countup" slot-scope="scope">
                  <span class="green--text pl-3"
                    >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                      scope.props.seconds
                    }}</span
                  >
                </template>
              </VueCountUptimer>
            </div>
            <SoloCampaignMap
              :system_name="item.campaign[0].system.system_name"
              :region_name="item.campaign[0].constellation.region.region_name"
            >
            </SoloCampaignMap>
            <VueCountUptimer
              v-if="item.campaign[0].structure"
              :start-time="moment.utc(item.campaign[0].structure.age).unix()"
              :end-text="'Window Closed'"
              :interval="1000"
              :leadingZero="false"
            >
              <template slot="countup" slot-scope="scope">
                <span class="green--text pl-3"
                  ><span v-if="scope.props.days != 0">
                    {{ scope.props.days }} Days - </span
                  >{{ scope.props.hours }} Hours</span
                >
              </template>
            </VueCountUptimer>
            <!-- <NewCampaginPriorityButton v-if="$can('edit_hack_priority')" /> -->
          </div>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { mapGetters, mapState } from "vuex";
export default {
  props: {
    windowSize: Object,
    filteredItems: Array,
    search: String,
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
          value: "count",
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

    rowClick(item) {
      //   if (this.$can("access_campaigns")) {
      //     var left = moment.utc(item.start).unix() - moment.utc().unix();
      //     if (left < 3600 && item.status_id < 3) {
      //       this.$router.push({ path: `/op/${item.link}` }); // -> /user/123
      //     }
      //   }

      if (this.$can("access_campaigns")) {
        this.$router.push({ path: `/op/${item.link}` }); // -> /user/123
      }
    },

    campaignStart(item) {
      item.campaign[0].status.status_name = "Active";
      item.campaign[0].status_id = 2;
      var request = {
        status_id: 2,
      };

      this.$store.dispatch("updateNewSoloOperation", item);

      axios({
        method: "put", //you can set what request you want to be
        url: "api/newcampaigns/" + item.campaign[0].id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
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

    pillDisabled(item) {
      if (item.campaign[0].status_id == 3) {
        return true;
      }
      return false;
    },

    pillColor(item) {
      if (item.campaign[0].status_id == 3) {
        return "blue-grey darken-4";
      }

      return "green darken-3";
    },
    itemType(typeID) {
      if (typeID == 32458) {
        return "Ihub";
      } else {
        return "TCU";
      }
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
<style>
.style-2 {
  background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.follow {
  margin-top: 40px;
}

.follow a {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
}

.rainbow-2 {
  animation: colorRotate 0.5s linear 0s infinite;
}

.style-1 {
  background-color: rgb(184, 22, 35);
}

@keyframes colorRotate {
  from {
    color: #6666ff;
  }
  10% {
    color: #0099ff;
  }
  50% {
    color: #00ff00;
  }
  75% {
    color: #ff3399;
  }
  100% {
    color: #6666ff;
  }
}
</style>
