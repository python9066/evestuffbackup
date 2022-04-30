<template>
  <v-row no-gutters>
    <v-col>
      <v-expansion-panels
        v-model="showCharTable"
        readonly
        popout
        style="cursor: context-menu"
      >
        <v-expansion-panel style="cursor: context-menu">
          <v-expansion-panel-header expand-icon="" style="cursor: context-menu">
            <v-row no-gutters>
              <v-col cols="2">
                <v-btn
                  color="primary"
                  @click="btnShowCharTable"
                  :outlined="charTableOutlined"
                  rounded
                  small
                >
                  Char Table
                </v-btn></v-col
              >
              <v-col cols="2"
                ><AddOperationUser :operationID="operationID"></AddOperationUser
              ></v-col>
            </v-row>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <OperationUserTable></OperationUserTable>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
// import { EventBus } from "../event-bus";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    operationID: Number,
  },
  data() {
    return {
      showCharTable: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    btnShowCharTable() {
      if (this.showCharTable == null) {
        this.showCharTable = 0;
      } else {
        this.showCharTable = null;
      }
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

    charTableOutlined() {
      if (this.showCharTable == null) {
        return true;
      } else {
        return false;
      }
    },
  },
  beforeDestroy() {},
};
</script>
