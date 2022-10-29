<template>
  <div>
    <v-dialog
      v-model="overlay"
      max-width="700px"
      z-index="0"
      @click:outside="close()"
      class="rounded-xl"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          class="mr-4"
          color="green lighten-1"
          v-bind="attrs"
          v-on="on"
          rounded
          small
          outlined
          >characters</v-btn
        >
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="700px"
        class="rounded-xl"
      >
        <v-card-title class="d-flex justify-space-between align-center primary">
          <div>Table of all your saved Characters</div>
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredItems"
            item-key="id"
            disable-pagination
            fixed-header
            hide-default-footer
          >
            <template v-slot:[`header.actions`]="{ headers }">
              <AddOperationUserButton :operationID="operationID" />
            </template>
            <template slot="no-data"> You have no saved Chars </template>
            <!-- :color="pillColor(item)" -->
            <template v-slot:[`item.addRemove`]="{ item }">
              <span>
                <v-btn
                  rounded
                  :outlined="true"
                  x-small
                  :color="pillColor(item)"
                  @click="pillClick(item)"
                >
                  <font-awesome-icon :icon="pillIcon(item)" pull="left" />
                  {{ pillText(item) }}
                </v-btn>
              </span>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <span>
                <NewUserEdit :char="item" :operationID="operationID" />
                <v-btn icon @click="removeChar(item)">
                  <font-awesome-icon
                    icon="fa-solid fa-trash-can"
                    color="orange darken-3"
                  />
                </v-btn>
              </span>
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
  },
  data() {
    return {
      headers: [
        { text: "Name", value: "name" },
        { text: "Role", value: "userrole.role" },
        { text: "Ship", value: "ship" },
        { text: "Entosis", value: "entosis" },
        { text: "", value: "addRemove", align: "center" },
        { text: "", value: "actions", align: "end" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      statusflag: 0,
      toggle_exclusive: 0,
      //   overlay: false,
    };
  },
  async created() {},

  async mounted() {},

  methods: {
    close() {
      this.overlay = false;
    },

    open() {
      this.overlay = true;
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

    async pillClick(item) {
      if (item.operation_id == this.operationID) {
        var request = {
          operation_id: null,
          system_id: null,
          user_status_id: 1,
        };

        await axios({
          //removes char from campaign
          method: "PUT",
          url:
            "/api/newcampaignusersremove/" +
            item.id +
            "/" +
            this.operationID +
            "/" +
            this.$store.state.user_id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      } else {
        var request = {
          operation_id: this.operationID,
          system_id: null,
          user_status_id: 1,
        };
        await axios({
          method: "PUT",
          url:
            "/api/newcampaignusersadd/" +
            item.id +
            "/" +
            this.operationID +
            "/" +
            this.$store.state.user_id,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });
      }
    },

    async removeChar(item) {
      await axios({
        method: "DELETE",
        url:
          "/api/newcampaignusers/" +
          item.id +
          "/" +
          this.operationID +
          "/" +
          this.$store.state.user_id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.$store.dispatch("getCampaignSystemsRecords");
    },
  },

  computed: {
    ...mapState(["campaignusers", "ownChars", "setOpenOperationAddChar"]),
    filteredItems() {
      return this.ownChars;
    },

    overlay: {
      get() {
        return this.setOpenOperationAddChar;
      },
      set(newValue) {
        return this.$store.dispatch("setOpenOperationAddChar", newValue);
      },
    },
  },
};
</script>

<style></style>
