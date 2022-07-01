<template>
  <v-row no-gutters v-resize="onResize" justify="center">
    <v-col cols="12" class="pl-5 pr-5"
      ><v-card rounded="xl"
        ><v-card-title class="primary pt-0 pb-0"
          ><v-row no-gutters justify="center"
            ><v-col cols="auto"
              ><v-row no-gutters justify="center"></v-row>
              <v-col class="pt-0 pb-0" cols="auto"
                >Operation - {{ opInfo.name }}</v-col
              >
              <v-row no-gutters justify="center"></v-row>
              <v-col v-if="opInfo.start" class="pt-0 pb-0" cols="auto">{{
                moment(opInfo.start).format("YYYY-MM-DD HH:mm:ss")
              }}</v-col>
            </v-col></v-row
          ></v-card-title
        ><v-card-text class="pt-3">
          <v-row no-gutters justify="space-between">
            <v-col cols="auto">
              <v-row no-gutters>
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <v-col cols="aut" v-if="opSetting.showTickList">
                    <transition
                      mode="out-in"
                      :enter-active-class="showEnter"
                      :leave-active-class="showLeave"
                    >
                      <OperationInfoPlanningCard
                        :loaded="loaded"
                        v-if="showCard == 1"
                      />
                      <OperationPreOpFormUpCard
                        :loaded="loaded"
                        v-if="showCard == 2"
                      />
                      <OperationInfoPostOpCard
                        :loaded="loaded"
                        v-if="showCard == 3"
                      />
                    </transition>
                  </v-col>
                </transition>
              </v-row>
              <v-row no-gutters>
                <v-col cols="12">
                  <OperationInfoReconCard :loaded="loaded" />
                </v-col>
              </v-row>
            </v-col>
            <transition
              mode="out-in"
              :enter-active-class="showEnter"
              :leave-active-class="showLeave"
            >
              <v-col cols="5" v-if="opSetting.showMessageTable">
                <OperationInfoMessageCard
                  :loaded="loaded"
                  :windowSize="windowSize" /></v-col
            ></transition>
            <transition
              mode="out-in"
              :enter-active-class="showEnter"
              :leave-active-class="showLeave"
            >
              <v-col :cols="fleetCardCols" v-if="opSetting.showFleets">
                <OperationInfoFleetCard
                  :loaded="loaded"
                  :windowSize="windowSize"
              /></v-col> </transition></v-row></v-card-text></v-card
    ></v-col>
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
  title() {
    return `EveStuff - MultiCampaigns`;
  },
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },
      loaded: false,
    };
  },

  async created() {
    await this.$store.dispatch(
      "getOperationSheetInfoPage",
      this.$route.params.link
    );
    await this.$store.dispatch("getOperationUsers");
    await this.$store.dispatch("getOperationInfoMumble");
    await this.$store.dispatch("getOperationInfoDoctrines");
    await this.$store.dispatch("getOperationRecon");
    await this.$store.dispatch("getAllianceTickList");

    Echo.private("operationinfooppage." + this.opInfo.id).listen(
      "OperationInfoPageSoloUpdate",
      (e) => {
        if (e.flag.flag == 1) {
          this.$store.dispatch("updateOperationSheetInfoPage", e.flag.message);
        }

        if (e.flag.flag == 2) {
          this.$store.dispatch(
            "updateOperationSheetInfoPageFleet",
            e.flag.message
          );
        }

        if (e.flag.flag == 3) {
          this.$store.dispatch("updateOperationUsers", e.flag.message);
        }

        if (e.flag.flag == 4) {
          this.$store.dispatch("updateOperationRecon", e.flag.message);
        }

        if (e.flag.flag == 5) {
          this.$store.dispatch("updateOperationReconSolo", e.flag.message);
        }

        if (e.flag.flag == 6) {
          this.$store.dispatch(
            "deleteOperationSheetInfoPageFleet",
            e.flag.message
          );
        }
      }
    );
  },

  async mounted() {
    this.onResize();
    this.setLoad();
    console.log(this.$vuetify.breakpoint.width);
    console.log(this.$vuetify.breakpoint.name);
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    async setLoad() {
      await sleep(1000);
      this.loaded = true;
    },
  },
  computed: {
    ...mapState[
      ("operationInfoPage", "operationInfoUsers", "operationInfoSetting")
    ],

    fleetCardCols() {
      if (this.opSetting.showMessageTable) {
        return 5;
      } else {
        return 10;
      }
    },

    opSetting: {
      get() {
        return this.$store.state.operationInfoSetting;
      },
      set(newValue) {
        return this.$store.dispatch(
          "updateOperationSheetInfoSetting",
          newValue
        );
      },
    },

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },

    showEnter() {
      if (this.loaded == true) {
        return "animate__animated animate__fadeIn animate__faster";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__fadeOut animate__faster";
      }
    },

    showCard() {
      if (this.opInfo.status) {
        return this.opInfo.status.id;
      } else {
        return 0;
      }
    },
  },
  beforeDestroy() {
    Echo.leave("operationinfooppage." + this.opInfo.id);
  },
};
</script>
