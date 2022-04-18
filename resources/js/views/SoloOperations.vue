<template>
  <v-row class="pr-5 pl-5 pt-1" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12 pb-5"
      ><v-expansion-panels>
        <v-expansion-panel class="rounded-xl">
          <v-expansion-panel-header
            color="primary"
            :class="filterRound"
            @click="filterClick()"
          >
            <template v-slot:default="{ open }">
              <v-row no-gutters>
                <v-col cols="4"> Filters </v-col>
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
            <v-row no-gutters
              ><v-col cols="12"
                ><v-card flat
                  ><v-card-text class="py-0"
                    ><span class="subheading">Filter Regions</span
                    ><v-autocomplete
                      chips
                      clearable
                      deletable-chips
                      solo-inverted
                      dense
                      hide-details
                      hide-selected
                      rounded
                      small-chips
                      multiple
                      v-model="regionFilter"
                      :items="systemlist"
                    ></v-autocomplete></v-card-text></v-card></v-col
            ></v-row>
            <v-row no-gutters
              ><v-col cols="12">
                <v-card flat
                  ><v-card-text class="py-0"
                    ><span class="subheading"> Filter Item</span
                    ><v-chip-group
                      active-class="primary--text"
                      column
                      v-model="filterItemTypeSelect"
                    >
                      <v-chip
                        v-for="(list, index) in filterItemTypeSelectList"
                        :key="index"
                        filter
                        :value="list.value"
                        outlined
                        small
                      >
                        {{ list.text }}
                      </v-chip>
                    </v-chip-group>
                  </v-card-text>
                </v-card>
              </v-col></v-row
            >
            <v-row no-gutters
              ><v-col cols="12">
                <v-card flat
                  ><v-card-text class="py-0"
                    ><span class="subheading"> Standing</span
                    ><v-chip-group
                      active-class="primary--text"
                      column
                      v-model="filterStandingSelect"
                      multiple
                    >
                      <v-chip
                        v-for="(list, index) in filterStandingSelectList"
                        :key="index"
                        filter
                        :value="list.value"
                        outlined
                        small
                      >
                        {{ list.text }}
                      </v-chip>
                    </v-chip-group>
                  </v-card-text>
                </v-card>
              </v-col></v-row
            >

            <v-row no-gutters
              ><v-col cols="12">
                <v-card flat
                  ><v-card-text class="py-0"
                    ><span class="subheading"> Status</span
                    ><v-chip-group
                      active-class="primary--text"
                      column
                      v-model="filterStatusSelect"
                      multiple
                    >
                      <v-chip
                        v-for="(list, index) in filterStatusSelectList"
                        :key="index"
                        filter
                        :value="list.value"
                        outlined
                        small
                      >
                        {{ list.text }}
                      </v-chip>
                    </v-chip-group>
                  </v-card-text>
                </v-card>
              </v-col></v-row
            >
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels></v-col
    >
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

      filterOpen: false,

      date: null,
      trip: {
        name: "",
        location: null,
        start: null,
        end: null,
      },

      regionFilter: [],

      filterItemTypeSelect: [],
      filterItemTypeSelectList: [
        { text: "All", value: 1 },
        { text: "Ihub", value: 2 },
        { text: "TCU", value: 3 },
      ],

      filterStandingSelect: [],
      filterStandingSelectList: [
        { text: "All", value: 1 },
        { text: "Goon", value: 2 },
        { text: "Friendly", value: 3 },
        { text: "Hostile", value: 4 },
      ],

      filterStatusSelect: [],
      filterStatusSelectList: [
        { text: "All", value: 1 },
        { text: "ACtive", value: 2 },
        { text: "Finished", value: 3 },
      ],
    };
  },

  created() {},

  async mounted() {
    this.onResize();
    this.log();
  },
  methods: {
    filterClick() {
      this.filterOpen = !this.filterOpen;
    },
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

  computed: {
    filterRound() {
      if (this.filterOpen) {
        return "rounded-t-xl";
      } else {
        return "rounded-xl";
      }
    },

    systemlist() {
      return [];
    },
  },
  beforeDestroy() {},
};
</script>
