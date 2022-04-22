<template>
  <div>
    <v-icon color="blue" @click="open()"> fa-solid fa-gear </v-icon>
    <v-dialog
      max-width="700px"
      z-index="0"
      v-model="showStationSettingPannel"
      @click:outside="close()"
    >
      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="700px"
        class="d-flex flex-column"
      >
        <v-card-title>Setting for Station Page</v-card-title>
        <v-card-text> </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn> Submit </v-btn>

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
  props: {
    sig: Object,
  },
  data() {
    return {
      openInfo: false,
    };
  },

  async created() {},

  methods: {
    close() {
      this.openInfo = false;
    },

    open() {
      this.openInfo = true;
    },
  },

  computed: {
    ...mapState([]),

    showStationSettingPannel() {
      if (!this.openInfo) {
        if (!this.sig.wormhole_info_mass) {
          return this.$store.getters.getCheckOpenInfoPannel(this.sig.id);
        } else {
          return false;
        }
      } else {
        return true;
      }
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
