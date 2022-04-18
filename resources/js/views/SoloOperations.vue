<template>
  <v-row
    class="pr-1 pl-1 pt-1"
    no-gutters
    v-resize="onResize"
    justify="center"
  ></v-row>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
import ApiL from "../service/apil";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {
    return `EveStuff - Operations`;
  },
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },

  created() {},

  async mounted() {
    this.onResize();
    this.log();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    log() {
      var request = {
        url: this.$route.path,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/url",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {},
  beforeDestroy() {},
};
</script>
