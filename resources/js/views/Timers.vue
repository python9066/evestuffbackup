<template>
    <div>Timer

<v-data-table dense
    :headers="headers"
    :items="timers"
    item-key="id"
    :loading="loading"
    :items-per-page="200"
    :sort-by="['end','region','alliance']"
    :sort-desc="[false, true]"
    multi-sort
    class="elevation-1"
  ></v-data-table>


<!-- <template>
  <v-data-table item-key="name" class="elevation-1" loading loading-text="Loading... Please wait"></v-data-table>
</template> -->


    </div>

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
            check:"not here",
            loading:true,

            headers: [
                { text: "Region", value: "region" },
                { text: "Constellation", value: "constellation" },
                { text: "System", value: "system" },
                { text: "Alliance", value: "alliance" },
                { text: "Ticker", value: "ticker" },
                { text: "Structure", value: "type" },
                { text: "ADM", value: "adm" },
                { text: "End", value: "end" },

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
    }
};

computed: {
}
</script>
