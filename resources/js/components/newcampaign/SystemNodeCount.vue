<template>
  <v-row no-gutters>
    <!-- <v-col cols="6">
      <v-progress-circular
        v-if="nodeCount > 0"
        :transitionDuration="5000"
        :radius="20"
        :strokeWidth="4"
        :value="(nodeCountHackingCount / nodeCount) * 100 || 0.000001"
      >
        <div class="caption">
          {{ nodeCountHackingCount }} /
          {{ nodeCount }}
        </div></v-progress-circular
      >
    </v-col>
    <v-col cols="6">
      <v-progress-circular
        v-if="nodeCount > 0"
        :transitionDuration="5000"
        :radius="20"
        :strokeWidth="4"
        strokeColor="#FF3D00"
        :value="(nodeRedCountHackingCount / nodeCount) * 100 || 0.000001"
      >
        <div class="caption">
          {{ nodeRedCountHackingCount }} /
          {{ nodeCount }}
        </div></v-progress-circular
      >
    </v-col> -->
    <v-col>
      <Vep
        :progress="progress"
        :size="size"
        :legend-value="blueNode"
        :fontSize="fontsize"
        color="green"
        :thickness="thickness"
      >
        <template v-slot:legend-value>
          <span slot="legend-value"> /{{ totalNode }}</span>
        </template>
        <!-- <template v-slot:legend-caption>
          <p slot="legend-caption">Friendly</p>
        </template> -->
      </Vep>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {},
  data() {
    return {
      totalNode: 10,
      blueNode: 5,
      redNode: 5,
      size: 50,
      fontSizeNumber: "0.80",
      thickness: 4,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    progress() {
      var num = (this.blueNode / this.totalNode) * 100;
      return num;
    },

    fontsize() {
      var text = this.fontSizeNumber + "rem";
      return text;
    },
  },
  beforeDestroy() {},
};
</script>
