<template>
  <div>
    dance
    <v-row no-gutters>
      <v-col cols="12">
        <CampaignTitleBar :item="campaigns"></CampaignTitleBar>
      </v-col>
    </v-row>
    <v-row no-gutters>
      <v-col cols="6" v-for="(item, index) in systems" :key="index">
        {{ item.system_name }}
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
