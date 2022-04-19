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
          value: "filteredItems.campaign[0].system.webway[0].jumps",
          sortable: false,
        },
        {
          text: "Region",
          value: "region",
          sortable: true,
        },

        {
          text: "Constellation",
          value: "constellation",
          sortable: true,
        },

        {
          text: "System",
          value: "system",
          sortable: true,
        },
        {
          text: "Alliance",
          value: "alliance",
          sortable: true,
        },
        {
          text: "Ticker",
          value: "ticker",
          sortable: true,
        },

        {
          text: "Structure",
          value: "structure",
          sortable: true,
        },

        {
          text: "Start/Progress",
          value: "startTime",
          sortable: true,
        },

        {
          text: "Countdown/Age",
          value: "startTime",
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
