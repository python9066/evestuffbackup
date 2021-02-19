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
                    <div>
                        <v-text-field
                            v-model="stationNameEdit"
                            :readonly="stationReadonly"
                            :outlined="stationOutlined"
                            autofocus
                            single-line
                            :label="stationLable()"
                            prepend-icon="faSvg fa-home"
                        ></v-text-field>
                    </div>
                    <v-fade-transition>
                        <div v-if="state == 2" class=" d-inline-flex">
                            <v-autocomplete
                                v-model="sysSelect"
                                :loading="sysLoading"
                                :items="sysItems"
                                :search-input.sync="sysSearch"
                                autofocus
                                label="System Name"
                                outlined
                                single-line
                                prepend-inner-icon="faSvg fa-home"
                            ></v-autocomplete>
                            <v-autocomplete
                                class=" ml-2"
                                v-model="tickSelect"
                                :loading="tickLoading"
                                :items="tickItems"
                                :search-input.sync="tickSearch"
                                single-line
                                label="Corp Ticker"
                                outlined
                                prepend-inner-icon="faSvg fa-home"
                            ></v-autocomplete>
                        </div>
                    </v-fade-transition>
                    <v-chip
                        v-if="state == 1"
                        pill
                        :disabled="stationNameNext"
                        color="green"
                        @click="stationNameAdd()"
                    >
                        Next
                    </v-chip>
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
                this.tickItems = this.tickList.filter(e => {
                    return (
                        (e.text || "")
                            .toLowerCase()
                            .indexOf((v || "").toLowerCase()) > -1
                    );
                });
                this.tickLoading = false;
            }, 500);
        },

        sysQuerySelections(v) {
            this.sysLoading = true;
            // Simulated ajax query
            setTimeout(() => {
                this.sysItems = this.systemList.filter(e => {
                    return (
                        (e.text || "")
                            .toLowerCase()
                            .indexOf((v || "").toLowerCase()) > -1
                    );
                });
                this.sysLoading = false;
            }, 500);
        },

        close() {
            this.showStationTimer = false;
        },

        async open() {
            await this.$store.dispatch("getSystemList");
            await this.$store.dispatch("getTickList");
        },

        stationLable() {
            if (this.state == 1) {
                return "Enter FULL Structure Name here";
            } else {
                return "";
            }
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

        stationReadonly() {
            if (this.state == 1) {
                return false;
            } else {
                return true;
            }
        },

        stationOutlined() {
            if (this.state == 1) {
                return true;
            } else {
                return false;
            }
        },

        tickList() {
            return this.ticklist;
        }
    },

    beforeDestroy() {}
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
}
</style>
