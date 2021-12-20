<template>
  <v-col class="px-0">
    <span
      v-show="
        station.repair_time == null &&
        station.station_status_id == 11 &&
        $can('edit_notifications')
      "
    >
      <v-menu :close-on-content-click="false" :value="timerShown">
        <template v-slot:activator="{ on, attrs }">
          <v-chip
            v-bind="attrs"
            v-on="on"
            pill
            outlined
            @click="timerShown = true"
            small
            color="warning"
          >
            Add Time
          </v-chip>
        </template>

        <template>
          <v-card tile min-height="150px">
            <v-card-title class="pb-0">
              <v-text-field
                v-model="repairTime"
                label="Reapir Time mm:ss"
                v-mask="'##:##'"
                autofocus
                placeholder="mm:ss"
                @keyup.enter="(timerShown = false), addRepairTime(station)"
                @keyup.esc="(timerShown = false), (repairTime = null)"
              ></v-text-field>
            </v-card-title>
            <v-card-text>
              <v-btn
                icon
                fixed
                left
                color="success"
                @click="(timerShown = false), addRepairTime(station)"
                ><v-icon>fas fa-check</v-icon></v-btn
              >

              <v-btn
                fixed
                right
                icon
                color="warning"
                @click="(timerShown = false), (repairTime = null)"
                ><v-icon>fas fa-times</v-icon></v-btn
              >
            </v-card-text>
          </v-card>
        </template>
      </v-menu>
    </span>
    <v-menu
      :close-on-content-click="false"
      :value="timerShownEdit"
      :key="'repairmenu' + station.id"
      v-if="
        station.station_status_id == 11 &&
        station.out_time != null &&
        $can('edit_notifications')
      "
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          :key="'editrepair' + station.id"
          v-bind="attrs"
          v-on="on"
          @click="(repairTime = null), (timerShownEdit = true)"
          icon
          color="warning"
        >
          <v-icon x-small>fas fa-edit</v-icon>
        </v-btn>
      </template>

      <template>
        <v-card tile min-height="150px" :key="'repaircard.' + station.id">
          <v-card-title class="pb-0">
            <v-text-field
              v-model="repairTime"
              label="Repair Time mm:ss"
              autofocus
              v-mask="'##:##'"
              placeholder="mm:ss"
              @keyup.enter="(timerShownEdit = false), addRepairTime(station)"
              @keyup.esc="(timerShownEdit = false), (repairTime = null)"
            ></v-text-field>
          </v-card-title>
          <v-card-text>
            <v-btn
              icon
              fixed
              left
              color="success"
              @click="
                (timerShownEdit = true),
                  (timerShownEdit = false),
                  addRepairTime(station)
              "
              ><v-icon>fas fa-check</v-icon></v-btn
            >

            <v-btn
              fixed
              right
              icon
              color="warning"
              @click="
                (timerShownEdit = true),
                  (timerShownEdit = false),
                  (repairTime = null)
              "
              ><v-icon>fas fa-times</v-icon></v-btn
            >
          </v-card-text>
        </v-card>
      </template>
    </v-menu>
  </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    station: Object,
  },

  data() {
    return {
      timerShown: false,
      timerShownEdit: false,
      repairTime: {
        mm: "",
        ss: "",
      },
    };
  },

  watch: {
    station: {
      handler() {
        this.showPannel;
      },
      deep: true,
    },
  },

  methods: {
    async addRepairTime(station) {
      var min = parseInt(this.repairTime.substr(0, 2));
      var sec = parseInt(this.repairTime.substr(3, 2));
      var finishTime = moment
        .utc()
        .add(sec, "seconds")
        .add(min, "minutes")
        .format("YYYY-MM-DD HH:mm:ss");
      station.repair_time = finishTime;
      this.$store.dispatch("updateStationNotification", station);
      var request = {
        repair_time: finishTime,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/updatestationnotification/" + station.id,
        data: request,
        headers: {
          Authorization: "Bearer " + this.$store.state.token,
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.$store.dispatch("getNotifications");
    },
  },

  computed: {
    showPannel() {
      if (
        this.station.repair_time == null &&
        this.station.station_status_id == 11 &&
        this.$can("edit_notifications")
      ) {
        this.timerShown = true;
      } else {
        this.timerShown = false;
      }
    },
  },
};
</script>

<style>
.style-2 {
  background-color: rgb(30, 30, 30, 1);
}
.style-1 {
  background-color: rgb(184, 22, 35);
}
</style>
