<template>
  <v-menu
    :close-on-content-click="false"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        icon
        v-bind="attrs"
        v-on="on"
        @click="addShown = true"
        color="blue"
      >
        <font-awesome-icon icon="fa-solid fa-clipboard" size="xl" />
      </v-btn>
    </template>
    <v-row no-gutters>
      <v-col cols="auto">
        <v-card rounded="xl"
          ><v-card-title class="blue"
            >Notes for {{ item.system_name }}</v-card-title
          ><v-card-text>
            <v-row no-gutters align="end"
              ><v-col cols="auto"
                ><v-textarea
                  hide-details
                  dense
                  v-model="item.pivot.notes"
                  :false-value="0"
                  :true-value="1"
                >
                </v-textarea>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions
            ><v-btn text @click="done()">Update</v-btn></v-card-actions
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
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    cross(value) {
      if (value == 1) {
        return "text-decoration-line-through green--text";
      }
    },

    async done() {
      this.opInfo.status_id = 4;
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

    showButton() {
      if (
        this.opInfo.post_op_coord_done &&
        this.opInfo.post_op_defrief_done &&
        this.opInfo.post_op_fc_done &&
        this.opInfo.post_op_recon_done &&
        this.opInfo.post_op_scouts_done
      ) {
        return true;
      } else {
        return false;
      }
    },

    count() {
      var count =
        this.opInfo.post_op_coord_done +
        this.opInfo.post_op_defrief_done +
        this.opInfo.post_op_fc_done +
        this.opInfo.post_op_recon_done +
        this.opInfo.post_op_scouts_done;
      return count;
    },

    countPercent() {
      return (this.count / 5) * 100;
    },
  },
  beforeDestroy() {},
};
</script>
