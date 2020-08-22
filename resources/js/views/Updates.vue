<template>
  <div>Some stuff</div>
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
      timer_data: [],
      all_alliance_ids: [],
      all_system_ids: [],
      system_fetch_ids: [],
      system_fetch: [],
      alliance_fetch: [],
      alliance_data: [],
      count: null,
      new_alliance_ids: [],
      url: null,
      loop: 0,
      system_data: [],
      system_data_length: 0,
      check:null,

      headers: [
        { text: "Designation", value: "name", width: "35%" },
        { text: "System", value: "system_name" },
        // { text: "structure__id", value: "structure_id" },
        { text: "Structure type", value: "structure_type_name" },
        {
          text: "Occupancy Level",
          value: "vulnerability_occupancy_level",
        },
        {
          text: "Vulnerable Start Time",
          value: "vulnerable_start_time",
        },
        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },
  async mounted() {
    // await this.getLatest();
    // await this.getSystems();
    await this.getAlliancesIDs();
    await this.saveAlliancesID();
    await this.getNewAllianceIDs();
    await this.getNewAlliacneData();
    await this.saveNewAlliacneData();
    await this.getTimers();
    await this.sameTimers();
    // await this.setStructureTypes();
    // await this.matchLatesttoNames();
  },
  methods: {
    async getAlliancesIDs() {
      this.all_alliance_ids = [];
      const res = await axios
        .get("https://esi.evetech.net/dev/alliances/?datasource=tranquility")
        .then((res) => {
          if (res.status == 200) {
            for (var i = 0; i < res.data.length; i++) {
              this.all_alliance_ids.push(res.data[i]);
            }
          }
        });
    },

    async saveAlliancesID() {
      // const emptyAlliancesTable = await axios.post("/emptyAlliancesTable");
      // this.saving_alliance_data = true;
      const alliance_data = await axios.post(
        "/saveAllianceIDs",
        this.all_alliance_ids
      );
      // this.saving_alliance_data = false;
    },

    async getNewAllianceIDs() {
      this.new_alliance_ids = [];
      const res = await axios.get("/getNewAllianceIDs").then((res) => {
        if (res.status == 200) {
          for (var i = 0; i < res.data.length; i++) {
            this.new_alliance_ids.push(res.data[i]);
          }
        }
      });
    },

    async getNewAlliacneData() {
      var count = 0;
      this.alliance_data = [];
      var loop = Math.ceil(this.new_alliance_ids.length / 200);
      if (this.new_alliance_ids.length < 201) {
        count = this.new_alliance_ids.length;
      } else {
        count = 200;
      }
      var p = 0;
      for (var c = 0; c < loop; c++) {
        for (var i = 0; i < count; i++) {
          this.url =
            "https://esi.evetech.net/latest/alliances/" +
            this.new_alliance_ids[p] +
            "/?datasource=tranquility";
          const res = await axios.get(this.url).then((res) => {
            if (res.status == 200) {
              res.data.id = this.new_alliance_ids[p];
              this.alliance_data.push(res.data);
              p++;
            }
          });
        }

        var left = this.new_alliance_ids.length - this.p;
        if (left < 201) {
          count = this.new_alliance_ids.length - 1;
        }

        await sleep(5000);
      }
    },

    async saveNewAlliacneData() {
      // const emptyAlliancesTable = await axios.post("/emptyAlliancesTable");
      // this.saving_alliance_data = true;
      const all_alliance_ids = await axios.post(
        "/saveAllianceData",
        this.alliance_data
      );
      // this.saving_alliance_data = false;
    },

    async getTimers() {
      this.timer_data = [];
      const res = await axios
        .get("https://esi.evetech.net/latest/sovereignty/structures/?datasource=tranquility")
        .then((res) => {
          if (res.status == 200) {
            for (var i = 0; i < res.data.length; i++) {
              this.timer_data.push(res.data[i]);
            }
          }
        });
    },


    async sameTimers() {
        this.check = "sameTimers";
      // const emptyAlliancesTable = await axios.post("/emptyAlliancesTable");
      // this.saving_alliance_data = true;
      const alliance_data = await axios.post(
        "/saveTimers",
        this.timer_data
      );
      // this.saving_alliance_data = false;
    },
  },
};

computed: {
}
</script>
