<template>
    <div class="pr-16 pl-16">
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="12">
                more to come
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="12">
                <v-spacer></v-spacer>
                <v-card tile flat color="#121212" class="align-end">
                    mroe to come
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
                        :items="filteredItems"
                        item-key="id"
                        :sort-by="['end_time']"
                        :items-per-page="50"
                        :search="search"
                        :footer-props="{
                            'items-per-page-options': [10, 20, 50, 100, -1]
                        }"
                        class="elevation-5"
                    >
                        <template
                            v-slot:[`item.alliance_ticker`]="{ item }"
                            class="d-inline-flex align-center"
                        >
                            <v-avatar size="35"
                                ><img :src="item.url"
                            /></v-avatar>
                            <span class="red--text pl-3"
                                >{{ item.alliance_ticker }}
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
                                v-if="!item.fc_user_id"
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
                                v-if="item.out == 0"
                                pill
                                small
                                :color="pillColor(item)"
                            >
                                {{ item.status_name }}
                            </v-chip>

                            <v-chip
                                v-else
                                pill
                                outlined
                                @click="stationdone(item)"
                                small
                                :color="pillColor(item)"
                            >
                                {{ item.status_name }} - Done?
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
            headers: [
                { text: "System", value: "system_name" },
                { text: "Name", value: "name" },
                { text: "Type", value: "item_name" },
                { text: "Status", value: "status_name", align: "center" },
                { text: "Ticker", value: "alliance_ticker" },
                { text: "Expires", value: "end_time" },
                { text: "CountDown", value: "count" },
                { text: "FC", value: "fc_name", align: "center" },
                { text: "Cyno", value: "recon_name" },
                { text: "GSOL", value: "gsol_name" },
                { text: "", value: "actions" }
            ]
        };
    },

    async created() {},

    async mounted() {
        await this.$store.dispatch("getRcStationRecords");
    },

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
        ...mapState(["rcstations"]),
        filteredItems() {
            return this.rcstations;
        }
    },
    beforeDestroy() {}
};
</script>
<style scoped></style>
