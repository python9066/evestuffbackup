<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        fab
        x-small
        v-bind="attrs"
        v-on="on"
        @click="addShown = true"
        color="warning"
        ><font-awesome-icon icon="fa-solid fa-person-rifle" size="2xl"
      /></v-btn>
    </template>
    <v-card tile class="rounded-xl">
      <v-card-text> </v-card-text>
      <v-card-actions> </v-card-actions>
    </v-card>
  </v-menu>
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
      addShown: false,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    close() {
      this.addShown = false;
    },

    checkText(num) {
      if (num) {
        return "Show";
      } else {
        return "Hide";
      }
    },

    textcolor(num) {
      if (num) {
        return "green--text";
      } else {
        return "red--text";
      }
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["operationInfoSetting"]),
    viewSettings: {
      get() {
        return this.$store.state.operationInfoSetting;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationInfoSetting", newValue);
      },
    },
  },

  beforeDestroy() {},
};
</script>
