<template>
  <div>
    <v-row no-gutters justify="center">
      <v-col cols="10">
        <CampaignTitleBar :item="campaigns"></CampaignTitleBar>
      </v-col>
    </v-row>
    <v-row no-gutters justify="space-between">
      <v-col cols="5" v-for="(item, index) in systems" :key="index">
        <CampaignSystemTable :item="item"></CampaignSystemTable>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  data() {
    return {};
  },

  async created() {
    this.operationID = this.$route.params.id;
    this.$store.dispatch("getOperationInfo", this.operationID);
  },

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapState(["newOperationInfo", "campaignSystems"]),
    ...mapGetters([]),

    campaigns() {
      return this.newOperationInfo.campaign;
    },

    systems() {
      return this.campaignSystems;
    },
  },
  beforeDestroy() {},
};
</script>
