<template>
  <v-row no-gutters>
    <v-col cols="auto">
      <v-card rounded="xl"
        ><v-card-title class="green"
          ><v-row no-gutters
            ><v-col cols="auto">Recon</v-col></v-row
          ></v-card-title
        ><v-card-text
          ><v-col cols="auto"><AddOperationReconButton /></v-col>
        </v-card-text>
      </v-card>
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
  props: {
    loaded: Boolean,
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {},

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },

    showEnter() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
      }
    },
  },
  beforeDestroy() {},
};
</script>
