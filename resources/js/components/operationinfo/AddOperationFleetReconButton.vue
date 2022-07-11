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
        color="success"
        ><font-awesome-icon icon="fa-solid fa-plus" size="2xl"
      /></v-btn>
    </template>
    <v-card tile class="rounded-xl">
      <v-card-text>
        <v-combobox
          outlined
          :items="dropDown"
          v-model="name"
          item-text="name"
          item-value="id"
          hide-details
          rounded
          dense
        ></v-combobox>
      </v-card-text>
      <v-card-actions
        ><v-row no-gutters>
          <v-col cols="auto"
            ><v-btn rounded color=" primary" @click="addRecon()"
              >Add</v-btn
            ></v-col
          ><v-spacer /><v-col cols="auto"
            ><v-btn rounded color=" warning" @click="close()">Close</v-btn
            ><font-awesome-icon icon="fa-solid fa-user-astronaut"
          /></v-col>
        </v-row>
      </v-card-actions>
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
      name: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async addRecon() {
      await sleep(500);
      if (this.type == "string") {
        var name = this.name;
      } else {
        var name = this.name.name;
      }
      var request = {
        name: name,
        opID: this.$store.state.operationInfoPage.id,
      };
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinforecon",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((response) => {
        var code = response.status;
        if (code == 201) {
          this.name = null;
          var text = name + " not found";
          this.$toast.error(text, {
            position: "bottom-left",
            timeout: 2000,
            closeOnClick: true,
            pauseOnFocusLoss: false,
            pauseOnHover: false,
            draggable: false,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
          });
        } else {
          var text = name + " added";
          this.name = null;
          this.addShown = false;
          this.$toast.success(text, {
            position: "bottom-left",
            timeout: 2000,
            closeOnClick: true,
            pauseOnFocusLoss: false,
            pauseOnHover: false,
            draggable: false,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false,
          });
        }
      });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["operationInfoRecon"]),

    dropDown() {
      var data = this.operationInfoRecon.filter(
        (r) => r.operation_info_id != this.$store.state.operationInfoPage.id
      );
      return data;
    },

    type() {
      return typeof this.name;
    },
  },
  beforeDestroy() {},
};
</script>
