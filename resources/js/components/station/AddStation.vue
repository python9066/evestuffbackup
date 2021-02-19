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
                    <p v-if="state == 1">Enter Structure Name</p>
                    <p v-if="state == 2">
                        Enter Details for {{ stationNameEdit }}
                    </p>
                </v-card-title>
                <v-card-text>
                    <div
                        class=" d-inline-flex align-content-center justify-content-around"
                        v-if="state == 1"
                    >
                        <v-text-field
                            v-model="stationNameEdit"
                            :readonly="stationReadonly"
                            :outlined="stationOutlined"
                            autofocus
                            :label="stationLable"
                            class=" shrink"
                            style="width:600px"
                        ></v-text-field>
                        <div class=" pl-2 pt-2">
                            <v-chip
                                v-if="state == 1"
                                pill
                                :disabled="stationNameNext"
                                color="green"
                                @click="stationNameAdd()"
                            >
                                Next
                            </v-chip>
                        </div>
                    </div>
                    <v-fade-transition>
                        <div v-if="state == 2">
                            <div>
                                <v-autocomplete
                                    v-model="structSelect"
                                    :loading="structLoading"
                                    :items="structItems"
                                    :search-input.sync="structSearch"
                                    clearable
                                    autofocus
                                    label="Structure Type"
                                    outlined
                                ></v-autocomplete>
                            </div>
                            <div class=" d-inline-flex justify-content-around">
                                <v-autocomplete
                                    v-model="sysSelect"
                                    :loading="sysLoading"
                                    clearable
                                    :items="sysItems"
                                    :search-input.sync="sysSearch"
                                    label="System Name"
                                    outlined
                                ></v-autocomplete>
                                <v-autocomplete
                                    class=" ml-2"
                                    v-model="tickSelect"
                                    :loading="tickLoading"
                                    clearable
                                    :items="tickItems"
                                    :search-input.sync="tickSearch"
                                    label="Corp Ticker"
                                    outlined
                                ></v-autocomplete>
                            </div>
                            <div>
                                <v-text-field
                                    v-model="refTime"
                                    label="Ref Time d hh:mm:ss"
                                    v-mask="'#d ##:##:##'"
                                    autofocus
                                    placeholder="d:hh:mm:ss"
                                    @keyup.enter="
                                        (timerShown = false), addHacktime()
                                    "
                                    @keyup.esc="
                                        (timerShown = false), (hackTime = null)
                                    "
                                ></v-text-field>
                            </div>
                        </div>
                    </v-fade-transition>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn>
                    <v-btn class="white--text" color="green" @click="submit()">
                        Submit
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
            tickerEdit: null,
            structItems: [],
            structtemEdit: null,
            structSearch: null,
            structSelect: null,
            structLoading: false,
            structerEdit: null,
            refTime: {
                d: "",
                hh: "",
                mm: "",
                ss: ""
            }
        };
    },

    watch: {
        sysSearch(val) {
            val && val !== this.sysSelect && this.sysQuerySelections(val);
        },

        tickSearch(val) {
            val && val !== this.tickSelect && this.tickQuerySelections(val);
        },

        structSearch(val) {
            val && val !== this.structSelect && this.structQuerySelections(val);
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

        structQuerySelections(v) {
            this.structLoading = true;
            // Simulated ajax query
            setTimeout(() => {
                this.structItems = this.structureList.filter(e => {
                    return (
                        (e.text || "")
                            .toLowerCase()
                            .indexOf((v || "").toLowerCase()) > -1
                    );
                });
                this.structLoading = false;
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

        submit() {
            var d = parseInt(this.refTime.substr(0, 1));
            var h = parseInt(this.refTime.substr(3, 2));
            var m = parseInt(this.refTime.substr(6, 2));
            var s = parseInt(this.refTime.substr(9, 2));
            console.log(d);
            console.log(h);
            console.log(m);
            console.log(s);
            var ds = d * 24 * 60 * 60;
            var hs = h * 60 * 60;
            var ms = m * 60;
            var sec = ds + hs + ms + s;
            var finishtime = moment
                .utc()
                .add(sec, "seconds")
                .format("YYYY-MM-DD HH:mm:ss");

            console.log(finishtime);
        },

        async open() {
            await this.$store.dispatch("getSystemList");
            await this.$store.dispatch("getTickList");
            await this.$store.dispatch("getStructureList");
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
        ...mapState(["systemlist", "ticklist", "structurelist"]),

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

        structureList() {
            return this.structurelist;
        },

        stationLable() {
            if (this.state == 1) {
                return "Enter FULL Structure Name here";
            } else {
                return "";
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
