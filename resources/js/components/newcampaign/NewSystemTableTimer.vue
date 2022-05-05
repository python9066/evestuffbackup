<template>
  <v-row no-gutters>
    <v-col>
      <!-- v-if="item.status_id < 3 && item.end_time == null" -->
      <VueCountUptimer
        :start-time="moment.utc(timeMoment).unix()"
        :end-text="'Window Closed'"
        :interval="1000"
      >
        <!-- <template slot="countup" slot-scope="scope">
          <span :class="countUptimerColor"
            >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
              scope.props.seconds
            }}</span
          >
        </template> -->
        <template slot="countup" slot-scope="scope">
          <span :class="countUptimerColor"
            >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
              scope.props.seconds
            }}</span
          >
        </template>
      </VueCountUptimer>
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
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    timeMoment() {
      return moment.utc(this.node.created_at).format("YYYY-MM-DD HH:mm:ss");
    },

    countUptimerColor() {
      var addFiveMins = moment
        .utc(this.timeMoment)
        .add(5, "minutes")
        .format("YYYY-MM-DD HH:mm:ss");
      console.log("five: " + fiveMins, " - ", "now: " + this.timeMoment);
      if (moment.utc().isBefore(fiveMins)) {
        return "red--text pl-3";
      } else {
        return "green--text pl-3";
      }
    },
  },
  beforeDestroy() {},
};
</script>
