<template>
  <v-row no-gutter>
    <v-col cols="12">
      <v-data-table
        :headers="headers"
        :items="ops"
        disable-sort
        dense
        hide-default-footer
        disable-pagination
        class="rounded-xl"
      >
        <template slot="no-data"> No Operations </template>
        <template v-slot:[`item.action`]="{ item }">
          <v-btn rounded color="green" @click="go(item.id)"> view </v-btn>
        </template>
        <template v-slot:[`header.action`]="{ headers }">
          <AddOperationInfo />
        </template>
      </v-data-table>
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
  props: {},
  data() {
    return {
      headers: [
        {
          text: "Name",
          value: "name",
          sortable: false,
        },
        {
          text: "Status",
          value: "status.name",
          sortable: false,
        },

        {
          text: "Start",
          value: "start",
          sortable: false,
        },
        {
          text: "",
          value: "action",
          align: "end",
          sortable: false,
        },
      ],
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    go(id) {
      this.$router.push({ path: `/operationinfo/${id}` });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["operationInfo"]),

    ops() {
      return this.operationInfo;
    },
  },
  beforeDestroy() {},
};
</script>
