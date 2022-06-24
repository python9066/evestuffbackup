<template>
  <div class="d-inline-flex">
    <span v-if="systemcount">
      <span v-for="(campaign, index) in campaigns" :key="index" class="pr-2">
        <v-chip pill :color="pillcolor(campaign)" dark>
          <span> {{ text(campaign) }}</span>
        </v-chip>
      </span>
    </span>
    <span v-else>
      <div>All Campaigns have finished</div>
    </span>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    campaigns: Array,
  },
  data() {
    return {
      test: "",
    };
  },

  created() {},
  async mounted() {},

  methods: {
    addCampaignClose() {
      this.picked = [];
      this.name = "";
    },

    text(campaign) {
      var name = campaign.system.system_name;
      if (campaign.event_type == "32458") {
        var type = "Ihub";
      } else {
        var type = "TCU";
      }

      var text = name + " - " + type;
      return text;
    },

    pillcolor(campaign) {
      if (campaign.alliance.standing < 0) {
        return "red darken-4";
      } else if (campaign.alliance.standing == 0) {
        ("gray");
      } else {
        return "blue darken-4";
      }
    },
  },

  computed: {
    ...mapGetters([]),

    systemcount() {
      let count = this.campaigns.length;
      if (count == 0) {
        return false;
      } else {
        return true;
      }
    },
  },
};
</script>
