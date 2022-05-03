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
            @click="addOpUser()"
          >
            <v-list-item-title>{{ list.name }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      <span v-else>name</span>
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
  methods: {},

  computed: {
    ...mapGetters(["getOwnHackingCharOnOp"]),

    ...mapState([]),

    freecharCount() {
      return this.getOwnHackingCharOnOp(this.operationID).length;
    },

    charsFree() {
      return this.getOwnHackingCharOnOp(this.operationID);
    },

    checkShowAdd() {
      if (
        this.node.op_users == null &&
        this.freecharCount != 0 &&
        this.node.node_status != 4 &&
        this.node.node_status != 5 &&
        this.node.node_status != 7 &&
        this.node.node_status != 8
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
