<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      persistent
      v-model="openInfo"
      class="rounded-xl"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="gray" v-bind="attrs" v-on="on" @click="open()" icon>
          <font-awesome-icon icon="fa-solid fa-gears" size="2xl" />
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
          >Globle Setting for Operation - {{ opInfo.name }}</v-card-title
        >
        <v-card-text>
          <v-row no-gutters justify="start">
            <v-col>
              <v-autocomplete
                v-model="opInfo.operation_id"
                :items="operationList"
                item-text="title"
                item-value="id"
                label="Select"
                clearable
                dense
                hint="Which Hacking Operation would you like to add"
                hide-selected
                persistent-hint
                rounded
                solo-inverted
              ></v-autocomplete>
            </v-col>
          </v-row>
          <v-row no-gutters justify="start">
            <v-col>
              <v-autocomplete
                v-model="pickedSystems"
                :items="systemList"
                label="Select"
                multiple
                chips
                deletable-chips
                dense
                hint="Which none hacking Operation Systems would you like to add"
                hide-selected
                persistent-hint
                rounded
                solo-inverted
              ></v-autocomplete>
            </v-col>
          </v-row>

          <v-row no-gutters justify="start">
            <v-col cols="auto">
              <v-row no-gutters>
                <v-col cols="auto">
                  <v-switch
                    v-model="opInfo.check_list"
                    dense
                    class="mt-0 pt-0"
                    :false-value="0"
                    :true-value="1"
                    hide-details="auto"
                    @change="changeCheck()"
                  ></v-switch>
                </v-col>
                <v-col cols="auto" class="d-flex align-baseline">
                  <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__flash animate__faster"
                    leave-active-class="animate__animated animate__flash animate__faster"
                  >
                    <span :key="opInfo.check_list">
                      Check List - {{ checkText(opInfo.check_list) }}</span
                    >
                  </transition>
                </v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="auto">
                  <v-switch
                    v-model="opInfo.fleet_table"
                    dense
                    class="mt-0 pt-0"
                    :false-value="0"
                    :true-value="1"
                    hide-details="auto"
                    @change="changeCheck()"
                  ></v-switch>
                </v-col>
                <v-col cols="auto" class="d-flex align-baseline">
                  <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__flash animate__faster"
                    leave-active-class="animate__animated animate__flash animate__faster"
                  >
                    <span :key="opInfo.fleet_table">
                      Fleet Table - {{ checkText(opInfo.fleet_table) }}</span
                    >
                  </transition>
                </v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="auto">
                  <v-switch
                    v-model="opInfo.recon_table"
                    dense
                    class="mt-0 pt-0"
                    :false-value="0"
                    :true-value="1"
                    hide-details="auto"
                    @change="changeCheck()"
                  ></v-switch>
                </v-col>
                <v-col cols="auto" class="d-flex align-baseline">
                  <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__flash animate__faster"
                    leave-active-class="animate__animated animate__flash animate__faster"
                  >
                    <span :key="opInfo.recon_table">
                      Recon List - {{ checkText(opInfo.recon_table) }}</span
                    >
                  </transition>
                </v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="auto">
                  <v-switch
                    v-model="opInfo.system_table"
                    dense
                    class="mt-0 pt-0"
                    :false-value="0"
                    :true-value="1"
                    hide-details="auto"
                    @change="changeCheck()"
                  ></v-switch>
                </v-col>
                <v-col cols="auto" class="d-flex align-baseline">
                  <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__flash animate__faster"
                    leave-active-class="animate__animated animate__flash animate__faster"
                  >
                    <span :key="opInfo.system_table">
                      System Table - {{ checkText(opInfo.system_table) }}</span
                    >
                  </transition>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn rounded @click="submit()" color="primery"> Update </v-btn>

          <v-btn rounded class="white--text" color="teal" @click="close()">
            Close
          </v-btn></v-card-actions
        >
      </v-card>

      <!-- <showStationSettingPannel
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showStationSettingPannel = false"
            >
            </showStationSettingPannel> -->
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
      openInfo: false,
      operationPick: null,
      systemsToUpdate: [],
      switch4: 0,
      switch3: 0,
      switch2: 0,
      switch1: 0,
    };
  },

  async created() {},

  methods: {
    close() {
      this.openInfo = false;
    },

    checkText(num) {
      if (num == 1) {
        return "Show";
      } else {
        return "Hide";
      }
    },

    async changeCheck() {
      var request = this.opInfo;
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/operationinfopage/" + this.opInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    submit() {
      var request = {
        operation_id: this.opInfo.operation_id,
        systemsToUpdate: this.systemsToUpdate,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinfosheet/" + this.opInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    open() {
      this.openInfo = true;
    },
  },

  computed: {
    ...mapState(["operationInfoOperationList"]),

    ...mapGetters(["getOperationInfoSystemList"]),

    operationList() {
      return this.operationInfoOperationList;
    },

    systemList() {
      return this.$store.state.systemlist;
    },

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },

    pickedSystems: {
      get() {
        this.systemsToUpdate = this.$store.getters.getOperationInfoSystemList;
        return this.$store.getters.getOperationInfoSystemList;
      },
      set(newValue) {
        this.systemsToUpdate = newValue;
      },
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
