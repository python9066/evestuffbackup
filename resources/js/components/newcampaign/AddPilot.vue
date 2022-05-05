<template>
  <v-row no-gutters>
    <v-col>
      <v-menu offset-y v-if="checkShowAdd">
        <template v-slot:activator="{ on, attrs }">
          <div>
            <v-chip
              v-bind="attrs"
              v-on="on"
              pill
              outlined
              small
              color="light-green accent-3"
            >
              Add
            </v-chip>
          </div>
        </template>

        <v-list>
          <v-list-item
            v-for="(list, index) in charsFree"
            :key="index"
            @click="addOpUser(list.id)"
          >
            <v-list-item-title>{{ list.name }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      <span v-else>{{ activePilotName }}</span>
    </v-col>
  </v-row>
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
    node: Object,
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
    async addOpUser(op_user_id) {
      var request = {
        node_id: this.node.id,
        op_user_id: op_user_id,
        system_id: this.node.system_id,
        opID: this.operationID,
      };
      await axios({
        method: "POST",
        url: "/api/addusertonode",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      // TODO Add logging
    },
  },

  computed: {
    ...mapGetters(["getOwnHackingCharOnOp"]),

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

    nodefree() {
      return this.node.op_users.length;
    },

    activePilot() {
      if (this.nodefree > 0) {
        return this.node.op_users[0];
      } else {
        null;
      }
    },

    activePilotName() {
      if (this.activePilot) {
        return this.activePilot.name;
      } else {
        return null;
      }
    },

    checkShowAdd() {
      if (
        this.nodefree == 0 &&
        this.freecharCount != 0 &&
        this.node.node_status.id != 4 &&
        this.node.node_status.id != 5 &&
        this.node.node_status.id != 7 &&
        this.node.node_status.id != 8
      ) {
        return true;
      } else {
        return false;
      }
    },
  },
  beforeDestroy() {},
};
</script>
