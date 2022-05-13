<template>
  <v-row no-gutters>
    <v-col>
      <v-expansion-panels popout style="cursor: context-menu">
        <v-expansion-panel style="cursor: context-menu">
          <v-expansion-panel-header expand-icon="" style="cursor: context-menu">
            <span>{{ title }}</span>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-row no-gutters v-for="(item, index) in item" :key="index">
              <v-col cols="12">
                {{ item.system.system_name }} - {{ eventType }} :
                {{ defenders_score }} / {{ attackers_score }}
              </v-col>
            </v-row>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
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
    title: Text,
    operationID: Number,
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
