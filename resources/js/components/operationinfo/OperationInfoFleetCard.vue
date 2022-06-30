<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-card rounded="xl" :max-height="heightCard"
        ><v-card-title class="red"
          >Fleets <v-btn fab small @click="addFleet()">A</v-btn></v-card-title
        ><v-card-text :style="style">
          <transition-group
            mode="out-in"
            tag="v-row"
            class="no-gutters justify-space-around"
            :enter-active-class="showEnter"
            :leave-active-class="showLeave"
          >
            <!-- <v-row no-gutters justify="space-around" key="dance"> -->
            <v-col
              cols="4"
              v-for="fleet in opInfo.fleets"
              :key="fleet.id"
              class="px-5"
            >
              <!-- <transition
                mode="out-in"
                :enter-active-class="showEnter"
                :leave-active-class="showLeave"
              > -->
              <OperationInfoFleetSoloCard
                :loaded="loaded"
                :fleetID="fleet.id"
              />
              <!-- </transition> -->
            </v-col>
            <!-- </v-row> -->
          </transition-group>
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
    windowSize: Object,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async addFleet() {
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinfofleet/" + this.opInfo.id,
        withCredentials: true,
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
        return "animate__animated animate__zoomIn";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__zoomOut";
      }
    },

    heightCard() {
      let num = this.windowSize.y - 232;
      return num;
    },

    heightList() {
      let num = this.windowSize.y - 300;
      return num;
    },

    style() {
      return "height: " + this.heightList + "px; overflow-y: scroll";
    },
  },
  beforeDestroy() {},
};
</script>
<style>
.scroll {
  overflow-y: scroll;
}
</style>
