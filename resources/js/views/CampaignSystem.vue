<template>
    <div></div>
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
                { text: "Support", value: 5 },
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
            showNotes: false,
            channel: "",
            overlay: false,
            bullhorn: false,
            link: "",
            showAdd: false,
            nodeItem: null
        };
    },

    async created() {
        if (this.$store.getters.getCampaignsCount == 0) {
            await this.$store.dispatch("getCampaigns");
        }

        this.campaignId = this.campaign.id;
        Echo.private("campaignsystem." + this.campaign.id).listen(
            "CampaignSystemUpdate",
            e => {
                // console.log(e);
                if (e.flag.flag == 1) {
                    // console.log(1);
                    this.loadUsersRecords();
                }
                if (e.flag.flag == 2) {
                    // console.log(2);
                    this.loadCampaignSystemRecords();
                }
                if (e.flag.flag == 3) {
                    // console.log(3);
                    this.loadCampaignSystemRecords();
                    this.loadUsersRecords();
                }
                if (e.flag.flag == 4) {
                    // console.log(4);
                    this.loadcampaigns();
                    this.loadCampaignSystemRecords();
                    this.loadUsersRecords();
                }
                if (e.flag.flag == 5) {
                    // console.log(4);
                    this.checkAddUser();
                }

                if (e.flag.flag == 6) {
                    //  console.log(6);
                    this.kickUser(e.flag.user_id);
                }

                if (e.flag.flag == 7) {
                    //  console.log(6);
                    this.$router.push("/campaignfinished");
                }

                if (e.flag.flag == 8) {
                    //  console.log(6);
                    this.loadCampaignSolaSystems();
                }

                if (e.flag.flag == 9) {
                    //  console.log(6);
                    this.loadCampaignSolaSystems();
                    this.loadCampaignSystemRecords();
                }

                if (e.flag.flag == 10) {
                    this.loadCampaignlogs();
                }
            },

            window.addEventListener("beforeunload", this.leaving)
        );
        this.channel = "campaignsystem." + this.campaign.id;
        this.test = 2;
        this.test2 = 1;
        this.navdrawer = true;
        this.addMember();
    },

    beforeMonunt() {},

    async beforeCreate() {},

    async mounted() {
        // await this.$store.dispatch("getCampaignSolaSystems");
        // await this.getSystems(this.campaign.constellation_id);
        // await this.$store.dispatch("getCampaignUsersRecords", this.campaign.id);
        // await this.$store.dispatch("getCampaignSystemsRecords");
        // await this.$store.dispatch("getUsersChars", this.$store.state.user_id);
        // await this.loadCampaignlogs();
    },
    methods: {
        checkAddUser() {
            if (this.userCount == 0) {
                this.bullhorn = true;
            }
        },

        openAdd(item) {
            this.nodeItem = item;
            this.showAdd = true;
        },

        async finishCampaign() {
            await axios({
                method: "get", //you can set what request you want to be
                url: "/api/campaignsystemfinished/" + this.campaign.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.$router.push("/campaignfinished");
        },

        kickUser(user_id) {
            if (this.$store.state.user_id == user_id) {
                this.$router.push("/campaignkick");
            }
        },

        async loadCampaigns() {
            this.$store.dispatch("getCampaigns");
        },

        loadUsersRecords() {
            this.$store.dispatch("getCampaignUsersRecords", this.campaign.id);
        },

        loadCampaignSolaSystems() {
            this.$store.dispatch("getCampaignSolaSystems");
        },

        loadCampaignlogs() {
            if (this.$can("super")) {
                this.$store.dispatch("getLoggingCampaign", this.campaign.id);
            }
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
                url: "/api/campaignsystemcheckaddchar/" + this.campaign.id,
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
                campaign_id: this.campaign.id
            };

            await axios({
                method: "POST", //you can set what request you want to be
                url: "/api/campaignsystemusers/" + this.campaign.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            await axios({
                method: "GET", //you can set what request you want to be
                url:
                    "/api/checkjoinleavecampaign/" +
                    this.campaign.id +
                    "/" +
                    user_id +
                    "/4",
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

            await axios({
                method: "GET", //you can set what request you want to be
                url:
                    "/api/checkjoinleavecampaign/" +
                    this.campaign.id +
                    "/" +
                    this.$store.state.user_id +
                    "/5",
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
                campaign_id: this.campaign.id,
                char_name: this.newCharName,
                link: this.newLink,
                ship: this.newShip,
                campaign_role_id: this.newRole
            };

            await axios({
                method: "POST", //you can set what request you want to be
                url: "/api/campaignusers/" + this.campaign.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.$store.dispatch("getCampaignUsersRecords", this.campaign.id);
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
                    this.campaign.id,
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
                    this.campaign.id +
                    "/" +
                    this.$store.state.user_id,
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

            this.$store.dispatch("getCampaignUsersRecords", this.campaign.id);
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
            "getCampaignByLink",
            "getActiveCampaigns",
            "getCampaignsCount",
            "getCampaignUsersByUserId",
            "getCampaignUsersByUserIdCount",
            "getTotalNodeCountByCampaign",
            "getHackingNodeCountByCampaign",
            "getRedHackingNodeCountByCampaign"
        ]),

        campaign() {
            return this.getCampaignByLink(this.$route.params.id);
        },

        userCharsDrop() {
            // let payload = {
            //     id: this.$store.state.user_id,
            //     campaignID: this.$route.params.id}
            return this.getCampaignUsersByUserId(this.$store.state.user_id);
        },

        userCount() {
            return this.getCampaignUsersByUserIdCount(
                this.$store.state.user_id
            );
        },
        barScoure() {
            var d =
                this.getCampaignById(this.campaign.id).defenders_score * 100;
            var a =
                this.getCampaignById(this.campaign.id).attackers_score * 100;

            if (d > 50) {
                return d;
            }

            return a;
        },

        barBgcolor() {
            var d =
                this.getCampaignById(this.campaign.id).defenders_score * 100;
            var a =
                this.getCampaignById(this.campaign.id).attackers_score * 100;

            if (d > 50) {
                return "red darken-4";
            }

            return "blue darken-4";
        },

        barColor() {
            var d =
                this.getCampaignById(this.campaign.id).defenders_score * 100;
            if (d > 50) {
                return "blue darken-4";
            }

            return "red darken-4";
        },

        barReverse() {
            var d =
                this.getCampaignById(this.campaign.id).defenders_score * 100;
            if (d > 50) {
                return false;
            }

            return true;
        },

        barActive() {
            if (this.getCampaignById(this.campaign.id).status_id > 1) {
                return true;
            }
            return false;
        },
        nodeCountAll() {
            return this.getTotalNodeCountByCampaign(this.campaign.id);
        },

        nodeCountHackingCountAll() {
            return this.getHackingNodeCountByCampaign(this.campaign.id);
        },

        nodeRedCountHackingCountAll() {
            return this.getRedHackingNodeCountByCampaign(this.campaign.id);
        }
    },
    beforeDestroy() {
        this.leaving();
        window.removeEventListener("beforeunload", this.leaving);
    }
};
</script>
