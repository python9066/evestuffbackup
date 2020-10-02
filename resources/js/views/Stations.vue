<template>
    <div class=" pr-16 pl-16">
        <messageStations></messageStations>
        <div class=" d-flex align-items-center">
            <v-card-title>Stations</v-card-title>
            <div v-if="$can('access hacks')">You can edit posts.</div>

            <v-btn
                :loading="loadingr"
                :disabled="loadingr"
                color="primary"
                class="ma-2 white--text"
                @click="loadtimers()"
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

            <v-btn-toggle right-align v-model="toggle_exclusive" mandatory :value="0">
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 1"
                >
                    All
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 2"
                >
                    On The Way
                </v-btn>
                <v-btn
                    :loading="loadingf"
                    :disabled="loadingf"
                    @click="statusflag = 3"
                >
                    Gunning
                </v-btn>
            </v-btn-toggle>
        </div>
        <v-data-table
            :headers="headers"
            :items="filteredItems"
            :expanded.sync="expanded"
            item-key="id"
            :loading="loadingt"
            :items-per-page="25"
            :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
            :sort-by="['timestamp']"
            :search="search"
            :sort-desc="[true, false]"
            multi-sort
            class="elevation-1"
        >
            >

            <template slot="no-data">
                No one is shooting our stuff atm, which I would say is a good
                thing?
            </template>
            <template v-slot:item.count="{ item }">
                <VueCountUptimer
                    :start-time="moment.utc(item.timestamp).unix()"
                    :end-text="'Window Closed'"
                    :interval="1000"
                    @timecheck="timecheck(item)"
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
                v-slot:item.station_status_name="{ item }"
                class="align-items-center"
            >
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <div class="align-items-center">

                            <v-btn
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                :color='pillColor(item.station_status_id)'
                            >
                                <v-icon v-if="item.station_status_id == 1" left>faSvg fa-plus</v-icon>

                                <v-icon v-if="item.station_status_id == 2" left>faSvg fa-route</v-icon>

                                <v-icon v-if="item.station_status_id == 3" left>faSvg fa-fist-raised</v-icon>

                                <v-icon v-if="item.station_status_id == 4" left>faSvg fa-thumbs-up</v-icon>

                                <v-icon v-if="item.station_status_id == 8" left>faSvg fa-shield-alt</v-icon>

                                <v-icon v-if="item.station_status_id == 9" left>faSvg fa-house-damage</v-icon>
                                {{ item.station_status_name }}
                            </v-btn>



                            <!-- EXTRA BUTTON -->
                            <v-fab-transition>
                                <v-btn
                                    icon
                                    @click="expanded = [item], expanded_id = item.id"
                                    v-if="
                                        item.station_status_id == 3 &&
                                            !expanded.includes(item)
                                    "
                                    color="success"
                                    ><v-icon>fas fa-plus</v-icon></v-btn
                                >
                                <v-btn
                                    icon
                                    @click="expanded = [], expanded_id = 0"
                                    v-if="
                                        item.station_status_id == 3 &&
                                            expanded.includes(item)
                                    "
                                    color="error"
                                    ><v-icon>fas fa-minus</v-icon></v-btn
                                >
                            </v-fab-transition>
                        </div>
                    </template>


                    <v-list>
                        <v-list-item
                            v-for="(list, index) in dropdown_edit"
                            :key="index"
                            @click="
                                (item.station_status_id = list.value),
                                    (item.station_status_name = list.title),
                                    (item.user_name = user_name),
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
            <template
                v-slot:expanded-item="{ headers, item }"
                class="align-center"
                height="100%"
            >
                <td :colspan="headers.length" align="center">
                    <div>
                        <v-col class="align-center">
                            <v-text-field
                                v-bind:value="item.text"
                                label="aDash Board Link - needs to be a link to a scan, making a new scan from where will not save"
                                outlined
                                shaped
                                @change="
                                    (payload = $event),
                                        updatetext(payload, item)
                                "
                            ></v-text-field>
                        </v-col>
                    </div>
                    <div
                        v-if="
                            item.text != null &&
                                item.text.includes('https://adashboard.info/intel/dscan/')
                        "
                    >
                        <v-card class="mx-auto" elevation="24">
                            <iframe
                            :name="'ifram'+ item.id"
                                :src="item.text"
                                style="left:0; bottom:0; right:0; width:100%; height:600px; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"
                            >
                            </iframe>
                        </v-card>
                    </div>
                    <div>
                        {{ item.text }}
                    </div>
                </td>
            </template>

            <template  v-slot:item.station_name="{ item } " class ="d-inline-flex align-center" >
               {{item.item_name}} - {{item.station_name}}
            </template>
        </v-data-table>
        <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
            {{ snackText }}

            <template v-slot:action="{ attrs }">
                <v-btn v-bind="attrs" text @click="snack = false">Close</v-btn>
            </template>
        </v-snackbar>

    </div>
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
export default {
    data() {
        return {
            atime: null,
            check: "not here",
            componentKey: 0,
            dialog1: false,
            dialog2: false,
            dialog3: false,
            diff: 0,
            delve: 0,
            endcount: "",
            expanded:[],
            expanded_id:0,
            icon: "justify",
            loadingt:true,
            loadingf:true,
            loadingr:true,
            name: "Timer",
            poll: null,
            periodbasis: 0,
            search: "",
            statusflag: 4,
            snack: false,
            snackColor: "",
            snackText: "",
            toggle_exclusive: 0,
            today: 0,
            text: "center",
            toggle_none: null,
            querious: 0,

            dropdown_edit: [
                { title: "On My Way", value: 2 },
                { title: "Gunning", value: 3 },
                { title: "Saved", value: 4 },
                { title: "Reffed - Shield", value: 8 },
                { title: "Reffed - Armor", value: 9 },
                { title: "New", value: 1 }
            ],

            headers: [
                { text: "Region", value: "region_name", width: "10%" },
                { text: "Constellation", value: "constellation_name", width: "8%" },
                { text: "System", value: "system_name", width: "8%" },
                { text: "Station", value: "station_name", width: "25%" },
                { text: "Timestamp", value: "timestamp", align: "center",width: "15%" },
                { text: "Age", value: "count", sortable: false,width: "5%"  },
                { text: "Status", value: "station_status_name", width: "15%",},
                { text: "Edited By", value: "user_name", width: "10%", align: "start"}


                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ]
        };
    },

    created() {

        Echo.private('stations')
        .listen('StationChanged', (e) => {
        this.checkexpanded(e.stations)
        this.$store.dispatch('updateStations',e.stations);
    })

        .listen('StationNew', (e) => {
        this.loadstations();

        })


        this.$store.dispatch("getStationData").then(() => {
            this.loadingt = false;
            this.loadingf = false;
            this.loadingr = false;
        });

    },


    async mounted() {
    },
    methods: {


        checkexpanded(stations){
            console.log(stations);
            if(stations.station_status_id != 3){
                if(stations.id == this.expanded_id)
                {
                    this.expanded = [];
                    this.expanded_id = 0;
                }
            }
        },



        updatetext(payload, item){
            // console.log(item);
            if(item.text != payload){
                item.text = payload
                 var request = {
                text: item.text
            };
                this.$store.dispatch('updateStations',item)
                axios({
                method: 'put', //you can set what request you want to be
                url: "api/stationrecords/" + item.id,
                data: request,
                headers: {
                    Authorization: 'Bearer ' + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
                })

                                    }

            },

        loadstations() {
            this.loadingr = true;
            this.$store.dispatch("getStationData").then(() => {
                this.loadingr = false;
            });
            // console.log("30secs");
        },

        pillColor(statusId){

            if (statusId == 1){
                return "success"
            }
            if (statusId == 2){
                return "primary"
            }
            if (statusId == 3){
                return "#FF5EEA"
            }
            if (statusId == 4){
                return "dark-orange"
            }
            if (statusId == 8){
                return "warning"
            }
            if (statusId == 9){
                return "warning"
            }

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

        },

        click(item) {

            if(item.station_status_id !=3){
                this.expanded = [];
                item.text = null;
            }

            var request = {
                station_status_id: item.station_status_id,
                user_id: this.$store.state.user_id,
            };
            axios({
                method: 'put', //you can set what request you want to be
                url: "api/stationrecords/" + item.id,
                data: request,
                headers: {
                    Authorization: 'Bearer ' + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
            })

        },

        sec(item) {
            var a = moment.utc();
            var b = moment(item.timestamp);
            this.diff = a.diff(b);
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
            "stations",
        ]),

        filteredItems() {
            // var timers = this.$store.state.timers;
            if (this.statusflag == 2) {
                return this.stations.filter(
                    stations => stations.station_status_id == 2
                );
            }
            if (this.statusflag == 3) {
                return this.stations.filter(
                    stations => stations.station_status_id == 3
                );
            }
            else {
                return this.stations.filter(
                    stations => stations.station_status_id != 10
                );
            }
        },



        user_name(){
            return this.$store.state.user_name
        },

    },
    beforeDestroy() {

        // clearInterval(this.poll);
        // console.log('KILL THEM ALL');
        Echo.leave('stations');

    },




};
</script>
