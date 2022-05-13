<template>
  <v-row no-gutters>
    <v-col>
      <v-expansion-panels popout>
        <v-expansion-panel>
          <v-expansion-panel-header>
            <span class="text-h2">{{ title }}</span>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-row no-gutters v-for="(item, index) in item" :key="index">
              <CampaignTitleBarContent
                :item="item"
                :title="title"
                :operationID="operationID"
              ></CampaignTitleBarContent>
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
    item: Object,
    title: String,
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
