<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="1">
          {{ item.system.system_name }} - {{ eventType }}
        </v-col>
        <v-col
          cols="7"
          class="d-flex justify-content-center align-content-center"
        >
          <v-icon small left dark :color="IconDColor" :class="IconDClass">
            {{ IconD }}
          </v-icon>
          <!-- // TODO Active (only show if campaign is active) -->
          <v-progress-linear
            :color="barColor"
            :value="barScoure"
            height="20"
            rounded
            :active="true"
            :reverse="barReverse"
            :background-color="barBgcolor"
            background-opacity="0.2"
          >
            <strong>
              {{ item.defenders_score * 100 }} ({{ nodesToLose }}) /
              {{ item.attackers_score * 100 }} ({{ nodesToWin }})
            </strong>
          </v-progress-linear>
          <v-icon small left dark :color="IconAColor" :class="IconAClass">
            {{ IconD }}
          </v-icon>
        </v-col>
        <v-col cols="2">
          <span class="text-caption"> Active Nodes -</span>
          <Vep
            :progress="blueProgress"
            :size="50"
            :legend-value="blueNode"
            fontSize="0.80rem"
            color="#00ff00"
            :thickness="4"
            :emptyThickness="1"
            emptyColor="#a4fca4"
          >
            <template v-slot:legend-value>
              <span slot="legend-value"> /{{ totalNode }}</span>
            </template>
          </Vep>
          <Vep
            :progress="redProgress"
            :size="50"
            :legend-value="redNode"
            fontSize="0.80rem"
            color="#ff0000"
            :thickness="4"
            :emptyThickness="1"
            emptyColor="#f08d8d"
          >
            <template v-slot:legend-value>
              <span slot="legend-value"> /{{ totalNode }}</span>
            </template>
          </Vep>
        </v-col>
        <v-col cols="2">
          <span> Nodes -</span>
          <Vep
            :progress="totalBlueProgress"
            :size="50"
            :legend-value="totalBlueNodeDone"
            fontSize="0.80rem"
            color="#00ff00"
            :thickness="4"
            :emptyThickness="1"
            emptyColor="#a4fca4"
          >
            <template v-slot:legend-value>
              <span slot="legend-value"> /{{ totalNodeDone }}</span>
            </template>
          </Vep>
          <Vep
            :progress="totalRedProgress"
            :size="50"
            :legend-value="totalRedNodeDone"
            fontSize="0.80rem"
            color="#ff0000"
            :thickness="4"
            :emptyThickness="1"
            emptyColor="#f08d8d"
          >
            <template v-slot:legend-value>
              <span slot="legend-value"> /{{ totalNodeDone }}</span>
            </template>
          </Vep>
        </v-col>
      </v-row>
      <v-row no-gutters>
        <v-col>
          <v-text-field v-model="newscore" type="number"></v-text-field>
          <v-btn @click="updateScore()"> update</v-btn>
          <v-btn @click="update()"> artisan</v-btn>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>



<script>
import Axios from "axios";
// import { EventBus } from "../event-bus";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    item: [Object, Array],
    title: String,
    operationID: Number,
  },
  data() {
    return {
      newscore: 0,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async updateScore() {
      var d = this.newscore / 100;

      var ascore = 1 - d;
      var request = {
        defenders_score: d,
        attackers_score: ascore,
      };

      await axios({
        method: "POST", //you can set what request you want to be
        url: "/api/testscoreupdate/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async update() {
      await axios({
        method: "POST", //you can set what request you want to be
        url: "/api/testscorerun",
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapGetters([
      "getRedCampaignNodes",
      "getBlueCampaignNodes",
      "getTotalCampaignNodes",
    ]),

    ...mapState([]),

    totalNode() {
      return this.getTotalCampaignNodes(this.item.id);
    },

    redNode() {
      return this.getRedCampaignNodes(this.item.id);
    },

    blueNode() {
      return this.getBlueCampaignNodes(this.item.id);
    },

    eventType() {
      if (this.event_type == "32458") {
        return "Ihub";
      } else {
        return "TCU";
      }
    },

    blueProgress() {
      if (this.totalNode) {
        var num = (this.blueNode / this.totalNode) * 100;
        return num;
      } else {
        return 0;
      }
    },

    redProgress() {
      if (this.totalNode) {
        var num = (this.redNode / this.totalNode) * 100;
        return num;
      } else {
        return 0;
      }
    },

    barScoure() {
      var d = this.item.defenders_score * 100;
      var a = this.item.attackers_score * 100;

      if (d > 50) {
        return d;
      }

      return a;
    },

    barReverse() {
      var d = this.item.defenders_score * 100;
      if (d > 50) {
        return false;
      }

      return true;
    },

    barColor() {
      var d = this.item.defenders_score * 100;
      if (d > 50) {
        return "blue darken-4";
      }

      return "red darken-4";
    },

    barBgcolor() {
      var d = this.item.defenders_score * 100;

      if (d > 50) {
        return "red darken-4";
      }
      return "blue darken-4";
    },

    nodesToLose() {
      var needed = 1 - this.item.defenders_score;
      var need = needed / 0.07;
      return Math.ceil(need);
    },

    nodesToWin() {
      var needed = 1 - this.item.attackers_score;
      var need = needed / 0.07;
      return Math.ceil(need);
    },

    IconDColor() {
      if (
        this.item.defenders_score == this.item.defenders_score_old ||
        this.item.defenders_score_old === null
      ) {
        return "grey darken-3";
      } else {
        return "blue darken-4";
      }
    },

    IconAColor() {
      if (
        this.item.attackers_score == this.item.attackers_score_old ||
        this.item.attackers_score_old === null
      ) {
        return "grey darken-3";
      } else {
        return "red darken-4";
      }
    },

    IconD() {
      if (
        this.item.attackers_score == this.item.attackers_score_old ||
        this.item.attackers_score_old === null
      ) {
        return "fas fa-minus-circle";
      } else {
        return "fas fa-arrow-alt-circle-up";
      }
    },

    IconDClass() {
      if (
        this.item.defenders_score > this.item.defenders_score_old &&
        this.item.defenders_score_old > 0
      ) {
        return "rotate";
      } else {
        return "rotate down";
      }
    },

    IconAClass() {
      if (
        this.item.attackers_score > this.item.attackers_score_old &&
        this.item.attackers_score_old > 0
      ) {
        return "rotate";
      } else {
        return "rotate down ml-2";
      }
    },

    totalNodeDone() {
      return this.totalRedNodeDone + this.totalBlueNodeDone;
    },

    totalRedNodeDone() {
      return this.r_node;
    },

    totalBlueNodeDone() {
      return this.b_blue;
    },

    totalBlueProgress() {
      if (this.totalNodeDone) {
        var num = (this.totalBlueNodeDone / this.totalNodeDone) * 100;
        return num;
      } else {
        return 0;
      }
    },

    totalRedProgress() {
      if (this.totalNodeDone) {
        var num = (this.totalRedNodeDone / this.totalNodeDone) * 100;
        return num;
      } else {
        return 0;
      }
    },
  },
  beforeDestroy() {},
};
</script>
<style scoped>
.rotate {
  -moz-transition: all 1s linear;
  -webkit-transition: all 1s linear;
  transition: all 1s linear;
}

.rotate.down {
  -ms-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  -webkit-transform: rotate(180deg);
  transform: rotate(180deg);
}
</style>
