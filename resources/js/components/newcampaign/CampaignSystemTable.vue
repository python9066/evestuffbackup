<template>
  <div>
    <v-card>
      <v-card-title class="justify-center primary pa-3">{{
        item.system_name
      }}</v-card-title>
      <v-card-text> </v-card-text>
    </v-card>
  </div>
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
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapGetters([]),

    ...mapState([]),
  },
  beforeDestroy() {},
};
</script>
