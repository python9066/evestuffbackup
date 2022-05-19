<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-row no-gutters
        ><v-col>
          <v-card>
            <v-card-title>
              Custom Operation
              <AddMultiCampaign></AddMultiCampaign>
            </v-card-title>
            <v-card-text>
              <!-- <v-data-table
                :headers="headers"
                :items="items"
                item-key="id"
                :items-per-page="25"
                :footer-props="{
                  'items-per-page-options': [15, 25, 50, 100, -1],
                }"
                class="elevation-1"
              >
                <template slot="no-data">
                  No Multi Campaigns have been made
                </template>
                <template v-slot:[`item.system`]="{ item }"> </template>
                <template v-slot:[`item.actions`]="{ item }">
                  <div class="d-inline-flex">
                    <v-btn @click="clickCampaign(item)" color="green"
                      >View</v-btn
                    >
                  </div>
                </template>
              </v-data-table> -->
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
  data() {
    return {
      headers: [
        { text: "Name", value: "name", width: "10%" },
        {
          text: "System - Target",
          value: "system",
          width: "70%",
          align: "center",
        },
        { text: "Status", value: "status_name", align: "end" },
        { text: "", value: "actions", align: "end" },
        // { text: "", value: "actions" },
      ],
    };
  },

  created() {
    this.$store.dispatch("getNewCampaignsList");
  },

  async mounted() {},
  methods: {},
  computed: {
    ...mapState([]),
  },
  beforeDestroy() {},
};
</script>
