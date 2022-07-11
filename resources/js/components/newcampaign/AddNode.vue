<template>
  <v-menu
    v-if="showButton"
    :close-on-content-click="false"
    origin="center center"
    transition="scale-transition"
    :value="addShown"
    rounded="xl"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        text
        v-bind="attrs"
        v-on="on"
        @click="addShown = true"
        color="success"
        ><font-awesome-icon icon="fa-solid fa-plus" size="xl" pull="left" />
        Nodee</v-btn
      >
    </template>
    <v-card :min-height="cardHight">
      <v-card-text>
        <v-select
          v-if="dropDownFocus"
          class="mt-2"
          v-model="nodeCampaignID"
          label="Campaign"
          placeholder="Which Hack is this for"
          item-text="name"
          item-value="id"
          :items="activeCampaigns"
          :autofocus="dropDownFocus"
        >
        </v-select>
        <v-text-field
          label="Node"
          placeholder="Enter Node"
          flat
          :autofocus="textFocus"
          v-mask="'AA##'"
          v-model="nodeText"
          @keyup.enter="addNode()"
          @keyup.esc="
            (addShown = false), (nodeText = ''), (pickedCampaign = [])
          "
        ></v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-btn icon fixed left color="success" @click="addNode()"
          ><font-awesome-icon icon="fa-solid fa-check" size="xl"
        /></v-btn>

        <v-btn
          fixed
          right
          icon
          color="warning"
          @click="(addShown = false), (nodeText = ''), (pickedCampaign = [])"
          ><font-awesome-icon icon="fa-solid fa-circle-xmark" size="xl"
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
      pickedCampaign: [],
      nodeCampaignID: [],
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async addNode() {
      if (this.activeCount == 1) {
        var campaign_id = this.activeCampaigns[0].id;
      } else {
        var campaign_id = this.pickedCampaign.id;
      }
      let node = this.nodeText.toUpperCase();
      var request = {
        system_id: this.item.id,
        campaign_id: campaign_id, //TODO need to code this so hardcode it
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

    ...mapState(["newOperationInfo"]),

    textFocus() {
      if (this.activeCount == 1) {
        return true;
      } else {
        return false;
      }
    },

    showButton() {
      if (this.activeCount > 0) {
        if (this.newOperationInfo.read_only == 1) {
          if (this.$can("view_operation_read_only")) {
            return true;
          } else {
            return false;
          }
        } else {
          return true;
        }
      } else {
        return false;
      }
    },

    dropDownFocus() {
      if (this.activeCount == 1) {
        return false;
      } else {
        return true;
      }
    },

    cardHight() {
      if (this.activeCount == 1) {
        return "150px";
      } else {
        return "230px";
      }
    },

    activeCount() {
      return this.activeCampaigns.length;
    },
  },
  beforeDestroy() {},
};
</script>
