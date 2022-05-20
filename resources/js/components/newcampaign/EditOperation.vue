<template>
  <v-row no-gutters>
    <v-col>
      <v-dialog v-model="overlay" min-width="1200" max-width="1200" z-index="0">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            class="mr-4"
            color="green lighten-1"
            v-bind="attrs"
            v-on="on"
            icon
            @click="open()"
            small
          ></v-btn>
          <font-awesome-icon icon="fa-solid fa-pen-to-square" size="2xl" />
        </template>
        <v-card min-width="1200" max-width="1200">
          <v-card-title> Edit your Mulit-Campaign Here </v-card-title>
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
              :items="newCampaignsList"
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
    </v-col>
  </v-row>
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
    operation: Object,
  },
  data() {
    return {
      picked: [],
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    open() {
      this.picked = this.operation.campaign.map((c) => c.id);
    },

    addCampaignClose() {},

    addCampaignDone() {},
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["newCampaignsList"]),
  },
  beforeDestroy() {},
};
</script>
