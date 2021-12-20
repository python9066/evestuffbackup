<template>
  <div>
    <v-dialog max-width="700px" z-index="0" v-model="showAddTower" persistent>
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="green" dark v-bind="attrs" v-on="on" @click="open()"
          ><v-icon left> faSvg fa-plus </v-icon>
          Add Tower
        </v-btn>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="1000px"
        class="d-flex flex-column"
      >
        <v-card-title class="justify-center"> Adding a New Tower </v-card-title>
        <v-card-text class="justify-content-center">
          <div
            class="d-inline-block align-content-center justify-content-around"
          >
            <div>
              <v-autocomplete
                v-model="structSelect"
                :loading="structLoading"
                :items="structItems"
                :search-input.sync="structSearch"
                clearable
                autofocus
                label="Structure Type"
                outlined
              ></v-autocomplete>
            </div>
            <div class="d-inline-flex justify-content-around">
              <v-autocomplete
                v-model="sysSelect"
                @input="getMoonList()"
                :loading="sysLoading"
                clearable
                :items="sysItems"
                :search-input.sync="sysSearch"
                label="System Name"
                class="pr-2"
                outlined
              ></v-autocomplete>
              <v-autocomplete
                v-model="moonSelect"
                :loading="moonLoading"
                :disabled="moonDisable"
                clearable
                :items="moonItems"
                :search-input.sync="moonSearch"
                label="Moon"
                outlined
              ></v-autocomplete>
            </div>
            <div>
              <v-autocomplete
                class="ml-2"
                v-model="tickSelect"
                :loading="tickLoading"
                clearable
                :items="tickItems"
                :search-input.sync="tickSearch"
                label="Alliance Ticker"
                outlined
              ></v-autocomplete>
            </div>

            <div>
              <v-radio-group row v-model="timeType">
                <v-radio label="Anchoring" value="3"></v-radio>
                <v-radio label="Anchored" value="9"></v-radio>
                <v-radio label="Onine" value="4"></v-radio>
                <v-radio label="Reffed" value="5"></v-radio>
              </v-radio-group>
            </div>
            <div>
              <v-text-field
                v-show="addShowTimer()"
                v-model="timeTime"
                label="Ref Time d hh:mm:ss"
                v-mask="'#d ##:##:##'"
                placeholder="d:hh:mm:ss"
                @keyup.enter="(timerShown = false), addHacktime()"
                @keyup.esc="(timerShown = false), (hackTime = null)"
              ></v-text-field>
            </div>
          </div>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn>
          <v-btn
            class="white--text"
            color="green"
            :disabled="showSubmit"
            @click="submit()"
          >
            Submit
          </v-btn></v-card-actions
        >
      </v-card>

      <!-- <showAddTower
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showAddTower = false"
            >
            </showAddTower> -->
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    station: Object,
  },
  data() {
    return {
      systems: [],
      towerNameEdit: null,
      state: 1,
      showAddTower: false,
      stationName: null,
      sysItems: [],
      systemEdit: null,
      sysSearch: null,
      sysSelect: null,
      sysLoading: false,
      moonItems: [],
      moonEdit: null,
      moonSearch: null,
      moonSelect: null,
      moonLoading: false,
      tickItems: [],
      ticktemEdit: null,
      tickSearch: null,
      tickSelect: null,
      tickLoading: false,
      tickerEdit: null,
      timeType: null,
      stationPull: [],
      structItems: [],
      structtemEdit: null,
      structSearch: null,
      structSelect: null,
      structLoading: false,
      structerEdit: null,
      timeTime: {
        d: "",
        hh: "",
        mm: "",
        ss: "",
      },
    };
  },

  watch: {
    sysSearch(val) {
      val && val !== this.sysSelect && this.sysQuerySelections(val);
    },

    tickSearch(val) {
      val && val !== this.tickSelect && this.tickQuerySelections(val);
    },

    structSearch(val) {
      val && val !== this.structSelect && this.structQuerySelections(val);
    },

    moonSearch(val) {
      val && val !== this.moonSelect && this.moonQuerySelections(val);
    },
  },

  methods: {
    close() {
      this.towerNameEdit = null;
      this.showAddTower = false;
      this.stationName = null;
      this.towerNameEdit = null;
      this.structItems = [];
      this.structSearch = null;
      this.structSelect = null;
      this.sysItems = [];
      this.timeTime = {
        d: "",
        hh: "",
        mm: "",
        ss: "",
      };
      this.timeType = null;
      this.sysSearch = null;
      this.sysSelect = null;
      this.systems = [];
      this.tickItems = [];
      this.tickSearch = null;
      this.tickSelect = null;
      this.moonItems = [];
      this.moonSearch = null;
      this.moonSelect = null;
      this.state = 1;
      this.showAddTower = false;
    },

    addShowTimer() {
      if (this.timeType == 3 || this.timeType == 5) {
        return true;
      } else {
        return false;
      }
    },

    async submit() {
      var d = parseInt(this.timeTime.substr(0, 1));
      var h = parseInt(this.timeTime.substr(3, 2));
      var m = parseInt(this.timeTime.substr(6, 2));
      var s = parseInt(this.timeTime.substr(9, 2));
      var ds = d * 24 * 60 * 60;
      var hs = h * 60 * 60;
      var ms = m * 60;
      var sec = ds + hs + ms + s;
      var outTime = moment
        .utc()
        .add(sec, "seconds")
        .format("YYYY-MM-DD HH:mm:ss");

      var request = {
        moon_id: this.moonSelect,
        alliance_id: this.tickSelect,
        item_id: this.structSelect,
        timestamp: moment.utc().format("YYYY-MM-DD HH:mm:ss"),
        tower_status_id: this.timeType,
        out_time: outTime,
      };

      await axios({
        method: "put", //you can set what request you want to be
        url: "api/towerrecords",
        data: request,
        headers: {
          Authorization: "Bearer " + this.$store.state.token,
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(
        (this.towerNameEdit = null),
        (this.showAddTower = false),
        (this.stationName = null),
        (this.towerNameEdit = null),
        (this.structItems = []),
        (this.structSearch = null),
        (this.timeType = null),
        (this.structSelect = null),
        (this.sysItems = []),
        (this.sysSearch = null),
        (this.sysSelect = null),
        (this.timeTime = {
          d: "",
          hh: "",
          mm: "",
          ss: "",
        }),
        (this.systems = []),
        (this.tickItems = []),
        (this.tickSearch = null),
        (this.tickSelect = null),
        (this.moonItems = []),
        (this.moonSearch = null),
        (this.moonSelect = null),
        (this.state = 1),
        (this.showAddTower = false)
      );
    },

    tickQuerySelections(v) {
      this.tickLoading = true;
      // Simulated ajax query
      setTimeout(() => {
        this.tickItems = this.tickList.filter((e) => {
          return (
            (e.text || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1
          );
        });
        this.tickLoading = false;
      }, 500);
    },

    structQuerySelections(v) {
      this.structLoading = true;
      // Simulated ajax query
      setTimeout(() => {
        this.structItems = this.structureList.filter((e) => {
          return (
            (e.text || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1
          );
        });
        this.structLoading = false;
      }, 500);
    },

    async getMoonList() {
      await this.$store
        .dispatch("getMoonList", this.sysSelect)
        .then((this.moonSearch = this.sysSearch));
    },

    sysQuerySelections(v) {
      this.sysLoading = true;
      // Simulated ajax query
      setTimeout(() => {
        this.sysItems = this.systemList.filter((e) => {
          return (
            (e.text || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1
          );
        });
        this.sysLoading = false;
      }, 500);
    },

    moonQuerySelections(v) {
      this.moonLoading = true;
      // Simulated ajax query
      setTimeout(() => {
        this.moonItems = this.moonList.filter((e) => {
          return (
            (e.text || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1
          );
        });
        this.moonLoading = false;
      }, 500);
    },

    async open() {
      await this.$store.dispatch("getSystemList");
      await this.$store.dispatch("getAllianceTickList");
      await this.$store.dispatch("getTowerList");
    },
  },

  computed: {
    ...mapGetters([]),
    ...mapState(["systemlist", "towerlist", "allianceticklist", "moonlist"]),
    showSubmit() {
      if (
        this.structSelect != null &&
        this.sysSelect != null &&
        this.tickSelect != null &&
        this.moonSelect != null
      ) {
        return false;
      } else {
        return true;
      }
    },

    systemList() {
      return this.systemlist;
    },

    structureList() {
      return this.towerlist;
    },

    tickList() {
      return this.allianceticklist;
    },

    moonDisable() {
      if (this.sysSelect > 0) {
        return false;
      } else {
        return true;
      }
    },

    moonList() {
      return this.moonlist;
    },
  },

  beforeDestroy() {},
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
