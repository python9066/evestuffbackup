<template>
  <v-row no-gutters justify="center">
    <v-col cols="10">
      <v-row no-gutters
        ><v-col>
          <v-card elevation="10" rounded="xl">
            <v-card-title class="primary">
              Custom Operation
              <AddMultiCampaign class="pl-3"></AddMultiCampaign>
            </v-card-title>
            <v-card-text>
              <v-data-table
                :headers="headers"
                :items="campaigns"
                fixed-header
                item-key="id"
                :items-per-page="25"
                :footer-props="{
                  'items-per-page-options': [15, 25, 50, 100, -1],
                }"
              >
                <!-- @click:row="rowClick($event)" -->
                <template slot="no-data">
                  No Multi Campaigns have been made
                </template>
                <template v-slot:[`item.campaign`]="{ item }">
                  <NewSystemItemList :campaigns="item.campaign" />
                </template>
                <template v-slot:[`item.status`]="{ item }">
                  {{ operationStatus(item) }}
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                  <v-row no-gutters justify="end">
                    <v-col cols="auto">
                      <EditOperation :operation="item" />
                    </v-col>
                    <v-col cols="auto">
                      <NewCustomCampaignDeleteButton
                        :item="item"
                      ></NewCustomCampaignDeleteButton>
                    </v-col>
                    <v-col cols="auto">
                      <v-btn @click="clickCampaign(item)" color="green"
                        >View</v-btn
                      >
                    </v-col>
                  </v-row>
                </template>

                <!-- <template v-slot:actions.="{ item }">
                LALALALA
            </template> -->
              </v-data-table>
            </v-card-text></v-card
          ></v-col
        ></v-row
      >
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import moment, { now, unix, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  props: {
    windowSize: Object,
  },
  data() {
    return {
      headers: [
        { text: "Name", value: "title", width: "10%" },
        {
          text: "System - Target",
          value: "campaign",
          width: "70%",
          align: "center",
        },
        { text: "Status", value: "status", align: "end" },
        { text: "", value: "actions", align: "end" },
        // { text: "", value: "actions" },
      ],
    };
  },

  created() {
    this.$store.dispatch("getNewCampaignsList");
    this.$store.dispatch("getCustomOperationList");
  },

  async mounted() {},
  methods: {
    operationStatus(item) {
      if (item.status == 1) {
        return "Active";
      }
    },

    clickCampaign(item) {
      this.$router.push({ path: `/op/${item.link}` }); // -> /user/123
    },
  },
  computed: {
    ...mapState(["newOperationList"]),

    campaigns() {
      return this.newOperationList;
    },

    height() {
      let num = this.windowSize.y - 375 * 2;
      return num;
    },
  },
  beforeDestroy() {},
};
</script>
