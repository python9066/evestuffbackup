<template>
  <v-row no-gutters>
    <v-col>
      <span>{{ activePilotText }}</span>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    node: Object,
    type: Number,
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
    ...mapGetters(["getOwnHackingCharOnOp"]),

    ...mapState([]),

    nodefree() {
      return this.node.op_users.length;
    },

    activePilot() {
      if (this.nodefree > 0) {
        return this.node.op_users[0];
      } else {
        null;
      }
    },

    activePilotText() {
      if (this.activePilot) {
        // return this.activePilot.name;

        switch (this.type) {
          case 1: // * return main name
            this.activePilot.user.name;

          case 2: // * return {{ship_name}} + T-{{entosi}}
            var entosis = " T-" + this.activePilot.entosis;
            var ship = this.activePilot.ship;
            var text = ship + entosis;
            return text;
        }
      } else {
        return null;
      }
    },
  },
  beforeDestroy() {},
};
</script>
