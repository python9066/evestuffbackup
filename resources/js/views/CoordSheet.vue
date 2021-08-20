<template>
    <div class="pr-16 pl-16" v-resize="onResize">
        <v-row no-gutters justify="space-between" align="center">
            <v-col cols="4" align="start">
                <v-card tile flat color="#121212" width="500px">
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        :loading="loadingt"
                        filled
                        hide-details
                    ></v-text-field>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="4">
                <v-card
                    max-width="600px"
                    min-width="600px"
                    color="#121212"
                    elevation="0"
                >
                    <v-card-text>
                        <v-select
                            class=" pb-2"
                            v-model="regionPicked"
                            :items="dropdown_region_list"
                            label="Filter by Region"
                            multiple
                            :loading="loadingt"
                            chips
                            deletable-chips
                            hide-details
                        ></v-select>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col class=" d-inline-flex" cols="4">
                <v-card
                    max-width="600px"
                    min-width="600px"
                    color="#121212"
                    elevation="0"
                >
                    <v-card-text>
                        <v-select
                            class=" pb-2"
                            v-model="itemPicked"
                            :items="dropdown_type_list"
                            label="Filter by Type"
                            :loading="loadingt"
                            multiple
                            chips
                            deletable-chips
                            hide-details
                        ></v-select>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col class=" d-inline-flex" cols="4">
                <v-card
                    max-width="600px"
                    min-width="600px"
                    color="#121212"
                    elevation="0"
                >
                    <v-card-text>
                        <v-select
                            class=" pb-2"
                            v-model="statusPicked"
                            :items="dropdown_status_list"
                            label="Filter by Status"
                            :loading="loadingt"
                            multiple
                            chips
                            deletable-chips
                            hide-details
                        ></v-select>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col
                class=" d-inline-flex justify-content-center w-auto"
                cols="12"
            >
                <v-card width="100%">
                    <v-data-table
                        :headers="headers"
                        :items="filter_end"
                        :item-class="itemRowBackground"
                        id="table"
                        item-key="id"
                        :height="height"
                        fixed-header
                        :loading="loadingt"
                        :items-per-page="50"
                        :footer-props="{
                            'items-per-page-options': [10, 20, 30, 50, 100, -1]
                        }"
                        :search="search"
                        :sort-by="['region_name']"
                        :sort-desc="[false, true]"
                        multi-sort
                        class="elevation-5"
                    >
                        <template slot="no-data">
                            All Hostile Stations our reffed!!!!!!
                        </template>

                        <template
                            v-slot:[`item.alliance_ticker`]="{ item }"
                            class="d-inline-flex align-center"
                        >
                            <span v-if="item.url">
                                <v-avatar size="35"
                                    ><img :src="item.url"
                                /></v-avatar>
                                <span :class="standingCheck(item)"
                                    >{{ item.alliance_ticker }}
                                </span>
                            </span>
                            <span v-else-if="$can('super')">
                                <AddCorpTicker :station="item"></AddCorpTicker
                                ><AddAllianceTicker
                                    :station="item"
                                ></AddAllianceTicker>
                            </span>
                        </template>
                        <template
                            v-slot:[`item.system_name`]="{ item }"
                            class="d-inline-flex align-center"
                        >
                            <v-btn
                                :href="link(item)"
                                target="_blank"
                                icon
                                color="green"
                            >
                                <v-icon> fas fa-map fa-xs</v-icon>
                            </v-btn>
                            <button
                                v-clipboard="item.system_name"
                                v-clipboard:success="Systemcopied"
                            >
                                {{ item.system_name }}
                            </button>
                        </template>

                        <template v-slot:[`item.status_name`]="{ item }">
                            <v-chip pill small :color="pillColor(item)">
                                {{ buttontext(item) }}
                            </v-chip>
                        </template>
                        <template v-slot:[`item.actions`]="{ item }">
                            <div class=" d-inline-flex">
                                <RcStationMessage
                                    class=" mr-2"
                                    :station="item"
                                ></RcStationMessage>
                                <div>
                                    <Info
                                        :station="item"
                                        v-if="showInfo(item)"
                                    ></Info>
                                </div>
                            </div>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
            <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
                {{ snackText }}

                <template v-slot:action="{ attrs }">
                    <v-btn v-bind="attrs" text @click="snack = false">
                        Copied
                    </v-btn>
                </template>
            </v-snackbar>
        </v-row>
    </div>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
