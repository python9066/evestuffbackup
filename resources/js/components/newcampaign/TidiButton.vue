<template>
  <v-row no-gutters align="baseline">
    <v-col cols="12" class="d-flex justify-content-end align-items-center h4">
      System TiDi:
      <span :class="colorTidi()">{{ item.tidi }}%</span>
      <v-menu :close-on-content-click="false" :value="tidiShow">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            class="pb-1"
            v-bind="attrs"
            v-on="on"
            @click="(tidiShow = true), (tidiEdit = null)"
            icon
            color="warning"
          >
            <v-icon
              ><font-awesome-icon icon="fa-solid fa-pen-to-square"
            /></v-icon>
          </v-btn>
        </template>

        <template>
          <v-card tile min-height="150px">
            <v-card-title class="pb-0">
              <v-text-field
                v-model="tidiEdit"
                label="Tidi %"
                autofocus
                v-mask="'###'"
                :placeholder="placeHolder()"
                @keyup.enter="(tidiShow = false), editTidi()"
                @keyup.esc="(tidiShow = false), (tidiEdit = null)"
              ></v-text-field>
            </v-card-title>
            <v-card-text>
              <v-btn
                icon
                fixed
                left
                color="success"
                @click="(tidiShow = false), editTidi()"
                ><v-icon><font-awesome-icon icon="fa-solid fa-check" /></v-icon
              ></v-btn>

              <v-btn
                fixed
                right
                icon
                color="warning"
                @click="(tidiShow = false), (tidiEdit = null)"
                ><v-icon
                  ><font-awesome-icon
                    icon="fa-solid fa-circle-xmark"
                  />s</v-icon
                ></v-btn
              >
            </v-card-text>
          </v-card>
        </template>
      </v-menu>
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
    item: Object,
  },
  data() {
    return {
      tidiShow: false,
      tidiEdit: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    placeHolder() {
      return "" + this.item.tidi;
    },

    colorTidi() {
      if (this.item.tidi > 59) {
        return "green--text font-weight-bold";
      } else if (this.item.tidi > 34) {
        return "orange--text font-weight-bold";
      } else {
        return "red--text font-weight-bold";
      }
    },

    async editTidi() {
      var request = {
        tidi: this.tidiEdit,
      };

      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/edittidi/" + this.item.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),
  },
  beforeDestroy() {},
};
</script>
