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
            :expanded.sync="expanded"
            hide-default-footer
            disable-pagination
            class="elevation-24 rounded-xl full-width"
          >
            <template slot="no-data">
              No Nodes in this system statemtmem
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon
                color="orange darken-3"
                small
                class="pl-5"
                @click="removenode(item)"
              >
                fas fa-trash-alt
              </v-icon>
            </template>
            <template v-slot:[`item.op_users`]="{ item }">
              <AddPilot :node="item" :operationID="operationID"></AddPilot>
              <NewNodeExtraChar
                :node="item"
                :operationID="operationID"
              ></NewNodeExtraChar>
            </template>
            <template v-slot:[`item.TODOMain`]="{ item }">
              <NewSystemTableSimpleText
                :node="item"
                :type="1"
              ></NewSystemTableSimpleText>
            </template>
            <template v-slot:[`item.TODOShip`]="{ item }">
              <NewSystemTableSimpleText
                :node="item"
                :type="2"
              ></NewSystemTableSimpleText>
            </template>
            <template v-slot:[`item.node_status.name`]="{ item }">
              <NewSystemTableStatusButton
                :node="item"
                :operationID="operationID"
              ></NewSystemTableStatusButton>
            </template>

            <template v-slot:[`item.created_at`]="{ item }">
              <NewSystemTableTimer
                :node="item"
                :operationID="operationID"
              ></NewSystemTableTimer>
            </template>

            <template v-slot:expanded-item="{ headers, item }">
              <td :colspan="headers.length" align="center">
                dance
                <NewJoinNodeTable
                  :node="item"
                  :operationID="operationID"
                ></NewJoinNodeTable>
              </td>
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
          sortable: true,
        },

        { text: "", value: "data-table-expand" },
      ],
      singleExpand: false,
      expanded: [],
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

    nonPrimeryNodes() {
      return this.nodes.filter((n) => n.non_prime_node_user.length > 0);
    },

    // expanded() {
    //   var data = this.nodes.filter((n) => n.none_pprime_node_user.length > 0);
    //   if (data) {
    //     return data;
    //   } else {
    //     return [];
    //   }
    // },
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
