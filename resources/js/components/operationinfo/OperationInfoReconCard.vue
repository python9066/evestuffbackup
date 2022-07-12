<template>
  <v-row no-gutters>
    <v-col cols="auto">
      <v-card rounded="xl"
        ><v-card-title class="green"
          ><v-row no-gutters
            ><v-col cols="auto">Recon</v-col
            ><v-col cols="auto"
              ><AddOperationReconButton /></v-col></v-row></v-card-title
        ><v-card-text>
          <v-row
            no-gutters
            v-for="recon in opInfo.recons"
            :key="`${recon.id}-card`"
          >
            <!-- <v-tooltip right> -->
            <v-col cols="auto">
              <v-tooltip right :disabled="hideToolTip(recon)"
                ><template v-slot:activator="{ on, attrs }">
                  <transition
                    mode="out-in"
                    :enter-active-class="showEnter"
                    :leave-active-class="showLeave"
                  >
                    <span
                      v-bind="attrs"
                      v-on="on"
                      :key="`${recon.id}-${recon.operation_info_recon_status_id}-span`"
                      :class="textColor(recon)"
                    >
                      {{ recon.name }} - {{ recon.main.name }}
                    </span>
                  </transition>
                </template>

                <span v-if="recon.operation_info_recon_status_id == 2"
                  >Fleet - {{ recon.fleet.name }} <br />
                  Role - {{ recon.fleet_role.name }}</span
                >
                <span
                  v-if="
                    recon.operation_info_recon_status_id == 1 && recon.system
                  "
                  >Location - {{ recon.system.system_name }}</span
                >
              </v-tooltip>
            </v-col>
            <v-col cols="auto">
              <v-btn
                x-small
                color="orange"
                icon
                @click="removeRecon(recon)"
                :key="`${recon.id}-${recon.operation_info_recon_status_id}-button`"
                ><font-awesome-icon icon="fa-solid fa-trash"
              /></v-btn>
            </v-col>
          </v-row>
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
  },
  data() {
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    textColor(recon) {
      if (recon.operation_info_recon_status_id == 1) {
        return "";
      }

      if (recon.operation_info_recon_status_id == 2) {
        return "green--text";
      }
    },

    async removeRecon(item) {
      console.log(item);

      var request = item;
      await axios({
        method: "post", //you can set what request you want to be
        url: "/api/operationinforeconremove/" + item.operation_info_id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    hideToolTip(recon) {
      if (recon.operation_info_fleet_id || recon.system) {
        return false;
      }
      return true;
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState([]),

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
  },
  beforeDestroy() {},
};
</script>
