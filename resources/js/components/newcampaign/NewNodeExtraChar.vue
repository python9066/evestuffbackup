<template>
  <div>
    <v-menu offset-y v-if="checkShowAdd">
      <template v-slot:activator="{ on, attrs }">
        <div>
          <v-btn v-bind="attrs" v-on="on" icon color="green darken-3">
            <v-icon small>fas fa-plus</v-icon>
          </v-btn>
        </div>
      </template>

      <v-list>
        <v-list-item
          v-for="(list, index) in charsFree"
          :key="index"
          @click="(charAddNode = list.id), clickCharAddNode()"
        >
          <v-list-item-title>{{ list.char_name }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    node: Object,
    operationID: Number,
  },
  data() {
    return {};
  },

  methods: {
    async clickCharAddNode() {
      //   var addChar = this.chars.find((user) => user.id == this.charAddNode);
      //   var request = {
      //     campaign_id: item.campaign_id,
      //     campaign_system_id: item.id,
      //     campaign_user_id: addChar.id,
      //   };
      //   await axios({
      //     method: "post",
      //     url: "/api/nodejoin/" + item.campaign_id,
      //     withCredentials: true,
      //     data: request,
      //     headers: {
      //       Accept: "application/json",
      //       "Content-Type": "application/json",
      //     },
      //   });
      //   request = null;
      //   request = {
      //     campaign_system_id: item.id,
      //     status_id: 4,
      //     system_id: item.system_id,
      //   };
      //   await axios({
      //     method: "put",
      //     url: "/api/campaignusers/" + addChar.id + "/" + item.campaign_id,
      //     withCredentials: true,
      //     data: request,
      //     headers: {
      //       Accept: "application/json",
      //       "Content-Type": "application/json",
      //     },
      //   });
    },
  },

  computed: {
    ...mapGetters(["getOwnHackingCharOnOp"]),

    nonePrimeNodeUserCount() {
      return this.node.none_prime_node_user.length;
    },

    primeNodeUserCount() {
      return this.node.prime_node_user.length;
    },

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

    checkShowAdd() {
      if (this.freecharCount != 0) {
        if (
          this.nonePrimeNodeUserCount > 0 ||
          this.primeNodeUserCount > 0 ||
          this.node.node_status.id == 8
          // || (this.node.node_status.id != 4 &&
          //   this.node.node_status.id != 5 &&
          //   this.node.node_status.id != 7)
        ) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    },
  },
};
</script>

<style></style>
