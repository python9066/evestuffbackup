<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      persistent
      v-model="showStationSettingPannel"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="gray" v-bind="attrs" v-on="on" @click="open()" icon>
          <v-icon> fas fa-cogs fa-xs </v-icon>
        </v-btn>
      </template>
      <v-card
        tile
        max-width="1000px"
        min-height="200px"
        max-height="700px"
        rounded="xl"
      >
        <v-card-title class="justify-center primary"
          >Setting for Station Page</v-card-title
        >
        <v-card-text>
          <v-row no-gutters justify="start">
            <v-col>
              <v-autocomplete
                v-model="pullPicked"
                :items="regionList"
                label="Select"
                chips
                clearable
                deletable-chips
                dense
                hint="Which Regions would you like updated"
                hide-selected
                multiple
                persistent-hint
                rounded
                small-chips
                solo-inverted
                return-object
              ></v-autocomplete>
            </v-col>
          </v-row>
          <v-row no-gutters justify="start">
            <v-col>
              <v-autocomplete
                v-model="fcPicked"
                :items="pullPicked"
                label="Select"
                chips
                clearable
                deletable-chips
                dense
                hint="Which Regions would you like FCs to see"
                hide-selected
                multiple
                persistent-hint
                rounded
                small-chips
                solo-inverted
                stationListPullRegions
              ></v-autocomplete>
            </v-col>
          </v-row>
          <v-row no-gutters justify="start">
            <v-col>
              <v-autocomplete
                v-model="webwayPicked"
                :items="systemlist"
                label="Select"
                chips
                clearable
                deletable-chips
                dense
                hint="Which Regions would you like FCs to see"
                hide-selected
                multiple
                persistent-hint
                rounded
                small-chips
                solo-inverted
                stationListPullRegions
              ></v-autocomplete>
            </v-col>
          </v-row>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn rounded @click="submit()" color="primery"> Update </v-btn>

          <v-btn rounded class="white--text" color="teal" @click="close()">
            Close
          </v-btn></v-card-actions
        >
      </v-card>

      <!-- <showStationSettingPannel
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showStationSettingPannel = false"
            >
            </showStationSettingPannel> -->
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {},
  data() {
    return {
      openInfo: false,
      pullPicked: [],
      fcPicked: [],
      webwayPicked: [],
    };
  },

  async created() {},

  methods: {
    close() {
      this.openInfo = false;
    },

    submit() {
      var request = {
        system_id: this.webwayPicked,
      };

      var request = {
        pull: this.pullPicked,
        fc: this.fcPicked,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/updatesetting",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(this.close());
    },

    open() {
      this.openInfo = true;
      this.setPicked();
    },

    setPicked() {
      this.pullPicked = this.pullPickedComp;
      this.fcPicked = this.fcPickedComp;
      this.webwayPicked = this.webwayPickedComp;
    },
  },

  computed: {
    ...mapState([
      "stationListPullRegions",
      "stationListFCRegions",
      "stationListRegionList",
      "systemlist",
      "webwayStartSystems",
    ]),

    regionList() {
      return this.stationListRegionList;
    },

    fcPickedComp() {
      return this.stationListFCRegions;
    },

    pullPickedComp() {
      return this.stationListPullRegions;
    },

    webwayPickedComp() {
      return this.webwayStartSystems;
    },

    showStationSettingPannel() {
      if (this.openInfo) {
        return true;
      } else {
        return false;
      }
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
