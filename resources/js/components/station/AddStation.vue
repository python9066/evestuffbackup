<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showStationTimer"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="green"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="open()"
                    ><v-icon>
                        faSvg fa-plus
                    </v-icon>
                    Add Timer
                </v-btn>
            </template>

            <v-card
                tile
                max-width="700px"
                min-height="200px"
                max-height="1000px"
                class=" d-flex flex-column"
            >
                <v-card-title class="justify-center">
                    Enter Structure Details
                </v-card-title>
                <v-card-text>
                    <v-fade-transition mode="in-out">
                        <div v-if="state == 1">
                            <v-text-field
                                v-model="stationNameEdit"
                                outlined
                                label="Enter FULL Structure Name here"
                                prepend-icon="faSvg fa-home"
                            ></v-text-field>
                            <v-chip
                                pill
                                class="ml-10"
                                :disabled="stationNameNext"
                                color="green"
                                @click="stationNameAdd()"
                            >
                                Next
                            </v-chip>
                        </div>
                    </v-fade-transition>

                    <v-fade-transition mode="out-in">
                        <div v-if="state == 2">
                            <v-text-field
                                v-model="stationName"
                                readonly
                                prepend-icon="faSvg fa-home"
                            ></v-text-field>
                            <v-autocomplete
                                v-model="sysSelect"
                                :loading="sysLoading"
                                :items="sysItems"
                                :search-input.sync="sysSearch"
                                autofocus
                                label="System Name"
                                outlined
                                prepend-icon="faSvg fa-home"
                            ></v-autocomplete>
                            <v-text-field
                                v-model="tickerEdit"
                                outlined
                                label="Corp Ticker"
                                prepend-icon="faSvg fa-home"
                            ></v-text-field>
                            <v-chip
                                pill
                                class="ml-10"
                                :disabled="stationNameNext"
                                color="green"
                                @click="stationNameAdd()"
                            >
                                Next
                            </v-chip>
                        </div>
                    </v-fade-transition>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn></v-card-actions
                >
            </v-card>

            <!-- <showStationTimer
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showStationTimer = false"
            >
            </showStationTimer> -->
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {},
    data() {
        return {
            systems: [],
            stationNameEdit: null,
            state: 1,
            showStationTimer: false,
            stationName: null,
            sysItems: [],
            systemEdit: null,
            sysSearch: null,
            sysSelect: null,
            sysLoading: false,
            tickItems: [],
            ticktemEdit: null,
            tickSearch: null,
            tickSelect: null,
            tickLoading: false,
            tickerEdit: null
        };
    },

    watch: {
        sysSearch(val) {
            val && val !== this.sysSelect && this.sysQuerySelections(val);
        },

        tickSearch(val) {
            val && val !== this.tickSelect && this.tickQuerySelections(val);
        }
    },

    methods: {
        tickQuerySelections(v) {
            this.tickLoading = true;
            // Simulated ajax query
            setTimeout(() => {
                this.tickItems = this.ticktemList.filter(e => {
                    return (
                        (e.text || "")
                            .toLowerCase()
                            .indexOf((v || "").toLowerCase()) > -1
                    );
                });
                this.tickLoading = false;
            }, 500);
        },

        close() {
            this.showStationTimer = false;
        },

        async open() {
            await this.$store.dispatch("getSystemList");
            await this.$store.dispatch("getTickList");
        },

        async stationNameAdd() {
            var request = {
                stationName: this.stationNameEdit
            };
            await axios({
                method: "put", //you can set what request you want to be
                url: "api/stationname",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            }).then(response => {
                let res = response.data;
                this.stationName = res.station_name;
                this.state = res.state;
            });
        }
    },

    computed: {
        ...mapGetters([]),
        ...mapState(["systemlist", "ticklist"]),

        stationNameNext() {
            if (this.stationNameEdit == null) {
                return true;
            } else {
                return false;
            }
        },

        systemList() {
            return this.systemlist;
        },

        tickList() {
            return this.ticktemlist;
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
