<template>
  <div>
    Some stuff
  </div>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
// import VueFilterDateFormat from "@vuejs-community/vue-filter-date-format";
// import VueFilterDateParse from "@vuejs-community/vue-filter-date-parse";
export default {
  data() {
    return {
      loading: true,
      search: "",
      fetching_ids: false,
      fetching_names: false,
      fetching_systems: false,
      saving: false,
      info: [],
      response: null,
      all_alliance_ids: [],
      all_system_ids: [],
      system_fetch_ids: [],
      system_fetch: [],
      alliance_fetch: [],
      alliance_data: [],
      systems: [],
      system_data: [],
      system_data_length: 0,
      alliance_response: null,
      systems_response: null,
      headers: [
        { text: "Designation", value: "name", width: "35%" },
        { text: "System", value: "system_name" },
        // { text: "structure__id", value: "structure_id" },
        { text: "Structure type", value: "structure_type_name" },
        {
          text: "Occupancy Level",
          value: "vulnerability_occupancy_level",
        },
        { text: "Vulnerable Start Time", value: "vulnerable_start_time" },
        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },
  async mounted() {
    this.loading = true;
    // await this.getLatest();
    // await this.getSystems();
    await this.getAlliancesIDs();
    await this.saveAlliancesID();
    // await this.getSystemIDs();
    // await this.getSystemNames();
    // await this.setStructureTypes();
    // await this.matchLatesttoNames();
    this.loading = false;
  },
  methods: {
    async getAlliancesIDs() {
      this.all_alliance_ids = [];
      const res = await axios
        .get("https://esi.evetech.net/dev/alliances/?datasource=tranquility")
        .then((res) => {
          if (res.status == 200) {
            this.alliance_response = "pull";
            for (var i = 0; i < res.data.length; i++) {
              this.all_alliance_ids.push(res.data[i]);
            }
          }
        })
    },

    async saveAlliancesID() {
			// const emptyAlliancesTable = await axios.post("/emptyAlliancesTable");
            // this.saving_alliance_data = true;
            this.system_data="hiefhie"
			const alliance_data = await axios.post(
				"/saveAllianceIDs",
				this.all_alliance_ids
			);
			// this.saving_alliance_data = false;
		}




    },

  computed: {},
};
</script>
