<template>
    <div class=" pt-16">
        <div class=" d-flex align-items-center">
            <v-card-title>Restock Requests</v-card-title>

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
                    @click="statusflag = 2"
                >
                    Outstanding
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 3"
                >
                    In Progress
                </v-btn>
            </v-btn-toggle>
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
                No Ammo Requests At The Moment
            </template>
            <template v-slot:item.count="{ item }">
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
                <div class=" d-inline-flex"></div>
            </template>
        </v-data-table>
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
        return {};
    },

    async created() {
        Echo.private("ammorequest")
            .listen("AmmoRequestNew", e => {
                this.$store.dispatch("loadStationInfo");
                this.$store.dispatch("addAmmoRequest", e.flag.message);
            })
            .listen("AmmoRequestUpdate", e => {
                this.$store.dispatch("updateAmmoRequest", e.flag.message);
            })
            .listen("AmmoRequestDelete", e => {
                this.$store.dispatch("deleteAmmoRequest", e.flag.id);
            })
            .listen("StationDataSet", e => {
                this.$store.dispatch("getStationData");
            });

        this.$store.dispatch("loadAmmoRequestData").then(() => {
            this.loadingt = false;
            this.loadingf = false;
            this.loadingr = false;
        });
    },

    async mounted() {},
    methods: {},

    computed: {},
    beforeDestroy() {
        Echo.leave("ammorequest");
    }
};
</script>

<style>
.style-4 {
    background-color: rgba(255, 153, 0, 0.199);
}
</style>
