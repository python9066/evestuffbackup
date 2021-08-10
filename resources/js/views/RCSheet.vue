<template>
    <div class="pr-16 pl-16">
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="12">
                <v-card
                    width="500"
                    tile
                    flat
                    color="#121212"
                    class="align-start"
                >
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
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
                        dense
                        :search="search"
                        :headers="_headers"
                        :items="filter_end"
                        item-key="id"
                        :sort-by="['end_time']"
                        :sort-desc="[false, true]"
                        multi-sort
                        :items-per-page="50"
                        :footer-props="{
                            'items-per-page-options': [10, 20, 50, 100, -1]
                        }"
                        class="elevation-5"
                    >
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
                                crop - alliance
                            </span>
                        </template>
                        <template v-slot:[`item.count`]="{ item }">
                            <CountDowntimer
                                v-if="showCountDown(item)"
                                :start-time="countDownStartTime(item)"
                                :end-text="'OUT'"
                                :interval="1000"
                                :day-text="'Days'"
                                @campaignStart="campaignStart(item)"
                            >
                                <template slot="countdown" slot-scope="scope">
                                    <span v-if="scope.props.days == 0"
                                        >{{ scope.props.hours }}:{{
                                            scope.props.minutes
                                        }}:{{ scope.props.seconds }}</span
                                    >
                                    <span v-if="scope.props.days != 0"
                                        >{{ numberDay(scope.props.days) }}
                                        {{ scope.props.hours }}:{{
                                            scope.props.minutes
                                        }}:{{ scope.props.seconds }}</span
                                    >
                                </template>
                            </CountDowntimer>
                        </template>
                        <template v-slot:[`item.fc_name`]="{ item }">
                            <RcFCButton
                                class=" mr-2"
                                :station="item"
                                v-if="showFC(item)"
                            ></RcFCButton>
                            <RcFCAdd
                                v-if="
                                    !item.fc_user_id &&
                                        $can('view_killsheet_add_fc')
                                "
                                :station="item"
                                class=" pl-6"
                            ></RcFCAdd>
                        </template>
                        <template v-slot:[`item.recon_name`]="{ item }">
                            <RcReconButton
                                class=" mr-2"
                                :station="item"
                            ></RcReconButton>
                        </template>

                        <template v-slot:[`item.status_name`]="{ item }">
                            <v-chip
                                v-if="
                                    item.out == 1 && $can('edit_killsheet_done')
                                "
                                pill
                                outlined
                                @click="stationdone(item)"
                                small
                                :color="pillColor(item)"
                            >
                                {{ item.status_name }} - Done?
                            </v-chip>

                            <v-chip
                                v-else-if="item.out == 1"
                                pill
                                small
                                :color="pillColor(item)"
                            >
                                {{ item.status_name }} - out
                            </v-chip>

                            <v-chip v-else pill small :color="pillColor(item)">
                                {{ item.status_name }}
                            </v-chip>
                        </template>

                        <template v-slot:[`item.gsol_name`]="{ item }">
                            <RcGsolButton
                                class=" mr-2"
                                :station="item"
                            ></RcGsolButton>
                        </template>
                        <template slot="no-data">
                            No Active or Upcoming Campaigns
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
import Axios from "axios";
import moment, { now, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

export default {
    title() {
        return `EVE`;
    },
    data() {
        return {
            regionPicked: [],
            itemPicked: [],
            statusPicked: [],
            search: ""
        };
    },

    async created() {
        if (this.$can("super")) {
            await this.$store.dispatch("getAllianceTickList");
            await this.$store.dispatch("getTickList");
        }
        await this.$store.dispatch("getRcRegions");
        await this.$store.dispatch("getRcStationRecords");
        await this.$store.dispatch("getRcFcs");
        await this.$store.dispatch("getRcItems");
        await this.$store.dispatch("getRcStatus");
        Echo.private("rcsheet").listen("RcSheetUpdate", e => {
            if (e.flag.message != null) {
                this.$store.dispatch("updateRcStation", e.flag.message);
            }

            if (e.flag.flag == 2) {
                this.$store.dispatch("getRcStationRecords");
                this.$store.dispatch("getRcFcs");
                this.$store.dispatch("getRcRegions");
                this.$store.dispatch("getRcItems");
                this.$store.dispatch("getRcStatus");
            }

            if (e.flag.flag == 3) {
                this.$store.dispatch("getRcFcs");
            }

            if (e.flag.flag == 4) {
                this.$store.dispatch("getRcStationRecords");
            }
        });
    },

    async mounted() {},

    methods: {
        showCountDown(item) {
            if (item.status_id != 5) {
                return true;
            }

            return false;
        },

        campaignStart(item) {
            var data = {
                id: item.id,
                out: 1
            };
            this.$store.dispatch("updateRcStation", data);
        },
        countDownStartTime(item) {
            return moment.utc(item.end_time).unix();
        },
        pillColor(item) {
            if (item.status_id == 1) {
                return "deep-orange lighten-1";
            }
            if (item.status_id == 2) {
                return "lime darken-4";
            }
            if (item.status_id == 3 || item.status_id == 8) {
                return "green darken-3";
            }
            if (item.status_id == 4) {
                return "green accent-4";
            }
            if (item.status_id == 5) {
                return "red darken-4";
            }
            if (item.status_id == 6) {
                return "#FF5EEA";
            }
            if (item.status_id == 7) {
                return "#801916";
            }
            if (item.status_id == 9) {
                return "#9C9C9C";
            }
        },
        numberDay(day) {
            return parseInt(day, 10) + "d";
        },

        async stationdone(item) {
            await axios({
                method: "put",
                url: "/api/finishrcstation/" + item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        showFC(item) {
            if (item.status_id == 540) {
                return false;
            }
            return true;
        }
    },

    computed: {
        ...mapState([
            "ticklist",
            "allianceticklist",
            "rcstations",
            "rcsheetRegion",
            "rcsheetItem",
            "rcsheetStatus"
        ]),
        filteredItems() {
            return this.rcstations;
        },

        filter_start() {
            let data = [];
            if (this.statusPicked.length != 0) {
                this.statusPicked.forEach(p => {
                    let pick = this.filteredItems.filter(f => f.status_id == p);
                    if (pick != null) {
                        pick.forEach(pk => {
                            data.push(pk);
                        });
                    }
                });
                return data;
            }
            return this.filteredItems;
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
            return this.rcsheetRegion;
        },

        dropdown_type_list() {
            return this.rcsheetItem;
        },

        dropdown_status_list() {
            return this.rcsheetStatus;
        },

        _headers() {
            if (this.$can("view_gsol_killsheet")) {
                var Headers = [
                    { text: "Region", value: "region_name" },
                    { text: "Const", value: "constellation_name" },
                    { text: "System", value: "system_name" },
                    { text: "Name", value: "name" },
                    { text: "Type", value: "item_name", width: "5%" },
                    { text: "Status", value: "status_name", align: "center" },
                    { text: "Ticker", value: "alliance_ticker" },
                    { text: "Expires", value: "end_time" },
                    { text: "CountDown", value: "count", sortable: false },
                    { text: "FC", value: "fc_name", align: "center" },
                    { text: "Cyno", value: "recon_name" },
                    { text: "GSOL", value: "gsol_name" },
                    { text: "", value: "actions" }
                ];
            } else {
                var Headers = [
                    { text: "Region", value: "region_name" },
                    { text: "Const", value: "constellation_name" },
                    { text: "System", value: "system_name" },
                    { text: "Name", value: "name" },
                    { text: "Type", value: "item_name", width: "5%" },
                    { text: "Status", value: "status_name", align: "center" },
                    { text: "Ticker", value: "alliance_ticker" },
                    { text: "Expires", value: "end_time" },
                    { text: "CountDown", value: "count", sortable: false },
                    { text: "FC", value: "fc_name", align: "center" },
                    { text: "Cyno", value: "recon_name" },
                    { text: "", value: "actions" }
                ];
            }

            return Headers;
        }
    },
    beforeDestroy() {
        Echo.leave("rcsheet");
    }
};
</script>
<style scoped></style>
