<template>
  <div class="pr-16 pl-16" v-resize="onResize">
    <div class="d-flex align-items-center">
      <v-card-title>Vulnerability Windows</v-card-title>

      <!-- <v-btn
                :loading="loading3"
                :disabled="loading3"
                color="prfdefdfimary"
                class="ma-2 white--text"
                @click="
                    loading3 = true;
                    loadtimers();
                "
            >
                Update
                <v-icon right dark>fas fa-sync-alt fa-xs</v-icon>
            </v-btn> -->
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>

      <v-card max-width="600px" min-width="600px" color="#121212" elevation="0">
        <v-card-text>
          <v-select
            class="pb-2"
            v-model="typePicked"
            :items="dropdown_search_list"
            label="Filter by Region"
            multiple
            chips
            deletable-chips
            hide-details
          ></v-select>
        </v-card-text>
      </v-card>

      <v-btn-toggle
        v-model="toggle_exclusive1"
        mandatory
        class="ml-4 mr-15"
        :value="2"
      >
        <v-btn
          :loading="loading3"
          :disabled="loading3"
          @click="(itemFlag = 1), (endtext = 'Time till Change')"
        >
          All
        </v-btn>
        <v-btn
          :loading="loading3"
          :disabled="loading3"
          @click="(itemFlag = 2), (endtext = 'Time till Closed')"
        >
          Open
        </v-btn>
        <v-btn
          :loading="loading3"
          :disabled="loading3"
          @click="(itemFlag = 3), (endtext = 'Time till Opened')"
        >
          Closed
        </v-btn>
      </v-btn-toggle>

      <v-btn-toggle v-model="toggle_exclusive" mandatory :value="1">
        <v-btn :loading="loading3" :disabled="loading3" @click="colorflag = 4">
          All
        </v-btn>
        <v-btn :loading="loading3" :disabled="loading3" @click="colorflag = 3">
          Goons
        </v-btn>
        <v-btn :loading="loading3" :disabled="loading3" @click="colorflag = 2">
          Friendly
        </v-btn>
        <v-btn :loading="loading3" :disabled="loading3" @click="colorflag = 1">
          Hostile
        </v-btn>
      </v-btn-toggle>
    </div>
    <v-data-table
      :headers="getHeaders()"
      :items="filterEnd"
      item-key="id"
      :height="height"
      fixed-header
      :loading="loading"
      :items-per-page="50"
      :footer-props="{
        'items-per-page-options': [10, 20, 30, 50, 100, -1],
      }"
      :sort-by="['time']"
      :search="search"
      :sort-desc="[false, true]"
      multi-sort
      class="elevation-1"
    >
      <template slot="no-data"> No open Windows </template>
      <template v-slot:[`item.alliance`]="{ item }">
        <!-- <v-img src="https://images.evetech.net/Alliance/1354830081_64.png"  style="height: inherit"></v-img> -->
        <v-avatar size="35"><img :src="item.url" /></v-avatar>
        <span v-if="item.standing > 0" class="blue--text pl-3"
          >{{ item.alliance }}
        </span>
        <span v-else-if="item.standing < 0" class="red--text pl-3"
          >{{ item.alliance }}
        </span>
        <span v-else class="pl-3">{{ item.alliance }}</span>
      </template>

      <template v-slot:[`item.count`]="{ item }">
        <template>
          <vue-countdown-timer
            @end_callback="(item.status = 1), handleCountdownEnd(item)"
            :start-time="moment.utc(item.start).unix()"
            :end-time="moment.utc(item.end).unix()"
            :end-text="'Window Closed'"
            :interval="1000"
          >
            <template slot="countdown" slot-scope="scope">
              <span class="red--text pl-3"
                >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                  scope.props.seconds
                }}</span
              >
            </template>
          </vue-countdown-timer>
        </template>
      </template>
      <template v-slot:[`item.window_station`]="{ item }">
        <span v-if="item.window_station == 'Open'" class="green--text"
          >{{ item.window_station }}
        </span>
        <span v-if="item.window_station == 'Closed'" class="red--text"
          >{{ item.window_station }}
        </span>
      </template>
      <template v-slot:[`item.age`]="{ item }">
        <template>
          <VueCountUptimer
            :start-time="moment.utc(item.age).unix()"
            :end-text="'Window Closed'"
            :interval="1000"
            :leadingZero="false"
          >
            <template slot="countup" slot-scope="scope">
              <span class="green--text pl-3"
                ><span v-if="scope.props.days != 0">
                  {{ scope.props.days }} Days - </span
                >{{ scope.props.hours }} Hours</span
              >
            </template>
          </VueCountUptimer>
        </template>
      </template>
    </v-data-table>
  </div>
