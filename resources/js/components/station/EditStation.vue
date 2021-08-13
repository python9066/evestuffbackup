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
                    color="pink darken-2"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="open()"
                    ><v-icon>
                        faSvg fa-edit
                    </v-icon>
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
                    <p>Edit Details for {{ item.station_name }}</p>
                    <p>ONLY ENTER WHAT NEEDS CHANGED</p>
                    <p></p
                ></v-card-title>

                <v-card-text>
                    <v-fade-transition>
                        <div>
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
                                <h5><strong>Timer Type</strong></h5>
                                <v-radio-group v-model="refType" row>
                                    <v-radio
                                        label="Anchoring"
                                        value="14"
                                    ></v-radio>
                                    <v-radio label="Armor" value="5"></v-radio>
                                    <v-radio label="Hull" value="13"></v-radio>
                                </v-radio-group>
                            </div>
                            <div v-if="this.type == 3">
                                <h5><strong>Image Link 2</strong></h5>
                                <v-img src="../image/info.png"> </v-img>
                                <v-text-field
                                    v-model="imageLink"
                                    label="Selected Items Screen Shot"
                                ></v-text-field>
                            </div>
                            <div>
                                <h5><strong>Station Timer</strong></h5>
                                <v-text-field
                                    v-model="refTime"
                                    label="Reinforced unit YYYY.MM.DD hh:mm:ss"
                                    v-mask="'####.##.## ##:##:##'"
                                    placeholder="YYYY.MM.DD HH:mm:ss"
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
                    <v-btn
                        class="white--text"
                        color="green"
                        :disabled="showSubmit"
                        @click="submit()"
                    >
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
    props: {
        item: Object
    },

    async created() {
        this.setShow();
    },
    data() {
        return {
            imageLink: null,
            systems: [],
            showStationTimer: false,
            sysItems: [],
            sysSearch: null,
            sysSelect: null,
            sysLoading: false,
            tickItems: [],
            tickSearch: null,
            tickSelect: null,
            tickLoading: false,
            structItems: [],
            structSearch: null,
            structSelect: null,
            structLoading: false,
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
        }
    },

    methods: {
        setShow() {
            if (this.type == 1) {
                this.show_on_main = 1;
            }
            if (this.type == 2) {
                this.show_on_chill = 1;
            }
            if (this.type == 3) {
                this.show_on_rc_move = 1;
            }
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
            this.refType = null;
            this.refTime = null;
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
            this.showStationTimer = false;
        },

        async submit() {
            var y = this.refTime.substr(0, 4);
            var mo = this.refTime.substr(5, 2);
            var d = this.refTime.substr(8, 2);
            var h = this.refTime.substr(11, 2);
            var m = this.refTime.substr(14, 2);
            var s = this.refTime.substr(17, 2);
            var full = y + "-" + mo + "-" + d + " " + h + ":" + m + ":" + s;

            var outTimecheck = moment(full).format("YYYY-MM-DD HH:mm:ss");
            if (outTimecheck != null && outTimecheck != item.out_time) {
                var outTime = outTimecheck;
            } else {
                var outTime = item.out_time;
            }

            if (this.sysSelect != null && this.sysSelect != item.system_id) {
                var system_id = this.sysSelect;
            } else {
                var system_id = item.system_id;
            }

            if (this.tickSelect != null && this.tickSelect != item.corp_id) {
                var corp_id = this.tickSelect;
            } else {
                var corp_id = item.corp_id;
            }

            if (
                this.structSelect != null &&
                this.structSelect != item.item_id
            ) {
                var item_id = this.structSelect;
            } else {
                var item_id = item.item_id;
            }

            if (
                this.refType != null &&
                this.refType != item.station_status_id
            ) {
                var station_status_id = this.refType;
            } else {
                var station_status_id = item.station_status_id;
            }

            if (
                this.imageLink != null &&
                this.imageLink != item.timer_image_link
            ) {
                var timer_image_link = this.imageLink;
            } else {
                var timer_image_link = item.timer_image_link;
            }

            var request = {
                system_id: system_id,
                corp_id: corp_id,
                item_id: item_id,
                station_status_id: station_status_id,
                out_time: outTime,
                timer_image_link: timer_image_link
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "api/updatetimerinfo/" + item.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            }).then(
                (this.showStationTimer = false),
                (this.refType = null),
                (this.refTime = null),
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
                (this.showStationTimer = false)
            );
        },

        async open() {
            await this.$store.dispatch("getSystemList");
            await this.$store.dispatch("getTickList");
            await this.$store.dispatch("getStructureList");
        }
    },

    computed: {
        ...mapGetters([]),
        ...mapState(["systemlist", "ticklist", "structurelist"]),

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
        showSubmit() {
            if (this.type == 3) {
                if (
                    this.structSelect != null &&
                    this.sysSelect != null &&
                    this.tickSelect != null &&
                    this.refType != null &&
                    this.refTime != "" &&
                    this.imageLink != null
                ) {
                    return false;
                } else {
                    return true;
                }
            } else {
                if (
                    (console.log(this.refTime),
                    this.structSelect != null &&
                        this.sysSelect != null &&
                        this.tickSelect != null &&
                        this.refType != null &&
                        this.refTime != "")
                ) {
                    return false;
                } else {
                    return true;
                }
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
