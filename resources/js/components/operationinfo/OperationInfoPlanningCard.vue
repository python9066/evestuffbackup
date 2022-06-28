<template>
  <v-row no-gutters>
    <v-col cols="auto">
      <v-card rounded="xl"
        ><v-card-title class="green">Pre-Op Planning</v-card-title
        ><v-card-text>
          <v-row no-gutters>
            <v-col cols="auto">Formup Time</v-col><v-col cols="auto"></v-col>
          </v-row>
          <v-row no-gutters align="end">
            <v-col cols="auto">
              <v-checkbox
                @change="changeCheck()"
                hide-details
                dense
                v-model="opInfo.planing_op_posted"
                :false-value="0"
                :true-value="1"
              >
              </v-checkbox>
            </v-col>
            <v-col cols="auto">
              <transition
                mode="out-in"
                :enter-active-class="showEnter"
                :leave-active-class="showLeave"
              >
                <span
                  :key="opInfo.planing_op_posted"
                  :class="cross(opInfo.planing_op_posted)"
                >
                  Forum OP Posted?
                </span>
              </transition>
            </v-col>
          </v-row>
          <v-row no-gutters align="end">
            <v-col cols="auto">
              <v-checkbox
                @change="changeCheck()"
                hide-details
                dense
                v-model="opInfo.planing_op_pre_ping"
                :false-value="0"
                :true-value="1"
              >
              </v-checkbox>
            </v-col>
            <v-col cols="auto">
              <transition
                mode="out-in"
                :enter-active-class="showEnter"
                :leave-active-class="showLeave"
              >
                <span
                  :key="opInfo.planing_op_pre_ping"
                  :class="cross(opInfo.planing_op_pre_ping)"
                >
                  OP Pre-Pinged?
                </span>
              </transition>
            </v-col>
          </v-row>
          <v-row no-gutters align="end">
            <v-col cols="auto">
              <v-checkbox
                @change="changeCheck()"
                hide-details
                dense
                v-model="opInfo.planing_op_recon_alerted"
                :false-value="0"
                :true-value="1"
              >
              </v-checkbox>
            </v-col>
            <v-col cols="auto">
              <transition
                mode="out-in"
                :enter-active-class="showEnter"
                :leave-active-class="showLeave"
              >
                <span
                  :key="opInfo.planing_op_recon_alerted"
                  :class="cross(opInfo.planing_op_recon_alerted)"
                >
                  Reacon Informed
                </span>
              </transition>
            </v-col>
          </v-row>
          <v-row no-gutters align="end">
            <v-col cols="auto">
              <v-checkbox
                @change="changeCheck()"
                hide-details
                dense
                v-model="opInfo.planing_op_capital_fc_found"
                :false-value="0"
                :true-value="1"
              >
              </v-checkbox>
            </v-col>
            <v-col cols="auto">
              <transition
                mode="out-in"
                :enter-active-class="showEnter"
                :leave-active-class="showLeave"
              >
                <span
                  :key="opInfo.planing_op_capital_fc_found"
                  :class="cross(opInfo.planing_op_capital_fc_found)"
                >
                  Capaital FC's Found
                </span>
              </transition>
            </v-col>
          </v-row>
          <v-row no-gutters align="end"
            ><v-col cols="auto"
              ><v-checkbox
                @change="changeCheck()"
                hide-details
                dense
                v-model="opInfo.planing_op_fc_found"
                :false-value="0"
                :true-value="1"
              >
              </v-checkbox>
            </v-col>
            <v-col cols="auto">
              <transition
                mode="out-in"
                :enter-active-class="showEnter"
                :leave-active-class="showLeave"
              >
                <span
                  :key="opInfo.planing_op_fc_found"
                  :class="cross(opInfo.planing_op_fc_found)"
                  >FC's Found
                </span>
              </transition>
            </v-col>
          </v-row>
          <v-row no-gutters align="end"
            ><v-col cols="auto"
              ><v-checkbox
                @change="changeCheck()"
                hide-details
                dense
                v-model="opInfo.planing_op_doctromes_decoded"
                :false-value="0"
                :true-value="1"
              >
              </v-checkbox>
            </v-col>
            <v-col cols="auto">
              <transition
                mode="out-in"
                :enter-active-class="showEnter"
                :leave-active-class="showLeave"
              >
                <span
                  :key="opInfo.planing_op_doctromes_decoded"
                  :class="cross(opInfo.planing_op_doctromes_decoded)"
                  >Doctrines decided
                </span>
              </transition>
            </v-col>
          </v-row>
          <v-row no-gutters align="end"
            ><v-col cols="auto"
              ><v-checkbox
                @change="changeCheck()"
                hide-details
                dense
                v-model="opInfo.planing_op_allies_infored"
                :false-value="0"
                :true-value="1"
              >
              </v-checkbox>
            </v-col>
            <v-col cols="auto">
              <transition
                mode="out-in"
                :enter-active-class="showEnter"
                :leave-active-class="showLeave"
              >
                <span
                  :key="opInfo.planing_op_allies_infored"
                  :class="cross(opInfo.planing_op_allies_infored)"
                  >Allies Informed
                </span>
              </transition>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    loaded: Boolean,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    cross(value) {
      if (value == 1) {
        return "text-decoration-line-through green--text";
      }
    },

    async changeCheck() {
      console.log(this.opInfo);
      var request = this.opInfo;
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/operationinfopage/" + this.opInfo.id,
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
    ...mapGetters([]),

    ...mapState["operationInfoPage"],

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
        return "animate__animated animate__flash animate__faster";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },
  },
  beforeDestroy() {},
};
</script>
