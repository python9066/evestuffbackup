<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-card rounded="xl"
        ><v-card-title class="red pt-1 pb-1"
          ><v-row no-gutters
            ><span class="pr-2">Fleets</span>
            <v-col cols="2">
              <v-btn fab x-small color="blue" @click="addFleet()"
                ><font-awesome-icon icon="fa-solid fa-plus" size="2xl" /></v-btn
            ></v-col>
            <v-col cols="8" class="d-flex justify-center align-center"
              ><span class="mr-3">{{ dankTitle }}</span>
              <AddOperationDankFleetButton />
            </v-col> </v-row></v-card-title
        ><v-card-text :style="style">
          <draggable
            v-model="opInfo.fleets"
            key="drag"
            v-bind="dragOptions"
            handle=".handle"
          >
            <transition-group
              mode="out-in"
              tag="v-row"
              name="flip-list"
              class="no-gutters justify-space-around"
              :enter-active-class="showEnter"
              :leave-active-class="showLeave"
            >
              <!-- <v-row no-gutters justify="space-around"> -->
              <OperationInfoFleetSoloCard
                v-for="fleet in opInfo.fleets"
                :key="`${fleet.id}-card`"
                :loaded="loaded"
                :fleetID="fleet.id"
              />
            </transition-group>
          </draggable>
          <!-- </v-row> -->
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
import draggable from "vuedraggable";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  components: {
    draggable,
  },
  title() {},
  props: {
    loaded: Boolean,
    windowSize: Object,
    drag: false,
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

    dankTitle() {
      if (this.opInfo.dankop) {
        return this.opInfo.dankop.name;
      } else {
        return "Add Dank Link";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__zoomOut";
      }
    },

    style() {
      return (
        "overflow-y: auto; height: " +
        this.heightList +
        "px; max-height: " +
        this.heightList +
        "px;"
      );
      //
    },

    fleetCol() {
      if (this.windowSize.y <= 1000) {
        return 6;
      } else {
        return 4;
      }
    },

    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
  },
  beforeDestroy() {},
};
</script>
<style>
.scroll {
  overflow-y: auto;
}
.compact {
  transform: scale(0.875);
  transform-origin: left;
}

.flip-list-move {
  transition: transform 0.5s;
}
.no-move {
  transition: transform 0s;
}
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
}
</style>
