<template>
  <v-row no-gutters>
    <v-col cols="auto">
      <v-card rounded="xl"
        ><v-card-title class="red">Post-Op Cooldown</v-card-title
        ><v-card-text>
          <v-row no-gutters align="end">
            <v-col cols="auto">
              <v-checkbox
                @change="changeCheck()"
                hide-details
                dense
                v-model="opInfo.post_op_defrief_done"
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
                  :key="opInfo.post_op_defrief_done"
                  :class="cross(opInfo.post_op_defrief_done)"
                >
                  Post-Op Debrief done
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
                v-model="opInfo.post_op_scouts_done"
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
                  :key="opInfo.post_op_scouts_done"
                  :class="cross(opInfo.post_op_scouts_done)"
                >
                  Scouts Stood Down
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
                v-model="opInfo.post_op_recon_done"
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
                  :key="opInfo.post_op_recon_done"
                  :class="cross(opInfo.post_op_recon_done)"
                >
                  Recon Stood Down
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
                v-model="opInfo.post_op_fc_done"
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
                  :key="opInfo.post_op_fc_done"
                  :class="cross(opInfo.post_op_fc_done)"
                >
                  FC's debriefed and stood down
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
                v-model="opInfo.post_op_coord_done"
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
                  :key="opInfo.post_op_coord_done"
                  :class="cross(opInfo.post_op_coord_done)"
                  >Coord debriefed
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
