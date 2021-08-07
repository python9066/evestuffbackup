<template>
    <div class=" pr-16 pl-16">
        <messageComponent></messageComponent>
        <div class=" d-flex align-items-center">
            <v-card-title>Campaigns</v-card-title>

            <v-btn
                :loading="loadingr"
                :disabled="loadingr"
                color="primary"
                class="ma-2 white--text"
                @click="
                    loadingr = true;
                    loadcampaigns();
                "
            >
                Update
                <v-icon right dark>fas fa-sync-alt fa-xs</v-icon>
            </v-btn>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>

            <v-btn-toggle
                v-model="toggle_exclusive1"
                mandatory
                class=" ml-4 mr-15"
                :value="2"
            >
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="itemFlag = 1"
                >
                    All
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="itemFlag = 2"
                >
                    IHUBs
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="itemFlag = 3"
                >
                    TCUs
                </v-btn>
            </v-btn-toggle>

            <v-btn-toggle v-model="toggle_exclusive" mandatory :value="1">
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="colorflag = 4"
                >
                    All
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="colorflag = 3"
                >
                    Goons
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="colorflag = 2"
                >
                    Friendly
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="colorflag = 1"
                >
                    Hostile
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="colorflag = 5"
                >
                    Active
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="colorflag = 6"
                >
                    Finished
                </v-btn>
            </v-btn-toggle>
        </div>
        <v-data-table
            :headers="headers"
            :items="filteredItems"
            item-key="id"
            :loading="loading"
            :items-per-page="25"
            :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
            :sort-by="['start']"
            :search="search"
            :sort-desc="[false, true]"
            multi-sort
            @click:row="rowClick($event)"
            class="elevation-1"
        >
            <template slot="no-data">
                No Active or Upcoming Campaigns
            </template>
            <template v-slot:[`item.alliance`]="{ item }">
                <v-avatar size="35"><img :src="item.url"/></v-avatar>
                <span v-if="item.standing > 0" class=" blue--text pl-3"
                    >{{ item.alliance }}
                </span>
                <span v-else-if="item.standing < 0" class="red--text pl-3"
                    >{{ item.alliance }}
                </span>
                <span v-else class="pl-3">{{ item.alliance }}</span>
            </template>

            <template v-slot:[`item.start`]="{ item }">
                <span v-if="item.status_id == 1"> {{ item.start }} </span>
                <span
                    v-else-if="item.status_id != 3 && item.status_id != 4"
                    class="d-flex full-width align-content-center"
                >
                    <span>
                        <v-icon
                            v-if="
                                item.defenders_score >
                                    item.defenders_score_old &&
                                    item.defenders_score_old > 0
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
                                item.defenders_score <
                                    item.defenders_score_old &&
                                    item.defenders_score_old > 0
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
                                item.defenders_score ==
                                    item.defenders_score_old ||
                                    item.defenders_score_old === null
                            "
                            small
                            left
                            dark
                            color="grey darken-3"
                        >
                            fas fa-minus-circle
                        </v-icon>
                    </span>

                    <v-progress-linear
                        :color="barColor(item)"
                        :value="barScoure(item)"
                        height="20"
                        rounded
                        :active="barActive(item)"
                        :reverse="barReverse(item)"
                        :background-color="barBgcolor(item)"
                        background-opacity="0.2"
                    >
                        <strong>
                            {{ item.defenders_score * 100 }} /
                            {{ item.attackers_score * 100 }}
                        </strong>
                    </v-progress-linear>
                    <span>
                        <v-icon
                            v-if="
                                item.attackers_score >
                                    item.attackers_score_old &&
                                    item.attackers_score_old > 0
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
                                item.attackers_score <
                                    item.attackers_score_old &&
                                    item.attackers_score_old > 0
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
                                item.attackers_score ==
                                    item.attackers_score_old ||
                                    item.attackers_score_old == null
                            "
                            small
                            right
                            dark
                            color="grey darken-3"
                        >
                            fas fa-minus-circle
                        </v-icon>
                    </span>
                </span>
                <span v-else-if="item.status_id == 3 || item.status_id == 4">
                    <p
                        v-if="item.attackers_score == 0"
                        class=" text-md-center green--text"
                    >
                        {{ item.alliance }}
                        <span class="font-weight-bold"> WON </span> the
                        {{ item.item_name }} timer.
                    </p>
                    <p v-else class=" text-md-center red--text">
                        {{ item.alliance }}
                        <span class="font-weight-bold"> LOST </span> the
                        {{ item.item_name }} timer.
                    </p>
                </span>
            </template>

            <template v-slot:[`item.count`]="{ item }">
                <div class=" d-inline-flex align-center">
                    <CountDowntimer
                        v-if="item.status_id == 1"
                        :start-time="moment.utc(item.start).unix()"
                        :end-text="'Window Closed'"
                        :interval="1000"
                        @campaignStart="campaignStart(item)"
                    >
                        <template slot="countdown" slot-scope="scope">
                            <span
                                v-if="
                                    scope.props.hours == 0 &&
                                        scope.props.days == 0 &&
                                        $can('access_campaigns')
                                "
                                class="red--text pl-3"
                            >
                                <v-chip
                                    class="ma-2 ma"
                                    filter
                                    pill
                                    :disabled="pillDisabled(item)"
                                    color="light-blue lighten-1"
                                >
                                    {{ scope.props.minutes }}:{{
                                        scope.props.seconds
                                    }}
                                </v-chip>
                            </span>
                            <span v-else class="red--text pl-3"
                                >{{ scope.props.days }}:{{
                                    scope.props.hours
                                }}:{{ scope.props.minutes }}:{{
                                    scope.props.seconds
                                }}</span
                            >
                        </template>
                    </CountDowntimer>
                    <div v-if="item.status_id > 1 && $can('access_campaigns')">
                        <v-chip
                            class="ma-2 ma"
                            filter
                            pill
                            :disabled="pillDisabled(item)"
                            :color="pillColor(item)"
                        >
                            {{ item.status_name }}
                        </v-chip>
                    </div>
                    <div v-else-if="item.status_id > 1">
                        <span class=" pl-5">{{ item.status_name }}</span>
                    </div>

                    <div
                        class="d-flex full-width align-content-center"
                        v-if="item.status_id == 2"
                    >
                        <VueCountUptimer
                            :start-time="moment.utc(item.start).unix()"
                            :end-text="'Campaign Started'"
                            :interval="1000"
                        >
                            <template slot="countup" slot-scope="scope">
                                <span class="green--text pl-3"
                                    >{{ scope.props.hours }}:{{
                                        scope.props.minutes
                                    }}:{{ scope.props.seconds }}</span
                                >
                            </template>
                        </VueCountUptimer>
                    </div>
                    <CampaignMap
                        :system_name="item.system"
                        :region_name="item.region"
                    >
                    </CampaignMap>
                    <VueCountUptimer
                        v-if="item.Age != null"
                        :start-time="moment.utc(item.Age).unix()"
                        :end-text="'Window Closed'"
                        :interval="1000"
                        :leadingZero="false"
                    >
                        <template slot="countup" slot-scope="scope">
                            <span class="green--text pl-3"
                                ><span v-if="scope.props.days != 0">
                                    {{ scope.props.days }} Days - </span
                                >{{ scope.props.hours }} Hours</span
                            >
                        </template>
                    </VueCountUptimer>
                </div>
            </template>
        </v-data-table>
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
    title() {
        return `EVE`;
    },
    data() {
        return {
            //timersAll: [869349],

            loadingr: true,
            loadingf: true,
            loading: true,
            endcount: "",
            search: "",
            componentKey: 0,
            toggle_exclusive: 0,
            toggle_exclusive1: 1,
            colorflag: 4,
            itemFlag: 2,
            name: "Timer",

            headers: [
                { text: "Region", value: "region", width: "10%" },
                { text: "Constellation", value: "constellation" },
                { text: "System", value: "system" },
                { text: "Alliance", value: "alliance" },
                { text: "Ticker", value: "ticker", align: "start" },
                { text: "ADM", value: "adm" },
                { text: "Structure", value: "item_name" },
                {
                    text: "Start/Progress",
                    value: "start",
                    width: "30%",
                    align: "center"
                },
                { text: "Countdown/Age", value: "count", sortable: false }
            ]
        };
    },

    created() {
        Echo.private("campaigns").listen("CampaignChanged", e => {
            this.loadcampaigns();
        }),
            this.$store.dispatch("getCampaigns").then(() => {
                this.loadingf = false;
                this.loadingr = false;
                this.loading = false;
            });
    },

    async mounted() {},
    methods: {
        async loadcampaigns() {
            this.loadingr = true;
            this.$store.dispatch("getCampaigns").then(() => {
                this.loadingr = false;
            });
        },

        campaignStart(item) {
            item.status_name = "Active";
            item.status_id = 2;
            var request = {
                status_id: 2
            };

            this.$store.dispatch("updateCampaign", item);

            axios({
                method: "put", //you can set what request you want to be
                url: "api/campaigns/" + item.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        fixTime(item) {
            return moment.utc(item.start).unix(); // retu  rn utc.unix()
        },

        rowClick(item) {
            if (this.$can("access_campaigns")) {
                var left = moment.utc(item.start).unix() - moment.utc().unix();
                if (left < 3600 && item.status_id < 3) {
                    this.$router.push({ path: `/campaign/${item.link}` }); // -> /user/123
                }
            }
        },

        barScoure(item) {
            var d = item.defenders_score * 100;
            var a = item.attackers_score * 100;

            if (d > 50) {
                return d;
            }

            return a;
        },

        barBgcolor(item) {
            var d = item.defenders_score * 100;
            var a = item.attackers_score * 100;

            if (d > 50) {
                return "red darken-4";
            }

            return "blue darken-4";
        },

        barColor(item) {
            var d = item.defenders_score * 100;
            if (d > 50) {
                return "blue darken-4";
            }

            return "red darken-4";
        },

        barReverse(item) {
            var d = item.defenders_score * 100;
            if (d > 50) {
                return false;
            }

            return true;
        },

        barActive(item) {
            if (item.status_id > 1) {
                return true;
            }
            return false;
        },

        pillDisabled(item) {
            if (item.status_id == 3) {
                return true;
            }
            return false;
        },

        pillColor(item) {
            if (item.status_id == 3) {
                return "blue-grey darken-4";
            }

            return "green darken-3";
        },

        transform(props) {
            Object.entries(props).forEach(([key, value]) => {
                // Adds leading zero
                const digits = value < 10 ? `0${value}` : value;
                props[key] = `${digits}`;
            });

            return props;
        },
        handleCountdownEnd(item) {
            this.$store.dispatch("markOver", item);
        }
    },
    computed: {
        ...mapState(["campaigns"]),

        filteredItems_start() {
            // var timers = this.$store.state.timers;
            if (this.colorflag == 1) {
                return this.campaigns.filter(
                    campaigns =>
                        campaigns.color == 1 &&
                        campaigns.status_id != 3 &&
                        campaigns.status_id != 4
                );
            }
            if (this.colorflag == 2) {
                return this.campaigns.filter(
                    campaigns =>
                        campaigns.color > 1 &&
                        campaigns.status_id != 3 &&
                        campaigns.status_id != 4
                );
            }
            if (this.colorflag == 3) {
                return this.campaigns.filter(
                    campaigns =>
                        campaigns.color == 3 &&
                        campaigns.status_id != 3 &&
                        campaigns.status_id != 4
                );
            }
            if (this.colorflag == 6) {
                return this.campaigns.filter(
                    campaigns =>
                        campaigns.status_id == 3 || campaigns.status_id == 4
                );
            }
            if (this.colorflag == 5) {
                return this.campaigns.filter(
                    campaigns => campaigns.status_id == 2
                );
            } else {
                return this.campaigns.filter(
                    campaigns =>
                        campaigns.status_id == 1 || campaigns.status_id == 2
                );
            }
        },

        filteredItems() {
            if (this.itemFlag == 1) {
                return this.filteredItems_start;
            } else if (this.itemFlag == 2) {
                return this.filteredItems_start.filter(
                    c => c.item_name == "Ihub"
                );
            } else {
                return this.filteredItems_start.filter(
                    c => c.item_name == "TCU"
                );
            }
        }
    },
    beforeDestroy() {
        Echo.leave("campaigns");
    }
};
</script>
