<template>
  <div class="grey lighten-5">
    <v-row no-gutters class="blue" justify="center">
      <!-- <v-col cols="6"> -->
      <v-col lg="1">
        <v-card class="pa-2" outlined tile> 1 of 3 </v-card>
      </v-col>
      <v-col md="1">
        <v-card class="pa-2" outlined tile> Variable width content </v-card>
      </v-col>
      <v-col lg="1">
        <v-card class="pa-2" outlined tile> 3 of 3 </v-card>
      </v-col>
      <v-btn @click="load()">test</v-btn>
      <!-- </v-col> -->
    </v-row>
  </div>
</template>
<!-- {{ $route.params.id }} - {{ test }} -  -->
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  data() {
    return {
      test: 1,
      test2: "",
      alignmentsAvailable: ["start", "center", "end", "baseline", "stretch"],
      rcdata: null,
      alignment: "center",
      dense: false,
      justifyAvailable: [
        "start",
        "center",
        "end",
        "space-around",
        "space-between",
      ],
      justify: "center",
    };
  },

  beforeMonunt() {},

  beforeCreate() {},

  async mounted() {
    if (this.$store.getters.getCampaignsCount == 0) {
      await this.$store.dispatch("getCampaigns");
    }
  },
  methods: {
    async load() {
      await axios({
        method: "get",
        url: "/api/test",
        headers: {
          Authorization: "Bearer " + this.$store.state.token,
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  async created() {
    this.test = 2;
    this.test2 = 1;
    this.navdrawer = true;
  },

  computed: {
    ...mapGetters([
      "getCampaignById",
      "getActiveCampaigns",
      "getCampaignsCount",
    ]),

    campaign() {
      return this.getCampaignById(this.$route.params.id);
    },
    barScoure() {
      var d = this.getCampaignById(this.$route.params.id).defenders_score * 100;
      var a = this.getCampaignById(this.$route.params.id).attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barBgcolor() {
      var d = this.getCampaignById(this.$route.params.id).defenders_score * 100;
      var a = this.getCampaignById(this.$route.params.id).attackers_score * 100;

      if (d > 50) {
        return "red darken-4";
      }

      return "blue darken-4";
    },

    barColor() {
      var d = this.getCampaignById(this.$route.params.id).defenders_score * 100;
      if (d > 50) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    barReverse() {
      var d = this.getCampaignById(this.$route.params.id).defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },

    barActive() {
      if (this.getCampaignById(this.$route.params.id).status_id > 1) {
        return true;
      }
      return false;
    },
  },
};
</script>
