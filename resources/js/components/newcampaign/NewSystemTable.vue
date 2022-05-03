<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :items="nodes"
            item-key="id"
            :items-per-page="50"
            :footer-props="{
              'items-per-page-options': [10, 20, 30, 50, 100, -1],
            }"
            class="elevation-24 rounded-xl full-width"
          >
            <template slot="no-data"> No Nodes in this system </template>
            <template v-slot:[`actions`]="{ item }">
              <v-icon
                color="orange darken-3"
                small
                class="pl-5"
                @click="removenode(item)"
              >
                fas fa-trash-alt
              </v-icon>
            </template>
            <template v-slot:[`op_users`]="{ item }">
              456
              <AddPilot :node="item"></AddPilot> </template
          ></v-data-table>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    item: Object,
    operationID: Number,
  },
  data() {
    return {
      headers: [
        {
          text: "NodeID",
          value: "name",
          sortable: false,
        },
        {
          text: "Pilot",
          value: "op_users",
          sortable: true,
        },

        {
          text: "Main",
          value: "TODOMain",
          sortable: true,
        },

        {
          text: "Ship",
          value: "TODOShip",
          sortable: true,
        },
        {
          text: "Status",
          value: "TODOStatus",
          sortable: true,
        },

        {
          text: "Age/Hack",
          value: "created_at",
          sortable: true,
        },
        {
          text: "",
          value: "actions",
          sortable: true,
        },
      ],
    };
  },

  methods: {
    async removenode(item) {
      var id = item.id;

      await axios({
        method: "DELETE",
        url: "/api/deletenode/" + id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapState([]),

    ...mapGetters([]),

    nodes() {
      return this.item.new_nodes;
    },
  },
};
</script>

<style>
.style-4 {
  background-color: rgba(255, 153, 0, 0.199);
}
.style-3 {
  background-color: rgb(255, 172, 77);
}
.style-2 {
  background-color: rgb(30, 30, 30, 1);
}
.style-1 {
  background-color: rgb(184, 22, 35);
}
</style>
