<template>
  <div class="d-inline-flex align-items-md-center pl-4">
    <div>
      <span class="d-inline-flex align-items-md-center pr-2">
        <span class="pl-2" v-show="showRcFCButton()">
          {{ station.fc.name }}
        </span>
      </span>
    </div>
    <div>
      <v-btn
        v-show="!showRcFCButton()"
        :key="'gunnerbutton' + station.gunner_id"
        class=""
        color="blue"
        x-small
        outlined
        @click="fcAdd()"
      >
        <v-icon x-small dark left> fas fa-plus </v-icon>
        FC</v-btn
      >
      <v-icon
        v-show="
          showRcFCButton() &&
          ($can('edit_killsheet_remove_char') ||
            this.station.fc.id == this.$store.state.user_id)
        "
        color="orange darken-3"
        small
        @click="fcRemove()"
      >
        fas fa-trash-alt
      </v-icon>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    station: Object,
    type: Number,
  },
  data() {
    return {};
  },

  mounted() {
    this.showName;
  },

  methods: {
    showRcFCButton() {
      if (this.station.fc) {
        return true;
      } else {
        return false;
      }
    },

    async fcAdd() {
      var data = {
        id: this.station.id,
        fc_user_id: this.$store.state.user_id,
        fc_name: this.$store.state.user_name,
      };
      this.$store.dispatch("updateRcStationCurrent", data);
      this.$store.dispatch("updateChillStationCurrent", data);
      this.$store.dispatch("updateWelpStationCurrent", data);

      var request = null;
      request = {
        user_id: this.$store.state.user_id,
      };

      await axios({
        method: "put",
        url: "/api/rcfcuseradd/" + this.station.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async fcRemove() {
      var data = {
        id: this.station.id,
        fc_user_id: null,
        fc_name: null,
      };
      this.$store.dispatch("updateRcStationCurrent", data);
      this.$store.dispatch("updateChillStationCurrent", data);
      this.$store.dispatch("updateWelpStationCurrent", data);

      await axios({
        method: "put",
        url: "/api/rcfcuserremove/" + this.station.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {},
};
</script>

<style></style>
