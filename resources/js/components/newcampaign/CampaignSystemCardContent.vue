<template>
  <!-- <div>
    <v-card rounded="xl">
      <v-card-title class="justify-center primary pa-3">{{
        item.system_name
      }}</v-card-title>
      <v-card-text> </v-card-text>
    </v-card>
  </div> -->
  <v-row no-gutters>
    <v-col cols="12">
      <v-row>
        <v-col cols="12"> DAnce DAnce </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <NewSystemTable :item="item"></NewSystemTable>
        </v-col>
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
  title() {},
  props: {
    item: Object,
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
