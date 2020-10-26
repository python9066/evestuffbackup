<template>
    <div>


        <v-row
            no-gutters
            v-if="this.getCampaignsCount > 1"
            justify="space-around"
        >
            <v-col md="10">
                <v-card
                    class="pa-2 d-flex justify-space-between full-width align-center"
                    tile
                >
                    <div class=" d-md-inline-flex">
                        <v-btn
                            class="mr-4"
                            color="blue darken-2"
                            v-if="showTable == false"
                            @click="showTable = true"
                            >Show Char table</v-btn
                        >
                        <v-btn
                            class="mr-4"
                            color="orange darken-2"
                            v-if="showTable == true"
                            @click="showTable = false"
                            >Hide Char table</v-btn
                        >
                        <v-menu
                            :close-on-content-click="false"
                            :value="addShown"
                            transition="fab-transition"
                            origin="100% -30%"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    class="mr-4"
                                    @click="addShown = true"
                                    v-bind="attrs"
                                    color="green lighten-1"
                                    v-on="on"
                                    >Add Char</v-btn
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
                        <v-menu
                            v-if="userCount != 0"
                            :close-on-content-click="false"
                            :value="removeShown"
                            transition="fab-transition"
                            origin="100% -30%"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    class="mr-4"
                                    @click="removeShown = true"
                                    v-bind="attrs"
                                    v-on="on"
                                    color="red lighten-2"
                                    >Edit/Remove Char</v-btn
                                >
                            </template>
                            <!---edit/delete form------>
                            <v-row no-gutters>
                                <div>
                                    <v-card class="pa-2" tile width="100%">
                                        <v-form
                                            @submit.prevent="editCharForm()"
                                        >
                                            <v-select
                                                v-model="editCharName"
                                                :items="userCharsDrop"
                                                label="Pick the char you want to edit"
                                                name="editChars"
                                                :item-text="'char_name'"
                                                :item-value="'id'"
                                                @change="charEditForm($event)"
                                            ></v-select>
                                            <v-select
                                                v-model="editRole"
                                                @change="roleEditForm($event)"
                                                :items="dropdown_roles"
                                                label="Role"
                                                required
                                                :placeholder="editTextRole"
                                            ></v-select>
                                            <v-text-field
                                                v-model="editShip"
                                                v-if="this.editrole == 1"
                                                label="Ship"
                                                required
                                                :placeholder="editTextShip"
                                            ></v-text-field>
                                            <v-radio-group
                                                v-model="editLink"
                                                v-if="this.editrole == 1"
                                                row
                                                label="Entosis Link"
                                                required
                                                :placeholder="editTextLink"
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

                                            <v-btn class="mr-2" type="submit"
                                                >submit</v-btn
                                            >
                                            <v-btn
                                                class="mr-2"
                                                @click="editFormRemove()"
                                                >Remove</v-btn
                                            >
                                            <v-btn
                                                class="mr-2"
                                                @click="editFormClose()"
                                                >Close</v-btn
                                            >
                                            <!-- <v-btn @click="clear">clear</v-btn> -->
                                        </v-form>
                                        <!---edit/delete form------>
                                    </v-card>
                                </div>
                            </v-row>
                        </v-menu>
                        <v-menu
                            :close-on-content-click="false"
                            transition="fab-transition"
                            origin="100% -30%"
                            :nudge-width="200"
                            offset-x
                        >
                            <template
                                v-slot:activator="{ on, attrs }"
                                v-if="$can('view_campaign_members')"
                            >
                                <v-btn
                                    class="mr-4"
                                    @click="showUsers = !showUsers"
                                    v-bind="attrs"
                                    color="warning"
                                    v-on="on"
                                    >People Watching</v-btn
                                >
                            </template>
                            <v-row no-gutters>
                                <div style="width: 400px;">
                                    <watchUserTable
                                        :campaign_id="this.campaignId"
                                    >
                                    </watchUserTable>
                                </div>
                            </v-row>
                        </v-menu>
                        <v-btn v-if="$can('super')" @click="overlay = !overlay">
                            test
                        </v-btn>
                        <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-if="$can('access_campaigns')"
                            fab
                            dark
                            small
                            v-bind="attrs"
                            v-on="on"
                            @click="sendAddCharMessage()"
                        >
                            <v-icon>fas fa-bullhorn</v-icon>
                        </v-btn>
                        </template>
                        <span> Send a message to all Users without a Char added </span>
                        </v-tooltip>
                    </div>
                    <v-spacer></v-spacer>
                    <div
                        class=" ml-auto d-inline-flex align-center"
                        v-if="nodeCountAll > 0"
                    >
                        <v-divider class="mx-4 my-0" vertical></v-divider>
                        <p class=" pt-4 pr-3">Active Nodes -</p>
                        <v-progress-circular
                            class=" pr-3"
                            :transitionDuration="5000"
                            :radius="25"
                            :strokeWidth="5"
                            :value="
                                (nodeCountHackingCountAll / nodeCountAll) *
                                    100 || 0.000001
                            "
                        >
                            <div class="caption">
                                {{ nodeCountHackingCountAll }} /
                                {{ nodeCountAll }}
                            </div></v-progress-circular
                        >
                        <v-progress-circular
                            :transitionDuration="5000"
                            :radius="25"
                            :strokeWidth="5"
                            strokeColor="#FF3D00"
                            :value="
                                (nodeRedCountHackingCountAll / nodeCountAll) *
                                    100 || 0.000001
                            "
                        >
                            <div class="caption">
                                {{ nodeRedCountHackingCountAll }} /
                                {{ nodeCountAll }}
                            </div></v-progress-circular
                        >
                    </div>

                </v-card>
            </v-col>
        </v-row>

        <v-row no-gutters justify="space-around" v-if="showTable == true">
            <userTable :campaign_id="$route.params.id"> </userTable>
        </v-row>

        <v-row no-gutters justify="center" :v-if="systemLoaded == true">
            <systemTable
                class=" px-5 pt-5"
                v-for="(system, index) in systems"
                :system_name="system.system_name"
                :system_id="system.id"
                :campaign_id="$route.params.id"
                :index="index"
                :key="system.id"
            >
            </systemTable>
        </v-row>

        <v-overlay z-index="0" :value="overlay">
            <v-card>
                <v-card-title> MAKE SURE TO ADD A CHAR </v-card-title>
                <v-card-text>
                    Remeber to add any chars you have in the campaign by
                    pressing the green "ADD CHAR" button</v-card-text
                >
                <v-card-actions>
                    <v-btn
                        class="white--text"
                        color="teal"
                        @click="(overlay = false), (addShown = true)"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-overlay>
    </div>
