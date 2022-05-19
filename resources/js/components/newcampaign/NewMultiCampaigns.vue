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
              <v-data-table
                :headers="headers"
                :items="campaigns"
                item-key="id"
                :items-per-page="25"
                :footer-props="{
                  'items-per-page-options': [15, 25, 50, 100, -1],
                }"
                class="elevation-1"
              >
                <!-- @click:row="rowClick($event)" -->
                <template slot="no-data">
                  No Multi Campaigns have been made
                </template>
                <template v-slot:[`item.system`]="{ item }">
                  <NewSystemItemList :campaigns="item.campaign">
                  </NewSystemItemList>
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                  <!-- <div class="d-inline-flex">
                    <v-btn
                      icon
                      @click="
                        (overlayEditID = item.id),
                          (overlayEditName = item.name),
                          (overlayEdit = !overlayEdit)
                      "
                      color="warning"
                      ><font-awesome-icon
                        icon="fa-solid fa-pen-to-square"
                        size="2xl"
                    /></v-btn>
                    <DeleteButton :item="item"></DeleteButton>
                    <v-btn @click="clickCampaign(item)" color="green"
                      >View</v-btn
                    >
                  </div> -->
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
  methods: {},
  computed: {
    ...mapState(["newOperationList"]),

    campaigns() {
      return this.newOperationList;
    },
  },
  beforeDestroy() {},
};
</script>
