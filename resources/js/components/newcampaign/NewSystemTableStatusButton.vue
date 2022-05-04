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
              {{ node.node_status.name }}
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
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async statusClick(list) {
      request = {
        status_id: list.value,
      };

      await axios({
        method: "post",
        url: "/api/campaignsolasystems/" + this.node.id,
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

    pillOutlined() {
      if (this.node.node_status.id == 7 || this.node.node_status.id == 9) {
        return false;
      } else {
        return true;
      }
    },

    filterDropDown() {
      var list = this.dropdown_edit.filter(
        (f) => f.value != this.node.node_status.id
      );

      return list;
    },

    pillColor() {
      if (this.node.node_status.id == 1) {
        return "deep-orange lighten-1";
      }
      if (this.node.node_status.id == 2) {
        return "lime darken-4";
      }
      if (this.node.node_status.id == 3 || this.node.node_status.id == 8) {
        return "green darken-3";
      }
      if (this.node.node_status.id == 4) {
        return "green accent-4";
      }
      if (this.node.node_status.id == 5) {
        return "red darken-4";
      }
      if (this.node.node_status.id == 6) {
        return "#FF5EEA";
      }
      if (this.node.node_status.id == 7) {
        return "#801916";
      }
      if (this.node.node_status.id == 9) {
        return "#9C9C9C";
      }
    },
  },
  beforeDestroy() {},
};
</script>
