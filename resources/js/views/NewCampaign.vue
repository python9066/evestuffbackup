<template>
  <div>
    dance
    <v-row no-gutters>
      <v-col cols="12">
        <CampaignTitleBar :item="campaigns"></CampaignTitleBar>
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
    ...mapGetters([]),

    ...mapState(["newOperationInfo"]),

    campaigns() {
      return this.newOperationInfo.campaign;
    },
  },
  beforeDestroy() {},
};
</script>
