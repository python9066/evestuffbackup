<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="12">
          <NewSystemTable :item="item" :operationID="operationID" />
        </v-col>
      </v-row>
      <v-row no-gutters class="pt-5">
        <v-col cols="8"><SystemCheckButton :item="item" /></v-col>
        <v-col cols="4"><TidiButton :item="item" /></v-col>
      </v-row>
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
  // TODO ADD ALL THE THINGS FOR MULTI CAMPAIGNS
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
    // TODO Workout for multi campagins
  },

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
