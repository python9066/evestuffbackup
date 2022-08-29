<template>
  <v-row no-gutters justify="center">
    <v-col cols="12">
      <v-row no-gutters
        ><v-col>
          <v-card elevation="10" rounded="xl">
            <v-card-title class="primary"> Hack Notifications </v-card-title>
            <v-card-text>
              <v-data-table
                :headers="headers"
                :items="filteredItems"
                fixed-header
                item-key="id"
                hide-default-footer
              >
                <!-- @click:row="rowClick($event)" -->
                <template slot="no-data">
                  Nothing being hacked at the moment
                </template>
              </v-data-table>
            </v-card-text></v-card
          ></v-col
        ></v-row
      >
    </v-col>
  </v-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {},
  data() {
    return {
      headers: [
        { text: "Region", value: "region_name", width: "10%" },
        {
          text: "Constellation",
          value: "constellation_name",
          width: "8%",
        },
        { text: "System", value: "system_name", width: "8%" },
        { text: "Structure", value: "item_name", width: "8%" },
        { text: "ADM", value: "adm", width: "5%" },
        {
          text: "Timestamp",
          value: "timestamp",
          align: "center",
          width: "20%",
        },
        { text: "Age", value: "count", sortable: false, width: "10%" },
        { text: "Status", value: "status_name", width: "20%" },
        {
          text: "Edited By",
          value: "user_name",
          width: "10%",
          align: "start",
        },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },

  methods: {},

  computed: {
    ...mapState(["notifications"]),

    filteredItems() {
      return this.notifications;
    },
  },
};
</script>

<style>
</style>
