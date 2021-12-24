<template>
  <div class="text-center">
    <v-menu v-model="menu">
      <template v-slot:activator="{ on, attrs }">
        <v-btn outlined rounded small v-bind="attrs" v-on="on">
          Add Missing Corp
        </v-btn>
      </template>
      <v-card>
        <v-card-title> ONLY USE IF CORP TICKER IS NOT FOUND</v-card-title>
        <v-card-text>
          <v-text-field
            outlined
            v-model="ticker"
            title="Corp Ticker"
          ></v-text-field></v-card-text
        ><v-card-actions>
          <v-btn>Submit</v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {},
  data() {
    return {
      menu: false,
      ticker: null,
    };
  },

  methods: {
    async submit() {
      await this.$store
        .dispatch("updateTickList", ticker)
        .then(this.$emit("setTicker"));
    },
  },

  computed: {},

  beforeDestroy() {},
};
</script>

<style></style>
