<template>
  <div class="pr-16 pl-16">
    <MultiCampaigns></MultiCampaigns>
    <StartCampaign></StartCampaign>
    <NewMultiCampaigns v-if="$can('super')"></NewMultiCampaigns>
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
  title() {
    return `EveStuff - MultiCampaigns`;
  },
  data() {
    return {};
  },

  created() {
    Echo.private("customoperationpage").listen(
      "CustomOperationPageUpdate",
      (e) => {
        if (e.flag.flag == 1) {
          this.$store.dispatch("addoperationlist", e.flag.message);
        }

        if (e.flag.flag == 2) {
          this.$store.dispatch("updateoperationlist", e.flag.message);
        }

        if (e.flag.flag == 3) {
          this.$store.dispatch("deleteoperationfromlist", e.flag.message);
        }
      }
    );
    this.$store.dispatch("getConstellationList");
  },

  async mounted() {
    this.log();
  },
  methods: {
    log() {
      var request = {
        url: this.$route.path,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/url",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },
  computed: {},
  beforeDestroy() {
    Echo.leave("customoperationpage");
  },
};
</script>
