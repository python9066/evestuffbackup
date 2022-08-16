<template>
  <div>
    <v-dialog
      max-width="1000px"
      z-index="0"
      persistent
      v-model="openCal"
      class="rounded-xl"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="pink" v-bind="attrs" v-on="on" @click="open()" icon>
          <font-awesome-icon icon="fa-solid fa-calculator" size="2xl" />
        </v-btn>
      </template>
      <v-card
        tile
        max-width="1000px"
        min-height="200px"
        max-height="700px"
        elevation="10"
        class="rounded-xl"
      >
        <v-card-title class="justify-center primary"
          >Simple ADM Calculator
        </v-card-title>
        <v-card-text>
          <v-row no-gutters>
            <v-col cols="12" class="pt-10">
              <v-slider
                hide-details
                hint="Im a hint"
                :max="6"
                :min="1"
                v-model="adm"
                :step="0.1"
                :thumb-color="color"
                :color="color"
                thumb-label="always"
                label="ADM"
              ></v-slider> </v-col
          ></v-row>
          <v-row no-gutters>
            <v-col cols="6">
              <v-row no-gutters>
                <v-col cols="8">Time To Reinforce</v-col>
                <v-col cols="4">{{ ttr }} - Minutes</v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="8">Time Per Command Node (without Spool)</v-col>
                <v-col cols="4">{{ tpcn }} - Minutes</v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="8">Best Case Contest (Inclide T1 Spool)</v-col>
                <v-col cols="4">{{ bcc }} - Minutes</v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="8">Contested (Best Guess)</v-col>
                <v-col cols="4">{{ c }} - Minutes</v-col>
              </v-row>
            </v-col>
            <v-col cols="6">
              <v-data-table
                :headers="headers"
                :items="tableData"
                disable-pagination
                hide-default-footer
                class="elevation-1"
                dense
              ></v-data-table>
            </v-col>
          </v-row>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn rounded class="white--text" color="teal" @click="close()">
            Close
          </v-btn></v-card-actions
        >
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {},
  data() {
    return {
      openCal: false,
      adm: null,

      tableData: [
        {
          multiplier: "1x",
          timeToReinforce: 10,
          timeToCap: 4,
        },
        {
          multiplier: "2x",
          timeToReinforce: 20,
          timeToCap: 8,
        },
        {
          multiplier: "3.5x",
          timeToReinforce: 35,
          timeToCap: 14,
        },
        {
          multiplier: "4.5x",
          timeToReinforce: 45,
          timeToCap: 18,
        },
        {
          multiplier: "4.9x",
          timeToReinforce: 49,
          timeToCap: 19.6,
        },
        {
          multiplier: "5.4x",
          timeToReinforce: 54,
          timeToCap: 21.6,
        },
        {
          multiplier: "6x",
          timeToReinforce: 60,
          timeToCap: 24,
        },
      ],
      headers: [
        {
          text: "Total Activiy Multiplier",
          value: "multiplier",
          align: "center",
        },
        {
          text: "Time to Reinforce a Structure",
          value: "timeToReinforce",
          align: "center",
        },
        {
          text: "Time to Capture a Command Node",
          value: "timeToCap",
          align: "center",
        },
      ],
    };
  },

  async created() {},

  methods: {
    close() {
      this.openCal = false;
    },

    open() {
      this.openCal = true;
    },
  },

  computed: {
    color() {
      if (this.adm > 0 && this.adm < 2) {
        return "#2E7F18";
      }

      if (this.adm > 1.9 && this.adm < 3) {
        return "#45731E";
      }

      if (this.adm > 2.9 && this.adm < 4) {
        return "#675E24";
      }

      if (this.adm > 3.9 && this.adm < 5) {
        return "#8D472B";
      }

      if (this.adm > 4.9 && this.adm < 7) {
        return "#B13433";
      }

      return "#C82538";
    },

    ttr() {
      var num = 10 * this.adm;
      return num.toFixed(2);
    },

    tpcn() {
      var num = 4 * this.adm;
      return num.toFixed(2);
    },

    bcc() {
      var num = (this.adm + 8) * 2.2;
      return num.toFixed(2);
    },

    c() {
      var num = (this.adm + 8) * 3.4;
      return num.toFixed(2);
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
