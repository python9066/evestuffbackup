<template>
  <v-row no-gutters>
    <v-col cols="12"></v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  props: {},
  data() {
    return {};
  },

  async created() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapGetters([]),
  },
  beforeDestroy() {},
};
</script>

<style></style>
