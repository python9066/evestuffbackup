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
          >Setting for Operation - {{ opInfo.name }}</v-card-title
        >
        <v-card-text>
          <v-row no-gutters justify="start">
            <v-col>
              <v-autocomplete
                v-model="operationPick"
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
          <!-- <v-row no-gutters justify="start">
            <v-col>
              <v-autocomplete
                v-model="fcPicked"
                :items="pullPicked"
                label="Select"
                chips
                clearable
                deletable-chips
                dense
                hint="Which Regions would you like FCs to see"
                hide-selected
                multiple
                persistent-hint
                rounded
                small-chips
                solo-inverted
                return-object
                stationListPullRegions
              ></v-autocomplete>
            </v-col>
          </v-row>
          <v-row no-gutters justify="start">
            <v-col>
              <v-autocomplete
                v-model="webwayPicked"
                :items="systemlist"
                label="Select"
                chips
                clearable
                deletable-chips
                dense
                hint="Set Staging Systems for Webway (1DQ is defaulted)"
                hide-selected
                multiple
                persistent-hint
                rounded
                small-chips
                solo-inverted
                return-object
              ></v-autocomplete>
            </v-col>
          </v-row> -->
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
    };
  },

  async created() {},

  methods: {
    close() {
      this.openInfo = false;
    },

    submit() {
      var request = {
        operation_id: this.operationPick,
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

    operationList() {
      return this.operationInfoOperationList;
    },

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
