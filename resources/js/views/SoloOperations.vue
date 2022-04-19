<template>
  <v-row class="pr-5 pl-5 pt-1" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12">
      <v-row no-gutters justify="space-between" class="pb-5">
        <v-col cols="5">
          Search
          <v-text-field
            label="Filled"
            filled
            rounded
            single-line
          ></v-text-field>
        </v-col>
        <v-col cols="6"
          ><v-expansion-panels>
            <v-expansion-panel class="rounded-xl">
              <v-expansion-panel-header
                color="primary"
                :class="filterRound"
                @click="filterClick()"
              >
                <template v-slot:actions>
                  <v-icon> fa-solid fa-filter </v-icon>
                </template>
                <template v-slot:default="{ open }">
                  <v-row no-gutters>
                    <v-col cols="2" class="font-bold"> Filters </v-col>
                    <v-col cols="8" class="align-content-start">
                      <v-fade-transition leave-absolute>
                        <span v-if="open" key="0">
                          Select your Filter Setting below.
                        </span>
                        <span v-else key="1">
                          {{ filterText }}
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
                          :items="regionList"
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
      </v-row>
      <v-row no-gutters justify="center">
        <v-col cols="12">
          <v-card elevation="10" rounded="xl" class="mb-5">
            <v-card-title class="justify-center primary pa-3"
              >Operations</v-card-title
            >
            <v-card-text class="pa-0">
              <SoloOperationsTable :windowSize="windowSize">
              </SoloOperationsTable>
            </v-card-text>
          </v-card> </v-col
      ></v-row>
    </v-col>
  </v-row>
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
  async created() {
    Echo.private("solooperation").listen("SoloOperationUpdate", (e) => {});
    await this.$store.dispatch("getSoloOperationList");
  },

  async mounted() {
    this.onResize();
    this.log();
  },
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

      search: null,

      regionFilter: [],

      filterItemTypeSelect: 32458,
      filterItemTypeSelectList: [
        { text: "All", value: 1 },
        { text: "Ihub", value: 32458 },
        { text: "TCU", value: 32226 },
      ],

      filterStandingSelect: 4,
      filterStandingSelectList: [
        { text: "All", value: 4 },
        { text: "Goon", value: 3 },
        { text: "Friendly", value: 2 },
        { text: "Hostile", value: 1 },
      ],

      filterStatusSelect: 2,
      filterStatusSelectList: [
        { text: "All", value: 1 },
        { text: "Upcoming", value: 2 },
        { text: "Active Only", value: 3 },
        { text: "Finished", value: 4 },
      ],
    };
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
    ...mapState(["newSoloOperations", "newSoloOperationsRegionList"]),
    filterRound() {
      if (this.filterOpen) {
        return "rounded-t-xl";
      } else {
        return "rounded-xl";
      }
    },

    filterText() {
      //Showing all {{Structurs/Ihubs/TCUs}} in {{All regions/[region list]}} belonging too {{Everyone/Goons/Friendly/Hosiles}} {{that are Active/Finished/Logged}} belonging to everyone

      var start = "Showing all ";
      var item = "";
      var regions = "";
      var standing = "";
      var status = "";
      switch (this.filterItemTypeSelect) {
        case 1:
          item = "structurs ";
          break;

        case 2:
          item = "Ihubs ";
          break;

        case 3:
          item = "TCUs ";
          break;
      }

      switch (this.filterStandingSelect) {
        case 1:
          standing = "";
          break;

        case 2:
          standing = "belonging to Goons ";
          break;

        case 3:
          standing = "belonging to Friendlys ";
          break;

        case 4:
          standing = "belonging to Hostiles ";
          break;
      }

      switch (this.filterStatusSelect) {
        case 1:
          status = "";
          break;

        case 2:
          status = "That are upcoming";
          break;

        case 3:
          status = "that are active.";
          break;

        case 4:
          status = "that have finished.";
          break;
      }
      return start + item + regions + standing + status;
    },

    filteredItemsStart() {
      if (this.filterStandingSelect == 1) {
        return this.operationList.filter(
          (o) => o.campaign[0].alliance.color == 1
        );
      } else if (this.filterStandingSelect == 2) {
        return this.operationList.filter(
          (o) =>
            o.campaign[0].alliance.color == 3 ||
            o.campaign[0].alliance.color == 2
        );
      } else if (this.filterStandingSelect == 3) {
        return this.operationList.filter(
          (o) => o.campaign[0].alliance.color == 3
        );
      } else {
        return this.operationList;
      }
    },

    filteredItemsMid() {
      if (this.filterItemTypeSelect != 1) {
        return this.filteredItemsStart.filter(
          (o) => o.campaign[0]["event_type"] == this.filterItemTypeSelect
        );
      } else {
        return this.filteredItemsStart;
      }
    },

    filterItemsMidMid() {
      if (this.filterStatusSelect != 1) {
        if (this.filterStatusSelect == 2) {
          return this.filteredItemsMid.filter(
            (o) =>
              o.campaign[0]["status_id"] == 1 || o.campaign[0]["status_id"] == 2
          );
        }

        if (this.filterStatusSelect == 3) {
          return this.filteredItemsMid.filter(
            (o) => o.campaign[0]["status_id"] == 2
          );
        }

        if (this.filterStatusSelect == 4) {
          return this.filteredItemsMid.filter(
            (o) =>
              o.campaign[0]["status_id"] == 3 || o.campaign[0]["status_id"] == 4
          );
        }
      } else {
        return this.filteredItemsMid;
      }
    },

    filterEnd() {
      let data = [];
      if (this.regionFilter.length != 0) {
        this.regionFilter.forEach((p) => {
          console.log(f.campaign[0]["constellation"]["region"]["id"]);
          let pick = this.filterItemsMidMid.filter(
            (f) => f.campaign[0]["constellation"]["region"]["id"] == p
          );
          if (pick != null) {
            pick.forEach((pk) => {
              data.push(pk);
            });
          }
        });
        return data;
      }
      return this.filterItemsMidMid;
    },

    regionList() {
      return this.newSoloOperationsRegionList;
    },

    operationList() {
      return this.newSoloOperations;
    },
  },
  beforeDestroy() {},
};
</script>
