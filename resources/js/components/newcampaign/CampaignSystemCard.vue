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
        :class="filterRound"
      >
        <v-row no-gutters>
          <v-col cols="2">
            {{ item.system_name }}
          </v-col>
        </v-row>
      </v-expansion-panel-header>
      <v-expansion-panel-content>DANCE DANCE DANCE </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>
<script>
import Axios from "axios";
// import { EventBus } from "../event-bus";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    item: Object,
  },
  data() {
    return {
      showSystemTable: 0,
    };
  },

  async created() {},

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
