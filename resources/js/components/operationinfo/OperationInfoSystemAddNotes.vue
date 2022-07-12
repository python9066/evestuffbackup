<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn icon v-bind="attrs" v-on="on" @click="open()" color="blue">
        <font-awesome-icon icon="fa-solid fa-clipboard" size="xl" />
      </v-btn>
    </template>
    <v-row no-gutters>
      <v-col cols="auto">
        <v-card rounded="xl"
          ><v-card-title class="blue py-1"
            >Notes for {{ item.system_name }}</v-card-title
          ><v-card-text>
            <v-row no-gutters align="end" class="pt-2"
              ><v-col cols="auto"
                ><v-textarea
                  hide-details
                  autofocus
                  outlined
                  rounded
                  dense
                  v-model="text"
                >
                </v-textarea>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-row no-gutters justify="space-between">
              <v-col cols="auto">
                <v-btn rounded color="success" @click="done()"
                  >Update</v-btn
                ></v-col
              ><v-col cols="auto">
                <v-btn rounded color="error" @click="close()"
                  >Close</v-btn
                ></v-col
              ></v-row
            ></v-card-actions
          >
        </v-card>
      </v-col>
    </v-row>
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
  props: {
    loaded: Boolean,
    item: [Array, Object],
  },
  data() {
    return {
      addShown: false,
      text: "",
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    open() {
      this.text = this.item.pivot.notes;
      this.addShown = true;
    },

    close() {
      this.text = "";
      this.addShown = false;
    },

    async done() {
      var request = {
        notes: this.text,
      };
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinfosystemnoteupdate/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((reponse) => {
        this.text = "";
        this.addShown = false;
      });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState["operationInfoPage"],

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
