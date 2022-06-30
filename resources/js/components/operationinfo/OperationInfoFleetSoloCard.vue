<template>
  <v-row no-gutters class="pt-2">
    <v-col cols="12">
      <v-card rounded="xl" :key="fleetID"
        ><v-card-title class="red"
          ><v-row no-gutters justify="space-between"
            ><v-col cols="auto">{{ fleetInfo.name }}</v-col
            ><v-col cols="auto"
              ><v-fab-transition
                ><v-speed-dial
                  v-model="fab"
                  v-if="readOnly"
                  direction="left"
                  transition="slide-y-reverse-transition"
                >
                  <template v-slot:activator>
                    <v-btn v-model="fab" color="blue darken-2" small fab>
                      <font-awesome-icon
                        v-if="fab"
                        size="xl"
                        icon="fa-solid fa-xmark"
                      />
                      <font-awesome-icon
                        v-else
                        size="xl"
                        icon="fa-solid fa-bars"
                      />
                    </v-btn>
                  </template>
                  <v-btn fab x-small color="green" @click="readOnlyOff()">
                    <font-awesome-icon
                      size="xl"
                      icon="fa-solid fa-pen-to-square"
                    />
                  </v-btn>
                  <v-btn fab x-small color="red" @click="deleteFleet()">
                    <font-awesome-icon size="xl" icon="fa-solid fa-trash-can" />
                  </v-btn>
                </v-speed-dial>
                <v-btn fab color="green" small v-else @click="readOnlyOn()">
                  <font-awesome-icon
                    icon="fa-solid fa-check"
                    size="xl" /></v-btn></v-fab-transition></v-col></v-row></v-card-title
        ><v-card-text>
          <v-row class="pt-2" no-gutters
            ><v-col cols="auto"></v-col>
            <v-combobox
              outlined
              :clearable="!readOnly"
              :readonly="readOnly"
              :append-icon="dropDownIcon"
              :items="operationInfoUsers"
              v-model="fleetInfo.fc"
              item-text="name"
              item-value="id"
              hide-details
              rounded
              label="FC"
              dense
              @change="updateFC()"
            ></v-combobox
          ></v-row>
          <v-row class="pt-2" no-gutters
            ><v-col cols="auto"></v-col
            ><v-combobox
              outlined
              :clearable="!readOnly"
              :readonly="readOnly"
              :append-icon="dropDownIcon"
              :items="operationInfoUsers"
              v-model="fleetInfo.boss"
              item-text="name"
              item-value="id"
              hide-details
              rounded
              label="Boss"
              dense
              @change="updateBoss()"
            ></v-combobox
          ></v-row>
          <v-row class="pt-2" no-gutters
            ><v-col cols="auto"></v-col
            ><v-autocomplete
              outlined
              :clearable="!readOnly"
              :readonly="readOnly"
              :append-icon="dropDownIcon"
              :items="operationInfoDoctrines"
              v-model="fleetInfo.doctrine_id"
              item-text="name"
              item-value="id"
              hide-details
              rounded
              label="Doctrine"
              dense
              @change="updateFleet()"
            ></v-autocomplete
          ></v-row>
          <v-row class="pt-2" no-gutters
            ><v-col cols="auto"></v-col
            ><v-autocomplete
              outlined
              :clearable="!readOnly"
              :readonly="readOnly"
              :append-icon="dropDownIcon"
              :items="operationInfoMumble"
              v-model="fleetInfo.mumble_id"
              item-text="name"
              item-value="id"
              hide-details
              rounded
              label="Mumble"
              dense
              @change="updateFleet()"
            ></v-autocomplete
          ></v-row>
          <v-row class="pt-2" no-gutters
            ><v-col cols="auto"></v-col>
            <v-autocomplete
              outlined
              :clearable="!readOnly"
              :readonly="readOnly"
              :append-icon="dropDownIcon"
              :items="allianceticklist"
              v-model="fleetInfo.alliance_id"
              hide-details
              rounded
              label="Alliance"
              dense
              @change="updateFleet()"
            ></v-autocomplete
          ></v-row>
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
    fleetID: Number,
  },
  data() {
    return {
      fab: false,
      readOnly: true,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    readOnlyOff() {
      this.readOnly = false;
      this.fab = false;
    },

    readOnlyOn() {
      this.readOnly = true;
    },

    async updateBoss() {
      if (this.typeBoss == "string") {
        var name = this.fleetInfo.boss ?? null;
      } else {
        if (this.fleetInfo.boss == null) {
          var name = null;
        } else {
          var name = this.fleetInfo.boss.name ?? null;
        }
      }

      var request = {
        name: name,
        type: 2,
        opID: this.opInfo.id,
      };
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/operationinfofleetname/" + this.fleetInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async updateFleet() {
      var request = this.fleetInfo;
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/operationinfofleet/" + this.fleetInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async updateFC() {
      if (this.typeFC == "string") {
        var name = this.fleetInfo.fc ?? null;
      } else {
        if (this.fleetInfo.fc == null) {
          var name = null;
        } else {
          var name = this.fleetInfo.fc.name ?? null;
        }
      }

      var request = {
        name: name,
        type: 1,
        opID: this.opInfo.id,
      };
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/operationinfofleetname/" + this.fleetInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async deleteFleet() {
      await axios({
        method: "delete", //you can set what request you want to be
        url: "/api/operationinfofleet/" + this.fleetInfo.id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapGetters(["getFleetInfo"]),

    ...mapState([
      "operationInfoPage",
      "operationInfoMumble",
      "operationInfoUsers",
      "operationInfoDoctrines",
      "allianceticklist",
    ]),

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },

    fleetInfo: {
      get() {
        return this.$store.getters.getFleetInfo(this.fleetID);
      },
      set(newValue) {
        return this.$store.dispatch(
          "updateOperationSheetInfoPageFleet",
          newValue
        );
      },
    },

    showEnter() {
      if (this.loaded == true) {
        return "animate__animated animate__zoomIn animate__faster";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__zoomOut animate__faster";
      }
    },

    typeFC() {
      return typeof this.fleetInfo.fc;
    },

    typeBoss() {
      return typeof this.fleetInfo.boss;
    },

    dropDownIcon() {
      if (this.readOnly) {
        return "";
      } else {
        return "$dropdown";
      }
    },
  },
  beforeDestroy() {},
};
</script>
