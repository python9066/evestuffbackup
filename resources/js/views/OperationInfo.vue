<template>
  <v-row no-gutters v-resize="onResize">
    <v-col cols="12"> </v-col>
    <AddOperationInfo />
  </v-row>
</template>
<script>
import Axios from "axios";
import moment, { now, unix, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - MultiCampaigns`;
  },
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },

  created() {
    this.$store.dispatch("getOperationSheetInfo");
    Echo.private("operationinfopage").listen("OperationInfoPageUpdate", (e) => {
      if (e.flag.flag == 1) {
      }

      if (e.flag.flag == 2) {
      }

      if (e.flag.flag == 3) {
      }
    });
  },

  async mounted() {
    this.onResize();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
  },
  computed: {},
  beforeDestroy() {
    Echo.leave("operationinfopage");
  },
};
</script>
