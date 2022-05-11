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
    <v-expansion-panel class="rounded-xl" style="cursor: context-menu">
      <v-expansion-panel-header
        style="cursor: context-menu"
        color="primary"
        class="py-0"
        :class="filterRound"
      >
        <v-card flat max-width elevation="0" color="primery">
          <v-card-title
            max-width
            class="d-flex justify-space-between align-center py-0"
            style="width: 100%"
          >
            <v-row no-gutters>
              <v-col cols="2" class="d-flex justify-start align-center">
                {{ item.system_name }}
              </v-col>
              <v-divider vertical></v-divider>
              <v-col cols="4" class="d-flex justify-start align-center">
                Nodes - 2/3 1/3
              </v-col>
              <v-divider vertical></v-divider>
              <v-col cols="2" class="d-flex justify-center align-center">
                On The Way - 0
              </v-col>
              <v-divider vertical></v-divider>

              <v-col cols="2" class="d-flex justify-center align-center">
                Ready to go - 0
              </v-col>
              <v-divider vertical></v-divider>
              <v-col
                cols="2"
                class="d-flex justify-end align-center"
                v-if="showSystemTable == 0"
              >
                <AddNode :item="item" :operationID="operationID"></AddNode>
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
  methods: {},

  computed: {
    ...mapGetters([]),

    ...mapState([]),

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
