<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showReconTask"
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
                    Add Task
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
                    <p>Make New Task</p>
                </v-card-title>
                <v-card-text>
                    <div
                        class=" d-inline-flex align-content-center justify-content-around"
                    >
                        <v-text-field
                            v-model="taskName"
                            autofocus
                            placeholder="Check the stuff around here"
                            label="Enter Name Here"
                            outlined
                            class=" shrink"
                            style="width:600px"
                        ></v-text-field>
                    </div>
                    <v-fade-transition>
                        <div>
                            <div class=" d-inline-flex justify-content-around">
                                <v-autocomplete
                                    v-model="sysSelect"
                                    :loading="sysLoading"
                                    chips
                                    deletable-chips
                                    multiple
                                    :items="sysItems"
                                    :search-input.sync="sysSearch"
                                    label="System Name"
                                    outlined
                                ></v-autocomplete>
                            </div>

                            <!-- <div class=" d-inline-flex justify-content-around">
                                <v-autocomplete
                                    v-model="system_test"
                                    chips
                                    deletable-chips
                                    multiple
                                    :items="system_test_items"
                                    label="System Test"
                                    outlined
                                ></v-autocomplete>
                            </div> -->
                        </div>
                    </v-fade-transition>
                    <v-fade-transition>
                        <div v-if="state == 3">
                            <div>
                                <v-text-field
                                    v-model="stationPull.structure_name"
                                    label="Structure Type"
                                    readonly
                                ></v-text-field>
                            </div>
                            <div class=" d-inline-flex justify-content-around">
                                <v-text-field
                                    v-model="stationPull.system_name"
                                    label="System Name"
                                    readonly
                                ></v-text-field>
                                <v-text-field
                                    v-model="stationPull.corp_ticker"
                                    label="Corp Ticker"
                                    readonly
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

            <!-- <showReconTask
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showReconTask = false"
            >
            </showReconTask> -->
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
            taskName: null,
            state: 1,
            showReconTask: false,
            stationName: null,
            sysItems: [],
            systemEdit: null,
            sysSearch: null,
            sysSelect: [],
            system_test: [],
            sysLoading: false,
            ticktemEdit: null,
            tickerEdit: null,
            stationPull: [],
            structtemEdit: null,
            structerEdit: null
        };
    },

    watch: {
        sysSearch(val) {
            val && val !== this.sysSelect && this.sysQuerySelections(val);
        }
    },

    methods: {
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
            this.taskName = null;
            this.showReconTask = false;
            this.stationName = null;
            this.taskName = null;
            this.sysItems = [];
            this.sysSearch = null;
            this.sysSelect = null;
            this.systems = [];
            this.state = 1;
            this.showReconTask = false;
        },

        async submit() {
            var request = {
                name: this.stationName,
                system_id: this.sysSelect
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "api/stationnew",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            }).then(
                (this.taskName = null),
                (this.showReconTask = false),
                (this.stationName = null),
                (this.taskName = null),
                (this.sysItems = []),
                (this.sysSearch = null),
                (this.sysSelect = null),
                (this.systems = []),
                (this.state = 1),
                (this.showReconTask = false)
            );
        },

        async open() {
            await this.$store.dispatch("getSystemList");
            await this.$store.dispatch("getTickList");
            await this.$store.dispatch("getStructureList");
        },

        async reconTaskAdd() {
            var request = {
                title: this.taskName
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
                if (res.state == 2) {
                    this.stationPull = res;
                    this.stationName = res.station_name;
                    this.state = res.state;
                }

                if (res.state == 3) {
                    this.stationPull = res;
                    this.state = res.state;
                }
            });
        }
    },

    computed: {
        ...mapGetters([]),
        ...mapState(["systemlist", "ticklist", "structurelist"]),

        submitTask() {
            if (this.taskName == null) {
                return true;
            } else {
                return false;
            }
        },

        systemList() {
            return this.systemlist;
        },
        showSubmit() {
            if (this.sysSelect != null) {
                return false;
            } else {
                return true;
            }
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
