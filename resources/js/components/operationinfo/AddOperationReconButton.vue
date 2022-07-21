<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
    :close-on-click="false"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        fab
        x-small
        v-bind="attrs"
        v-on="on"
        @click="addShown = true"
        color="blue"
        ><font-awesome-icon icon="fa-solid fa-plus" size="2xl"
      /></v-btn>
    </template>
    <v-card tile class="rounded-xl">
      <v-card-subtitle>
        <v-menu
          :close-on-content-click="false"
          v-model="infoShown"
          z-index="0"
          content-class="rounded-xl"
          ><template v-slot:activator="{ on, attrs }">
            <v-btn
              fab
              x-small
              v-bind="attrs"
              v-on="on"
              @click="infoShown = true"
              color="blue"
              ><font-awesome-icon icon="fa-solid fa-circle-info" size="2xl" />
            </v-btn>
          </template>
          <v-card>
            <v-card-text>
              If you have added a Cyno before you will find the characters in
              the drop down list below. Just select it and press ADD<br />
              <br />
              Character not in the dropdown:<br />
              If own character just enter the fullname in the dropdown list and
              hit ADD.<br /><br />
              If character is own by someone else. Enter fullname in the drop
              down list, then toggle the "Add Main"<br />Once toggled a new
              dropdown should show, find the main character in the dropdown list
              select it and press add<br />If you cant find the name, that means
              the person has never logged into the site.</v-card-text
            ></v-card
          ></v-menu
        >
        <span> {{ infoText }} </span>
      </v-card-subtitle>
      <v-card-text>
        <v-row no-gutters>
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
        </v-row>
        <v-row no-gutters>
          <v-col cols="auto">
            <v-switch
              v-model="showMain"
              dense
              class="mt-0 pt-0"
              :false-value="0"
              :true-value="1"
              hide-details="auto"
            ></v-switch>
          </v-col>
          <v-col cols="auto" class="d-flex align-baseline">
            <span> Add Main</span>
          </v-col>
        </v-row>
        <v-row no-gutters>
          <v-autocomplete
            v-if="showMain"
            outlined
            :items="dropDownMain"
            v-model="mainName"
            item-text="name"
            item-value="id"
            hide-details
            rounded
            dense
          ></v-autocomplete>
        </v-row>
      </v-card-text>
      <v-card-actions
        ><v-row no-gutters>
          <v-col cols="auto"
            ><v-btn rounded color=" primary" @click="addRecon()"
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
      infoShown: false,
      showMain: 0,
      mainName: null,
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

      if (this.showMain) {
        var mainID = this.mainName;
      } else {
        var mainID = this.$store.state.user_id;
      }
      var request = {
        name: name,
        user_id: mainID,
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

    close() {
      this.name = null;
      this.addShown = false;
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState(["operationInfoRecon", "userList"]),

    dropDown() {
      var data = this.operationInfoRecon.filter(
        (r) => r.operation_info_id != this.$store.state.operationInfoPage.id
      );
      return data;
    },

    dropDownMain() {
      return this.userList;
    },

    infoText() {
      return "<-- read before adding Cyno";
    },

    type() {
      return typeof this.name;
    },
  },
  beforeDestroy() {},
};
</script>
