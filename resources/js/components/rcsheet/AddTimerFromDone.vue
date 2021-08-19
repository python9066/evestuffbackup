<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showAddTimer"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="cyan darken-1"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="open()"
                >
                    Add Hull Timer
                    <v-icon> </v-icon>
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
                    <p>
                        Enter Details for {{ item.station_name }}
                    </p></v-card-title
                >

                <v-card-text>
                    <v-fade-transition>
                        <div>
                            <div>
                                <v-text-field
                                    v-model="item.name"
                                    readonly
                                    solo
                                    label="Station Name"
                                ></v-text-field>
                            </div>
                            <div class=" d-inline-flex justify-content-around">
                                <v-text-field
                                    v-model="item.system_name"
                                    readonly
                                    solo
                                    label="System Name"
                                ></v-text-field>
                                <v-text-field
                                    class=" ml-2"
                                    solo
                                    v-model="item.corp_ticker"
                                    label="Corp Ticker"
                                ></v-text-field>
                            </div>
                            <div>
                                <h5><strong>Timer Type</strong></h5>
                                <v-text-field
                                    class=" ml-2"
                                    solo
                                    v-model="this.hull"
                                    readonly
                                    label="Ref Type"
                                ></v-text-field>
                            </div>
                            <div>
                                <h5><strong>Image Link 2</strong></h5>
                                <v-img src="../image/info.png"> </v-img>
                                <v-text-field
                                    v-model="imageLink"
                                    autofocus
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
    props: {
        item: Object
    },

    async created() {},
    data() {
        return {
            showAddTimer: false,
            imageLink: null,
            refType: null,
            refTime: {
                d: "",
                hh: "",
                mm: "",
                ss: ""
            },
            hull: "Hull"
        };
    },

    methods: {
        close() {
            this.showStationTimer = false;
            this.refType = null;
            this.refTime = null;
        },

        async submit() {
            var outTime = null;
            var editText = "Added the timer";

            editText = editText + "\n";
            if (this.notes == null) {
                var note =
                    moment.utc().format("HH:mm:ss") +
                    " - " +
                    this.$store.state.user_name +
                    ": " +
                    editText;
            } else {
                var note =
                    moment.utc().format("HH:mm:ss") +
                    " - " +
                    this.$store.state.user_name +
                    ": " +
                    editText +
                    this.item.notes;
            }

            var y = this.refTime.substr(0, 4);
            var mo = this.refTime.substr(5, 2);
            var d = this.refTime.substr(8, 2);
            var h = this.refTime.substr(11, 2);
            var m = this.refTime.substr(14, 2);
            var s = this.refTime.substr(17, 2);
            var full = y + "-" + mo + "-" + d + " " + h + ":" + m + ":" + s;
            var outTime = moment(full).format("YYYY-MM-DD HH:mm:ss");

            var request = {
                station_status_id: 13,
                out_time: outTime,
                timer_image_link: this.imageLink,
                show_on_rc_move: 1,
                show_on_rc: 0,
                status_update: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
                notes: note
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "api/updatetimerinfo/" + this.item.id,
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
                (this.showStationTimer = false)
            );
        },

        async open() {
            // this.$emit("timeropen");
        }
    },

    computed: {
        ...mapGetters([]),
        ...mapState([]),

        vaildDate() {
            if (this.count == 19) {
                var y = this.refTime.substr(0, 4);
                var mo = this.refTime.substr(5, 2);
                var d = this.refTime.substr(8, 2);
                var h = this.refTime.substr(11, 2);
                var m = this.refTime.substr(14, 2);
                var s = this.refTime.substr(17, 2);
                var full = y + "-" + mo + "-" + d + " " + h + ":" + m + ":" + s;
                var vaild = moment(full)
                    .format("YYYY-MM-DD HH:mm:ss", true)
                    .isValid();
                return vaild;
            } else {
                return false;
            }
        },

        test() {
            if (this.count == 19) {
                var y = this.refTime.substr(0, 4);
                var mo = this.refTime.substr(5, 2);
                var d = this.refTime.substr(8, 2);
                var h = this.refTime.substr(11, 2);
                var m = this.refTime.substr(14, 2);
                var s = this.refTime.substr(17, 2);
                var full = y + "-" + mo + "-" + d + " " + h + ":" + m + ":" + s;
                return full;
            }
        },

        test2() {
            if (this.count == 19) {
                var y = this.refTime.substr(0, 4);
                var mo = this.refTime.substr(5, 2);
                var d = this.refTime.substr(8, 2);
                var h = this.refTime.substr(11, 2);
                var m = this.refTime.substr(14, 2);
                var s = this.refTime.substr(17, 2);
                var full = y + "-" + mo + "-" + d + " " + h + ":" + m + ":" + s;
                var outTime = moment(full).format("YYYY-MM-DD HH:mm:ss");
                return outTime;
            }
        },

        count() {
            return this.refTime.length;
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
