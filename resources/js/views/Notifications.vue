<template>
    <div class=" pr-16 pl-16">
        <div class=" d-flex align-items-center">
            <v-card-title>Notifications -/- {{today.UTC}} </v-card-title>

            <v-btn
                :loading="loadingr"
                :disabled="loadingr"
                color="primary"
                class="ma-2 white--text"
                @click="
                    loadtimers();
                "
            >
                Update
                <v-icon right dark>fas fa-sync-alt fa-xs</v-icon>
            </v-btn>
            <div>
                <v-btn-toggle v-model="icon" borderless group>
                    <v-dialog
                        v-model="dialog1"
                        fullscreen
                        hide-overlay
                        transition="dialog-bottom-transition"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <template>
                                <v-btn
                                    v-show="delvecheck == 1"
                                    color="primary"
                                    class="ma-2 white--text"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="dialog1 = true"
                                >
                                    Delve
                                    <v-icon right dark>fas fa-map fa-xs</v-icon>
                                </v-btn>
                            </template>
                        </template>
                        <v-card>
                            <v-toolbar dark color="primary">
                                <v-btn icon dark @click="dialog1 = false">
                                    <v-icon>fas fa-times-circle</v-icon>
                                </v-btn>
                                <v-toolbar-title>Delve</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <div
                                style="position: absolute; top: 64px; right: 0px; bottom: 0px; left: 0px;"
                            >
                                <iframe
                                    :src="delveLink"
                                    style="left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"
                                >
                                </iframe>
                            </div>
                        </v-card>
                    </v-dialog>

                    <v-dialog
                        v-model="dialog2"
                        fullscreen
                        hide-overlay
                        transition="dialog-bottom-transition"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                v-show="queriousCheck == 1"
                                color="primary"
                                class="ma-2 white--text"
                                v-bind="attrs"
                                v-on="on"
                                @click="dialog2 = true"
                            >
                                Querious
                                <v-icon right dark>fas fa-map fa-xs</v-icon>
                            </v-btn>
                        </template>
                        <v-card>
                            <v-toolbar dark color="primary">
                                <v-btn icon dark @click="dialog2 = false">
                                    <v-icon>fas fa-times-circle</v-icon>
                                </v-btn>
                                <v-toolbar-title>Querious</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <div
                                style="position: absolute; top: 64px; right: 0px; bottom: 0px; left: 0px;"
                            >
                                <iframe
                                    :src="queriousLink"
                                    style="left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"
                                >
                                </iframe>
                            </div>
                        </v-card>
                    </v-dialog>
                    <v-dialog
                        v-model="dialog3"
                        fullscreen
                        hide-overlay
                        transition="dialog-bottom-transition"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                v-show="periodbasisCheck == 1"
                                color="primary"
                                class="ma-2 white--text"
                                v-bind="attrs"
                                v-on="on"
                                @click="dialog3 = true"
                            >
                                Period Basis
                                <v-icon right dark>fas fa-map fa-xs</v-icon>
                            </v-btn>
                        </template>
                        <v-card>
                            <v-toolbar dark color="primary">
                                <v-btn icon dark @click="dialog3 = false">
                                    <v-icon>fas fa-times-circle</v-icon>
                                </v-btn>
                                <v-toolbar-title>Period Basis</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <div
                                style="position: absolute; top: 64px; right: 0px; bottom: 0px; left: 0px;"
                            >
                                <iframe
                                    :src="periodbasisLink"
                                    style="left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"
                                >
                                </iframe>
                            </div>
                        </v-card>
                    </v-dialog>
                </v-btn-toggle>
            </div>

            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>

            <v-btn-toggle v-model="toggle_exclusive" mandatory :value="0">
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 4"
                >
                    All
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 1"
                >
                    New
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 3"
                >
                    Repairing
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 5"
                >
                    Contested
                </v-btn>
            </v-btn-toggle>
        </div>
        <v-data-table
            :headers="headers"
            :items="filteredItems"
            item-key="id"
            :loading="loadingt"
            :items-per-page="25"
            :sort-by="['timestamp']"
            :search="search"
            :sort-desc="[true, false]"
            multi-sort
            class="elevation-1"
        >
            <template v-slot:item.count="{ item }">
                <VueCountUptimer
                    :start-time="item.timestamp + ' UTC'"
                    :end-text="'Window Closed'"
                    :interval="1000"
                >
                    <template slot="countup" slot-scope="scope">
                        <span class="red--text pl-3"
                            >{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                    </template>
                </VueCountUptimer>
            </template>

            <template
                v-slot:item.status_name="{ item }"
                class="align-items-center"
            >
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <div class="align-items-center">
                            <v-btn
                                v-if="item.status_id == 1"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="success"
                            >
                                <v-icon left>fas fa-plus</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                            <v-btn
                                v-if="item.status_id == 2"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="error"
                            >
                                <v-icon left>fas fa-fire fa-sm</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                            <v-btn
                                v-if="item.status_id == 3"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="dark-orange"
                            >
                                <v-icon left>fas fa-toolbox</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                            <v-btn
                                v-if="item.status_id == 4"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="primary"
                            >
                                <v-icon left>fas fa-thumbs-up</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                            <v-btn
                                v-if="item.status_id == 5"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="warning"
                            >
                                <v-icon left>fas fa-exclamation-circle</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                        </div>
                    </template>

                    <v-list>
                        <v-list-item
                            v-for="(list, index) in dropdown_edit"
                            :key="index"
                            @click="
                                (item.status_id = list.value),
                                    (item.status_name = list.title),
                                    click(item)
                            "
                        >
                            <v-list-item-title>{{
                                list.title
                            }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
            <v-data-footer items-per-page-options="{25,50,100}"></v-data-footer>
        </v-data-table>
        <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
            {{ snackText }}

            <template v-slot:action="{ attrs }">
                <v-btn v-bind="attrs" text @click="snack = false">Close</v-btn>
            </template>
        </v-snackbar>
    </div>

    <!-- <template>
  <v-data-table item-key="name" class="elevation-1" loading loading-text="Loading... Please wait"></v-data-table>
</template> -->
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
import ApiL from "../service/apil";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
// import VueFilterDateFormat from "@vuejs-community/vue-filter-date-format";
// import VueFilterDateParse from "@vuejs-community/vue-filter-date-parse";
export default {
    data() {
        return {
            //timersAll: [],
            check: "not here",
            loadingt:true,
            loadingf:true,
            loadingr:true,
            // loading3: true,
            endcount: "",
            search: "",
            componentKey: 0,
            toggle_exclusive: 0,
            statusflag: 4,
            today: moment(),
            name: "Timer",
            atime: null,
            diff: 0,
            snack: false,
            snackColor: "",
            snackText: "",
            pagination: {},
            dialog1: false,
            dialog2: false,
            dialog3: false,
            text: "center",
            icon: "justify",
            toggle_none: null,
            delve: 0,
            periodbasis: 0,
            querious: 0,
            poll: null,

            dropdown_edit: [
                { title: "Reffed", value: 2 },
                { title: "Repairing", value: 3 },
                { title: "Saved", value: 4 },
                { title: "Contested", value: 5 }
            ],

            headers: [
                { text: "Region", value: "region_name", width: "10%" },
                { text: "Constellation", value: "constellation_name" },
                { text: "System", value: "system_name" },
                { text: "Structure", value: "item_name" },
                { text: "ADM", value: "adm" },
                { text: "Timestamp", value: "timestamp" },
                { text: "Age", value: "count", sortable: false },
                { text: "Status", value: "status_name" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ]
        };
    },

    created() {

        Echo.private('notes')
        .listen('NotificationChanged', (e) => {
        // console.log(e.notifications.id);
        this.$store.dispatch('updateNotification',e);
    })

        .listen('NotificationNew', (e) => {
        // console.log('NEW UPDATE');
        this.loadtimers();

        })

    },


    async mounted() {
        // await this.getLatest();
        // await this.loadtimers();
        this.$store.dispatch("getNotifications").then(() => {
            this.loadingt = false;
            this.loadingf = false;
            this.loadingr = false;
        });


        this.$store.dispatch("getqueriousLink");
        this.$store.dispatch("getdelveLink");
        this.$store.dispatch("getperiodbasisLink");


    //    this.poll = setInterval(() => {
    //         this.loadingr = true;
    //         this.$store.dispatch("getNotifications").then(() => {
    //             this.loadingr = false;
    //         });
    //         this.$store.dispatch("getqueriousLink");
    //         this.$store.dispatch("getdelveLink");
    //         this.$store.dispatch("getperiodbasisLink");
    //     }, 30000);
    },
    methods: {
        //     startCallBack: function (x) {
        //     console.log(x)
        //   },
        //   endCallBack: function (x) {
        //     console.log(x)
        //   },
        // async getTimerDataAll() {
        //     this.loading = true;
        //     await axios.get("/getTimerData").then(res => {
        //         if (res.status == 200) {
        //             this.timersAll = res.data.timers;
        //         }
        //     this.loading = false;
        //     this.loading3 = false;

        //     });
        // },




        loadtimers() {
            this.loadingr = true;
            this.$store.dispatch("getNotifications").then(() => {
                this.loadingr = false;
            });
            this.$store.dispatch("getqueriousLink");
            this.$store.dispatch("getdelveLink");
            // this.$store.dispatch("getperiodbasisLink");
            // console.log("30secs");
        },

        save() {
            this.snack = true;
            this.snackColor = "success";
            this.snackText = "Data saved";
        },
        cancel() {
            this.snack = true;
            this.snackColor = "error";
            this.snackText = "Canceled";
        },
        open() {
            this.snack = true;
            this.snackColor = "info";
            this.snackText = "Dialog opened";
        },
        close() {
            // console.log("Dialog closed");
        },

        click(item) {
            // console.log("Dialog clicked");
            // console.log(item);
            var request = {
                status_id: item.status_id
            };
            axios({
                method: 'put', //you can set what request you want to be
                url: "api/notifications/" + item.id,
                data: request,
                headers: {
                    Authorization: 'Bearer ' + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
            })
            // console.log(request);
            // ApiL().put("notifications/" + item.id, request);
        },

        sec(item) {
            var a = moment.utc();
            var b = moment(item.timestamp);
            this.diff = a.diff(b);
            // console.log(a.diff(b));
            return this.diff;
        }

        // handleCountdownEnd() {
        //     console.log("hi");
        // }
        // handleCountdownEnd(item) {
        //     console.log('hi')
        //     this.$store.dispatch('markOver',item);
        // },
    },

    computed: {
        ...mapState([
            "notifications",
            "delveLink",
            "queriousLink",
            "periodbasisLink"
        ]),

        filteredItems() {
            // var timers = this.$store.state.timers;
            if (this.statusflag == 1) {
                return this.notifications.filter(
                    notifications => notifications.status_id == 1
                );
            }
            if (this.statusflag == 3) {
                return this.notifications.filter(
                    notifications => notifications.status_id == 3
                );
            }
            if (this.statusflag == 5) {
                return this.notifications.filter(
                    notifications => notifications.status_id == 5
                );
            } else {
                return this.notifications;
            }
        },

        delvecheck() {
            if (this.delveLink === "") {
                return 0;
            } else if (this.delveLink === "nope") {
                return 0;
            } else {
                return 1;
            }
        },

        periodbasisCheck() {
            if (this.periodbasisLink === "") {
                return 0;
            } else if (this.periodbasisLink === "nope") {
                return 0;
            } else {
                return 1;
            }
        },

        queriousCheck() {
            if (this.queriousLink === "") {
                return 0;
            } else if (this.queriousLink === "nope") {
                return 0;
            } else {
                return 1;
            }
        }
    },
    beforeDestroy() {

        // clearInterval(this.poll);
        // console.log('KILL THEM ALL');
        Echo.leave('notes');

    },




};
</script>