</template>
<!-- {{ $route.params.id }} - {{ test }} -  -->
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

export default {
    data() {
        return {
            dropdown_roles: [
                { text: "Hacker", value: 1 },
                { text: "Scout", value: 2 },
                { text: "FC", value: 3 },
                { text: "Command", value: 4 }
            ],
            dropdown_status: [
                { text: "None", value: 1 },
                { text: "On the way", value: 2 },
                { text: "Ready to go", value: 3 }
            ],

            newCharName: null,
            newNameRules: [v => !!v || "Name is required"],
            newRole: null,
            newRoleRules: [v => !!v || "You need to pick a role"],
            newShip: null,
            newShipRules: [v => !!v || "Ship is required"],
            newLink: null,
            newLinkRules: [v => !!v || "T1 or T2?"],

            editCharName: null,
            editNameRules: [v => !!v || "Name is required"],
            editRole: null,
            editTextRole: null,
            editRoleRules: [v => !!v || "You need to pick a role"],
            editShip: null,
            editTextShip: null,
            editShipRules: [v => !!v || "Ship is required"],
            editLink: null,
            editTextLink: null,
            editLinkRules: [v => !!v || "T1 or T2?"],
            editUserForm: 1,
            editrole_name: null,

            oldChar: [],
            role: 0,
            editrole: 0,
            systems: [],
            test: 1,
            test2: "",
            valid: false,
            addShown: false,
            removeShown: false,
            showTable: false,
            systemLoaded: false,
            campaignId: 0,
            showUsers: false,
            channel: "",
            overlay: false
        };
    },

    async created() {
        this.campaignId = this.$route.params.id;
        // Echo.private("campaignsystem." + this.campaignId).listen(
        //     "CampaignSystemUpdate",
        //     e => {
        //         // console.log(e);
        //         if (e.flag.flag == 1) {
        //             // console.log(1);
        //             this.loadUsersRecords();
        //         }
        //         if (e.flag.flag == 2) {
        //             // console.log(2);
        //             this.loadCampaignSystemRecords();
        //         }
        //         if (e.flag.flag == 3) {
        //             // console.log(3);
        //             this.loadCampaignSystemRecords();
        //             this.loadUsersRecords();
        //         }
        //         if (e.flag.flag == 4) {
        //             // console.log(4);
        //             this.loadcampaigns();
        //             this.loadCampaignSystemRecords();
        //             this.loadUsersRecords();
        //         }
        //         if (e.flag.flag == 5) {
        //             // console.log(4);
        //             this.checkAddUser();
        //         }

        //         if (e.flag.flag == 6) {
        //             //  console.log(6);
        //             this.kickUser(e.flag.user_id);
        //         }
        //     },

        //     window.addEventListener("beforeunload", this.leaving)
        // );
        this.channel = "campaignsystem." + this.campaignId;
        this.navdrawer = true;
        this.addMember();
    },

    beforeMonunt() {},

    beforeCreate() {},

    async mounted() {
        if (this.$store.getters.getCampaignsCount == 0) {
            await this.$store.dispatch("getCampaigns");
        }
        // console.log(this.campaignId)
        await this.getSystems(this.this.campaignId);
        await this.$store.dispatch(
            "getCampaignUsersRecords",
            this.campaignId
        );
        await this.$store.dispatch("getCampaignSystemsRecords");
    },
    methods: {
        checkAddUser() {
            if (this.userCount == 0) {
                this.overlay = true;
            }
        },

        kickUser(user_id){
            if(this.$store.state.user_id == user_id){
                this.$router.push('/campaignkick');
            }
        },

        async loadCampaigns() {
            this.$store.dispatch("getCampaigns");
        },

        loadUsersRecords() {
            this.$store.dispatch(
                "getCampaignUsersRecords",
                this.campaignId
            );
        },

        loadCampaignSystemRecords() {
            this.$store.dispatch("getCampaignSystemsRecords");
        },

        async loadcampaigns() {
            this.loadingr = true;
            this.$store.dispatch("getCampaigns").then(() => {
                this.loadingr = false;
            });
        },

        async sendAddCharMessage() {
            await axios({
                method: "get", //you can set what request you want to be
                url: "/api/campaignsystemcheckaddchar/" + this.campaignId,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async getSystems(id) {
            // console.log(id, this.$store.state.token);
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/campaignjoinsystems/" + id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.systems = res.data.systems;
            this.systemLoaded = true;
        },

        async addMember() {
            let user_id = this.$store.state.user_id;
            if (user_id == 0) {
                await sleep(1000);
                user_id = this.$store.state.user_id;
                if (user_id == 0) {
                    await sleep(1000);
                    user_id = this.$store.state.user_id;
                }
            }
            var request = {
                user_id: user_id,
                campaign_id: this.campaignId
            };

            await axios({
                method: "POST", //you can set what request you want to be
                url: "/api/campaignsystemusers/" + this.campaignId,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async leaving() {
            Echo.leave(this.channel);
            await axios({
                method: "delete", //you can set what request you want to be
                url:
                    "/api/campaignsystemusers/" +
                    this.$store.state.user_id +
                    "/" +
                    this.campaignId,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        roleForm(a) {
            this.role = a;
            // console.log("LALAL");
            // console.log(a);
        },

        newCharFormClose() {
            this.addShown = false;
            this.newCharName = null;
            this.newRole = null;
            this.newShip = null;
            this.newLink = null;
        },

        roleEditForm(a) {
            this.editrole = a;
            // console.log("LALAL");
            // console.log(a);
        },

        charEditForm($event) {
            this.oldChar = this.userCharsDrop.find(user => user.id == $event);
            this.editRole = this.oldChar.role_id;
            this.editTextShip = this.oldChar.ship;
            this.editTextLink = this.oldChar.link;
            if (this.oldChar.role_id == 1) {
                this.editrole = 1;
            } else {
                this.editrole = 0;
            }
        },

        editFormClose() {
            this.removeShown = false;
            this.editCharName = null;
            this.editRole = null;
            this.editTextRole = null;
            this.editShip = null;
            this.editTextShip = null;
            this.editLink = null;
            this.editTextLink = null;
            this.editrole = null;
        },

        async newCharForm() {
            var request = {
                site_id: this.$store.state.user_id,
                campaign_id: this.campaignId,
                char_name: this.newCharName,
                link: this.newLink,
                ship: this.newShip,
                campaign_role_id: this.newRole
            };

            await axios({
                method: "POST", //you can set what request you want to be
                url: "/api/campaignusers/" + this.campaignId,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.$store.dispatch(
                "getCampaignUsersRecords",
                this.campaignId
            );
            this.role = null;
            this.newCharName = null;
            this.newLink = null;
            this.newShip = null;
            this.newRole = null;
            this.addShown = false;
        },

        editCharForm() {
            this.removeShown = false;

            var link = this.oldChar.link;
            var ship = this.oldChar.ship;
            var role = this.oldChar.role_id;
            var role_name = this.oldChar.role_name;

            if (this.oldChar.role_id != this.editRole) {
                var role = this.editRole;
                var role_name = this.dropdown_roles.find(
                    droprole => droprole.value == role
                ).text;
            }
            if (this.oldChar.ship != this.editShip) {
                var ship = this.editShip;
            }
            if (this.oldChar.link != this.editLink) {
                var link = this.editLink;
            }
            // console.log(role_name);
            var request = {
                link: link,
                ship: ship,
                campaign_role_id: role
            };

            var item = {
                id: this.oldChar.id,
                link: link,
                ship: ship,
                role_id: role,
                role_name: role_name
            };

            this.$store.dispatch("updateCampaignUsers", item);

            axios({
                method: "PUT", //you can set what request you want to be
                url:
                    "/api/campaignusers/" +
                    this.oldChar.id +
                    "/" +
                    this.campaignId,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.editCharName = null;
            this.editRole = null;
            this.editTextRole = null;
            this.editShip = null;
            this.editTextShip = null;
            this.editLink = null;
            this.editTextLink = null;
        },

        async editFormRemove() {
            await axios({
                method: "DELETE", //you can set what request you want to be
                url:
                    "/api/campaignusers/" +
                    this.oldChar.id +
                    "/" +
                    this.campaignId,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.removeShown = false;
            this.editCharName = null;
            this.editRole = null;
            this.editTextRole = null;
            this.editShip = null;
            this.editTextShip = null;
            this.editLink = null;
            this.editTextLink = null;

            this.$store.dispatch(
                "getCampaignUsersRecords",
                this.campaignId
            );
            this.$store.dispatch("getCampaignSystemsRecords");
        },
        campaignStart() {
            var data = {
                id: this.campaign.id,
                status_id: 2,
                status_name: "Active"
            };
            this.$store.dispatch("updateCampaignSystem", data);
            this.$store.dispatch("updateCampaign", data);
        }
    },

    computed: {
        ...mapGetters([
            "getCampaignById",
            "getActiveCampaigns",
            "getCampaignsCount",
            "getCampaignUsersByUserId",
            "getCampaignUsersByUserIdCount",
            "getTotalNodeCountByCampaign",
            "getHackingNodeCountByCampaign",
            "getRedHackingNodeCountByCampaign"
        ]),

        campaign() {
            return this.getCampaignById(this.campaignId);
        },

        closechannel(){
            if (this.campaigan.defenders_score == 1 || this.campaigan.defenders_score == 0){
                Echo.leave(this.channel);
            }
        },

        userCharsDrop() {
            // let payload = {
            //     id: this.$store.state.user_id,
            //     campaignID: this.campaignId}
            return this.getCampaignUsersByUserId(this.$store.state.user_id);
        },

        userCount() {
            return this.getCampaignUsersByUserIdCount(
                this.$store.state.user_id
            );
        },
        barScoure() {
            var d =
                this.getCampaignById(this.campaignId).defenders_score *
                100;
            var a =
                this.getCampaignById(this.campaignId).attackers_score *
                100;

            if (d > 50) {
                return d;
            }

            return a;
        },

        barBgcolor() {
            var d =
                this.getCampaignById(this.campaignId).defenders_score *
                100;
            var a =
                this.getCampaignById(this.campaignId).attackers_score *
                100;

            if (d > 50) {
                return "red darken-4";
            }

            return "blue darken-4";
        },

        barColor() {
            var d =
                this.getCampaignById(this.campaignId).defenders_score *
                100;
            if (d > 50) {
                return "blue darken-4";
            }

            return "red darken-4";
        },

        barReverse() {
            var d =
                this.getCampaignById(this.campaignId).defenders_score *
                100;
            if (d > 50) {
                return false;
            }

            return true;
        },

        barActive() {
            if (this.getCampaignById(this.campaignId).status_id > 1) {
                return true;
            }
            return false;
        },
        nodeCountAll() {
            return this.getTotalNodeCountByCampaign(this.campaignId);
        },

        nodeCountHackingCountAll() {
            return this.getHackingNodeCountByCampaign(this.campaignId);
        },

        nodeRedCountHackingCountAll() {
            return this.getRedHackingNodeCountByCampaign(this.campaignId);
        }
    },
    beforeDestroy() {
        this.leaving();
        window.removeEventListener("beforeunload", this.leaving);
    }
};
</script>
