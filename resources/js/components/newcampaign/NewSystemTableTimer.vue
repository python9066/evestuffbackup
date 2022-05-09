<template>
  <v-row no-gutters>
    <v-col>
      <!-- v-if="this.node.node_status.id < 3 && item.end_time == null" -->

      <VueCountUptimer
        v-if="showAgeCountUp"
        :start-time="moment.utc(timeMoment).unix()"
        :end-text="'Window Closed'"
        :interval="1000"
      >
        <template slot="countup" slot-scope="scope">
          <transition
            name="custom-classes"
            enter-active-class="animate__animated animate__heartBeat animate__repeat-2"
            leave-active-class="animate__animated animate__flash animate__faster"
            mode="out-in"
          >
            <div
              :key="`${node.id}-1-timer-age`"
              v-if="scope.props.minutes < 5 || scope.props.hours > 0"
              class="green--text pl-3"
            >
              {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                scope.props.seconds
              }}
            </div>

            <div :key="`${node.id}-2-timer-age`" v-else class="red--text pl-3">
              {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                scope.props.seconds
              }}
            </div>
          </transition>
        </template>
      </VueCountUptimer>
      <v-menu
        :close-on-content-click="false"
        :value="timerShown"
        v-else-if="checkHackUser"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-chip
            v-bind="attrs"
            v-on="on"
            pill
            :outlined="pillOutlined"
            @click="timerShown = true"
            small
            color="warning"
          >
            Add Time
          </v-chip>
        </template>

        <template>
          <v-card tile min-height="150px">
            <v-card-title class="pb-0">
              <v-text-field
                v-model="hackTime"
                label="Hack Time mm:ss"
                v-mask="'##:##'"
                autofocus
                placeholder="mm:ss"
                @keyup.enter="(timerShown = false), addHacktime()"
                @keyup.esc="(timerShown = false), (hackTime = null)"
              ></v-text-field>
            </v-card-title>
            <v-card-text>
              <v-btn
                icon
                fixed
                left
                color="success"
                @click="(timerShown = false), addHacktime()"
                ><v-icon>fas fa-check</v-icon></v-btn
              >

              <v-btn
                fixed
                right
                icon
                color="warning"
                @click="(timerShown = false), (hackTime = null)"
                ><v-icon>fas fa-times</v-icon></v-btn
              >
            </v-card-text>
          </v-card>
        </template>
      </v-menu>
      <CountDowntimer
        v-else
        :start-time="startTime"
        :end-text="endText"
        :interval="1000"
      >
        <template slot="countdown" slot-scope="scope">
          <span :class="hackCountDownTextColor"
            ><span v-if="scope.props.hours > 0">{{ scope.props.hours }}:</span
            >{{ scope.props.minutes }}:{{ scope.props.seconds }}</span
          >
          <v-menu :close-on-content-click="false" :value="timerShown">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-if="checkHackUserEdit"
                v-bind="attrs"
                v-on="on"
                @click="(timerShown = true), (hackTime = null)"
                icon
                color="warning"
              >
                <v-icon x-small>fas fa-edit</v-icon>
              </v-btn>
            </template>

            <template>
              <v-card tile min-height="150px">
                <v-card-title class="pb-0">
                  <v-text-field
                    v-model="hackTime"
                    label="Hack Time mm:ss"
                    autofocus
                    v-mask="'##:##'"
                    placeholder="mm:ss"
                    @keyup.enter="(timerShown = false), addHacktime(item)"
                    @keyup.esc="(timerShown = false), (hackTime = null)"
                  ></v-text-field>
                </v-card-title>
                <v-card-text>
                  <v-btn
                    icon
                    fixed
                    left
                    color="success"
                    @click="(timerShown = false), addHacktime(item)"
                    ><v-icon>fas fa-check</v-icon></v-btn
                  >

                  <v-btn
                    fixed
                    right
                    icon
                    color="warning"
                    @click="(timerShown = false), (hackTime = null)"
                    ><v-icon>fas fa-times</v-icon></v-btn
                  >
                </v-card-text>
              </v-card>
            </template>
          </v-menu>
        </template>
        <template slot="end-text" slot-scope="scope">
          <span :style="hackTextColor">{{ scope.props.endText }}</span>
        </template>
      </CountDowntimer>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    node: Object,
    operationID: Number,
  },
  data() {
    return {
      timerShown: false,
      hackTime: {
        mm: "",
        ss: "",
      },
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async addHacktime() {
      var min = parseInt(this.hackTime.substr(0, 2));
      var sec = parseInt(this.hackTime.substr(3, 2));
      var base = min * 60 + sec;
      var sec = min * 60 + sec;
      var sec = sec / (this.node.system.tidi / 100);
      var finishTime = moment
        .utc()
        .add(sec, "seconds")
        .format("YYYY-MM-DD HH:mm:ss");

      var request = {
        end_time: finishTime,
        input_time: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
        base_time: base,
        system_id: this.node.system_id,
      };

      await axios({
        method: "put",
        url: "/api/addprimetimer/" + this.opUserInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    checkHackUserEdit() {
      // if (
      //     item.site_id == this.$store.state.user_id &&
      //     item.status_id == 3
      // ) {
      //     return true;
      // } else if (item.status_id == 7 || item.status_id == 8) {
      //     return true;
      // } else {
      //     return false;
      // }

      if (
        this.node.node_status.id == 7 ||
        this.node.node_status.id == 8 ||
        this.node.node_status.id == 3 ||
        this.node.node_status.id == 9
      ) {
        return true;
      } else {
        return false;
      }
    },

    endText() {
      if (this.node.node_status.id == 7 || this.node.node_status.id == 8) {
        return "Did they Finish?";
      } else if (this.node.node_status.id == 3) {
        return "Did you Finish?";
      } else if (this.node.node_status.id == 9) {
        return "Did it Finish?";
      } else {
        return "Finished!!! ";
      }
    },

    opUserInfo() {
      if (this.node.prime_node_user.length > 0) {
        return this.node.prime_node_user[0];
      } else {
        return null;
      }
    },

    showAgeCountUp() {
      switch (this.node.node_status.id) {
        case 1: // * New
          return true;

        case 2: // * Warm up
          return false;

        case 3: // * Hacking
          return false;

        case 4: // * Success
          return false;

        case 5: // * Failed
          return false;

        case 6: // * Pushed off
          return true;

        case 7: // * Hostile Hacking
          return false;

        case 8: // * Friendly Hacking
          return false;

        case 9: // * Passive
          return false;

        case 10: // * Other
          return true;
      }
    },

    startTime() {
      if (this.node.node_status.id == 2) {
        return moment.utc(this.opUserInfo.updated_at).unix();
      }
      if (this.opUserInfo) {
        return moment.utc(this.opUserInfo.end_time).unix();
      } else {
        return null;
      }
    },

    hackTextColor() {
      if (this.node.node_status.id == 7) {
        return "color: while";
      } else {
        return "color: green";
      }
    },

    hackCountDownTextColor() {
      if (this.node.node_status.id == 7) {
        return "white--text pl-3";
      } else {
        return "blue--text pl-3";
      }
    },

    checkHackUser() {
      if (this.opUserInfo) {
        if (this.opUserInfo.end_time == null && this.node.node_status.id == 3) {
          return true;
        } else if (
          this.opUserInfo.end_time == null &&
          (this.node.node_status.id == 7 ||
            this.node.node_status.id == 8 ||
            this.node.node_status.id == 9)
        ) {
          return true;
        } else {
          return false;
        }
      } else {
        if (this.node.node_status.id == 3) {
          return true;
        } else if (
          this.node.node_status.id == 7 ||
          this.node.node_status.id == 8 ||
          this.node.node_status.id == 9
        ) {
          return true;
        } else {
          return false;
        }
      }
    },

    pillOutlined() {
      if (this.node.node_status.id == 7) {
        return false;
      } else {
        return true;
      }
    },

    timeMoment() {
      return moment.utc(this.node.created_at).format("YYYY-MM-DD HH:mm:ss");
    },

    countUptimerColor() {},
  },
  beforeDestroy() {},
};
</script>

<style>
.style-2 {
  background-color: rgb(30, 30, 30, 1);
}
.style-1 {
  background-color: rgb(184, 22, 35);
}
</style>
