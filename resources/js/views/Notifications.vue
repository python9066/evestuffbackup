<template>
    <div class=" pr-16 pl-16">
        <div class=" d-flex align-items-center">
            <v-card-title>Notifications

                 <VueCountUptimer
                        :start-time="'2020-08-31 09:25:00 UTC'"
                        :end-text="'Window Closed'"
                        :interval="1000"
                    >
                    <template slot="countup" slot-scope="scope">
        <span class="red--text pl-3">{{scope.props.hours}}:{{scope.props.minutes}}:{{scope.props.seconds}}</span>
      </template>

                    </VueCountUptimer>
            </v-card-title>




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
            :headers="headers"
            :items="filteredItems"
            item-key="id"
            :loading="loading"
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
        <span class="red--text pl-3">{{scope.props.hours}}:{{scope.props.minutes}}:{{scope.props.seconds}}</span>
      </template>

                    </VueCountUptimer>

            </template>
            <v-data-footer items-per-page-options="[25,50,100]"></v-data-footer>
        </v-data-table>
    </div>

    <!-- <template>
  <v-data-table item-key="name" class="elevation-1" loading loading-text="Loading... Please wait"></v-data-table>
</template> -->
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapState } from 'vuex';
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
            loading: true,
            loading3: true,
            endcount: "",
            search: "",
            componentKey: 0,
            toggle_exclusive: 1,
            colorflag: 3,
            today: moment(),
            name: 'Timer',
            atime: null,
            diff: 0,



            headers: [
                { text: "Region", value: "region_name", width: "10%" },
                { text: "Constellation", value: "constellation_name" },
                { text: "System", value: "system_name" },
                { text: "Structure", value: "item_name"},
                { text: "Timestamp", value: "timestamp" },
                { text: "Countdown", value: "count", sortable: false },
                { text: "Status", value: "status_name" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ]
        };
    },
    async mounted() {
        // await this.getLatest();
        await this.loadtimers();
        //this.startInterval();
        // await this.getSystems();
        // await this.getTimerDataAll();
        // await this.saveAlliancesID();
        // await this.getNewAllianceIDs();
        // await this.getNewAlliacneData();
        // await this.saveNewAlliacneData();
        // await this.getTimers();
        // await this.sameTimers();
        // await this.setStructureTypes();
        // await this.matchLatesttoNames();
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

        async loadtimers() {
           await this.$store.dispatch("getNotifications");
            this.loading3 = false;
            this.loading = false;
        },

        sec (item){
             var a = moment.utc();
            var b = moment(item.timestamp);
            this.diff = a.diff(b)
            console.log(a.diff(b))
            return this.diff
        },




        // transform(props) {
        //     Object.entries(props).forEach(([key, value]) => {
        //         // Adds leading zero
        //         const digits = value < 10 ? `0${value}` : value;
        //         props[key] = `${digits}`;
        //     });

        //     return props;
        // },

        // endCounterDown(item) {
        //     var today = new Date();
        //     var date =
        //         today.getUTCFullYear() +
        //         "-" +
        //         (today.getUTCMonth() + 1) +
        //         "-" +
        //         today.getUTCDate();
        //     var time =
        //         today.getUTCHours() +
        //         ":" +
        //         today.getUTCMinutes() +
        //         ":" +
        //         today.getUTCSeconds();
        //     var dateTime = date + " " + time;
        //     var a = moment(item.end);
        //     var b = moment(dateTime);
        //     var data = a.diff(b);
        //     console.log(data);
        //     return data;
        // },
        // fromNowStart(item) {
        //     return moment(item.start);
        // },
        // fromNowEnd(item) {
        //     return moment(item.end);
        // },

        // handleCountdownEnd() {
        //     console.log("hi");
        // }
        // handleCountdownEnd(item) {
        //     console.log('hi')
        //     this.$store.dispatch('markOver',item);
        // },
    },
    computed: {



        ...mapState(['notifications']),
        filteredItems() {
            // var timers = this.$store.state.timers;
            return this.notifications
        }
    }
};
</script>