</template>
<script>
import Axios from "axios";
import moment, { now, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {
    return `EveStuff - Vulnerability Windows`;
  },
  data() {
    return {
      check: "not here",
      loading: true,
      loading3: true,
      endcount: "",
      search: "",
      componentKey: 0,
      toggle_exclusive: 1,
      toggle_exclusive1: 1,
      itemFlag: 2,
      colorflag: 3,
      today: moment(),
      name: "Timer",
      test: now(),
      endtext: "Time Till Close",
      typePicked: [],
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },
  async mounted() {
    this.log();
    this.onResize();
    this.loadtimers();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    log() {
      var request = {
        url: this.$route.path,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/url",
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async loadtimers() {
      await this.$store.dispatch("getTimerDataAll");
      await this.$store.dispatch("getTimerDataAllRegion");
      this.loading3 = false;
      this.loading = false;
    },

    getHeaders() {
      return [
        { text: "Region", value: "region", width: "10%" },
        { text: "Constellation", value: "constellation" },
        { text: "System", value: "system" },
        { text: "Alliance", value: "alliance", width: "30%" },
        { text: "Ticker", value: "ticker" },
        { text: "Window", value: "window_station" },
        { text: "Structure", value: "type" },
        { text: "ADM", value: "adm" },
        { text: this.endtext, value: "time" },
        { text: "Countdown", value: "count", sortable: false },
        { text: "Age", value: "age" },
      ];
    },

    transform(props) {
      Object.entries(props).forEach(([key, value]) => {
        // Adds leading zero
        const digits = value < 10 ? `0${value}` : value;
        props[key] = `${digits}`;
      });

      return props;
    },
    handleCountdownEnd(item) {
      this.$store.dispatch("markOver", item);
    },
  },
  computed: {
    ...mapState(["timers", "timersRegions"]),
    filteredItems_start() {
      if (this.colorflag == 1) {
        return this.timers.filter(
          (timers) => timers.color == 1 && timers.status == 0
        );
      }
      if (this.colorflag == 2) {
        return this.timers.filter(
          (timers) => timers.color > 1 && timers.status == 0
        );
      }
      if (this.colorflag == 3) {
        return this.timers.filter(
          (timers) => timers.color == 3 && timers.status == 0
        );
      } else {
        return this.timers.filter((timers) => timers.status == 0);
      }
    },

    height() {
      let num = this.windowSize.y - 315;
      return num;
    },

    dropdown_search_list() {
      return this.timersRegions;
    },

    filteredItems() {
      if (this.itemFlag == 1) {
        return this.filteredItems_start;
      }

      if (this.itemFlag == 2) {
        return this.filteredItems_start.filter(
          (t) => t.window_station === "Open"
        );
      }

      if (this.itemFlag == 3) {
        return this.filteredItems_start.filter(
          (t) => t.window_station === "Closed"
        );
      }
    },

    filterEnd() {
      let data = [];
      if (this.typePicked.length != 0) {
        this.typePicked.forEach((p) => {
          let pick = this.filteredItems.filter((f) => f.region_id == p);
          if (pick != null) {
            pick.forEach((pk) => {
              data.push(pk);
            });
          }
        });
        return data;
      }
      return this.filteredItems;
    },
  },
};
</script>
