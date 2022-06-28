<template>
  <v-row no-gutters v-resize="onResize" justify="center">
    <v-col cols="11"
      ><v-card rounded="xl"
        ><v-card-title class="primary"
          ><v-row no-gutters justify="center"
            ><v-col cols="auto">Operation - {{ opInfo.name }}</v-col></v-row
          ></v-card-title
        ><v-card-text class="pt-3"
          ><v-row no-gutters justify="space-between">
            <v-col cols="4">
              <v-row no-gutters>
                <v-col cols="auto"
                  ><OperationInfoPlanningCard :loaded="loaded" />
                </v-col>
                <v-col cols="auto">
                  <v-row no-gutters
                    ><v-col cols="auto"
                      ><OperationPreOpFormUpCard :loaded="loaded" /></v-col
                  ></v-row>
                </v-col>
                <v-col cols="auto">
                  <v-row no-gutters
                    ><v-col cols="auto"
                      ><OperationInfoPostOpCard
                        :loaded="
                          loaded
                        " /></v-col></v-row></v-col></v-row></v-col
            ><v-col cols="auto"
              ><OperationInfoMessageCard :loaded="loaded" /></v-col
            ><v-col cols="auto">Fleets</v-col></v-row
          ></v-card-text
        ></v-card
      ></v-col
    >
  </v-row>
</template>
<script>
import Axios from "axios";
import moment, { now, unix, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
import VueGridLayout from "vue-grid-layout";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - MultiCampaigns`;
  },
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },
      loaded: false,
    };
  },

  async created() {
    await this.$store.dispatch(
      "getOperationSheetInfoPage",
      this.$route.params.id
    );
    Echo.private("operationinfooppage." + this.$route.params.id).listen(
      "OperationInfoPageSoloUpdate",
      (e) => {
        if (e.flag.flag == 1) {
          this.$store.dispatch("updateOperationSheetInfoPage", e.flag.message);
        }

        if (e.flag.flag == 2) {
        }

        if (e.flag.flag == 3) {
        }
      }
    );
  },

  async mounted() {
    this.onResize();
    this.setLoad();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    async setLoad() {
      await sleep(1000);
      this.loaded = true;
    },
  },
  computed: {
    ...mapState["operationInfoPage"],

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },
  },
  beforeDestroy() {
    Echo.leave("operationinfopage");
  },
};
</script>
