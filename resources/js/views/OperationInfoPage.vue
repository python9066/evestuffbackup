<template>
  <v-row no-gutters v-resize="onResize" justify="center">
    <v-col cols="11"
      ><v-card rounded="xl"
        ><v-card-title class="primary"
          ><v-row no-gutters justify="center"
            ><v-col cols="auto">Operation - {{ opInfo.name }}</v-col></v-row
          ></v-card-title
        ><v-card-text class="pt-3"
          ><v-row no-gutters justify="space-around"
            ><v-col cols="auto"
              ><v-row no-gutters
                ><v-col cols="auto"
                  ><v-card rounded="xl"
                    ><v-card-title class="green">Pre-Op Planning</v-card-title
                    ><v-card-text>
                      <v-row no-gutters>
                        <v-col cols="auto">Formup Time</v-col
                        ><v-col cols="auto"></v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="auto">Fourm OP Posted</v-col
                        ><v-col cols="auto"></v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="auto">OP Pre-Pinged?</v-col
                        ><v-col cols="auto"></v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="auto">Reacon Informed</v-col
                        ><v-col cols="auto"></v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="auto">Campaital FC's Found</v-col
                        ><v-col cols="auto"></v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="auto">FC's Found</v-col
                        ><v-col cols="auto"></v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="auto">Doctrines decided</v-col
                        ><v-col cols="auto"></v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="auto">Allies Informed</v-col
                        ><v-col cols="auto"></v-col>
                      </v-row>
                    </v-card-text> </v-card></v-col
              ></v-row>
              <v-row no-gutters><v-col cols="auto">Forming</v-col></v-row>
              <v-row no-gutters><v-col cols="auto">Post</v-col></v-row></v-col
            ><v-col cols="auto">Message</v-col
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
    };
  },

  async created() {
    await this.$store.dispatch(
      "getOperationSheetInfoPage",
      this.$route.params.id
    );
    Echo.private("operationinfooppage." + this.$route.params.id).listen(
      "OperationInfoPageUpdate",
      (e) => {
        if (e.flag.flag == 1) {
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
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
  },
  computed: {
    ...mapState["operationInfoPage"],

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage");
      },
    },
  },
  beforeDestroy() {
    Echo.leave("operationinfopage");
  },
};
</script>
