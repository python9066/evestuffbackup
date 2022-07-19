<template>
  <v-row no-gutters v-resize="onResize" justify="center">
    <v-col cols="12" class="pl-5 pr-5"
      ><v-card rounded="xl"
        ><v-card-title class="primary pt-2 pb-2"
          ><v-row no-gutters justify="space-between">
            <v-col cols="auto">
              <v-row no-gutters>
                <transition
                  mode="out-in"
                  :enter-active-class="showEnter"
                  :leave-active-class="showLeave"
                >
                  <v-col cols="auto" v-if="showCheckList" class="mr-2">
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
                <v-col cols="auto">
                  <OperationInfoMessageCard
                    :loaded="loaded"
                    :windowSize="windowSize"
                /></v-col>
              </v-row>
            </v-col>
            <v-row no-gutters justify="center" align="center">
              <v-col class="py-0 pr-2" cols="auto"
                >Operation - {{ opInfo.name }}
              </v-col>
              <v-col v-if="opInfo.start" class="pt-0 pb-0" cols="auto">{{
                moment(opInfo.start).format("YYYY-MM-DD HH:mm:ss")
              }}</v-col>
              <v-col v-if="showCountDown" class="pt-0 pb-0" cols="auto">
                <CountDowntimer
                  :start-time="moment.utc(opInfo.start).unix()"
                  :end-text="'Campaign Over'"
                  :interval="1000"
                >
                  <template slot="countdown" slot-scope="scope">
                    <span class="red--text pl-3">
                      <span
                        v-if="scope.props.hours > 1 && scope.props.days > 1"
                      >
                        <v-chip color="red">
                          {{ scope.props.days }} - {{ scope.props.hours }}:{{
                            scope.props.minutes
                          }}:{{ scope.props.seconds }}
                        </v-chip>
                      </span>
                      <span
                        v-else-if="
                          scope.props.hours > 1 && scope.props.days == 0
                        "
                      >
                        <v-chip color="red">
                          {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                            scope.props.seconds
                          }}
                        </v-chip>
                      </span>

                      <span v-else>
                        <v-chip color="red">
                          {{ scope.props.minutes }}:{{ scope.props.seconds }}
                        </v-chip>
                      </span>
                    </span>
                  </template>
                </CountDowntimer></v-col
              >

              <v-col class="pt-0 pb-0" cols="auto" v-if="showCountUp">
                <VueCountUptimer
                  :start-time="moment.utc(opInfo.start).unix()"
                  :end-text="'Window Closed'"
                  :interval="1000"
                >
                  <template slot="countup" slot-scope="scope">
                    <span class="green--text pl-3">
                      <span
                        v-if="scope.props.hours > 1 && scope.props.days > 1"
                      >
                        <v-chip color="green">
                          {{ scope.props.days }} - {{ scope.props.hours }}:{{
                            scope.props.minutes
                          }}:{{ scope.props.seconds }}</v-chip
                        >
                      </span>
                      <span
                        v-else-if="
                          scope.props.hours > 1 && scope.props.days == 0
                        "
                      >
                        <v-chip color="green">
                          {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                            scope.props.seconds
                          }}</v-chip
                        >
                      </span>

                      <span v-else>
                        <v-chip color="green">
                          {{ scope.props.minutes }}:{{
                            scope.props.seconds
                          }}</v-chip
                        >
                      </span>
                    </span>
                  </template>
                </VueCountUptimer></v-col
              >
              <v-col v-if="!opInfo.start" class="pt-0 pb-0" cols="auto">
                <AddOperationStartTime />
              </v-col>
            </v-row>
            <v-col cols="auto" class="d-flex justify-content-end"
              ><OperationInfoSettingPannel></OperationInfoSettingPannel
            ></v-col> </v-row></v-card-title
        ><v-card-text class="pt-3">
          <v-row no-gutters justify="space-between">
            <transition
              mode="out-in"
              :enter-active-class="showEnter"
              :leave-active-class="showLeave"
            >
              <v-col cols="auto" v-if="showReconTable">
                <v-row no-gutters>
                  <v-col cols="12">
                    <OperationInfoReconCard
                      :windowSize="windowSize"
                      :loaded="loaded"
                    />
                  </v-col>
                </v-row>
              </v-col>
            </transition>
            <transition
              mode="out-in"
              :enter-active-class="showEnter"
              :leave-active-class="showLeave"
            >
              <v-col cols="8" v-if="showSystemTable">
                <OpertationInfoSystemTable
                  :loaded="loaded"
                  :windowSize="windowSize"
              /></v-col>
            </transition>
            <transition
              mode="out-in"
              :enter-active-class="showEnter"
              :leave-active-class="showLeave"
            >
              <v-col cols="5" v-if="showFleets">
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
import { mapGetters, mapState } from "vuex";
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
    await this.$store.dispatch("getSystemList");
    await this.$store.dispatch("getOperationSheetInfoOperationList");
    await this.$store.dispatch("getOperationInfoJamList");

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

        if (e.flag.flag == 7) {
          this.$store.dispatch("updateOperationMessage", e.flag.message);
        }

        if (e.flag.flag == 8) {
          this.$store.dispatch("updateOperationStatus", e.flag.message);
        }

        if (e.flag.flag == 9) {
          this.$store.dispatch("updateOperationOperation", e.flag.message);
        }

        if (e.flag.flag == 10) {
          this.$store.dispatch("updateOperationCampaigns", e.flag.message);
        }

        if (e.flag.flag == 11) {
          this.$store.dispatch("updateOperationSystems", e.flag.message);
        }

        if (e.flag.flag == 12) {
          this.$store.dispatch("getOperationInfoDoctrines");
        }

        if (e.flag.flag == 13) {
          this.$store.dispatch("removeOperationReconSolo", e.flag.message);
        }

        if (e.flag.flag == 14) {
          this.$store.dispatch("updateOperationSoloSystems", e.flag.message);
        }
      }
    );
  },

  async mounted() {
    this.onResize();
    this.setLoad();
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

    showCheckList() {
      if (this.opSetting.showTickList && this.opInfo.check_list) {
        return true;
      }

      return false;
    },

    showFleets() {
      if (this.opSetting.showFleets && this.opInfo.fleet_table) {
        return true;
      }

      return false;
    },

    showReconTable() {
      if (this.opSetting.showReconTable && this.opInfo.recon_table) {
        return true;
      }

      return false;
    },

    showSystemTable() {
      if (this.opSetting.showSystemTable && this.opInfo.system_table) {
        return true;
      }

      return false;
    },

    showCountDown() {
      if (
        this.opInfo.start &&
        moment.utc().format("YYYY-MM-DD HH:mm:ss") <
          moment(this.opInfo.start).format("YYYY-MM-DD HH:mm:ss")
      ) {
        return true;
      } else {
        return false;
      }
    },

    showCountUp() {
      if (
        this.opInfo.start &&
        moment.utc().format("YYYY-MM-DD HH:mm:ss") >
          moment(this.opInfo.start).format("YYYY-MM-DD HH:mm:ss")
      ) {
        return true;
      } else {
        return false;
      }
    },
  },
  beforeDestroy() {
    Echo.leave("operationinfooppage." + this.opInfo.id);
  },
};
</script>
