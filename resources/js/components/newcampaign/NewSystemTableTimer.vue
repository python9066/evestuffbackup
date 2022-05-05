<template>
  <v-row no-gutters>
    <v-col>
      <!-- v-if="item.status_id < 3 && item.end_time == null" -->
      <VueCountUptimer
        :start-time="timeMoment.unix()"
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
      return moment(this.node.created_at).utc().format("YYYY-MM-DD HH:mm:ss");
    },

    countUptimerColor() {
      var fiveMins = this.timeMoment.subtract(5, "minutes");
      if (moment(this.timeMoment).utc().isSameOrBefore(fiveMins)) {
        return "red--text pl-3";
      } else {
        return "green--text pl-3";
      }
    },
  },
  beforeDestroy() {},
};
</script>
