<template>
    <div class=" pr-16 pl-16">
        <div class=" d-flex align-items-center">
            <v-card-title>Timers to input to RC</v-card-title>
            <!-- <ChillAddStation v-if="$can('edit_chill_timers')"></ChillAddStation> -->
            <AddStation :type="3"></AddStation>

            <v-text-field
                class=" ml-5"
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </div>
        <v-data-table
            :headers="headers"
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
                No timers to move over to RC
            </template>
            <template v-slot:[`item.count`]="{ item }">
                <CountDowntimer
                    v-if="showCountDown(item)"
                    :start-time="countDownStartTime(item)"
                    :end-text="'Coming Out'"
                    :interval="1000"
                    :day-text="'Days'"
                    @campaignStart="campaignStart(item)"
                >
                    <template slot="countdown" slot-scope="scope">
                        <span
                            :class="countDownColor(item)"
                            v-if="scope.props.days == 0"
                            >{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                        <span
                            :class="countDownColor(item)"
                            v-if="scope.props.days != 0"
                            >{{ numberDay(scope.props.days) }}
                            {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                                scope.props.seconds
                            }}</span
                        >
                    </template>
                </CountDowntimer>
            </template>

            <template
                v-slot:[`item.station_status_name`]="{ item }"
                class="align-items-center"
            >
                <div>
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
                                <button
                                    v-clipboard="fixStatusText(item)"
                                    v-clipboard:success="Statuscopied"
                                >
                                    {{ fixStatusText(item) }}
                                </button>
                            </v-chip>
                        </div>
                    </template>
                </div>
            </template>

            <template
                v-slot:[`item.station_name`]="{ item }"
                class="d-inline-flex align-center"
            >
                <button
                    v-clipboard="item.station_name"
                    v-clipboard:success="Namecopied"
                >
                    {{ item.station_name }}
                </button>
            </template>
            <template
                v-slot:[`item.corp_ticker`]="{ item }"
                class="d-inline-flex align-center"
            >
                <button
                    v-clipboard="item.corp_ticker"
                    v-clipboard:success="Corpcopied"
                >
                    {{ item.corp_ticker }}
                </button>
            </template>
            <template
                v-slot:[`item.system_name`]="{ item }"
                class="d-inline-flex align-center"
            >
                <button
                    v-clipboard="item.system_name"
                    v-clipboard:success="Systemcopied"
                >
                    {{ item.system_name }}
                </button>
            </template>
            <template
                v-slot:[`item.item_name`]="{ item }"
                class="d-inline-flex align-center"
            >
                <button
                    v-clipboard="item.item_name"
                    v-clipboard:success="Stationcopied"
                >
                    {{ item.item_name }}
                </button>
            </template>
            <template
                v-slot:[`item.alliance_ticker`]="{ item }"
                class="d-inline-flex align-center"
            >
                <span v-if="item.standing > 0" class=" blue--text pl-3"
                    ><button
                        v-clipboard="item.alliance_ticker"
                        v-clipboard:success="Alliancecopied"
                    >
                        {{ item.alliance_ticker }}
                    </button>
                </span>
                <span v-else-if="item.standing < 0" class="red--text pl-3"
                    ><button
                        v-clipboard="item.alliance_ticker"
                        v-clipboard:success="Alliancecopied"
                    >
                        {{ item.alliance_ticker }}
                    </button>
                </span>
                <span v-else class="pl-3"
                    ><button
                        v-clipboard="item.alliance_ticker"
                        v-clipboard:success="Alliancecopied"
                    >
                        {{ item.alliance_ticker }}
                    </button></span
                >
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class=" d-inline-flex">
                    <RcStationMessage
                        class=" mr-2 pt-2"
                        :station="item"
                    ></RcStationMessage>
                    <v-btn
                        v-if="show()"
                        :href="item.timer_image_link"
                        target="_blank"
                        icon
                        color="green"
                    >
                        <v-icon> far fa-images</v-icon>
                    </v-btn>
                </div>
            </template>

            <template
                v-slot:[`item.timestamp`]="{ item }"
                class="d-inline-flex align-center"
            >
                <RcMoveCopyButton
                    :item="item"
                    type="outtime"
                ></RcMoveCopyButton>
            </template>

            <template v-slot:[`item.actions2`]="{ item }">
                <div class="d-inline-flex align-center">
                    <EditStation :item="item"> </EditStation>
                    <v-btn
                        v-if="show()"
                        @click="removeStationGood(item)"
                        icon
                        color="green"
                    >
                        <v-icon> fas fa-check-circle</v-icon>
                    </v-btn>

                    <v-btn @click="removeStationBad(item)" icon color="red">
                        <v-icon> fas fa-times-circle</v-icon>
                    </v-btn>
                </div>
            </template>
        </v-data-table>

        <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
            {{ snackText }}

            <template v-slot:action="{ attrs }">
                <v-btn v-bind="attrs" text @click="snack = false">
                    Copied
                </v-btn>
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
                    text: "Corp",
                    value: "corp_ticker",
                    width: "5%"
                },

                {
                    text: "Alliance",
                    value: "alliance_ticker",
                    width: "5%"
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
                    width: "5%"
                },
                {
                    text: "Status",
                    value: "station_status_name",
                    align: "center",
                    width: "10%"
                },
                {
                    text: "",
                    value: "actions",
                    align: "start"
                },

                {
                    text: "",
                    value: "actions2",
                    align: "end"
                }
            ]
        };
    },

    async created() {
        if (this.$can("finish_move_timer")) {
            Echo.private("rcmovesheet")
                .listen("RcMoveUpdate", e => {
                    if (e.flag.message != null) {
                        this.$store.dispatch(
                            "updateStationNotification",
                            e.flag.message
                        );
                    }
                })
                .listen("RcMoveDelete", e => {
                    this.$store.dispatch(
                        "deleteStationNotification",
                        e.flag.id
                    );
                });
            this.$store.dispatch("getStationData").then(() => {
                this.loadingt = false;
                this.loadingf = false;
                this.loadingr = false;
            });
        } else {
            Echo.private("rcmovesheet")
                .listen("RcMoveUpdate", e => {
                    if (e.flag.message != null) {
                        console.log("got ping");
                        if (e.flag.message.added_by_user_id == this.user_id) {
                            console.log("belongs to me");
                            this.$store.dispatch(
                                "updateStationNotification",
                                e.flag.message
                            );
                        }
                    }
                })
                .listen("RcMoveDelete", e => {
                    this.$store.dispatch(
                        "deleteStationNotification",
                        e.flag.id
                    );
                });
            this.$store.dispatch("getStationDataByUserId").then(() => {
                this.loadingt = false;
                this.loadingf = false;
                this.loadingr = false;
            });
        }
    },

    async mounted() {},
    methods: {
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

        removeStationGood(item) {
            axios({
                method: "put", //you can set what request you want to be
                url: "api/rcmovedone/" + item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        removeStationBad(item) {
            axios({
                method: "delete", //you can set what request you want to be
                url: "api/rcmovedonebad/" + item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        campaignStart(item) {
            item.station_status_id = 6;
            this.$store.dispatch("updateStationNotification", item);
        },

        Statuscopied() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "Status Copied";
        },

        Systemcopied() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "System Copied";
        },

        Corpcopied() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "Corp Ticker Copied";
        },

        Alliancecopied() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "Alliance Ticker Copied";
        },

        Stationcopied() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "Station Type Copied";
        },

        Namecopied() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "Station Name Copied";
        },

        Timecopied() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "Absolute Time Copied";
        },

        itemRowBackground: function(item) {
            if (item.under_attack == 1) {
                return "style-4";
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

        show() {
            if (this.$can("finish_move_timer")) {
                return true;
            } else {
                return false;
            }
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
        },

        fixStatusText(item) {
            var ret = item.station_status_name.replace("Upcoming - ", "");
            return ret;
        },

        fixTimeStamp(item) {
            var str = item.out_time.replace(/\s+/g, "");
            str = str.replace(/[:]/g, "");
            str = str.replace(/[-]/g, "");
            str = str.substring(2);
            return str;
        }
    },
    computed: {
        ...mapState(["stations"]),

        filteredItems() {
            return this.stations.filter(s => s.show_on_rc_move == 1);
        },
        user_name() {
            return this.$store.state.user_name;
        },

        user_id() {
            return this.$store.state.user_id;
        }
    },
    beforeDestroy() {
        Echo.leave("rcmovesheet");
    }
};
</script>

<style>
.style-4 {
    background-color: rgba(255, 153, 0, 0.199);
}
</style>
