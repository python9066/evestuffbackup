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
                                <span class="red--text pl-3"
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
        await this.$store.dispatch("loadStationInfo");
        await this.$store.dispatch("getRcRegions");
        await this.$store.dispatch("getRcStationRecords");
        await this.$store.dispatch("getRcItems");
        await this.$store.dispatch("getRcStatus");
        this.loadingt = false;
        Echo.private("stations").listen("updateStationNotification");

        if (this.$can("view_admin_logs")) {
            await this.$store.dispatch("getLoggingRcSheet");
            Echo.private("rcsheetadminlogs").listen("RcSheetAddLogging", e => {
                console.log("ytoyoyo");
                this.$store.dispatch("addLoggingRcSheet", e.flag.message);
            });
        }
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
        ...mapState([
            "coordstations",
            "coordsheetRegion",
            "coordsheetItem",
            "coordsheetStatus"
        ]),
        ...mapGetters(["getShowOnCoordStations"]),

        filteredItems() {
            return this.getShowOnCoordStations;
        },

        user_name() {
            return this.$store.state.user_name;
        },

        dropdown_region_list() {
            return this.coordsheetRegion;
        },

        dropdown_type_list() {
            return this.coordsheetItem;
        },

        dropdown_status_list() {
            return this.coordsheetStatus.filter(l => l.text != null);
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
