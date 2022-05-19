<template>
  <div>
    <v-dialog
      v-model="overlay"
      min-width="1200"
      max-width="1200"
      z-index="0"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          class="mr-4"
          color="green lighten-1"
          v-bind="attrs"
          v-on="on"
          rounded
          small
          >Add Campaign</v-btn
        >
      </template>
      <v-card min-width="1200" max-width="1200">
        <v-card-title> Make your Mulit-Campaign Here </v-card-title>
        <v-card-text>
          <v-text-field
            label="Multi-Campaign Name"
            v-model="name"
            hint="Enter The name of your Campaign here"
            filled
          >
          </v-text-field>
          <v-select
            v-model="picked"
            :items="newCampaignslist"
            label="Select"
            multiple
            chips
            deletable-chips
            hint="Which Campaigns do you want"
            persistent-hint
          ></v-select>
        </v-card-text>
        <v-card-actions>
          <v-btn color="success" class="mr-4" @click="addCampaignDone()"
            >Done</v-btn
          >
          <v-btn color="warning" class="mr-4" @click="addCampaignClose()"
            >Close</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
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
    return {};
  },

  created() {
    this.$store.dispatch("newCampaignslist");
  },

  async mounted() {},
  methods: {},
  computed: {
    ...mapState([""]),
  },
  beforeDestroy() {},
};
</script>
