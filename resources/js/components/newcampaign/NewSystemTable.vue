<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :single-expand="singleExpand"
            :items="nodes"
            disable-sort
            :item-class="itemRowBackground"
            :expanded="expanded"
            hide-default-footer
            disable-pagination
            class="elevation-24 rounded-xl full-width"
          >
            <template slot="no-data">
              No Nodes in this system statemtmem
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-btn icon class="pl-5" @click="removenode(item)">
                <font-awesome-icon
                  icon="fa-solid fa-trash-can"
                  color="orange darken-3"
              /></v-btn>
            </template>
            <template v-slot:[`item.op_users`]="{ item }">
              <AddPilot :node="item" :operationID="operationID" />
              <NewNodeExtraChar :node="item" :operationID="operationID" />
            </template>
            <template v-slot:[`item.TODOMain`]="{ item }">
              <NewSystemTableSimpleText :node="item" :type="1" />
            </template>
            <template v-slot:[`item.TODOShip`]="{ item }">
              <NewSystemTableSimpleText :node="item" :type="2" />
            </template>
            <template v-slot:[`item.node_status.name`]="{ item }">
              <NewSystemTableStatusButton
                :node="item"
                :operationID="operationID"
              />
            </template>

            <template v-slot:[`item.created_at`]="{ item }">
              <NewSystemTableTimer :node="item" :operationID="operationID" />
            </template>

            <template v-slot:expanded-item="{ headers, item }">
              <td :colspan="headers.length" align="center">
                <NewJoinNodeTable :node="item" :operationID="operationID" />
              </td>
            </template>

            <template v-slot:[`header.actions`]="{ headers }">
              <AddNode :item="item" :operationID="operationID" />
            </template>
          </v-data-table>
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
          value: "node_status.name",
          sortable: true,
        },

        {
          text: "Age/Hack",
          value: "created_at",
          sortable: true,
          align: "center",
        },
        {
          text: "",
          value: "actions",
          align: "end",
          sortable: false,
        },
      ],
      singleExpand: false,
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

    itemRowBackground: function (item) {
      if (item.node_status.id == 7) {
        return "style-1";
      } else if (item.node_status.id == 8) {
        return "style-2";
      }
      //   else if (item.under_attack == 1) {
      //     return "style-4";
      //   }
    },
  },

  computed: {
    ...mapState([]),

    ...mapGetters([]),

    nodes() {
      return this.item.new_nodes;
    },

    expanded() {
      if (this.nodes) {
        var data = this.nodes.filter((f) => f.none_prime_node_user.length > 0);
        return data;
      }
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
