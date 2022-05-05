<template>
  <v-row no-gutters>
    <v-col>
      <!-- v-if="item.status_id < 3 && item.end_time == null" -->
      <VueCountUptimer
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

    countUptimerColor() {},
  },
  beforeDestroy() {},
};
</script>
