<template>
    <div>
        <v-row no-gutters v-if="this.getCampaignsCount > 1">
            <v-col lg="1"></v-col>
            <v-col md="10">
                <v-card class="pa-2" tile width="100%">
                    <v-card-title align="center" class="justify-center">
                        Campaign page for the {{ this.campaign.item_name }} in
                        {{ this.campaign.system }}
                    </v-card-title>
                </v-card>
            </v-col>
            <v-col lg="1"></v-col>
        </v-row>
        <v-row
            no-gutters
            v-if="this.getCampaignsCount > 1"
            class="pt-5"
            justify="space-around"
        >
            <v-col md="10">
                <v-card class="pa-2" tile width="100%">
                    <v-card-title align="center" class="justify-center">
                        {{ this.campaign.region }} -
                        {{ this.campaign.constellation }}
                        {{ this.campaign.system }} -
                        <v-avatar size="35"
                            ><img :src="this.campaign.url"
                        /></v-avatar>
                        -
                        {{ this.campaign.alliance }}
                    </v-card-title>
                    <div class="d-flex full-width align-content-center">
                        <v-icon
                            v-if="
                                this.campaign.defenders_score >
                                    this.campaign.defenders_score_old &&
                                    this.campaign.defenders_score_old > 0
                            "
                            small
                            left
                            dark
                            color="blue darken-4"
                        >
                            fas fa-arrow-alt-circle-up
                        </v-icon>
                        <v-icon
                            v-if="
                                this.campaign.defenders_score <
                                    this.campaign.defenders_score_old &&
                                    this.campaign.defenders_score_old > 0
                            "
                            small
                            left
                            dark
                            color="blue darken-4"
                        >
                            fas fa-arrow-alt-circle-down
                        </v-icon>
                        <v-icon
                            v-if="
                                this.campaign.defenders_score ==
                                    this.campaign.defenders_score_old ||
                                    this.campaign.defenders_score_old === null
                            "
                            small
                            left
                            dark
                            color="grey darken-3"
                        >
                            fas fa-minus-circle
                        </v-icon>

                        <v-progress-linear
                            :color="this.barColor"
                            :value="this.barScoure"
                            height="20"
                            rounded
                            :active="this.barActive"
                            :reverse="this.barReverse"
                            :background-color="this.barBgcolor"
                            background-opacity="0.2"
                        >
                            <strong>
                                {{ this.campaign.defenders_score * 100 }} /
                                {{ this.campaign.attackers_score * 100 }}
                            </strong>
                        </v-progress-linear>

                        <v-icon
                            v-if="
                                this.campaign.attackers_score >
                                    this.campaign.attackers_score_old &&
                                    this.campaign.attackers_score_old > 0
                            "
                            small
                            right
                            dark
                            color="red darken-4"
                        >
                            fas fa-arrow-alt-circle-up
                        </v-icon>
                        <v-icon
                            v-if="
                                this.campaign.attackers_score <
                                    this.campaign.attackers_score_old &&
                                    this.campaign.attackers_score_old > 0
                            "
                            small
                            right
                            dark
                            color="red darken-4"
                        >
                            fas fa-arrow-alt-circle-down
                        </v-icon>
                        <v-icon
                            v-if="
                                this.campaign.attackers_score ==
                                    this.campaign.attackers_score_old ||
                                    this.campaign.attackers_score_old == null
                            "
                            small
                            right
                            dark
                            color="grey darken-3"
                        >
                            fas fa-minus-circle
                        </v-icon>
                    </div>
                </v-card>
            </v-col>
        </v-row>
        <v-row
            no-gutters
            v-if="this.getCampaignsCount > 1"
            justify="space-around"
        >
            <v-col md="10">
                <v-card class="pa-2" tile width="100%">
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
                            <v-col>
                                <v-card class="pa-2" tile width="100%">
                                    <v-form @submit.prevent="newCharForm()">
                                        <v-text-field
                                            v-model="newCharName"
                                            label="Char Name"
                                            required
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

                                        <v-btn class="mr-4" type="submit"
                                            >submit</v-btn
                                        >
                                        <v-btn
                                            class="mr-4"
                                            @click="newCharFormClose()"
                                            >Close</v-btn
                                        >
                                        <!-- <v-btn @click="clear">clear</v-btn> -->
                                    </v-form>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-menu>
                    <v-menu
                        :close-on-content-click="false"
                        :value="removeShown"
                        transition="fab-transition"
                        origin="100% -30%"
                    >
                    <template v-slot:activator="{ on, attrs }" v-if="this.getCampaignUsersByUserIdCount" != 0>
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
                            <v-col>
                                <v-card class="pa-2" tile width="100%">
                                    <v-form @submit.prevent="editCharForm()">
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
                            </v-col>
                        </v-row>
                    </v-menu>
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
                </v-card>
            </v-col>
        </v-row>

        <v-row
            no-gutters
            justify="space-around"
            v-if="showTable == true"
        >
            <userTable :campaign_id="$route.params.id"> </userTable>
        </v-row>

        <v-row
            no-gutters
            justify="center"
            :v-if="systemLoaded == true"
        >
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
    </div>
