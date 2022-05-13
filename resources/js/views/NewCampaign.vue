<template>
  <div>
    <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <CampaignTitleBar
          :operationID="operationID"
          :title="newOperationInfo.title"
        ></CampaignTitleBar>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <CampaignActiveBar :operationID="operationID"></CampaignActiveBar>
      </v-col>
    </v-row>
    <v-row no-gutters justify="space-around">
      <v-col
        cols="6"
        class="px-5"
        v-for="(item, index) in systems"
        :key="index.id"
      >
        <CampaignSystemCard
          :key="`${index.id}-card`"
          :item="item"
          :operationID="operationID"
        ></CampaignSystemCard>
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
    this.operationLink = this.$route.params.id;
    await this.$store.dispatch("getOperationInfo", this.operationLink);
    Echo.private("operations." + this.operationID).listen(
      "OperationUpdate",
      (e) => {
        if (e.flag.flag == 1) {
        }

        if (e.flag.flag == 2) {
        }
        if (e.flag.flag == 3) {
        }

        // * solo system update
        if (e.flag.flag == 4) {
          this.$store.dispatch("updateNewCampaigns", e.flag.message);
        }

        // * 5 is to remove op char from  chartable
        if (e.flag.flag == 5) {
          this.$store.dispatch("removeCharfromOpList", e.flag.userid);
        }

        // * 6 update op char table
        if (e.flag.flag == 6) {
          this.$store.dispatch("updateOpChar", e.flag.message);
        }

        if (e.flag.flag == 7) {
          this.$store.dispatch("updateNewCampaignSystem", e.flag.message);
        }

        if (e.flag.flag == 8) {
        }
      }
    );

    Echo.private("operationsown." + this.$store.state.user_id).listen(
      "OperationOwnUpdate",
      (e) => {
        if (e.flag.flag == 1) {
        }

        if (e.flag.flag == 2) {
        }
        // * 3 add/update char to char table
        if (e.flag.flag == 3) {
          this.$store.dispatch("updateNewOwnChar", e.flag.message);
        }
        if (e.flag.flag == 4) {
        }
        // * 5 is to remove op char from own list
        if (e.flag.flag == 5) {
          this.$store.dispatch("removeCharfromOwnList", e.flag.userid);
        }

        if (e.flag.flag == 6) {
        }

        if (e.flag.flag == 7) {
        }

        if (e.flag.flag == 8) {
        }
      }
    );
  },

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapState(["newOperationInfo", "newCampaignSystems"]),
    ...mapGetters([]),

    operationID() {
      return this.newOperationInfo.id;
    },

    systems() {
      return this.newCampaignSystems;
    },
  },
  beforeDestroy() {
    Echo.leave("operations." + this.operationID);
    Echo.leave("operationsown." + this.$store.state.user_id);
  },
};
</script>
