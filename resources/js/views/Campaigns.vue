<template>
    <div class=" pr-16 pl-16">
        <div class=" d-flex align-items-center">
            <v-card-title>Campagins</v-card-title>

            <v-btn
                :loading="loading3"
                :disabled="loading3"
                color="primary"
                class="ma-2 white--text"
                @click="
                    loading3 = true;
                    loadtimers();
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

            <v-btn-toggle v-model="toggle_exclusive" mandatory :value="1">
                <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    @click="colorflag = 4"
                >
                    All
                </v-btn>
                <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    @click="colorflag = 3"
                >
                    Goons
                </v-btn>
                <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    @click="colorflag = 2"
                >
                    Friendly
                </v-btn>
                <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    @click="colorflag = 1"
                >
                    Hostile
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
            :sort-by="['end']"
            :search="search"
            :sort-desc="[false, true]"
            multi-sort
            class="elevation-1"
        >
        <template slot="no-data">

                    No open Windows

            </template>
            <template v-slot:item.alliance="{ item }">
                <v-avatar size="35"><img :src="item.url"/></v-avatar>
                <span v-if="item.standing > 0" class=" blue--text pl-3"
                    >{{ item.alliance }}
                </span>
                <span v-else-if="item.standing < 0" class="red--text pl-3"
                    >{{ item.alliance }}
                </span>
                <span v-else class="pl-3">{{ item.alliance }}</span>
            </template>

            <template v-slot:item.count="{ item }">
                <template>
                    <vue-countdown-timer
                        @start_callback="startCallBack('event started')"
                        @end_callback="
                            (item.status = 1), handleCountdownEnd(item)
                        "
                        :start-time="item.start + ' UTC'"
                        :end-time="item.end + ' UTC'"
                        :end-text="'Window Closed'"
                        :interval="1000"
                    >
                        <template slot="countdown" slot-scope="scope">
                            <span class="red--text pl-3"
                                >{{ scope.props.hours }}:{{
                                    scope.props.minutes
                                }}:{{ scope.props.seconds }}</span
                            >
                        </template>
                    </vue-countdown-timer>
                </template>
            </template>
        </v-data-table>
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
    data() {
        return {
            //timersAll: [],

            loading: true,
            loading3: true,
            endcount: "",
            search: "",
            componentKey: 0,
            toggle_exclusive: 1,
            colorflag: 3,
            name: "Timer",

            headers: [
                { text: "Region", value: "region_name", width: "10%" },
                { text: "Constellation", value: "constellation_name" },
                { text: "System", value: "system_name" },
                { text: "Alliance", value: "alliance_name", width: "30%" },
                { text: "Ticker", value: "alliance_ticker" },
                { text: "Structure", value: "item_name" },
                { text: "Countdown", value: "count", sortable: false }


            ]
        };
    },

    created(){



    this.$store.dispatch('getCampagins')



    },


    async mounted() {

    },
    methods: {


        async loadtimers() {
            await this.$store.dispatch("getTimerDataAll");
            this.loading3 = false;
            this.loading = false;
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
        ...mapState(["campagains"]),
        filteredItems() {
            // var timers = this.$store.state.timers;
            if (this.colorflag == 1) {
                return this.campagins.filter(
                    campagins => campagins.color == 1 && campagins.status == 0
                );
            }
            if (this.colorflag == 2) {
                return this.campagins.filter(
                    campagins => campagins.color > 1 && campagins.status == 0
                );
            }
            if (this.colorflag == 3) {
                return this.campagins.filter(
                    campagins => campagins.color == 3 && campagins.status == 0
                );
            } else {
                return this.campagins.filter(campagins => campagins.status == 0);
            }
        }
    }
};
</script>
