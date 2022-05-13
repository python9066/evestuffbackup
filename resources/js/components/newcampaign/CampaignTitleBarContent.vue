<template>
  <v-row no-gutters>
    <v-col>
      {{ item.system.system_name }} - {{ eventType }} :
      {{ item.defenders_score }} / {{ item.attackers_score }}
    </v-col>
    <v-col>
      <!-- // TODO Active (only show if campaign is active) -->
      <v-progress-linear
        :color="barColor"
        :value="barScoure"
        height="20"
        rounded
        :active="true"
        :reverse="barReverse"
        :background-color="barBgcolor"
        background-opacity="0.2"
      >
        <strong>
          {{ item.defenders_score * 100 }} ({{ nodesToLose }}) /
          {{ item.attackers_score * 100 }} ({{ nodesToWin }})
        </strong>
      </v-progress-linear>
    </v-col>
  </v-row>
</template>



<script>
import Axios from "axios";
// import { EventBus } from "../event-bus";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    item: [Object, Array],
    title: String,
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

    eventType() {
      if (this.event_type == "32458") {
        return "Ihub";
      } else {
        return "TCU";
      }
    },

    barColor() {
      var d = this.item.defenders_score * 100;
      if (d > 0.5) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    barScoure() {
      var d = this.item.defenders_score * 100;
      var a = this.item.attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barReverse() {
      var d = this.item.defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },

    barBgcolor() {
      var d = this.item.defenders_score * 100;

      if (d > 50) {
        return "red darken-4";
      }

      return "blue darken-4";
    },

    nodesToLose() {
      var needed = 1 - this.item.defenders_score;
      var need = needed / 0.07;
      return Math.ceil(need);
    },

    nodesToWin() {
      var needed = 1 - this.item.attackers_score;
      var need = needed / 0.07;
      return Math.ceil(need);
    },
  },
  beforeDestroy() {},
};
</script>
