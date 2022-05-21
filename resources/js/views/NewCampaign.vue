<template>
  <div>
    <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <CampaignTitleBar
          :operationID="operationID"
          :title="newOperationInfo.title"
          :activeCampaigns="activeCampaigns"
          :warmUpCampaigns="warmUpCampaigns"
        ></CampaignTitleBar>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center" class="pb-5">
      <v-col cols="10">
        <CampaignActiveBar :operationID="operationID"></CampaignActiveBar>
      </v-col>
    </v-row>
    <v-row no-gutters justify="space-around">
      <transition
        name="custom-classes"
        enter-active-class="animate__animated animate__bounceIn animate__repeat-2"
        leave-active-class="animate__animated animate__bounceIn animate__faster"
        mode="out-in"
      >
        <v-col
          cols="6"
          class="px-5"
          v-for="(item, index) in openSystems"
          :key="`${index.id}-cell`"
        >
          <CampaignSystemCard
            :key="`${index.id}-card`"
            :item="item"
            :operationID="operationID"
          ></CampaignSystemCard>
        </v-col>
      </transition>
    </v-row>
  </div>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
import moment from "moment";

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
    await this.$store.dispatch("getOperationInfo", this.operationLink);
    Echo.private("operations." + this.operationID).listen(
      "OperationUpdate",
      (e) => {
        if (e.flag.flag == 1) {
        }

        if (e.flag.flag == 2) {
        }
        if (e.flag.flag == 3) {
        }

        // * solo system update
        if (e.flag.flag == 4) {
          this.$store.dispatch("updateNewCampaigns", e.flag.message);
        }

        // * 5 is to remove op char from  chartable
        if (e.flag.flag == 5) {
          this.$store.dispatch("removeCharfromOpList", e.flag.userid);
        }

        // * 6 update op char table
        if (e.flag.flag == 6) {
          this.$store.dispatch("updateOpChar", e.flag.message);
        }

        if (e.flag.flag == 7) {
          this.$store.dispatch("updateNewCampaignSystem", e.flag.message);
        }

        if (e.flag.flag == 8) {
        }
      }
    );

    Echo.private("operationsown." + this.$store.state.user_id).listen(
      "OperationOwnUpdate",
      (e) => {
        if (e.flag.flag == 1) {
        }

        if (e.flag.flag == 2) {
        }
        // * 3 add/update char to char table
        if (e.flag.flag == 3) {
          this.$store.dispatch("updateNewOwnChar", e.flag.message);
        }
        if (e.flag.flag == 4) {
        }
        // * 5 is to remove op char from own list
        if (e.flag.flag == 5) {
          this.$store.dispatch("removeCharfromOwnList", e.flag.userid);
        }

        if (e.flag.flag == 6) {
        }

        if (e.flag.flag == 7) {
        }

        if (e.flag.flag == 8) {
        }
      }
    );
  },

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapState(["newOperationInfo", "newCampaignSystems", "newCampaigns"]),
    ...mapGetters([]),

    operationID() {
      return this.newOperationInfo.id;
    },

    systems() {
      return this.newCampaignSystems;
    },

    CampaignAllIDs() {
      var check = this.newCampaigns.length;
      if (check > 0) {
        return this.newCampaigns.map((c) => c.id);
      } else {
        return [];
      }
    },

    // * active = able to hack
    activeCampaigns() {
      var check = this.newCampaigns.length;
      if (check > 0) {
        var campaigns = this.newCampaigns.filter((c) => {
          if (c.status_id == 2) {
            return true;
          } else {
            return false;
          }
        });

        return campaigns;
      } else {
        return [];
      }
    },

    activeCampaignsIDs() {
      if (this.activeCampaigns.length > 0) {
        var ids = this.activeCampaigns.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    warmUpCampaigns() {
      var check = this.newCampaigns.length;
      if (check > 0) {
        var campaigns = this.newCampaigns.filter((c) => {
          if (c.status_id == 5) {
            return true;
          } else {
            return false;
          }
        });
        return campaigns;
      } else {
        return [];
      }
    },

    warmUpIDs() {
      if (this.warmUpCampaigns.length > 0) {
        var ids = this.warmUpCampaigns.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    // * warmup and active
    openCampaings() {
      if (this.activeCampaigns.length > 0 && this.warmUpCampaigns.length > 0) {
        let open = this.activeCampaigns.concat(this.warmUpCampaigns);
        open = open.filter((item, index) => {
          return open.indexOf(item) == index;
        });
        return open;
      } else if (this.activeCampaigns.length > 0) {
        return this.activeCampaigns;
      } else if (this.warmUpCampaigns.length > 0) {
        return this.warmUpCampaigns;
      } else {
        return [];
      }
    },

    openCampaignIDs() {
      if (this.openCampaings.length > 0) {
        var ids = this.openCampaings.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    upComingCampaigns() {
      var campaigns = this.newCampaigns.filter((c) => c.status_id == 1);
      return campaigns;
    },

    upComingCampaignIDs() {
      if (this.upComingCampaigns.length > 0) {
        var ids = this.upComingCampaigns.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    overCampaigns() {
      if (this.newCampaigns.length > 0) {
        var campaigns = this.newCampaigns.filter(
          (c) => c.status_id == 3 || c.status_id == 4
        );
        return campaigns;
      } else {
        return [];
      }
    },

    overCampaignsIDs() {
      if (this.overCampaigns.length > 0) {
        var ids = this.overCampaigns.map((c) => c.id);
        return ids;
      } else {
        return [];
      }
    },

    openSystems() {
      var systems = this.newCampaignSystems.filter((s) => {
        let systems = s.new_campaigns.filter((c) =>
          this.openCampaignIDs.includes(c.id)
        );
        if (systems.length > 0) {
          return true;
        } else {
          return false;
        }
      });
      return systems;
    },

    activeSystem() {
      var systems = this.newCampaignSystems.filter((s) => {
        let systems = s.new_campaigns.filter((c) =>
          this.activeCampaigns.includes(c.id)
        );
        if (systems.length > 0) {
          return true;
        } else {
          return false;
        }
      });
      return systems;
    },
  },
  beforeDestroy() {
    Echo.leave("operations." + this.operationID);
    Echo.leave("operationsown." + this.$store.state.user_id);
  },
};
</script>
