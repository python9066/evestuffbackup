<template>
  <v-row no-gutters>
    <v-col>
      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <div>
            <v-chip
              v-bind="attrs"
              v-on="on"
              pill
              :outlined="pillOutlined"
              small
              :color="pillColor"
            >
              {{ nodeStatusName }}
            </v-chip>
          </div>
        </template>

        <v-list>
          <v-list-item
            v-for="(list, index) in filterDropDown"
            :key="index"
            @click="statusClick(list)"
          >
            <v-list-item-title> {{ list.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
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
    extra: {
      type: Number,
      default: 1,
    },
  },
  data() {
    return {
      dropdown_edit: [
        { title: "New", value: 1 },
        { title: "Warm up", value: 2 },
        { title: "Hacking", value: 3 },
        { title: "Friendly Hacking", value: 8 },
        { title: "Passive", value: 9 },
        { title: "Success", value: 4 },
        { title: "Pushed off", value: 6 },
        { title: "Hostile Hacking", value: 7 },
        { title: "Failed", value: 5 },
      ],

      dropdown_edit_extra: [
        { title: "Warm up", value: 2 },
        { title: "Hacking", value: 3 },
        { title: "Pushed off", value: 6 },
      ],
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async statusClick(list) {
      if (this.extra == 1) {
        var request = {
          status_id: list.value,
          system_id: this.node.system_id,
          opID: this.operationID,
          extra: false,
        };
      } else {
        var request = {
          status_id: list.value,
          system_id: this.node.op_user.system_id,
          opID: this.operationID,
          extra: true,
        };
      }

      await axios({
        method: "post",
        url: "/api/updatenodestats/" + this.node.id,
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
    ...mapGetters([]),

    ...mapState([]),

    nodeStatusID() {
      if (this.extra == 2) {
        return this.node.node_status.id;
      } else if (this.node.prime_node_user.length > 0) {
        return this.node.prime_node_user[0].node_status.id;
      } else {
        return this.node.node_status.id;
      }
    },

    nodeStatusName() {
      if (this.extra == 2) {
        return this.node.node_status.name;
      } else if (this.node.prime_node_user.length > 0) {
        return this.node.prime_node_user[0].node_status.name;
      } else {
        return this.node.node_status.name;
      }
    },

    pillOutlined() {
      if (this.nodeStatusID == 7 || this.nodeStatusID == 9) {
        return false;
      } else {
        return true;
      }
    },

    filterDropDown() {
      if (this.extra == 1) {
        var list = this.dropdown_edit.filter(
          (f) => f.value != this.nodeStatusID
        );
      } else {
        var list = this.dropdown_edit_extra.filter(
          (f) => f.value != this.nodeStatusID
        );
      }

      return list;
    },

    pillColor() {
      if (this.nodeStatusID == 1) {
        return "deep-orange lighten-1";
      }
      if (this.nodeStatusID == 2) {
        return "lime darken-4";
      }
      if (this.nodeStatusID == 3 || this.nodeStatusID == 8) {
        return "green darken-3";
      }
      if (this.nodeStatusID == 4) {
        return "green accent-4";
      }
      if (this.nodeStatusID == 5) {
        return "red darken-4";
      }
      if (this.nodeStatusID == 6) {
        return "#FF5EEA";
      }
      if (this.nodeStatusID == 7) {
        return "#801916";
      }
      if (this.nodeStatusID == 9) {
        return "#9C9C9C";
      }
    },
  },
  beforeDestroy() {},
};
</script>
