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
      <!-- <v-row no-gutters>
        <v-col cols="12">
          <grid-layout
            :layout.sync="layout"
            :col-num="1"
            :row-height="30"
            :is-draggable="true"
            :is-resizable="false"
            :is-mirrored="false"
            :vertical-compact="true"
            :margin="[10, 10]"
            :use-css-transforms="true"
          >
            <grid-item
              v-for="item in layout"
              :x="item.x"
              :y="item.y"
              :w="item.w"
              :h="item.h"
              :i="item.i"
              :key="item.i"
            >
              {{ item.i }}
            </grid-item>
          </grid-layout>
        </v-col>
      </v-row> -->
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
    return `EveStuff - MultiCampaigns`;
  },
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },
      layout: [
        { x: 0, y: 0, w: 2, h: 2, i: "0" },
        { x: 0, y: 1, w: 2, h: 2, i: "1" },
        { x: 0, y: 2, w: 2, h: 2, i: "2" },
        { x: 0, y: 3, w: 2, h: 2, i: "3" },
        { x: 0, y: 4, w: 2, h: 2, i: "4" },
        { x: 0, y: 5, w: 2, h: 2, i: "5" },
        { x: 0, y: 6, w: 2, h: 2, i: "6" },
        { x: 0, y: 7, w: 2, h: 2, i: "7" },
        { x: 0, y: 8, w: 2, h: 2, i: "8" },
        { x: 0, y: 9, w: 2, h: 2, i: "9" },
        { x: 0, y: 10, w: 2, h: 2, i: "10" },
        { x: 0, y: 11, w: 2, h: 2, i: "11" },
        { x: 0, y: 12, w: 2, h: 2, i: "12" },
        { x: 0, y: 13, w: 2, h: 2, i: "13" },
        { x: 0, y: 14, w: 2, h: 2, i: "14" },
        { x: 0, y: 15, w: 2, h: 2, i: "15" },
        { x: 0, y: 16, w: 2, h: 2, i: "16" },
        { x: 0, y: 17, w: 2, h: 2, i: "17" },
        { x: 0, y: 18, w: 2, h: 2, i: "18" },
        { x: 0, y: 19, w: 2, h: 2, i: "19" },
      ],
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
