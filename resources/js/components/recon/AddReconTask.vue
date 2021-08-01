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
                                <v-textarea
                                    outlined
                                    v-model="infoText"
                                    placeholder="Enter more details about the task here"
                                    label="Task Details"
                                    class="shrink"
                                    style="width:600px"
                                ></v-textarea>
                            </div>
                        </div>
                    </v-fade-transition>
                    <v-fade-transition>
                        <div>
                            <div class=" d-inline-flex justify-content-around">
                                <v-autocomplete
                                    v-model="systemValue"
                                    :items="systemList"
                                    outlined
                                    deletable-chips
                                    chips
                                    label="Enter Systems here"
                                    multiple
                                    style="width:600px"
                                ></v-autocomplete>
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
            systemValue: [],
            taskName: null,
            infoText: null
        };
    },

    methods: {
        close() {
            this.taskName = null;
            this.showReconTask = false;
            this.taskName = null;
            this.systemValue = [];
        },

        async submit() {
            var request = {
                name: this.stationName
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
