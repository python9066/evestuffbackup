<template>
  <v-row no-gutters>
    <v-col>
      <!-- v-if="this.node.node_status.id < 3 && item.end_time == null" -->
      <VueCountUptimer
        v-if="node.node_status.id < 3"
        :start-time="moment.utc(timeMoment).unix()"
        :end-text="'Window Closed'"
        :interval="1000"
      >
        <template slot="countup" slot-scope="scope">
          <span
            v-if="scope.props.minutes < 5 || scope.props.hours > 0"
            class="green--text pl-3"
            >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
              scope.props.seconds
            }}</span
          >
          <span v-else class="red--text pl-3"
            >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
              scope.props.seconds
            }}</span
          >
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
      //   var sec = sec / (this.CampaignSolaSystem[0]["tidi"] / 100);
      var finishTime = moment
        .utc()
        .add(sec, "seconds")
        .format("YYYY-MM-DD HH:mm:ss");

      var request = {
        end_time: finishTime,
        input_time: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
        base_time: base,
      };

      //   await axios({
      //     method: "put",
      //     url: "/api/campaignsystems/" + item.id,
      //     withCredentials: true,
      //     data: request,
      //     headers: {
      //       Accept: "application/json",
      //       "Content-Type": "application/json",
      //     },
      //   });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    opUserInfo() {
      if (this.node.op_users.length > 0) {
        return this.node.op_users[0];
      } else {
        return null;
      }
    },
    checkHackUser() {
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
