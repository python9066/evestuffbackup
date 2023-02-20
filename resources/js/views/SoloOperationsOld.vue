<template>
  <v-row class="pr-5 pl-5 pt-1" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12">
      <v-row no-gutters justify="center">
        <v-col cols="12">
          <v-card elevation="10" rounded="xl" class="mb-5">
            <v-card-title class="justify-center primary pa-3">Operations</v-card-title>
            <v-row no-gutters align="baseline" justify="space-between" class="mt-2 ml-2">
              <v-col cols="1">
                <v-text-field
                  label="Search"
                  v-model="search"
                  filled
                  dense
                  rounded
                  single-line
                  clearable
                  color="blue"
                ></v-text-field>
              </v-col>
              <!-- <v-col cols="2">
                <v-autocomplete
                  label="Region"
                  :items="regionList"
                  v-model="regionPicked"
                  filled
                  dense
                  auto-select-first
                  rounded
                  single-line
                  clearable
                  color="blue"
                ></v-autocomplete> </v-col
              > -->
              <!-- <v-col cols="2">
                <v-autocomplete
                  label="Constellation"
                  :items="constellationList"
                  v-model="constellationPicked"
                  filled
                  dense
                  auto-select-first
                  rounded
                  single-line
                  clearable
                  color="blue"
                ></v-autocomplete>
              </v-col> -->

              <!-- <v-col cols="1">
                <v-btn icon @click="click()">
                  <font-awesome-icon :icon="text" size="2xl" />
                </v-btn>
              </v-col> -->

              <!-- <v-col cols="6">
                <v-row no-gutters
                  ><v-col cols="12" class="d-flex justify-content-around"
                    ><v-btn-toggle
                      rounded
                      active-class="primary--text"
                      column
                      v-model="filterItemTypeSelect"
                    >
                      <v-btn
                        v-for="(list, index) in filterItemTypeSelectList"
                        :key="index"
                        filter
                        :value="list.value"
                        outlined
                        small
                      >
                        {{ list.text }}
                      </v-btn> </v-btn-toggle
                    ><v-btn-toggle
                      rounded
                      active-class="primary--text"
                      column
                      v-model="filterStandingSelect"
                    >
                      <v-btn
                        v-for="(list, index) in filterStandingSelectList"
                        :key="index"
                        filter
                        :value="list.value"
                        outlined
                        small
                      >
                        {{ list.text }}
                      </v-btn>
                    </v-btn-toggle>
                    <v-btn-toggle
                      rounded
                      active-class="primary--text"
                      column
                      mandatory
                      v-model="filterStatusSelect"
                    >
                      <v-btn
                        v-for="(list, index) in filterStatusSelectList"
                        :key="index"
                        filter
                        :value="list.value"
                        outlined
                        small
                      >
                        {{ list.text }}
                      </v-btn>
                    </v-btn-toggle>
                  </v-col>
                </v-row>
              </v-col> -->
            </v-row>
            <!-- <v-card-text class="pa-0">
              <SoloOperationsTable
                :windowSize="windowSize"
                :filteredItems="filterEnd"
                :search="search"
              >
              </SoloOperationsTable>
            </v-card-text> -->
          </v-card>
        </v-col></v-row
      >
    </v-col>
  </v-row>
</template>
<script>
import { mapState } from "vuex";
export default {
  async created() {
    // await this.$store.dispatch("getWebwayStartSystems");
    // await this.$store.dispatch("getSoloOperationList");
    // Echo.private("solooperation").listen("SoloOperationUpdate", (e) => {
    //   // 1 = update solo Op
    //   if (e.flag.flag == 1) {
    //     this.$store.dispatch("updateSoloOperationList", e.flag.message);
    //   }
    // });
  },

  //   async mounted() {
  //     this.onResize();
  //     this.log();
  //   },
  //   title() {
  //     return `EveStuff - Operations`;
  //   },
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },

      filterOpen: false,

      date: null,
      regionPicked: null,
      constellationPicked: null,
      pOnly: 0,

      search: null,

      regionFilter: [],

      filterItemTypeSelect: 32458,
      filterItemTypeSelectList: [
        { text: "Ihub", value: 32458 },
        { text: "TCU", value: 32226 },
      ],

      filterStandingSelect: null,
      filterStandingSelectList: [
        { text: "Goon", value: 3 },
        { text: "Friendly", value: 2 },
        { text: "Hostile", value: 1 },
      ],

      filterStatusSelect: 1,
      filterStatusSelectList: [
        { text: "Upcoming", value: 1 },
        { text: "Active Only", value: 2 },
        { text: "Finished", value: 3 },
      ],
    };
  },

  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    click() {
      if (this.pOnly == 0) {
        this.pOnly = 1;
      } else {
        this.pOnly = 0;
      }
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
    ...mapState([
      "newSoloOperations",
      "newSoloOperationsRegionList",
      "newSoloOperationsConstellationList",
    ]),

    filterRound() {
      if (this.filterOpen == 0) {
        return "rounded-t-xl";
      } else {
        return "rounded-xl";
      }
    },

    filterStart() {
      if (this.filterItemTypeSelect) {
        return this.newSoloOperations.filter(
          (d) => d.campaign[0].event_type == this.filterItemTypeSelect
        );
      } else {
        return this.newSoloOperations;
      }
    },

    filterMind() {
      if (this.regionPicked) {
        return this.filterStart.filter(
          (d) => d.campaign[0].constellation.region_id == this.regionPicked
        );
      } else {
        return this.filterStart;
      }
    },

    filterMind1() {
      if (this.constellationPicked) {
        return this.filterMind.filter(
          (d) => d.campaign[0].constellation_id == this.constellationPicked
        );
      } else {
        return this.filterMind;
      }
    },

    filterMind2() {
      if (this.filterStandingSelect) {
        if (this.filterStandingSelect == 3) {
          return this.filterMind1.filter((d) => d.campaign[0].alliance.color == 3);
        } else if (this.filterStandingSelect == 2) {
          return this.filterMind1.filter((d) => d.campaign[0].alliance.standing > 0);
        } else {
          return this.filterMind1.filter((d) => d.campaign[0].alliance.standing <= 0);
        }
      } else {
        return this.filterMind1;
      }
    },

    filterMind3() {
      if (this.pOnly == 1) {
        return this.filterMind2.filter((d) => d.priority == 1);
      } else {
        return this.filterMind2;
      }
    },

    filterEnd() {
      if (this.filterStatusSelect == 1) {
        return this.filterMind3.filter(
          (d) =>
            d.campaign[0].status_id == 5 ||
            d.campaign[0].status_id == 1 ||
            d.campaign[0].status_id == 2
        );
      } else if (this.filterStatusSelect == 2) {
        return this.filterMind3.filter(
          (d) => d.campaign[0].status_id == 5 || d.campaign[0].status_id == 2
        );
      } else {
        return this.filterMind3.filter(
          (d) => d.campaign[0].status_id == 3 || d.campaign[0].status_id == 4
        );
      }
    },

    text() {
      if (this.pOnly == 0) {
        return "fa-regular fa-bell";
      } else {
        return "fa-solid fa-bell";
      }
    },

    regionList() {
      return this.newSoloOperationsRegionList;
    },
    constellationList() {
      return this.newSoloOperationsConstellationList;
    },

    operationList() {
      return this.newSoloOperations;
    },
  },
  beforeDestroy() {},
};
</script>
