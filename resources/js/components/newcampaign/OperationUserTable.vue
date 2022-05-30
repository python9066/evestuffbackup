<template>
  <v-col cols="12">
    <v-card tile>
      <v-card-title class="d-flex justify-space-between align-center">
        <div>Table of all Characters involved in this Campaign</div>
        <div>
          <v-btn-toggle
            right-align
            v-model="toggle_exclusive"
            mandatory
            :value="0"
          >
            <v-btn @click="statusflag = 5"> All </v-btn>
            <v-btn @click="statusflag = 1"> Hackers </v-btn>
            <v-btn @click="statusflag = 2"> Scouts </v-btn>
            <v-btn @click="statusflag = 3"> FCs </v-btn>
            <v-btn @click="statusflag = 4"> Commands </v-btn>
          </v-btn-toggle>
        </div>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          item-key="id"
          height
          fixed-header
          disable-pagination
          hide-default-footer
          class="elevation-24"
          dense
        >
          <template slot="no-data"> No one is here </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-col>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: {
    windowSize: Object,
  },
  data() {
    return {
      headers: [
        { text: "Name", value: "name", width: "10%" },
        { text: "Main", value: "user.name" },
        { text: "Role", value: "userrole.role" },
        { text: "Ship", value: "ship" },
        { text: "Entosis", value: "entosis" },
        { text: "System", value: "system.system_name" },
        { text: "Node", value: "user_node.node.name" },
        { text: "Status", value: "userstatus.name" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      statusflag: 0,
      toggle_exclusive: 0,
    };
  },

  computed: {
    ...mapState(["opUsers"]),

    filteredItems() {
      return this.opUsers;
    },

    height() {
      let num = this.windowSize.y - 629;
      return num;
    },
  },
};
</script>

<style></style>
