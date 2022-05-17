<template>
  <div>
    <v-menu offset-y v-if="checkShowAdd">
      <template v-slot:activator="{ on, attrs }">
        <div>
          <v-btn v-bind="attrs" v-on="on" icon color="green darken-3"
            ><font-awesome-icon icon="fa-solid fa-plus" size="s" />
          </v-btn>
        </div>
      </template>

      <v-list>
        <v-list-item
          v-for="(list, index) in charsFree"
          :key="index"
          @click="clickCharAddNode(list.id)"
        >
          <v-list-item-title>{{ list.name }}</v-list-item-title>
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
    async clickCharAddNode(op_user_id) {
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
