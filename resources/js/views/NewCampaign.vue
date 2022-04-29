<template>
  <div>
    <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <CampaignTitleBar :item="campaigns"></CampaignTitleBar>
      </v-col>
    </v-row>
    <v-row no-gutters justify="space-around">
      <v-col
        cols="6"
        class="px-5"
        v-for="(item, index) in systems"
        :key="index"
      >
        <CampaignSystemCard :item="item"></CampaignSystemCard>
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
