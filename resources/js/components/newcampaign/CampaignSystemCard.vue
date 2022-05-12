<template>
  <!-- <div>
    <v-card rounded="xl">
      <v-card-title class="justify-center primary pa-3">{{
        item.system_name
      }}</v-card-title>
      <v-card-text> </v-card-text>
    </v-card>
  </div> -->

  <v-expansion-panels
    class="pb-5"
    v-model="showSystemTable"
    popout
    style="cursor: context-menu"
  >
    <v-expansion-panel class="rounded-xl" style="cursor: context-menu" readonly>
      <v-expansion-panel-header
        style="cursor: context-menu"
        class="py-0 pr-2"
        :class="filterRound"
        hide-actions
      >
        <v-card flat max-width elevation="0">
          <v-card-title
            max-width
            class="d-flex justify-space-between align-center py-0 px-0"
            style="width: 100%"
          >
            <v-row no-gutters>
              <v-col cols="1" class="d-flex justify-start align-center mr-2">
                {{ item.system_name }}
              </v-col>
              <v-divider class="mx-4" vertical></v-divider>
              <v-col cols="3" class="d-flex justify-start align-center">
                <SystemNodeCount :item="item.new_nodes" />
              </v-col>
              <v-spacer></v-spacer>
              <v-divider class="mx-4" vertical></v-divider>
              <v-col cols="3" class="d-flex justify-center align-center">
                <OnTheWay :operationID="operationID" :item="item"></OnTheWay>
              </v-col>
              <v-divider class="mx-4" vertical></v-divider>

              <v-col cols="3" class="d-flex justify-center align-center">
                <ReadyToGo :operationID="operationID" :item="item"></ReadyToGo>
              </v-col>
              <v-divider class="mx-4" vertical></v-divider>
              <v-spacer></v-spacer>
              <v-col cols="1" class="d-flex justify-end align-center">
                <v-btn icon @click="clickIcon()">
                  <v-icon :class="iconRotate">fas fa-angle-up</v-icon></v-btn
                >
              </v-col>
            </v-row>
          </v-card-title>
        </v-card>
      </v-expansion-panel-header>
      <v-expansion-panel-content
        ><CampaignSystemCardContent
          :item="item"
          :operationID="operationID"
        ></CampaignSystemCardContent>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
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
    item: Object,
    operationID: Number,
  },
  data() {
    return {
      showSystemTable: 0,
    };
  },

  async created() {
    EventBus.$on("showSystemTable", (data) => {
      if (data == 0) {
        this.showSystemTable = null;
      } else {
        this.showSystemTable = 0;
      }
    });
  },

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    clickIcon() {
      if (this.showSystemTable == 0) {
        this.showSystemTable = null;
      } else {
        this.showSystemTable = 0;
      }
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    iconRotate() {
      if (this.showSystemTable == 0) {
        return "toggleUpDown";
      } else {
        return "toggleUpDown rotate";
      }
    },

    filterRound() {
      if (this.showSystemTable) {
        return "rounded-t-xl";
      } else {
        return "rounded-xl";
      }
    },
  },
  beforeDestroy() {},
};
</script>

<style scoped>
.toggleUpDown {
  transition: transform 0.3s ease-in-out !important;
}

.toggleUpDown.rotate {
  transform: rotate(180deg);
}
</style>


