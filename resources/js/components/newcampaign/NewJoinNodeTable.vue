<template>
  <div>
    <!-- ?? //! hide-default-header -->
    <v-data-table
      v-if="showTable"
      :headers="headers"
      :items="filteredItems"
      item-key="node"
      disable-sort
      dense
      hide-default-footer
      disable-pagination
      class="elevation-24 rounded-xl full-width"
    >
      <template v-slot:[`item.op_user.name`]="{ item }">
        <div class="d-inline-flex align-items-center">
          <div class="d-inline-flex align-items-center">
            {{ item.op_user.name }}
          </div>
        </div>
      </template>
      <template v-slot:[`item.node_status.name`]="{ item }">
        <div class="d-inline-flex align-items-center">
          <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
              <div>
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                  pill
                  outlined
                  small
                  :color="pillColor(item)"
                >
                  {{ item.node_status.name }}
                </v-chip>
              </div>
            </template>

            <v-list>
              <v-list-item
                v-for="(list, index) in dropdown_edit"
                :key="index"
                @click="
                  (item.node_status.id = list.value),
                    (item.node_status.name = list.title),
                    statusClick(item)
                "
              >
                <v-list-item-title>{{ list.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </div>
      </template>
      <template v-slot:[`item.created_at`]="{ item }">
        <NewSystemTableTimer
          :node="item"
          :operationID="operationID"
        ></NewSystemTableTimer>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon
          class="pl-4"
          color="orange darken-3"
          small
          @click="deleteNode(item)"
        >
          fas fa-trash-alt
        </v-icon>
      </template>
      <template v-slot:[`item.op_user.ship`]="{ item }" class="pl-0">
        <span v-if="item.op_user.name != null">
          {{ item.op_user.ship }} - T{{ item.op_user.entosis }}
        </span>
      </template>
    </v-data-table>
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
    return {
      headers: [
        {
          text: "",
          value: "padding",
          width: "5%",
          align: "start",
        },
        {
          text: "Pilot",
          value: "op_user.name",
          width: "25%",
          align: "start",
        },

        {
          text: "Main",
          value: "op_user.user.name",
          align: "center",
        },
        {
          text: "Ship",
          value: "op_user.ship",
          align: "start",
        },
        {
          text: "Status",
          value: "node_status.name",
          align: "start",
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
        // { text: "", value: "actions", width: "5%", align: "center" },
        // {
        //     text: "",
        //     value: "b",
        //     width: "5%",
        //     align: "start"
        // }
      ],

      dropdown_edit: [
        { title: "New", value: 1 },
        { title: "Warm up", value: 2 },
        { title: "Hacking", value: 3 },
        { title: "Pushed off", value: 6 },
      ],
    };
  },

  methods: {
    pillColor(item) {
      if (item.node_status.id == 1) {
        return "deep-orange lighten-1";
      }
      if (item.node_status.id == 2) {
        return "lime darken-4";
      }
      if (item.node_status.id == 3) {
        return "green darken-3";
      }
      if (item.node_status.id == 6) {
        return "FF5EEA";
      }
    },

    async deleteNode(item) {
      await axios({
        method: "PUT", //you can set what request you want to be
        url: "/api/deleteextranode/" + item.id + "/" + item.campaign_id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async statusClick(item) {
      var request = [];
      if (
        item.node_status.id == 1 ||
        item.node_status.id == 2 ||
        item.node_status.id == 3
      ) {
        request = {
          dance: item.node_status.id,
        };
      }
      if (item.node_status.id == 6) {
        await this.deleteNode(item);
        return;
      }
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/nodejoinupdate/" + item.id + "/" + item.campaign_id,
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

    filteredItems() {
      return this.node.none_prime_node_user;
    },

    showTable() {
      var count = this.filteredItems.length;
      if (count > 0) {
        return true;
      } else {
        return false;
      }
    },
  },
};
</script>

<style></style>
