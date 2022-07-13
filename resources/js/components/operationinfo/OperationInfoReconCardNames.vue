<template>
  <v-row :key="`${recon.id}-card`" no-gutters>
    <!-- <v-tooltip right> -->

    <v-col cols="auto">
      <v-tooltip right :disabled="hideToolTip(recon)">
        <template v-slot:activator="{ on, attrs }">
          <span :class="textColor(recon)" v-bind="attrs" v-on="on">
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
    textColor(recon) {
      if (recon.operation_info_recon_status_id == 1) {
        return "";
      }

      if (recon.operation_info_recon_status_id == 2) {
        return "green--text";
      }

      if (recon.operation_info_recon_status_id == 3) {
        return "blue--text";
      }

      if (recon.operation_info_recon_status_id == 4) {
        return "gradient-text";
      }
    },

    change(recon) {
      return "#change-" + recon.id + "-" + recon.operation_info_recon_status_id;
    },

    changeID(recon) {
      return "change-" + recon.id + "-" + recon.operation_info_recon_status_id;
    },

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

    hideToolTip(recon) {
      if (recon.operation_info_fleet_id || recon.system) {
        return false;
      }
      return true;
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

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
