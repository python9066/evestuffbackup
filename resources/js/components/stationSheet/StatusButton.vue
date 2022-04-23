<template>
  <div>
    <v-menu transition="slide-y-transition" bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-chip pill :color="pillColor(item)" v-bind="attrs" v-on="on">
          {{ buttontext(item) }}
        </v-chip>
      </template>
      <v-list>
        <v-list-item
          ><v-chip
            pill
            v-if(item.status.id
            !="16)"
            color="green"
            @click="statusUpdate(16)"
          >
            Online
          </v-chip></v-list-item
        >

        <v-list-item
          ><v-chip pill color="red" @click="destroyed()">
            DEAD
          </v-chip></v-list-item
        >
      </v-list>
    </v-menu>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { EventBus } from "../../app";
import moment from "moment";

export default {
  props: {
    item: Object,
  },

  async created() {},
  data() {
    return {};
  },

  methods: {
    test(n) {
      console.log(n);
    },

    async destroyed() {
      await axios({
        method: "delete",
        url: "/api/rcmovedonebad/" + this.item.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async statusUpdate(statusID) {
      var request = null;
      request = {
        station_status_id: statusID,
        show_on_rc: 0,
        show_on_coord: 1,
      };

      await axios({
        method: "put",
        url: "/api/updatestationnotification/" + this.item.id,
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    buttontext() {
      var ret = this.item.status.name.replace("Upcoming - ", "");
      return ret;
    },

    pillColor() {
      if (this.item.status.id == 4) {
        return "orange darken-1";
      }
      if (this.item.status.id == 18) {
        return "brown lighten-2";
      }
      if (this.item.status.id == 16) {
        return "green";
      }
      if (this.item.status.id == 7) {
        return "red";
      }
    },
  },

  computed: {},

  beforeDestroy() {},
};
</script>

<style></style>


