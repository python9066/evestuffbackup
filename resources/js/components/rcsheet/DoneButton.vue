<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      persistent
      v-model="showDoneOverlay"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          small
          v-bind="attrs"
          v-on="on"
          @click="open()"
          :color="pillColor()"
        >
          {{ buttontext() }}
          <v-icon right> faSvg fa-check-circle</v-icon>
        </v-btn>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column"
      >
        <v-card-title class="justify-center">
          <p>What is the Status of {{ item.name }}</p>
        </v-card-title>

        <v-card-text class="d-inline-flex">
          <AddTimerFromDone
            v-if="showAddTimer()"
            @timeropen="close()"
            :item="item"
            :type="type"
          ></AddTimerFromDone>
          <v-btn color="orange darken-1" class="mx-4" @click="statusUpdate(4)">
            Repaired</v-btn
          >
          <v-btn color="red" class="mx-4" @click="softDestroyed()">
            Destoryed</v-btn
          >
          <v-btn color="brown lighten-2" class="mx-4" @click="statusUpdate(18)">
            Unknown</v-btn
          >
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn color="red" @click="close()"> Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";

export default {
  props: {
    item: Object,
    type: Number,
  },

  async created() {},
  data() {
    return {
      showDoneOverlay: false,
    };
  },

  watch: {},

  methods: {
    pillColor() {
      if (this.item.status_id == 13) {
        return "red darken-4";
      }
      if (this.item.status_id == 5) {
        return "lime darken-4";
      }
      if (this.item.status_id == 14) {
        return "green accent-4";
      }
      if (this.item.status_id == 17) {
        return "#FF5EEA";
      }
    },
    buttontext() {
      var ret = this.item.status_name.replace("Upcoming - ", "");
      return ret;
    },

    async open() {},

    close() {
      this.showDoneOverlay = false;
    },

    showAddTimer() {
      if (this.item.status_id == 5 || this.item.status_id == 8) {
        return true;
      } else {
        return false;
      }
    },

    async statusUpdate(statusID) {
      switch (this.type) {
        case 1:
          var data = {
            id: this.item.id,
            show_on_rc: 0,
          };
          this.$store.dispatch("updateRcStation", data);

        case 2:
          var data = {
            id: this.item.id,
            show_on_chill: 0,
          };
          this.$store.dispatch("updateChillStation", data);
      }

      var request = null;
      if (this.type == 1) {
        request = {
          station_status_id: statusID,
          show_on_rc: 0,
          show_on_coord: 1,
        };

        await axios({
          method: "put",
          url: "/api/updatestationnotification/" + this.item.id,
          data: request,
          headers: {
            Authorization: "Bearer " + this.$store.state.token,
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }

      if (this.type == 2) {
        request = {
          station_status_id: statusID,
          show_on_chill: 0,
          show_on_coord: 0,
        };

        await axios({
          method: "put",
          url: "/api/chillupdatestationnotification/" + this.item.id,
          data: request,
          headers: {
            Authorization: "Bearer " + this.$store.state.token,
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }
    },

    async softDestroyed() {
      if (this.type == 1) {
        var data = {
          id: this.item.id,
          show_on_rc: 0,
          show_on_coord: 1,
        };

        this.$store.dispatch("updateRcStation", data);

        await axios({
          method: "put",
          url: "/api/softdestory/" + this.item.id,
          headers: {
            Authorization: "Bearer " + this.$store.state.token,
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }

      if (this.type == 2) {
        var data = {
          id: this.item.id,
          show_on_chill: 0,
        };
        this.$store.dispatch("updateChillStation", data);

        await axios({
          method: "delete",
          url: "/api/chilldelete/" + this.item.id,
          headers: {
            Authorization: "Bearer " + this.$store.state.token,
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }
    },
  },

  computed: {},

  beforeDestroy() {},
};
</script>

<style></style>
