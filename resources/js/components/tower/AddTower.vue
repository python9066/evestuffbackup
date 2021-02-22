<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showAddTower"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="green"
                    dark44444444
                    v-bind="attrs"
                    v-on="on"
                    @click="open()"
                    ><v-icon left>
                        faSvg fa-plus
                    </v-icon>
                    Add Tower
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
                    Adding a New Tower
                </v-card-title>
                <v-card-text>
                    <div>
                        <div class=" d-inline-flex justify-content-around">
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
                                @input="getMoonList()"
                                :loading="sysLoading"
                                clearable
                                :items="sysItems"
                                :search-input.sync="sysSearch"
                                label="System Name"
                                outlined
                            ></v-autocomplete>
                            <v-autocomplete
                                v-model="moonSelect"
                                :loading="moonLoading"
                                :disabled="moonDisable"
                                clearable
                                :items="moonItems"
                                :search-input.sync="moonSearch"
                                label="Moon"
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
                    </div>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn>
                    <v-btn
                        class="white--text"
                        color="green"
                        :disabled="showSubmit"
                        @click="submit()"
                        v-if="state == 2"
                    >
                        Submit </v-btn
                    ><v-btn
                        class="white--text"
                        color="green"
                        :disabled="showSubmit3"
                        @click="submit3()"
                        v-if="state == 3"
                    >
                        Submit
                    </v-btn></v-card-actions
                >
            </v-card>

            <!-- <showAddTower
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showAddTower = false"
            >
            </showAddTower> -->
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        station: Object
    },
    data() {
        return {
            systems: [],
            towerNameEdit: null,
            state: 1,
            showAddTower: false,
            stationName: null,
            sysItems: [],
            systemEdit: null,
            sysSearch: null,
            sysSelect: null,
            sysLoading: false,
            moonItems: [],
            moonEdit: null,
            moonSearch: null,
            moonSelect: null,
            moonLoading: false,
            tickItems: [],
            ticktemEdit: null,
            tickSearch: null,
            tickSelect: null,
            tickLoading: false,
            tickerEdit: null,
            stationPull: [],
            structItems: [],
            structtemEdit: null,
            structSearch: null,
            structSelect: null,
            structLoading: false,
            structerEdit: null,
            refType: null,
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
        },

        moonSearch(val) {
            val && val !== this.moonSelect && this.moonQuerySelections(val);
        }
    },

    methods: {
        close() {
            this.towerNameEdit = null;
            this.showAddTower = false;
            this.refType = null;
            this.refTime = null;
            this.stationName = null;
            this.towerNameEdit = null;
            this.structItems = [];
            this.structSearch = null;
            this.structSelect = null;
            this.sysItems = [];
            this.sysSearch = null;
            this.sysSelect = null;
            this.systems = [];
            this.tickItems = [];
            this.tickSearch = null;
            this.tickSelect = null;
            this.state = 1;
            this.showAddTower = false;
        },

        async submit() {
            var d = parseInt(this.refTime.substr(0, 1));
            var h = parseInt(this.refTime.substr(3, 2));
            var m = parseInt(this.refTime.substr(6, 2));
            var s = parseInt(this.refTime.substr(9, 2));
            var ds = d * 24 * 60 * 60;
            var hs = h * 60 * 60;
            var ms = m * 60;
            var sec = ds + hs + ms + s;
            var outTime = moment
                .utc()
                .add(sec, "seconds")
                .format("YYYY-MM-DD HH:mm:ss");

            var request = {
                station_status_id: this.refType,
                out_time: outTime,
                status_update: moment.utc().format("YYYY-MM-DD HH:mm:ss")
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "api/updatestationnotification/" + station.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            }).then(
                (this.towerNameEdit = null),
                (this.showAddTower = false),
                (this.refType = null),
                (this.refTime = null),
                (this.stationName = null),
                (this.towerNameEdit = null),
                (this.structItems = []),
                (this.structSearch = null),
                (this.structSelect = null),
                (this.sysItems = []),
                (this.sysSearch = null),
                (this.sysSelect = null),
                (this.systems = []),
                (this.tickItems = []),
                (this.tickSearch = null),
                (this.tickSelect = null),
                (this.state = 1),
                (this.showAddTower = false)
            );
        },

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

        async getMoonList() {
            await this.$store.dispatch("getMoonList", this.sysSelect);
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

        moonQuerySelections(v) {
            this.moonLoading = true;
            // Simulated ajax query
            setTimeout(() => {
                this.moonItems = this.moonList.filter(e => {
                    return (
                        (e.text || "")
                            .toLowerCase()
                            .indexOf((v || "").toLowerCase()) > -1
                    );
                });
                this.moonLoading = false;
            }, 500);
        },

        async open() {
            await this.$store.dispatch("getSystemList");
            await this.$store.dispatch("getTickList");
            await this.$store.dispatch("getStructureList");
        }
    },

    computed: {
        ...mapGetters([]),
        ...mapState(["systemlist", "structurelist", "ticklist", "moonlist"]),
        showSubmit() {
            if (
                this.structSelect != null &&
                this.sysSelect != null &&
                this.tickSelect != null &&
                this.refType != null &&
                this.refTime != null
            ) {
                return false;
            } else {
                return true;
            }
        },

        systemList() {
            return this.systemlist;
        },

        structureList() {
            return this.structurelist;
        },

        tickList() {
            return this.ticklist;
        },

        moonDisable() {
            if (this.sysSelect > 0) {
                return false;
            } else {
                return true;
            }
        },

        moonList() {
            return this.moonlist;
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
