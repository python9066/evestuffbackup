<template>
  <div>
    <v-card>
      <v-card-title>Title</v-card-title>
      <v-card-text>
        <v-row no-gutters v-for="(item, index) in item" :key="index">
          <v-col cols="12">
            {{ item.system.system_name }} - {{ item.defenders_score }}/{{
              item.attackers_score
            }}
          </v-col>
        </v-row>
      </v-card-text>
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
