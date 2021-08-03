<template>
    <div class=" pr-16 pl-16">
        <div class=" d-flex align-items-center">
            <v-card-title> Initial Campaigns</v-card-title>

            <v-btn
                :loading="loadingf"
                :disabled="loadingf"
                @click="overlay = !overlay"
                color="light-blue darken-4"
            >
                ADD CAMPAIGN
            </v-btn>
        </div>
        <v-data-table
            :headers="headers"
            :items="campaigns"
            item-key="id"
            :loading="loading"
            :items-per-page="25"
            :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
            class="elevation-1"
        >
            <!-- @click:row="rowClick($event)" -->
            <template slot="no-data">
                No Multi Campaigns have been made
            </template>
            <template v-slot:[`item.system`]="{ item }">
                <StartSystemItemList :campaignID="item.id">
                </StartSystemItemList>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
                <v-btn
                    icon
                    @click="
                        (overlayEditID = item.id),
                            (overlayEditName = item.name),
                            (overlayEdit = !overlayEdit)
                    "
                    color="warning"
                    ><v-icon small>fas fa-edit</v-icon></v-btn
                >
                <v-btn icon @click="deleteCampaign(item)" color="warning"
                    ><v-icon small>fas fa-trash</v-icon></v-btn
                >
                <v-btn @click="clickCampaign(item)" color="green">View</v-btn>
            </template>
        </v-data-table>
        <v-overlay :value="overlay">
            <StartCampaignAdd
                @closeAddNew="updatemultiCampaginAdd()"
                @closeAdd="overlay = !overlay"
            ></StartCampaignAdd>
        </v-overlay>
    </div>
</template>
<script>
import Axios from "axios";
import moment, { now, unix, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

export default {
    data() {
        return {
            loadingr: true,
            loadingf: true,
            loading: true,
            endcount: "",
            search: "",
            componentKey: 0,
            toggle_exclusive: 0,
            colorflag: 4,
            name: "Timer",
            overlay: false,
            overlayEdit: false,
            overlayEditID: "",
            overlayEditName: "",
            headers: [
                { text: "Name", value: "name", width: "10%" },
                {
                    text: "Constellations - Target",
                    value: "system",
                    width: "70%",
                    align: "center"
                },
                { text: "", value: "actions", align: "end" }
            ]
        };
    },

    created() {
        this.$store.dispatch("getConstellationList");
        this.$store.dispatch("getStartCampaigns").then(() => {
            this.loadingf = false;
            this.loadingr = false;
            this.loading = false;
        });
        this.loadStartCampaignJoinData();

        Echo.private("startcampaigns").listen("StartcampaignUpdate", e => {
            this.$store.dispatch("getStartCampaigns");
            this.loadStartCampaignJoinData();
        });
    },

    async mounted() {},
    methods: {
        updatemultiCampaginAdd() {
            this.overlay = !this.overlay;
            this.$store.dispatch("getStartCampaigns");
            this.loadStartCampaignJoinData();
        },

        clickCampaign(item) {
            this.$router.push({ path: `/scampaign/${item.id}` }); // -> /user/123
        },

        loadStartCampaignJoinData() {
            this.$store.dispatch("getStartCampaignJoinData");
        },

        async deleteCampaign(item) {
            await axios({
                method: "delete", //you can set what request you want to be
                url: "/api/startcampaigns/" + item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            sleep(500);

            this.$store.dispatch("getStartCampaigns");
        }
    },
    computed: {
        ...mapState(["startcampaigns"]),

        campaigns() {
            return this.startcampaigns;
        }
    },
    beforeDestroy() {
        Echo.leave("startcampaigns");
    }
};
</script>