export default {
    data() {
        return {
            regionPicked: [],
            itemPicked: [],
            statusPicked: [],
            search: "",
            toggleFC: false,
            logs: false,
            snack: false,
            snackColor: "",
            snackText: "",
            loadingt: true,
            windowSize: {
                x: 0,
                y: 0
            },

            headers: [
                {
                    text: "Region",
                    value: "region_name",
                    width: "5%"
                },
                {
                    text: "Constellation",
                    value: "constellation_name",
                    width: "8%"
                },
                {
                    text: "System",
                    value: "system_name",
                    width: "8%"
                },
                {
                    text: "Alliance",
                    value: "alliance_ticker",
                    width: "10%"
                },
                {
                    text: "Type",
                    value: "item_name",
                    width: "10%"
                },
                {
                    text: "Name",
                    value: "station_name",
                    width: "15%"
                },
                {
                    text: "Status",
                    value: "station_status_name",
                    align: "center",
                    width: "10%"
                },
                {
                    text: "Gunner/Info",
                    value: "actions",
                    width: "10%",
                    align: "start"
                }
            ]
        };
    },

    async created() {
        if (this.$can("super")) {
            await this.$store.dispatch("getAllianceTickList");
            await this.$store.dispatch("getTickList");
        }

        if (this.$can("view_station_info_killsheet")) {
        }
        await this.$store.dispatch("getCoordStationRecords");
        await this.$store.dispatch("loadStationInfo");
        await this.$store.dispatch("getCoordRegions");
        await this.$store.dispatch("getCoordItems");
        await this.$store.dispatch("getCoordStatus");
        this.loadingt = false;
        Echo.private("coord")
            .listen("updateStationNotification")
            .listen("StaionUpdateCoord", e => {
                if (e.flag.message != null) {
                    this.$store.dispatch(
                        "updateStationNotification",
                        e.flag.message
                    );
                }

                if (e.flag.flag == 1) {
                    this.freshUpdate();
                }
            });
    },

    async mounted() {
        this.onResize();
    },
    methods: {
        onResize() {
            this.windowSize = { x: window.innerWidth, y: window.innerHeight };
        },
        updatetext(payload, item) {
            // console.log(item);
            if (item.text != payload) {
                item.text = payload;
                var request = {
                    text: item.text
                };
                this.$store.dispatch("updateStationNotification", item);
                axios({
                    method: "put", //you can set what request you want to be
                    url: "api/updatestationnotification/" + item.id,
                    data: request,
                    headers: {
                        Authorization: "Bearer " + this.$store.state.token,
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    }
                });
            }
        },

        Systemcopied() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "System Copied";
        },

        showNewTimer(item) {
            if (
                (item.station_status_id == 8 ||
                    item.station_status_id == 9 ||
                    item.station_status_id == 14) &&
                item.out_time == null &&
                this.$can("edit_chill_timers")
            ) {
                return true;
            } else {
                return false;
            }
        },

        showInfo(item) {
            if (
                item.item_id == 37534 ||
                item.item_id == 35841 ||
                item.item_id == 35840
            ) {
                return false;
            }
            if (item.fitted == "Fitted") {
                return true;
            } else {
                return false;
            }
        },

        countDownStartTime(item) {
            if (item.station_status_id == 11) {
                return moment.utc(item.repair_time).unix();
            } else {
                return moment.utc(item.timestamp).unix();
            }
        },

        countDownColor(item) {
            if (item.station_status_id == 11) {
                return "green--text pl-3";
            } else {
                return "blue--text pl-3";
            }
        },

        link(item) {
            if (item.region_name == "Black Rise") {
                return (
                    "https://evemaps.dotlan.net/map/Black_Rise/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "The Bleak Lands") {
                return (
                    "https://evemaps.dotlan.net/map/The_Bleak_Lands/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "The Citadel") {
                return (
                    "https://evemaps.dotlan.net/map/The_Citadel/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Cloud Ring") {
                return (
                    "https://evemaps.dotlan.net/map/Cloud_Ring/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Cobalt Edge") {
                return (
                    "https://evemaps.dotlan.net/map/Cobalt_Edge/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Etherium Reach") {
                return (
                    "https://evemaps.dotlan.net/map/Etherium_Reach/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "The Forge") {
                return (
                    "https://evemaps.dotlan.net/map/The_Forge/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "The Kalevala Expanse") {
                return (
                    "https://evemaps.dotlan.net/map/The_Kalevala_Expanse/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Molden Heath") {
                return (
                    "https://evemaps.dotlan.net/map/Molden_Heath/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Outer Passage") {
                return (
                    "https://evemaps.dotlan.net/map/Outer_Passage/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Outer Ring") {
                return (
                    "https://evemaps.dotlan.net/map/Outer_Ring/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Paragon Soul") {
                return (
                    "https://evemaps.dotlan.net/map/Paragon_Soul/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Period Basis") {
                return (
                    "https://evemaps.dotlan.net/map/Period_Basis/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Perrigen Falls") {
                return (
                    "https://evemaps.dotlan.net/map/Perrigen_Falls/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Pure Blind") {
                return (
                    "https://evemaps.dotlan.net/map/Pure_Blind/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Scalding Pass") {
                return (
                    "https://evemaps.dotlan.net/map/Scalding_Pass/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Sinq Laison") {
                return (
                    "https://evemaps.dotlan.net/map/Sinq_Laison/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "The Spire") {
                return (
                    "https://evemaps.dotlan.net/map/The_Spire/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Vale of the Silent") {
                return (
                    "https://evemaps.dotlan.net/map/Vale_of_the_Silent/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Verge Vendor") {
                return (
                    "https://evemaps.dotlan.net/map/Verge_Vendor/" +
                    item.system_name +
                    "#const"
                );
            }
            if (item.region_name == "Wicked Creek") {
                return (
                    "https://evemaps.dotlan.net/map/Wicked_Creek/" +
                    item.system_name +
                    "#const"
                );
            }
            return (
                "https://evemaps.dotlan.net/map/" +
                item.region_name +
                "/" +
                item.system_name +
                "#const"
            );
        },

        showGunner(item) {
            if (this.$can("edit_chill_timers")) {
                if (
                    item.item_id == 37534 ||
                    item.item_id == 35841 ||
                    item.item_id == 35840
                ) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        },

        loadstations() {
            this.loadingr = true;
            this.$store.dispatch("getStationData").then(() => {
                this.loadingr = false;
            });
            // console.log("30secs");
        },

        pillIcon(statusId) {
            if (statusId == 1) {
                return "faSvg fa-plus";
            }
            if (statusId == 2) {
                return "faSvg fa-route";
            }
            if (statusId == 3) {
                return "faSvg fa-fist-raised";
            }
            if (statusId == 4) {
                return "faSvg fa-thumbs-up";
            }
            if (statusId == 5 || statusId == 13) {
                return "faSvg fa-clock";
            }
            if (statusId == 6) {
                return "faSvg fa-life-ring";
            }
            if (statusId == 7) {
                return "faSvg fa-dumpster-fire";
            }
            if (statusId == 8) {
                return "faSvg fa-shield-alt";
            }
            if (statusId == 9) {
                return "faSvg fa-house-damage";
            }
            if (statusId == 11) {
                return "faSvg fa-toolbox";
            }
            if (statusId == 14) {
                return "faSvg fa-anchor";
            }
        },

        numberDay(day) {
            return parseInt(day, 10) + "d";
        },

        pillColor(statusId) {
            if (statusId == 1) {
                return "success";
            }
            if (statusId == 2) {
                return "primary";
            }
            if (statusId == 3) {
                return "#FF5EEA";
            }
            if (statusId == 4 || statusId == 11 || statusId == 13) {
                return "dark-orange";
            }
            if (statusId == 5) {
                return "indigo accent-4";
            }
            if (statusId == 6) {
                return "primary";
            }
            if (statusId == 7) {
                return "red";
            }
            if (statusId == 8 || statusId == 9) {
                return "warning";
            }
            if (statusId == 14) {
                return "deep-orange darken-2";
            }
        },

        campaignStart(item) {
            item.station_status_id = 6;
            this.$store.dispatch("updateStationNotification", item);
        },

        save() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "Data saved";
        },

        itemRowBackground: function(item) {
            if (item.under_attack == 1) {
                return "style-4";
            }
        },

        adashColor(item) {
            if (item.text != null) {
                return "green";
            } else {
                return "red";
            }
        },
        cancel() {
            this.snack = true;
            this.snackColor = "error";
            this.snackText = "Canceled";
        },
        open() {
            this.snack = true;
            this.snackColor = "info";
            this.snackText = "Dialog opened";
        },
        close() {},

        click(item, list) {
            if (item.station_status_id == 11) {
                item.repair_time = null;
            }
            item.station_status_id = list.value;
            item.station_status_name = list.title;
            item.user_name = this.user_name;

            var request = {
                station_status_id: item.station_status_id,
                user_id: this.$store.state.user_id,
                status_update: moment.utc().format("YYYY-MM-DD  HH:mm:ss"),
                out_time: null,
                repair_time: item.repair_time
            };
            axios({
                method: "put", //you can sfefeet what request you want to be
                url: "api/updatestationnotification/" + item.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        sec(item) {
            var a = moment.utc();
            var b = moment(item.timestamp);
            this.diff = a.diff(b);
            return this.diff;
        },

        standingCheck(item) {
            if (item.standing > 0) {
                return "blue--text pl-3";
            } else if (item.standing < 0) {
                return "red--text pl-3";
            } else {
                return "white--text pl-3";
            }
        },

        showCountDown(item) {
            if (
                item.station_status_id == 5 ||
                item.station_status_id == 8 ||
                item.station_status_id == 9 ||
                item.station_status_id == 11 ||
                item.station_status_id == 13 ||
                item.station_status_id == 14
            ) {
                return true;
            }

            return false;
        }
    },

    computed: {
        ...mapState(["coordsheetRegion", "coordsheetItem", "coordsheetStatus"]),
        ...mapGetters(["getShowOnCoordStations"]),

        filterSet() {
            return this.getShowOnCoordStations;
        },
        filter_start() {
            let data = [];
            if (this.statusPicked.length != 0) {
                this.statusPicked.forEach(p => {
                    let pick = this.filterSet.filter(
                        f => f.station_status_id == p
                    );
                    if (pick != null) {
                        pick.forEach(pk => {
                            data.push(pk);
                        });
                    }
                });
                return data;
            }
            return this.filterSet;
        },

        filter_mid() {
            let data = [];
            if (this.itemPicked.length != 0) {
                this.itemPicked.forEach(p => {
                    let pick = this.filter_start.filter(f => f.item_id == p);
                    if (pick != null) {
                        pick.forEach(pk => {
                            data.push(pk);
                        });
                    }
                });
                return data;
            }
            return this.filter_start;
        },

        filter_end() {
            let data = [];
            if (this.regionPicked.length != 0) {
                this.regionPicked.forEach(p => {
                    let pick = this.filter_mid.filter(f => f.region_id == p);
                    if (pick != null) {
                        pick.forEach(pk => {
                            data.push(pk);
                        });
                    }
                });
                return data;
            }
            return this.filter_mid;
        },

        dropdown_region_list() {
            return this.coordsheetRegion;
        },

        dropdown_type_list() {
            return this.coordsheetItem;
        },

        dropdown_status_list() {
            return this.coordsheetStatus.filter(l => l.text != null);
        },

        height() {
            let num = this.windowSize.y - 370;
            return num;
        },

        user_name() {
            return this.$store.state.user_name;
        },

        dropdown_region_list() {
            return this.coordsheetRegion;
        },

        dropdown_type_list() {
            return this.coordsheetItem;
        }
    },
    beforeDestroy() {
        Echo.leave("notes");
        Echo.leave("stationinfo");
    }
};
</script>

<style>
.style-4 {
    background-color: rgba(255, 153, 0, 0.199);
}
</style>
