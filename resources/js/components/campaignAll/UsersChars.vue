<template>
    <v-col cols="10">
        <v-card tile min-width="700px">
            <v-card-title class="d-flex justify-space-between align-center ">
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
                                ><v-icon left small>fas fa-plus</v-icon>
                                Char</v-btn
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
                                            <v-radio
                                                label="Tech 1"
                                                value="1"
                                            ></v-radio>
                                            <v-radio
                                                label="Tech 2"
                                                value="2"
                                            ></v-radio>
                                        </v-radio-group>

                                        <v-btn
                                            color="success"
                                            class="mr-4"
                                            type="submit"
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
                    <template slot="no-data">
                        You have no saved Chars
                    </template>
                    <!-- :color="pillColor(item)" -->
                    <template v-slot:item.addRemove="{ item }">
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
                    <template v-slot:item.actions="{ item }">
                        <span>
                            <UsersCharsEdit
                                :item="item"
                                :campaign_id="campaign_id"
                            >
                            </UsersCharsEdit>

                            <v-icon
                                color="orange darken-3"
                                small
                                @click="removeChar(item)"
                            >
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
    </v-col>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        campaign_id: Number
    },
    data() {
        return {
            headers: [
                { text: "Name", value: "char_name" },
                { text: "Role", value: "role_name" },
                { text: "Ship", value: "ship" },
                { text: "Entosis", value: "link" },
                { text: "", value: "addRemove", align: "center" },
                { text: "", value: "actions", align: "end" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],

            dropdown_roles: [
                { text: "Hacker", value: 1 },
                { text: "Support", value: 5 },
                { text: "Scout", value: 2 },
                { text: "FC", value: 3 },
                { text: "Command", value: 4 }
            ],
            statusflag: 0,
            toggle_exclusive: 0,
            newCharName: null,
            newNameRules: [v => !!v || "Name is required"],
            newRole: null,
            newRoleRules: [v => !!v || "You need to pick a role"],
            newShip: null,
            newShipRules: [v => !!v || "Ship is required"],
            newLink: null,
            newLinkRules: [v => !!v || "T1 or T2?"],

            role: 0,
            editrole: 0,
            oldChar: [],

            addShown: false
        };
    },

    methods: {
        close() {
            this.$emit("closeAddChar", "yo");
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
            // console.log("LALAL");
            // console.log(a);
        },

        roleEditForm(a) {
            this.editrole = a;
            // console.log("LALAL");
            // console.log(a);
        },

        pillColor(item) {
            if (item.campaign_id == this.campaign_id) {
                return "red";
            } else {
                return "green";
            }
        },

        pillText(item) {
            if (item.campaign_id == this.campaign_id) {
                return "Remove";
            } else {
                return "Add";
            }
        },

        pillIcon(item) {
            if (item.campaign_id == this.campaign_id) {
                return "fas fa-minus";
            } else {
                return "fas fa-plus";
            }
        },

        async pillClick(item) {
            if (item.campaign_id == this.campaign_id) {
                var request = {
                    campaign_id: null,
                    campaign_system_id: null,
                    system_id: null,
                    status_id: 1
                };

                await axios({
                    method: "PUT", //you can set what request you want to be
                    url:
                        "/api/campaignusers/" +
                        item.id +
                        "/" +
                        this.campaign_id,
                    data: request,
                    headers: {
                        Authorization: "Bearer " + this.$store.state.token,
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    }
                });
                var request2 = {
                    id: item.id
                };

                await axios({
                    method: "PUT", //you can set what request you want to be
                    url: "/api/campaignsystemmovechar/" + this.campaign_id,
                    data: request2,
                    headers: {
                        Authorization: "Bearer " + this.$store.state.token,
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    }
                });

                await this.$store.dispatch(
                    "getCampaignUsersRecords",
                    this.campaign_id
                );
                await this.$store.dispatch(
                    "getUsersChars",
                    this.$store.state.user_id
                );
                this.$store.dispatch("getCampaignSystemsRecords");

                request = null;
                request = {
                    user_id: this.$store.user_id,
                    type: "removed",
                    char_name: this.newCharName
                };

                await axios({
                    method: "put", //you can set what request you want to be
                    url: "/api/checkaddremovechar/" + this.campaign_id,
                    data: request,
                    headers: {
                        Authorization: "Bearer " + this.$store.state.token,
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    }
                });
            } else {
                var request = {
                    campaign_id: this.campaign_id,
                    campaign_system_id: null,
                    system_id: null,
                    status_id: 1
                };

                await axios({
                    method: "PUT", //you can set what request you want to be
                    url:
                        "/api/campaignusers/" +
                        item.id +
                        "/" +
                        this.campaign_id,
                    data: request,
                    headers: {
                        Authorization: "Bearer " + this.$store.state.token,
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    }
                });
                var request2 = {
                    id: item.id
                };

                await axios({
                    method: "PUT", //you can set what request you want to be
                    url: "/api/campaignsystemmovechar/" + this.campaign_id,
                    data: request2,
                    headers: {
                        Authorization: "Bearer " + this.$store.state.token,
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    }
                });

                await this.$store.dispatch(
                    "getCampaignUsersRecords",
                    this.campaign_id
                );
                await this.$store.dispatch(
                    "getUsersChars",
                    this.$store.state.user_id
                );
                this.$store.dispatch("getCampaignSystemsRecords");
                request = null;
                request = {
                    user_id: this.$store.user_id,
                    type: "added",
                    char_name: this.newCharName
                };

                await axios({
                    method: "put", //you can set what request you want to be
                    url: "/api/checkaddremovechar/" + this.campaign_id,
                    data: request,
                    headers: {
                        Authorization: "Bearer " + this.$store.state.token,
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    }
                });
            }
        },

        async newCharForm() {
            var request = {
                site_id: this.$store.state.user_id,
                campaign_id: this.campaign_id,
                char_name: this.newCharName,
                link: this.newLink,
                ship: this.newShip,
                campaign_role_id: this.newRole
            };

            await axios({
                method: "POST", //you can set what request you want to be
                url: "/api/campaignusers/" + this.campaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            await this.$store.dispatch(
                "getCampaignUsersRecords",
                this.campaign_id
            );
            await this.$store.dispatch(
                "getUsersChars",
                this.$store.state.user_id
            );

            request = null;
            request = {
                user_id: this.$store.user_id,
                type: "added",
                char_name: this.newCharName
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/checkaddremovechar/" + this.campaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.role = null;
            this.newCharName = null;
            this.newLink = null;
            this.newShip = null;
            this.newRole = null;
            this.addShown = false;
        },

        async removeChar(item) {
            // console.log(item);
            await axios({
                method: "DELETE", //you can set what request you want to be
                url:
                    "/api/campaignusers/" +
                    item.id +
                    "/" +
                    this.campaign_id +
                    "/" +
                    this.$store.state.user_id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            await this.$store.dispatch(
                "getUsersChars",
                this.$store.state.user_id
            );
            this.$store.dispatch("getCampaignUsersRecords", this.campaign_id);
            this.$store.dispatch("getCampaignSystemsRecords");
        }
    },

    computed: {
        ...mapState(["campaignusers", "userschars"]),
        filteredItems() {
            return this.userschars;
        }
    }
};
</script>

<style></style>
