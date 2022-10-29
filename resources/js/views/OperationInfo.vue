<template>
  <v-row no-gutters v-resize="onResize">
    <v-col cols="12">
      <v-row no-gutters justify="center">
        <v-col cols="10">
          <v-card rounded="xl">
            <v-card-title class="primary">Operations</v-card-title>
            <v-card-text>
              <OperationInfoTable />
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import moment, { now, unix, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
import VueGridLayout from "vue-grid-layout";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  components: {
    GridLayout: VueGridLayout.GridLayout,
    GridItem: VueGridLayout.GridItem,
  },
  title() {
    return `EveStuff - Operation Info`;
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

      // add/update solo operationinfo
      if (e.flag.flag == 2) {
        this.$store.dispatch("updateOperationPageInfo", e.flag.message);
      }

      if (e.flag.flag == 3) {
        this.$store.dispatch("removeOperationPageInfo", e.flag.message);
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
