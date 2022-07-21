<template>
  <v-row :key="`${recon.id}-card`" no-gutters>
    <!-- <v-tooltip right> -->

    <v-col cols="auto">
      <v-tooltip right :disabled="hideToolTip">
        <template v-slot:activator="{ on, attrs }">
          <span :class="textClass" v-bind="attrs" v-on="on">
            {{ recon.name }} - {{ recon.main.name }}
          </span>
        </template>
        <span v-if="recon.operation_info_recon_status_id == 2"
          >Fleet - {{ recon.fleet.name }} <br />
          Role - {{ recon.fleet_role.name }}</span
        >
        <span v-if="recon.operation_info_recon_status_id == 3"
          >System - {{ recon.system.system_name }}</span
        >

        <span v-if="recon.operation_info_recon_status_id == 4"
          >Fleet - {{ recon.fleet.name }} <br />
          Role - {{ recon.fleet_role.name }} <br />System -
          {{ recon.system.system_name }}
        </span>
      </v-tooltip>
    </v-col>
    <v-col cols="auto">
      <v-btn x-small color="orange" icon @click="removeRecon(recon)"
        ><font-awesome-icon icon="fa-solid fa-trash"
      /></v-btn>
      <v-btn x-small :color="deadColor" icon @click="dead(recon)"
        ><font-awesome-icon icon="fa-solid fa-skull-crossbones"
      /></v-btn>
      <v-btn x-small :color="onlineColor" icon @click="online(recon)"
        ><font-awesome-icon icon="fa-solid fa-power-off"
      /></v-btn>
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
    loaded: Boolean,
    recon: Object,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async removeRecon(item) {
      var request = item;
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinforeconremove/" + item.operation_info_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async dead(item) {
      if (item.dead == 0) {
        item.dead = 1;
      } else {
        item.dead = 0;
      }

      var request = item;
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinforecondead/" + item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async online(item) {
      if (item.online == 0) {
        item.online = 1;
      } else {
        item.online = 0;
      }

      var request = item;
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinforecononline/" + item.id,
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

    deadColor() {
      if (this.recon.dead == 0) {
        return "blue";
      } else {
        return "red";
      }
    },

    textColor() {
      if (this.recon.operation_info_recon_status_id == 1) {
        return "blue--text text--lighten-4";
      }

      if (this.recon.operation_info_recon_status_id == 2) {
        return "green--text";
      }

      if (this.recon.operation_info_recon_status_id == 3) {
        return "blue--text";
      }

      if (this.recon.operation_info_recon_status_id == 4) {
        return "gradient-text";
      }
    },

    textCross() {
      if (this.recon.dead == 1) {
        return "text-decoration-line-through";
      } else {
        return "";
      }
    },

    hideToolTip() {
      if (this.recon.operation_info_fleet_id || this.recon.system) {
        return false;
      }
      return true;
    },

    textOnline() {
      if (this.recon.online == 1) {
        return "";
      } else {
        return "text--disabled font-italic";
      }
    },

    textClass() {
      var a = this.textColor;
      var b = this.textCross;
      var c = this.textOnline;
      if (c || b) {
        return c + " " + b;
      } else {
        return a;
      }
    },

    onlineColor() {
      if (this.recon.online == 0) {
        return "red";
      } else {
        return "blue";
      }
    },

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },
  },
  beforeDestroy() {},
};
</script>
<style>
.gradient-text {
  /* Fallback: Set a background color. */

  /* Create the gradient. */
  background: linear-gradient(to right, #2196f3, #4caf50);

  /* Set the background size and repeat properties. */
  background-size: 100%;
  background-repeat: repeat;

  /* Use the text as a mask for the background. */
  /* This will show the gradient as a text color rather than element bg. */
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;

  /* Animate the text when loading the element. */
  /* This animates it on page load and when hovering out. */
  animation: rainbow-text-simple-animation-rev 0.75s ease forwards;
}
</style>
