<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      persistent
      v-model="showStationSettingPannel"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="gray" v-bind="attrs" v-on="on" @click="open()" icon>
          <v-icon> fas fa-cogs fa-xs </v-icon>
        </v-btn>
      </template>
      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="700px"
        class="d-flex flex-column"
      >
        <v-card-title>Setting for Station Page</v-card-title>
        <v-card-text>
          <v-autocomplete
            v-model="pullPicked"
            :items="regionList"
            label="Select"
            chips
            clearable
            deletable-chips
            dense
            hint="Which Campaigns do you want"
            hide-selected
            multiple
            persistent-hint
            rounded
            small-chips
            solo-inverted
            return-object
          ></v-autocomplete>
          <v-autocomplete
            v-model="fcPicked"
            :items="pullPicked"
            label="Select"
            chips
            clearable
            deletable-chips
            dense
            hint="Which Campaigns do you want"
            hide-selected
            multiple
            persistent-hint
            rounded
            small-chips
            solo-inverted
            stationListPullRegions
          ></v-autocomplete>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn> Update </v-btn>

          <v-btn class="white--text" color="teal" @click="close()">
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
    };
  },

  async created() {},

  methods: {
    close() {
      this.openInfo = false;
    },

    open() {
      this.openInfo = true;
      this.setPicked();
    },

    setPicked() {
      this.pullPicked = this.pullPickedComp;
      this.fcPicked = this.fcPickedComp;
    },
  },

  computed: {
    ...mapState([
      "stationListPullRegions",
      "stationListFCRegions",
      "stationListRegionList",
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
