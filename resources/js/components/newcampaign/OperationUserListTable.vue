<template>
  <v-col cols="12">
    <v-card tile>
      <v-card-title class="d-flex justify-space-between align-center">
        <div>Table of all Users on this Campaign Page</div>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          item-key="id"
          disable-pagination
          fixed-header
          :height="height"
          hide-default-footer
          class="elevation-24"
          dense
        >
          <template slot="no-data"> No one is here </template>
          <template v-slot:[`item.INchars`]="{ item }">
            <v-chip
              v-for="(char, index) in item.op_users"
              :key="index"
              small
              class="pr-2"
            >
              {{ char.name }} - {{ char.userrole.role }}
              <span v-if="char.role_id == 1">
                - {{ char.ship }} - T{{ char.entosis }}</span
              ></v-chip
            >
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-col>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: { windowSize: Object },
  data() {
    return {
      headers: [
        { text: "Name", value: "name" },
        { text: "Chars", value: "INchars" },
        { text: "", value: "actions" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
    };
  },

  methods: {},

  computed: {
    ...mapState(["operationUserList"]),

    filteredItems() {
      return this.operationUserList;
    },

    height() {
      let num = this.windowSize.y - 629;
      return num;
    },
  },
};
</script>

<style></style>
