<template>
  <div>
    <v-dialog
      v-model="overlay"
      max-width="700px"
      z-index="0"
      @click:outside="close()"
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
        rounded="xl"
      >
        <v-card-title class="d-flex justify-space-between align-center primary">
          <div>Table of all your saved Characters</div>
          <div>
            <v-menu
              :close-on-content-click="false"
              :value="addShown"
              transition="fab-transition"
              origin="100% -30%"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  text
                  v-bind="attrs"
                  v-on="on"
                  @click="addShown = true"
                  color="success"
                  ><v-icon left small>fas fa-plus</v-icon> Char</v-btn
                >
              </template>
              <v-row no-gutters>
                <div>
                  <v-card class="pa-2" tile width="100%">
                    <v-form @submit.prevent="newCharForm()">
                      <v-text-field
                        v-model="newCharName"
                        label="Char Name"
                        required
                        autofocus
                        :rules="newNameRules"
                      ></v-text-field>
                      <v-select
                        v-model="newRole"
                        @change="roleForm($event)"
                        :rules="newRoleRules"
                        :items="dropdown_roles"
                        label="Role"
                        required
                      ></v-select>
                      <v-text-field
                        v-model="newShip"
                        :rules="newShipRules"
                        v-if="this.role == 1"
                        label="Ship"
                        required
                      ></v-text-field>
                      <v-radio-group
                        v-model="newLink"
                        :rules="newLinkRules"
                        v-if="this.role == 1"
                        row
                        label="Entosis Link"
                        required
                      >
                        <v-radio label="Tech 1" value="1"></v-radio>
                        <v-radio label="Tech 2" value="2"></v-radio>
                      </v-radio-group>

                      <v-btn color="success" class="mr-4" type="submit"
                        >submit</v-btn
                      >
                      <v-btn
                        color="warning"
                        class="mr-4"
                        @click="newCharFormClose()"
                        >Close</v-btn
                      >
                      <!-- <v-btn @click="clear">clear</v-btn> -->
                    </v-form>
                  </v-card>
                </div>
              </v-row>
            </v-menu>
          </div>
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
              <span>
                <v-btn
                  rounded
                  :outlined="true"
                  x-small
                  :color="pillColor(item)"
                  @click="pillClick(item)"
                >
                  <v-icon x-small left dark>
                    {{ pillIcon(item) }}
                  </v-icon>
                  {{ pillText(item) }}
                </v-btn>
              </span>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <span>
                <UsersCharsEdit :char="item" :campaign_id="campaign_id">
                </UsersCharsEdit>

                <v-icon color="orange darken-3" small @click="removeChar(item)">
                  fas fa-trash-alt
                </v-icon>
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
    operationID: Text,
  },
  data() {
    return {
      headers: [
        { text: "Name", value: "char_name" },
        { text: "Role", value: "role_name" },
        { text: "Ship", value: "ship" },
        { text: "Entosis", value: "link" },
        { text: "", value: "addRemove", align: "center" },
        { text: "", value: "actions", align: "end" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],

      dropdown_roles: [
        { text: "Hacker", value: 1 },
        { text: "Support", value: 5 },
        { text: "Scout", value: 2 },
        { text: "FC", value: 3 },
        { text: "Command", value: 4 },
      ],
      statusflag: 0,
      toggle_exclusive: 0,
      newCharName: null,
      newNameRules: [(v) => !!v || "Name is required"],
      newRole: null,
      newRoleRules: [(v) => !!v || "You need to pick a role"],
      newShip: null,
      newShipRules: [(v) => !!v || "Ship is required"],
      newLink: null,
      newLinkRules: [(v) => !!v || "T1 or T2?"],

      role: 0,
      editrole: 0,
      oldChar: [],
      overlay: false,

      addShown: false,
    };
  },

  methods: {
    close() {
      this.overlay = false;
    },

    newCharFormClose() {
      this.addShown = false;
      this.newCharName = null;
      this.newRole = null;
      this.newShip = null;
      this.newLink = null;
    },

    roleForm(a) {
      this.role = a;
    },

    roleEditForm(a) {
      this.editrole = a;
    },

    pillColor(item) {
      if (item.campaign_id == this.operationID) {
        return "red";
      } else {
        return "green";
      }
    },

    pillText(item) {
      if (item.campaign_id == this.operationID) {
        return "Remove";
      } else {
        return "Add";
      }
    },

    pillIcon(item) {
      if (item.campaign_id == this.operationID) {
        return "fas fa-minus";
      } else {
        return "fas fa-plus";
      }
    },

    async pillClick(item) {
      if (item.campaign_id == this.operationID) {
        let data = item;
        data.campaign_id = null;
        data.campaign_system_id = null;
        data.system_id = null;
        data.status_id = 1;
        data.node_id = null;
        data.system_name = null;
        data.user_status_name = "None";
        this.$store.dispatch("deleteCampaignUser", data.id); //remove char from campaign
        this.$store.dispatch("updateUsersChars", data); // update user char

        data = null;

        data = {
          campaign_system_status_id: 1,
          end_time: null,
          main_name: null,
          site_id: null,
          user_id: null,
          user_link: null,
          user_name: null,
          user_ship: null,
        };
        var payload = {
          user_id: item.id,
          data: data,
        };
        this.$store.dispatch("updateCampaignSystemByUserID", payload); // removes from old node for new campaign

        var request = {
          operation_id: null,
          system_id: null,
          user_status_id: 1,
        };

        await axios({
          //removes char from campaign
          method: "PUT",
          url:
            "/api/newcampaignusersremove/" + item.id + "/" + this.operationID,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });

        //------logging---////
        // TODO LOGGING FOR SHITE
        // request = null;
        // request = {
        //   user_id: this.$store.state.user_id,
        //   type: "removed",
        //   char_name: this.newCharName,
        // };

        // await axios({
        //   method: "put",
        //   url: "/api/newcheckaddremovechar/" + this.operationID,
        //   withCredentials: true,
        //   data: request,
        //   headers: {
        //     Accept: "application/json",
        //     "Content-Type": "application/json",
        //   },
        // });

        //------logging End-----//
      } else {
        //--add char to campaign--//
        let data = item;
        data.campaign_id = this.operationID;
        data.campaign_system_id = null;
        data.system_id = null;
        data.status_id = 1;
        data.node_id = null;
        data.system_name = null;
        data.user_status_name = "None";
        this.$store.dispatch("addCampaignUserNew", data); //add char to campaign
        this.$store.dispatch("updateUsersChars", data); // update user char

        data = null;

        data = {
          campaign_system_status_id: 1,
          end_time: null,
          main_name: null,
          site_id: null,
          user_id: null,
          user_link: null,
          user_name: null,
          user_ship: null,
        };
        var payload = {
          user_id: item.id,
          data: data,
        };
        this.$store.dispatch("updateCampaignSystemByUserID", payload); // removes from old node for new campaign

        var request = {
          operation_id: this.operationID,
          system_id: null,
          user_status_id: 1,
        };

        // add char to campaign
        await axios({
          method: "PUT",
          url: "/api/newcampaignusersadd/" + item.id + "/" + this.operationID,
          withCredentials: true,
          data: request,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
        });

        //--------LOGGING START------------//
        // TODO sort out logging
        // request = null;
        // request = {
        //   user_id: this.$store.state.user_id,
        //   type: "added",
        //   char_name: this.newCharName,
        // };

        // await axios({
        //   method: "put",
        //   url: "/api/checkaddremovechar/" + this.operationID,
        //   withCredentials: true,
        //   data: request,
        //   headers: {
        //     Accept: "application/json",
        //     "Content-Type": "application/json",
        //   },
        // });

        //------logging End-----//
      }
    },

    async newCharForm() {
      var request = {
        user_id: this.$store.state.user_id,
        operation_id: this.operationID,
        name: this.newCharName,
        entosis: this.newLink,
        ship: this.newShip,
        role_id: this.newRole,
        user_status_id: 1,
      };

      await axios({
        method: "POST",
        url: "/api/newcampaignusers/" + this.operationID,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      await this.$store.dispatch("getCampaignUsersRecords", this.operationID);
      await this.$store.dispatch("getUsersChars", this.$store.state.user_id);

      //------logging Start-----//
      // TODO sort out logging
      //   request = null;
      //   request = {
      //     user_id: this.$store.state.user_id,
      //     type: "added",
      //     char_name: this.newCharName,
      //   };

      //   await axios({
      //     method: "put",
      //     url: "/api/checkaddremovechar/" + this.operationID,
      //     withCredentials: true,
      //     data: request,
      //     headers: {
      //       Accept: "application/json",
      //       "Content-Type": "application/json",
      //     },
      //   });

      //------logging End-----//

      this.role = null;
      this.newCharName = null;
      this.newLink = null;
      this.newShip = null;
      this.newRole = null;
      this.addShown = false;
    },

    async removeChar(item) {
      this.$store.dispatch("deleteUsersChars", item.id);
      this.$store.dispatch("deleteCampaignUser", item.id);
      await axios({
        method: "DELETE",
        url: "/api/campaignusers/" + item.id,
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
    ...mapState(["campaignusers", "userschars"]),
    filteredItems() {
      return this.userschars;
    },
  },
};
</script>

<style></style>
