<template>
  <div class="d-inline-flex align-items-md-center pl-4">
    <div>
      <span class="d-inline-flex align-items-md-center pr-2">
        <span class="pl-2" v-show="showRcReconButton()">
          {{ station.recon_name }}
        </span>
      </span>
    </div>
    <div>
      <v-btn
        v-show="!showRcReconButton()"
        :key="'gunnerbutton' + station.gunner_id"
        class=""
        color="blue"
        x-small
        outlined
        @click="reconAdd()"
      >
        <v-icon x-small dark left> fas fa-plus </v-icon>
        CYNO</v-btn
      >
      <v-icon
        v-show="
          showRcReconButton() &&
          ($can('edit_killsheet_remove_char') ||
            this.station.recon_user_id == this.$store.state.user_id)
        "
        color="orange darken-3 "
        small
        @click="reconRemove()"
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
    showRcReconButton() {
      if (this.station.recon_user_id) {
        return true;
      } else {
        return false;
      }
    },

    async reconAdd() {
      var data = {
        id: this.station.id,
        recon_user_id: this.$store.state.user_id,
        recon_name: this.$store.state.user_name,
      };

      switch (this.type) {
        case 1:
          this.$store.dispatch("updateRcStation", data);

        case 2:
          this.$store.dispatch("updateChillStation", data);

        case 3:
          this.$store.dispatch("updateWelpStation", data);
      }

      var request = null;
      request = {
        user_id: this.$store.state.user_id,
      };

      await axios({
        method: "put",
        url: "/api/rcreconuseradd/" + this.station.id,
        data: request,
        headers: {
          Authorization: "Bearer " + this.$store.state.token,
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async reconRemove() {
      var data = {
        id: this.station.id,
        recon_user_id: null,
        recon_name: null,
      };

      switch (this.type) {
        case 1:
          this.$store.dispatch("updateRcStation", data);

        case 2:
          this.$store.dispatch("updateChillStation", data);

        case 3:
          this.$store.dispatch("updateWelpStation", data);
      }

      await axios({
        method: "put",
        url: "/api/rcreconuserremove/" + this.station.id,
        headers: {
          Authorization: "Bearer " + this.$store.state.token,
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
