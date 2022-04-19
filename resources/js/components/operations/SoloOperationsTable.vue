<template>
  <v-col cols="12" class="pa-0">
    <v-row no-gutters>
      <v-col cols="12">
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          fixed-header
          :height="height"
          item-key="id"
          :items-per-page="50"
          :footer-props="{
            'items-per-page-options': [10, 20, 30, 50, 100, -1],
          }"
          class="elevation-24 rounded-xl full-width"
        >
        </v-data-table>
      </v-col>
    </v-row>
  </v-col>
</template>
<script>
import Axios from "axios";
import { mapGetters, mapState } from "vuex";
export default {
  props: {
    windowSize: Object,
    filteredItems: Array,
  },
  data() {
    return {
      headers: [
        {
          text: "WebWay",
          value: "campaign[0].system.webway[0].jumps",
          sortable: false,
        },
        {
          text: "Region",
          value: "campaign[0].constellation.region.region_name",
          sortable: true,
        },

        {
          text: "Constellation",
          value: "campaign[0].constellation.constellation_name",
          sortable: true,
        },

        {
          text: "System",
          value: "campaign[0].system.system_name",
          sortable: true,
        },
        {
          text: "Alliance",
          value: "campaign[0].alliance.name",
          sortable: true,
        },
        {
          text: "Ticker",
          value: "campaign[0].alliance.ticker",
          sortable: true,
        },

        {
          text: "Structure",
          value: "campaign[0].event_type",
          sortable: true,
        },

        {
          text: "Start/Progress",
          value: "campaign[0].start_time",
          sortable: true,
        },

        {
          text: "Countdown/Age",
          value: "campaign[0].structure.age",
          sortable: true,
        },
      ],
    };
  },
  mounted() {},
  methods: {},
  computed: {
    height() {
      let num = this.windowSize.y - 262;
      return num;
    },
  },
};
</script>