</template>
<!-- {{ $route.params.id }} - {{ test }} -  -->
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters } from "vuex";
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
            systemLoaded: false
        };
    },

    beforeMonunt() {},

    beforeCreate() {},

    async mounted() {
        if (this.$store.getters.getCampaignsCount == 0) {
            await this.$store.dispatch("getCampaigns");
        }
        // console.log(this.$route.params.id)
        await this.getSystems(this.campaign.constellation_id);
        await this.$store.dispatch("getCampaignUsersRecords",this.$route.params.id);
        await this.$store.dispatch("getCampaignSystemsRecords");
    },
    methods: {

        loadCampaigns(){
            this.$store.dispatch("getCampaigns")
        },

        loadUsersRecords(){
            this.$store.dispatch("getCampaignUsersRecords",this.$route.params.id);
        },

        loadCampaignSystemRecords(){
            this.$store.dispatch("getCampaignSystemsRecords");
        },

        async loadcampaigns() {
            this.loadingr = true
            this.$store.dispatch("getCampaigns").then(() =>{
                this.loadingr = false
            });

        },

        async getSystems(id) {
            // console.log(id, this.$store.state.token);
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/systemsinconstellation/" + id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.systems = res.data.systems;
            this.systemLoaded = true;
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
            this.editTextRole = null;
            this.editTextShip = null;
            this.editTextLink = null;
            this.oldCha = null;
            this.removeShown = false;
            this.editrole = 0;
            this.editUserForm = 0;

        },

        async newCharForm() {


            var request = {
                site_id: this.$store.state.user_id,
                campaign_id: this.$route.params.id,
                char_name: this.newCharName,
                link: this.newLink,
                ship: this.newShip,
                campaign_role_id: this.newRole
            };

            await axios({
                method: "POST", //you can set what request you want to be
                url: "/api/campaignusers/" + this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.$store.dispatch(
                "getCampaignUsersRecords",
                this.$route.params.id
            );
            this.role = null;
            this.newCharName = null;
            this.newLink = null;
            this.newShip = null;
            this.newRole = null;
            this.addShown = false;
        },

        editCharForm() {
            this.removeShown = false
            if (this.oldChar.role_id != this.editRole) {
                var role = this.editRole;
            }
            if (this.oldChar.ship != this.editShip) {
                var ship = this.editShip;
            }
            if (this.oldChar.link != this.editLink) {
                var link = this.editLink;
            }
            if (this.oldChar.role_name != this.editrole_name) {
                var role_name = this.dropdown_roles.find(
                    droprole => droprole.value == role
                ).text;
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
                url: "/api/campaignusers/" + this.oldChar.id + "/" + this.$route.params.id,
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

        editFormRemove() {
            axios({
                method: "DELETE", //you can set what request you want to be
                url: "/api/campaignusers/" + this.oldChar.id + "/" + this.$route.params.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.removeShown = false
            this.editCharName = null;
            this.editRole = null;
            this.editTextRole = null;
            this.editShip = null;
            this.editTextShip = null;
            this.editLink = null;
            this.editTextLink = null;

            this.$store.dispatch("getCampaignUsersRecords",this.$route.params.id);
            this.$store.dispatch("getCampaignSystemsRecords");

        }
    },

    async created() {
        Echo.private('campaignsystem.'+ this.$route.params.id)
        .listen('CampaiganSystemUpdate',(e) => {
            if(e.flag.flag == 1){
                this.loadUsersRecords()
            }
            if(e.flag.flag == 2){
                this.loadCampaignSystemRecords()
            }
            if(e.flag.flag == 3){
                this.loadCampaignSystemRecords()
                this.loadUsersRecords()
            }
            if(e.flag.flag == 3){
                this.loadcampaigns()
            }






        });
        this.test = 2;
        this.test2 = 1;
        this.navdrawer = true;
    },

    computed: {
        ...mapGetters([
            "getCampaignById",
            "getActiveCampaigns",
            "getCampaignsCount",
            "getCampaignUsersByUserId",
            "getCampaignUsersByUserIdCount"
        ]),

        campaign() {
            return this.getCampaignById(this.$route.params.id);
        },

        userCharsDrop() {
            let payload = {
                id: this.$store.state.user_id,
                campaignID: this.$route.params.id}
            return this.getCampaignUsersByUserId(payload);
        },
        barScoure() {
            var d =
                this.getCampaignById(this.$route.params.id).defenders_score *
                100;
            var a =
                this.getCampaignById(this.$route.params.id).attackers_score *
                100;

            if (d > 50) {
                return d;
            }

            return a;
        },

        barBgcolor() {
            var d =
                this.getCampaignById(this.$route.params.id).defenders_score *
                100;
            var a =
                this.getCampaignById(this.$route.params.id).attackers_score *
                100;

            if (d > 50) {
                return "red darken-4";
            }

            return "blue darken-4";
        },

        barColor() {
            var d =
                this.getCampaignById(this.$route.params.id).defenders_score *
                100;
            if (d > 50) {
                return "blue darken-4";
            }

            return "red darken-4";
        },

        barReverse() {
            var d =
                this.getCampaignById(this.$route.params.id).defenders_score *
                100;
            if (d > 50) {
                return false;
            }

            return true;
        },

        barActive() {
            if (this.getCampaignById(this.$route.params.id).status_id > 1) {
                return true;
            }
            return false;
        }
    },
    beforeDestroy(){
        Echo.leave('campaignsystem.'+ this.$route.params.id)
    }
};
</script>
