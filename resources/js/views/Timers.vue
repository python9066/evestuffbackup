<template>
    <div class=" pr-16 pl-16">
        <messageComponent></messageComponent>
        <div class=" d-flex align-items-center">
            <v-card-title>Vulnerability Windows</v-card-title>

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

            <v-btn-toggle
                v-model="toggle_exclusive1"
                mandatory
                class=" ml-4 mr-15"
                :value="2"
            >
                <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    @click="(itemFlag = 1), (endtext = 'Time till Change')"
                >
                    All
                </v-btn>
                <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    @click="(itemFlag = 2), (endtext = 'Time till Closed')"
                >
                    Open
                </v-btn>
                <v-btn
                    :loading="loading3"
                    :disabled="loading3"
                    @click="(itemFlag = 3), (endtext = 'Time till Opened')"
                >
                    Closed
                </v-btn>
            </v-btn-toggle>

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
            :headers="getHeaders()"
            :items="filterEnd"
            item-key="id"
            :loading="loading"
            :items-per-page="25"
            :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
            :sort-by="['time']"
            :search="search"
            :sort-desc="[false, true]"
            multi-sort
            class="elevation-1"
        >
            <template slot="no-data">
                No open Windows
            </template>
            <template v-slot:item.alliance="{ item }">
                <!-- <v-img src="https://images.evetech.net/Alliance/1354830081_64.png"  style="height: inherit"></v-img> -->
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
                        @end_callback="
                            (item.status = 1), handleCountdownEnd(item)
                        "
                        :start-time="moment.utc(item.start).unix()"
                        :end-time="moment.utc(item.end).unix()"
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
            <template v-slot:item.window_station="{ item }">
                <span v-if="item.window_staiton == 'Open'" class=" green--text"
                    >{{ item.window_station }}
                </span>
                <span v-if="item.window_staiton == 'Closed'" class=" red--text"
                    >{{ item.window_station }}
                </span>
            </template>
            <template v-slot:item.age="{ item }">
                <template>
                    <VueCountUptimer
                        :start-time="moment.utc(item.age).unix()"
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
            check: "not here",
            loading: true,
            loading3: true,
            endcount: "",
            search: "",
            componentKey: 0,
            toggle_exclusive: 1,
            toggle_exclusive1: 1,
            itemFlag: 2,
            colorflag: 3,
            today: moment(),
            name: "Timer",
            test: now(),
            endtext: "Time Till Close"
        };
    },
    async mounted() {
        this.loadtimers();
    },
    methods: {
        async loadtimers() {
            await this.$store.dispatch("getTimerDataAll");
            this.loading3 = false;
            this.loading = false;
        },

        getHeaders() {
            return [
                { text: "Region", value: "region", width: "10%" },
                { text: "Constellation", value: "constellation" },
                { text: "System", value: "system" },
                { text: "Alliance", value: "alliance", width: "30%" },
                { text: "Ticker", value: "ticker" },
                { text: "Window", value: "window_station" },
                { text: "Structure", value: "type" },
                { text: "ADM", value: "adm" },
                { text: this.endtext, value: "time" },
                { text: "Countdown", value: "count", sortable: false },
                { text: "Age", value: "age" }
            ];
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
        ...mapState(["timers"]),
        filteredItems() {
            if (this.colorflag == 1) {
                return this.timers.filter(
                    timers => timers.color == 1 && timers.status == 0
                );
            }
            if (this.colorflag == 2) {
                return this.timers.filter(
                    timers => timers.color > 1 && timers.status == 0
                );
            }
            if (this.colorflag == 3) {
                return this.timers.filter(
                    timers => timers.color == 3 && timers.status == 0
                );
            } else {
                return this.timers.filter(timers => timers.status == 0);
            }
        },

        filterEnd() {
            if (this.itemFlag == 1) {
                return this.filteredItems;
            }

            if (this.itemFlag == 2) {
                return this.filteredItems.filter(
                    t => t.window_station === "Open"
                );
            }

            if (this.itemFlag == 3) {
                return this.filteredItems.filter(
                    t => t.window_station === "Closed"
                );
            }
        }
    }
};
</script>
