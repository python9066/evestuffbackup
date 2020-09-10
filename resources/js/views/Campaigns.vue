<template>
    <div class=" pr-16 pl-16">
        <div class=" d-flex align-items-center">
            <v-card-title>Campaigns</v-card-title>

            <v-btn
                :loading="loadingr"
                :disabled="loadingr"
                color="primary"
                class="ma-2 white--text"
                @click="
                    loadingr = true;
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
            class="elevation-1"
        >
        <template slot="no-data">

                    No Active or Upcoming Campaigns

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

            <template v-slot:item.start="{ item }">

                <span v-if="item.status_id == 1"> {{item.start}} </span>
                <span v-else class="d-flex full-width align-content-center" >

                       <v-progress-linear
                            v-if="item.defenders_score > 0.49"
                            :value="(item.defenders_score * 100)"
                            height = 15
                            :rounded = true
                            >
                            <strong> {{item.defenders_score * 100}} / {{item.attackers_score * 100}} </strong>
                        </v-progress-linear>

                        <v-progress-linear
                            v-if="item.attackers_score > 0.49"
                            :value="(item.attackers_score * 100)"
                            height = 15
                            :rounded = true
                            color = "red darken-4"
                            reverse = true

                            >
                            <strong> {{item.defenders_score * 100}} / {{item.attackers_score * 100}} </strong>
                        </v-progress-linear>

                </span>

            </template>

            <template v-slot:item.count="{ item }">
                <CountDowntimer
                    :start-time="item.start + ' UTC'"
                    :end-text="'Window Closed'"
                    :interval="1000"
                >
                    <template slot="countdown" slot-scope="scope">
                        <span class="red--text pl-3"
                            >{{ scope.props.days }}:{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                    </template>
                </CountDowntimer>
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

            loadingr: true,
            loadingf: true,
            loading: true,
            endcount: "",
            search: "",
            componentKey: 0,
            toggle_exclusive: 1,
            colorflag: 3,
            name: "Timer",

            headers: [
                { text: "Region", value: "region", width: "10%" },
                { text: "Constellation", value: "constellation" },
                { text: "System", value: "system" },
                { text: "Alliance", value: "alliance" },
                { text: "Ticker", value: "ticker", align: "start" },
                { text: "Structure", value: "item_name" },
                { text: "Start/Progress", value: "start", width: "30%", align: "center"},
                { text: "Countdown", value: "count", sortable: false }


            ]
        };
    },

    created(){



    this.$store.dispatch('getCampaigns').then(() => {
            this.loadingf = false;
            this.loadingr = false;
            this.loading = false;
        });



    },


    async mounted() {

    },
    methods: {


        async loadtimers() {
            await this.$store.dispatch("getCampaigns");
            this.loadingr = false;
            this.loadingf = false;
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
        filteredItems() {
            // var timers = this.$store.state.timers;
            if (this.colorflag == 1) {
                return this.campaigns.filter(
                    campaigns => campaigns.color == 1
                );
            }
            if (this.colorflag == 2) {
                return this.campaigns.filter(
                    campaigns => campaigns.color > 1
                );
            }
            if (this.colorflag == 3) {
                return this.campaigns.filter(
                    campaigns => campaigns.color == 3
                );
            }
            if (this.colorflag == 5) {
                return this.campaigns.filter(
                    campaigns => campaigns.status_id == 2
                );
            } else {
                return this.campaigns;
            }
        }
    }
};
</script>
