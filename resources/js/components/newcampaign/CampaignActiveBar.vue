<template>
  <v-row no-gutters>
    <v-col>
      <v-expansion-panel v-model="showCharTable">
        <v-expansion-panel-header>
          <v-btn @click="btnShowCharTable"> Char Table </v-btn>
        </v-expansion-panel-header>
        <v-expansion-panel-content></v-expansion-panel-content>
      </v-expansion-panel>
    </v-col>
  </v-row>
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
    item: Array,
  },
  data() {
    return {
      showCharTable: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    btnShowCharTable() {},
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),
  },
  beforeDestroy() {},
};
</script>
