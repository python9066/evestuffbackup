<template>
    <div class=" pr-16 pl-16">
        <div class=" d-flex align-items-center">
            <v-card-title>Vulnerability Windows fefefefefe </v-card-title>
            <v-btn
                :loading="loading3"
                :disabled="loading3"
                color="primary"
                class="ma-2 white--text"
                @click="
                    loading3 = true;
                    getTimerDataAll();
                    getTimerDataBlue();
                    getTimerDataRed();
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
        </div>
        <v-data-table
            :headers="headers"
            :items="timersAll"
            item-key="id"
            :loading="loading"
            :items-per-page="15"
            :sort-by="['end', 'region', 'alliance']"
            :search="search"
            :sort-desc="[false, true]"
            multi-sort
            class="elevation-1"
        >
        	<template v-slot:item.alliance="{item}">

                       <!-- <v-img src="https://images.evetech.net/Alliance/1354830081_64.png"  style="height: inherit"></v-img> -->
                       <v-avatar size="35"><img :src= item.url ></v-avatar>
						<span v-if="item.standing > 0" class=" blue--text pl-3" >{{item.alliance}} </span>
                        <span v-else-if="item.standing < 0" class="red--text pl-3" >{{item.alliance}} </span>
                        <span v-else class="pl-3">{{item.alliance}} k {{item.standing}} </span>

					</template>

            <template v-slot:item.count="{ item }">
                <countdown
                    :key="componentKey"
                    ref="countdown"
                    v-if="endCounterDown(item) > 0"
                    :time="endCounterDown(item)"
                    auto-start
                    class="red--text"
                    @end="handleCountdownEnd()"
                    :transform="transform"
                >
                    <template slot-scope="props">
                        {{ props.hours }}:{{ props.minutes }}:{{
                            props.seconds
                        }}
                    </template>
                </countdown>
                <div id="closed" v-else>WINDOW CLOSED</div>
            </template>
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
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
// import VueFilterDateFormat from "@vuejs-community/vue-filter-date-format";
// import VueFilterDateParse from "@vuejs-community/vue-filter-date-parse";
export default {
    data() {
        return {
            timersAll: [],
            timersRed: [],
            timersBlue: [],
            check: "not here",
            loading: true,
            loading3: true,
            endcount: "",
            search: '',
            componentKey: 0,
            item:"timersAll",



            headers: [
                { text: "Region", value: "region" },
                { text: "Constellation", value: "constellation" },
                { text: "System", value: "system" },
                { text: "Alliance", value: "alliance" },
                { text: "Ticker", value: "ticker" },
                { text: "Structure", value: "type" },
                { text: "ADM", value: "adm" },
                { text: "End", value: "end" },
                { text: "Countdown", value: "count" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ]
        };
    },
    async mounted() {
        // await this.getLatest();
        // await this.getSystems();
        await this.getTimerDataAll();
        await this.getTimerDataBlue();
        await this.getTimerDataRed();
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
        async getTimerDataAll() {
            this.loading = true;
            await axios.get("/getTimerData?s=all").then(res => {
                if (res.status == 200) {
                    this.timersAll = res.data.timers;
                }

            });
        },

        async getTimerDataBlue() {
            this.loading = true;
            await axios.get("/getTimerData?s=blue").then(res => {
                if (res.status == 200) {
                    this.timersBlue = res.data.timers;
                }

            });
        },

        async getTimerDataRed() {
            this.loading = true;
            await axios.get("/getTimerData?s=red").then(res => {
                if (res.status == 200) {
                    this.timersRed = res.data.timers;
                }
                this.loading = false;
                this.loading3 = false;
            });
        },



        transform(props) {
            Object.entries(props).forEach(([key, value]) => {
                // Adds leading zero
                const digits = value < 10 ? `0${value}` : value;
                props[key] = `${digits}`;
            });

            return props;
        },

        endCounterDown(item) {
            var today = new Date();
            var date =
                today.getUTCFullYear() +
                "-" +
                (today.getUTCMonth() + 1) +
                "-" +
                today.getUTCDate();
            var time =
                today.getUTCHours() +
                ":" +
                today.getUTCMinutes() +
                ":" +
                today.getUTCSeconds();
            var dateTime = date + " " + time;
            var a = moment(item.end);
            var b = moment(dateTime);
            return a.diff(b);
        },
        fromNowStart(item) {
            return moment(item.start);
        },
        fromNowEnd(item) {
            return moment(item.end);
        },

        handleCountdownEnd() {
            this.componentKey += 1;
        }
    }
};

computed: {


}
</script>
