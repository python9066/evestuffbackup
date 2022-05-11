<template>
  <div class="ml-auto">
    <v-menu transition="fade-transition" v-if="freecharCount != 0">
      <template v-slot:activator="{ on, attrs }">
        <v-chip
          dark
          :color="filterCharsOnTheWay"
          v-bind="attrs"
          v-on="on"
          small
        >
          On the Way
        </v-chip>
      </template>
      <v-list>
        <v-list-item
          v-for="(list, index) in charsFree"
          :key="index"
          @click="clickOnTheWay(list.id)"
        >
          <v-list-item-title>{{ list.name }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
    <span v-else> On the way - </span>
    <v-menu transition="fade-transition">
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          class="mx-2"
          v-bind="attrs"
          :disabled="fabOnTheWayDisbale"
          v-on="on"
          fab
          color="green darken-4"
          dark
          x-small
        >
          {{ OnTheWayCount }}
        </v-btn>
      </template>
      <v-list>
        <v-list-item v-for="(list, index) in charsOnTheWayAll" :key="index">
          <v-list-item-title>
            {{ list.name }} - {{ list.ship }} - T{{ list.entosis
            }}<span class="pl-3" v-if="seeReadyToGoOnTheWay(list)">
              <v-icon
                color="orange darken-3"
                small
                @click="removeReadyToGoOnTheWay(list)"
              >
                fas fa-trash-alt
              </v-icon></span
            ></v-list-item-title
          >
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    item: Object,
    operationID: Number,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async clickOnTheWay(opUserID) {
      var request = {
        user_status_id: 2,
        system_id: this.system_id,
      };

      await axios({
        method: "put",
        url: "/api/onthewayreadytogo/" + this.operationID + "/" + opUserID,
        data: request,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    seeReadyToGoOnTheWay(item) {
      if (
        this.$can("campaigns_admin_access") ||
        this.$store.state.user_id == item.user_id
      ) {
        return true;
      } else {
        false;
      }
    },
  },

  computed: {
    ...mapGetters(["getOwnHackingCharOnOp", "getOpUsersOnTheWayAll"]),

    ...mapState([]),

    freecharCount() {
      var data = this.getOwnHackingCharOnOp(this.operationID);
      if (data) {
        return this.getOwnHackingCharOnOp(this.operationID).length;
      } else {
        return 0;
      }
    },

    charsFree() {
      var data = this.getOwnHackingCharOnOp(this.operationID);
      if (data) {
        return this.getOwnHackingCharOnOp(this.operationID);
      } else {
        return [];
      }
    },

    charsOnTheWayAll() {
      return this.getOpUsersOnTheWayAll.filter(
        (q) => q.system_id == this.item.id
      );
    },

    OnTheWayCount() {
      if (this.charsOnTheWayAll) {
        return this.charsOnTheWayAll.length;
      } else {
        return 0;
      }
    },

    fabOnTheWayDisbale() {
      if (this.OnTheWayCount == 0) {
        return true;
      } else {
        return false;
      }
    },

    filterCharsOnTheWay() {
      var count = this.charsFree.filter(
        (char) => char.status_id == 2 && char.system_id == this.system_id
      ).length;

      if (count > 0) {
        return "green";
      } else {
        return "red";
      }
    },
  },
  beforeDestroy() {},
};
</script>
