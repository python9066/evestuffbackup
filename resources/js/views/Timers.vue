<template>
    <div class=" pr-16 pl-16">
        <v-card-title>Vulnerability Windows </v-card-title>
        <v-data-table
            :headers="headers"
            :items="timers"
            item-key="id"
            :loading="loading"
            :items-per-page="5"
            :sort-by="['end', 'region', 'alliance']"
            :sort-desc="[false, true]"
            multi-sort
            class="elevation-1"
        >
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
								{{ props.hours }}:{{ props.minutes }}:{{ props.seconds }}
							</template>
						</countdown>
                        <div id="closed" v-else> WINDOW CLOSED </div>
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
            timers: [],
            check: "not here",
            loading: true,
            endcount:"",
            componentKey: 0,

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
        await this.getTimerData();
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


        async getTimerData() {
            this.loading = true;
            await axios.get("/getTimerData").then(res => {
                if (res.status == 200) {
                    this.timers = res.data.timers;
                }
                this.loading = false;
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
