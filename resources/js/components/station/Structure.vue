<template>
    <div class=" pt-16">
        <div class=" d-flex align-items-center">
            <v-card-title>Structures Notifications</v-card-title>
            <AddStation v-if="$can('add_timer')"></AddStation>

            <v-text-field
                class=" ml-5"
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>

            <v-btn-toggle
                right-align
                v-model="toggle_exclusive"
                mandatory
                :value="2"
            >
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 1"
                >
                    All
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="
                        (statusflag = 2),
                            (sortby = 'timestamp'),
                            (sortdesc = true)
                    "
                >
                    Active
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="
                        (statusflag = 3),
                            (sortby = 'timestamp'),
                            (sortdesc = false)
                    "
                >
                    UpComing
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 4"
                >
                    Reffed
                </v-btn>
            </v-btn-toggle>
        </div>
        <v-data-table
            :headers="_headers"
            :items="filteredItems"
            :item-class="itemRowBackground"
            item-key="id"
            :loading="loadingt"
            :items-per-page="25"
            :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
            :sort-by.sync="sortby"
            :search="search"
            :sort-desc.sync="sortdesc"
            multi-sort
            class="elevation-1"
        >
            >

            <template slot="no-data">
                No one is shooting our stuff atm, which I would say is a good
                thing?
            </template>
            <template v-slot:item.count="{ item }">
                <CountDowntimer
                    v-if="showCountDown(item)"
                    :start-time="moment.utc(item.timestamp).unix()"
                    :end-text="'Coming Out'"
                    :interval="1000"
                    :day-text="'Days'"
                    @campaignStart="campaignStart(item)"
                >
                    <template slot="countdown" slot-scope="scope">
                        <span
                            class="blue--text pl-3"
                            v-if="scope.props.days == 0"
                            >{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                        <span
                            class="blue--text pl-3"
                            v-if="scope.props.days != 0"
                            >{{ numberDay(scope.props.days) }}
                            {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                                scope.props.seconds
                            }}</span
                        >
                    </template>
                </CountDowntimer>
                <VueCountUptimer
                    v-else
                    :start-time="moment.utc(item.timestamp).unix()"
                    :end-text="'Window Closed'"
                    :interval="1000"
                >
                    <template slot="countup" slot-scope="scope">
                        <span class="red--text pl-3"
                            >{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                    </template>
                </VueCountUptimer>
            </template>

            <template
                v-slot:item.station_status_name="{ item }"
                class="align-items-center"
            >
                <div
                    v-if="$can('edit_station_notifications')"
                    class="align-items-center d-inline-flex"
                >
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                :color="pillColor(item.station_status_id)"
                            >
                                <v-icon left>
                                    {{
                                        pillIcon(item.station_status_id)
                                    }}</v-icon
                                >
                                {{ item.station_status_name }}
                            </v-btn>
                        </template>

                        <v-list>
                            <v-list-item
                                v-for="(list, index) in dropdown_edit"
                                :key="index"
                                @click="click(item, list)"
                            >
                                <v-list-item-title>{{
                                    list.title
                                }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                    <StationAttack
                        v-if="$can('edit_station_notifications')"
                        :station="item"
                    ></StationAttack>
                    <StationMessage
                        :station="item"
                        class=" pl-2"
                        v-if="$can('edit_station_notifications')"
                    >
                    </StationMessage>

                    <v-fab-transition>
                        <StationTimer
                            :key="'stationTimer' + item.id"
                            :station="item"
                        >
                        </StationTimer>
                    </v-fab-transition>
                    <v-fab-transition>
                        <StationNewTimer
                            :station="item"
                            v-if="
                                (item.station_status_id == 8 ||
                                    item.station_status_id == 9) &&
                                    item.out_time == null &&
                                    $can('super')
                            "
                        >
                        </StationNewTimer>
                    </v-fab-transition>
                </div>
                <div v-else>
                    <template>
                        <div class="align-items-center d-inline-flex">
                            <v-chip
                                class="ma-2"
                                lable
                                :color="pillColor(item.station_status_id)"
                            >
                                <v-icon left>{{
                                    pillIcon(item.station_status_id)
                                }}</v-icon>
                                {{ item.station_status_name }}
                            </v-chip>

                            <CountDowntimer
                                v-if="
                                    item.status_id == 11 &&
                                        item.end_time != null
                                "
                                :start-time="
                                    moment.utc(item.repair_time).unix()
                                "
                                :interval="1000"
                                end-text="Is it Secured?"
                            >
                                <template slot="countdown" slot-scope="scope">
                                    <span class="blue--text pl-3"
                                        >{{ scope.props.minutes }}:{{
                                            scope.props.seconds
                                        }}</span
                                    >
                                </template>
                            </CountDowntimer>
                        </div>
                    </template>
                </div>
            </template>

            <template
                v-slot:item.station_name="{ item }"
                class="d-inline-flex align-center"
            >
                {{ item.station_name }}
            </template>
            <template
                v-slot:item.alliance_ticker="{ item }"
                class="d-inline-flex align-center"
            >
                <v-avatar size="35"><img :src="item.url"/></v-avatar>
                <span v-if="item.standing > 0" class=" blue--text pl-3"
                    >{{ item.alliance_ticker }}
                </span>
                <span v-else-if="item.standing < 0" class="red--text pl-3"
                    >{{ item.alliance_ticker }}
                </span>
                <span v-else class="pl-3">{{ item.alliance_ticker }}</span>
            </template>

            <template v-slot:item.actions="{ item }" v-if="$can('gunner')">
                <div class=" d-inline-flex">
                    <StationGunner
                        class=" mr-2"
                        :station="item"
                        v-if="showGunner(item)"
                    ></StationGunner>
                    <Info :station="item" v-if="showInfo(item)"></Info>
                </div>
            </template>
        </v-data-table>
        <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
            {{ snackText }}

            <template v-slot:action="{ attrs }">
                <v-btn v-bind="attrs" text @click="snack = false">Close</v-btn>
            </template>
        </v-snackbar>
    </div>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
export default {
    data() {
        return {
            atime: null,
            check: "not here",
            componentKey: 0,
            dialog1: false,
            dialog2: false,
            dialog3: false,
            diff: 0,
            endcount: "",
            icon: "justify",
            loadingt: true,
            loadingf: true,
            loadingr: true,
            name: "Timer",
            poll: null,
            search: "",
            statusflag: 2,
            snack: false,
            snackColor: "",
            snackText: "",
            toggle_exclusive: 1,
            today: 0,
            text: "center",
            toggle_none: null,
            sortdesc: true,
            sortby: "timestamp",

            dropdown_edit: [
                { title: "On My Way", value: 2 },
                { title: "Repairing", value: 11 },
                { title: "Hostile", value: 12 },
                { title: "Saved", value: 4 },
                { title: "Reffed - Armor", value: 8 },
                { title: "Reffed - Hull", value: 9 },
                { title: "Destroyed", value: 7 },
                { title: "Anchoring", value: 14 },
                { title: "New", value: 1 }
            ]
        };
    },

    async created() {
        Echo.private("notes")
            .listen("StationNotificationNew", e => {
                if (this.$can("gunner")) {
                    this.$store.dispatch("loadStationInfo");
                }
                this.$store.dispatch("addStationNotification", e.flag.message);
            })
            .listen("StationNotificationUpdate", e => {
                this.$store.dispatch(
                    "updateStationNotification",
                    e.flag.message
                );
            })
            .listen("StationNotificationDelete", e => {
                this.$store.dispatch("deleteStationNotification", e.flag.id);
            })
            .listen("StationDataSet", e => {
                this.$store.dispatch("getStationData");
            });

        if (this.$can("gunner")) {
            Echo.private("stationinfo")
                .listen("StationInfoGet", e => {
                    this.$store.dispatch("loadStationInfo");
                })
                .listen("StationCoreUpdate", e => {
                    console.log(e);
                    this.$store.dispatch("updateCores", e.flag.message);
                });

            await this.$store.dispatch("loadStationInfo");
        }

        this.$store.dispatch("getStationData").then(() => {
            this.loadingt = false;
            this.loadingf = false;
            this.loadingr = false;
        });
    },

    async mounted() {},
    methods: {
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

        showInfo(item) {
            if (this.$can("gunner")) {
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

        showGunner(item) {
            if (this.$can("gunner")) {
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
            if (statusId == 12) {
                return "faSvg fa-exclamation-circle";
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
            if (statusId == 8 || statusId == 12 || statusId == 9) {
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
            if (list.value != 12) {
                item.out_time = null;
            }
            item.station_status_id = list.value;
            item.station_status_name = list.title;
            item.user_name = this.user_name;

            var request = {
                station_status_id: item.station_status_id,
                user_id: this.$store.state.user_id,
                status_update: moment.utc().format("YYYY-MM-DD  HH:mm:ss"),
                out_time: item.out_time
            };
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
            if (item.station_status_id == 12 && item.out_time != null) {
                return true;
            }

            return false;
        }
    },

    computed: {
        ...mapState(["stations"]),

        filteredItems() {
            if (this.statusflag == 2) {
                return this.stations.filter(
                    stations =>
                        stations.station_status_id != 5 &&
                        stations.station_status_id != 10 &&
                        stations.station_status_id != 13
                );
            }
            if (this.statusflag == 3) {
                return this.stations.filter(
                    stations =>
                        stations.station_status_id == 5 ||
                        stations.station_status_id == 13
                );
            }

            if (this.statusflag == 4) {
                return this.stations.filter(
                    stations =>
                        stations.station_status_id == 8 ||
                        stations.station_status_id == 9 ||
                        stations.station_status_id == 7
                );
            } else {
                return this.stations.filter(
                    stations => stations.station_status_id != 10
                );
            }
        },

        user_name() {
            return this.$store.state.user_name;
        },

        _headers() {
            if (this.$can("gunner")) {
                var Headers = [
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
                        text: "Timestamp",
                        value: "timestamp",
                        align: "center",
                        width: "15%"
                    },
                    {
                        text: "Age/CountDown",
                        value: "count",
                        sortable: false,
                        width: "5%"
                    },
                    {
                        text: "Status",
                        value: "station_status_name",
                        width: "10%"
                    },
                    {
                        text: "Gunner/Info",
                        value: "actions",
                        width: "10%",
                        align: "start"
                    },
                    {
                        text: "Edited By",
                        value: "user_name",
                        width: "10%",
                        align: "start"
                    }

                    // { text: "Vulernable End Time", value: "vulnerable_end_time" }
                ];
                return Headers;
            } else {
                var Headers = [
                    {
                        text: "Region",
                        value: "region_name",
                        width: "8%"
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
                        text: "Timestamp",
                        value: "timestamp",
                        align: "center",
                        width: "15%"
                    },
                    {
                        text: "Age/CountDown",
                        value: "count",
                        sortable: false,
                        width: "5%"
                    },
                    {
                        text: "Status",
                        value: "station_status_name",
                        width: "15%"
                    },
                    {
                        text: "Edited By",
                        value: "user_name",
                        width: "10%",
                        align: "start"
                    }
                ];
                return Headers;
            }
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
