<template>
  <v-dialog
    :close-on-content-click="false"
    v-model="addShown"
    max-width="700px"
    z-index="0"
    content-class="rounded-xl"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        text
        v-bind="attrs"
        v-on="on"
        @click="addShown = true"
        color="success"
        ><font-awesome-icon icon="fa-solid fa-plus" size="xl" pull="left" />
        Operation</v-btn
      >
    </template>
    <v-card
      tile
      max-width="700px"
      min-height="200px"
      max-height="700px"
      class="rounded-xl"
    >
      <v-card-title class="primary">Operation Information</v-card-title>
      <v-card-text>
        <v-text-field
          class="pt-2"
          label="Name"
          placeholder="Enter Name"
          rounded
          filled
          autofocus
          v-model="nameText"
        ></v-text-field>
        <v-textarea
          label="Info"
          filled
          placeholder="Enter Info"
          rounded
          v-model="infoText"
        ></v-textarea>
      </v-card-text>
      <v-card-actions
        ><v-row no-gutters>
          <v-col cols="auto"
            ><v-btn
              rounded
              color=" primary"
              :disabled="showButton"
              @click="addOperationInfo()"
              >Add</v-btn
            ></v-col
          ><v-spacer /><v-col cols="auto"
            ><v-btn rounded color=" warning" @click="close()"
              >Close</v-btn
            ></v-col
          >
        </v-row>
      </v-card-actions>
    </v-card>
  </v-dialog>
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
      nameText: "",
      infoText: "",
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async addOperationInfo() {
      var request = {
        name: this.nameText,
        info: this.infoText,
      };
      await axios({
        method: "POST",
        url: "/api/operationinfosheet",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(
        (this.nameText = ""),
        (this.infoText = ""),
        (this.addShown = false)
      );

      // TODO Add logging
    },

    close() {
      (this.nameText = ""), (this.infoText = ""), (this.addShown = false);
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    showButton() {
      if (this.nameText != "" && this.infoText != "") {
        return false;
      } else {
        return true;
      }
    },
  },
  beforeDestroy() {},
};
</script>
