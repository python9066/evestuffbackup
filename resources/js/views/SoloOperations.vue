<template>
  <v-row class="pr-5 pl-5 pt-1" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12 pb-5"
      ><v-expansion-panels>
        <v-expansion-panel class="rounded-xl">
          <v-expansion-panel-header color="primary" class="rounded-t-xl">
            <template v-slot:default="{ open }">
              <v-row no-gutters>
                <v-col cols="4"> Trip name </v-col>
                <v-col cols="8" class="text--secondary">
                  <v-fade-transition leave-absolute>
                    <span v-if="open" key="0"> Enter a name for the trip </span>
                    <span v-else key="1">
                      {{ trip.name }}
                    </span>
                  </v-fade-transition>
                </v-col>
              </v-row>
            </template>
          </v-expansion-panel-header>
          <v-expansion-panel-content class="rounded-xl">
            <v-text-field
              v-model="trip.name"
              placeholder="Caribbean Cruise"
            ></v-text-field>
          </v-expansion-panel-content>
        </v-expansion-panel> </v-expansion-panels
    ></v-col>
    <v-col cols="12">
      <v-card elevation="10" rounded="xl" class="mb-5">
        <v-card-title class="justify-center primary pa-3"
          >Operations</v-card-title
        >
        <v-card-text class="pa-0">
          <SoloOperationsTable :windowSize="windowSize"> </SoloOperationsTable>
        </v-card-text>
      </v-card> </v-col
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

      date: null,
      trip: {
        name: "",
        location: null,
        start: null,
        end: null,
      },
      locations: [
        "Australia",
        "Barbados",
        "Chile",
        "Denmark",
        "Ecuador",
        "France",
      ],
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
