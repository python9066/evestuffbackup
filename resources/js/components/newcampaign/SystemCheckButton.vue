<template>
  <v-row no-gutters align="baseline">
    <v-col cols="3">
      <v-btn color="green" small @click="checkClick()">
        <v-icon small left dark> fas fa-search-location </v-icon>
        System Checked</v-btn
      ></v-col
    >
    <v-col cols="9">This is where the text will go when button pressed </v-col>
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
  props: {},
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    checkClick() {},
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),
  },
  beforeDestroy() {},
};
</script>
