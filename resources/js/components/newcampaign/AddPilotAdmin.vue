<template>
  <div>
    <v-dialog
      v-model="overlay"
      max-width="700px"
      z-index="0"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn v-bind="attrs" v-on="on" icon color="blue"
          ><font-awesome-icon icon="fa-solid fa-plus" />
        </v-btn>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="700px"
        class="rounded-xl mt-2"
      >
        <v-card-title class="d-flex justify-space-between align-center primary">
          <div>All Free Hackers</div>
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredItems"
            item-key="id"
            disable-pagination
            fixed-header
            hide-default-footer
            class="elevation-24"
          >
            <template slot="no-data"> You have no saved Chars </template>
            <!-- :color="pillColor(item)" -->
            <template v-slot:[`item.addRemove`]="{ item }">
              <v-btn rounded x-small outlined color="green" @click="add(item)">
                <font-awesome-icon icon="fa-solid fa-plus" pull="left" />
                Add
              </v-btn>
            </template>
          </v-data-table>
        </v-card-text>
        <v-card-actions>
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    operationID: Number,
    node: [Array, Object],
  },
  data() {
    return {
      overlay: false,
      addShown: false,
      headers: [
        { text: "Name", value: "name" },
        { text: "Role", value: "userrole.role" },
        { text: "Ship", value: "ship" },
        { text: "Entosis", value: "entosis" },
        { text: "", value: "addRemove", align: "center" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },

  methods: {
    close() {
      this.overlay = false;
    },

    pillColor(item) {
      if (item.operation_id == this.operationID) {
        return "red";
      } else {
        return "green";
      }
    },

    pillText(item) {
      if (item.operation_id == this.operationID) {
        return "Remove";
      } else {
        return "Add";
      }
    },

    pillIcon(item) {
      if (item.operation_id == this.operationID) {
        return "fa-solid fa-minus";
      } else {
        return "fa-solid fa-plus";
      }
    },

    async add(item) {
      var request = {
        opUserID: item.id,
        id: this.node.id,
      };
      await axios({
        method: "POST",
        url: "/api/addcharadmin",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async pillClick(item) {},
  },

  computed: {
    ...mapState(["opUsers"]),
    filteredItems() {
      return this.opUsers.filter((o) => o.role_id == 1 && o.userstatus.id != 4);
    },
  },
};
</script>

<style></style>
