<template>
  <v-menu :close-on-content-click="false" :value="addShown">
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        text
        v-bind="attrs"
        v-on="on"
        @click="addShown = true"
        color="success"
        ><font-awesome-icon icon="fa-solid fa-plus" size="2xl" pull="left" />
        Node</v-btn
      >
    </template>
    <v-card tile min-height="250px">
      <v-card-title class="pb-0"> </v-card-title>
      <v-card-text>
        <v-text-field
          class="mt-2"
          label="Node"
          placeholder="Enter Node"
          flat
          v-mask="'AA##'"
          v-model="nodeText"
          @keyup.enter="addNode()"
          @keyup.esc="(addShown = false), (nodeText = '')"
        ></v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-btn icon fixed left color="success" @click="addNode()"
          ><font-awesome-icon icon="fa-solid fa-check"
        /></v-btn>

        <v-btn
          fixed
          right
          icon
          color="warning"
          @click="(addShown = false), (nodeText = '')"
          ><font-awesome-icon icon="fa-solid fa-circle-xmark"
        /></v-btn>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    item: Object,
    operationID: Number,
    activeCampaigns: Array,
  },
  data() {
    return {
      addShown: false,
      nodeText: "",
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async addNode() {
      let node = this.nodeText.toUpperCase();
      var request = {
        system_id: this.item.id,
        campaign_id: 0, //TODO need to code this so hardcode it
        name: node,
      };
      this.nodeText = "";
      this.addShown = false;
      await axios({
        method: "POST",
        url: "/api/addnode",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      // TODO Add logging
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    activeCount() {
      return this.activeCampaigns.length;
    },
  },
  beforeDestroy() {},
};
</script>
