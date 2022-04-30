<template>
  <div>
    <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <CampaignTitleBar
          :item="campaigns"
          :operationID="operationID"
        ></CampaignTitleBar>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <CampaignActiveBar :operationID="operationID"></CampaignActiveBar>
      </v-col>
    </v-row>
    <v-row no-gutters justify="space-around">
      <draggable v-model="systems">
        <transition-group type="transition" name="flip-list">
          <v-col
            cols="6"
            class="px-5"
            v-for="(item, index) in systems"
            :key="index.id"
          >
            <CampaignSystemCard
              :item="item"
              :operationID="operationID"
            ></CampaignSystemCard>
          </v-col>
        </transition-group>
      </draggable>
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
    this.$store.dispatch("getOperationInfo", this.operationLink);
  },

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapState(["newOperationInfo", "campaignSystems"]),
    ...mapGetters([]),

    operationID() {
      return this.newOperationInfo.id;
    },

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

<style>
.button {
  margin-top: 35px;
}
.flip-list-move {
  transition: transform 0.5s;
}
.no-move {
  transition: transform 0s;
}
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
}
.list-group {
  min-height: 20px;
}
.list-group-item {
  cursor: move;
}
.list-group-item i {
  cursor: pointer;
}
</style>
