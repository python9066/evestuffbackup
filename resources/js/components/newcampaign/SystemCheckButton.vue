<template>
  <v-row no-gutters align="baseline">
    <v-col cols="3">
      <v-btn color="green" small @click="checkClick()">
        <v-icon small left dark
          ><font-awesome-icon icon="fa-solid fa-magnifying-glass-location" />
        </v-icon>
        System Checked</v-btn
      ></v-col
    >
    <v-col cols="9" class="d-flex" v-if="item.check_user"
      >Checked By {{ item.check_user.name }}
      <VueCountUptimer
        :start-time="moment.utc(item.scouted_at).unix()"
        :end-text="'Window Closed'"
        :interval="1000"
        ><template slot="countup" slot-scope="scope">
          <transition
            name="custom-classes"
            enter-active-class="animate__animated animate__heartBeat animate__repeat-2"
            leave-active-class="animate__animated animate__flash animate__faster"
            mode="out-in"
            ><span
              :key="`${item.id}-1-timer-age`"
              v-if="scope.props.minutes < 5"
              class="green--text pl-2 pr-2"
              >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                scope.props.seconds
              }}</span
            >
            <span
              :key="`${item.id}-2-timer-age`"
              v-else
              class="red--text pl-2 pr-2"
              >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                scope.props.seconds
              }}</span
            >
          </transition>
        </template>
      </VueCountUptimer>
      ago.
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
    return {};
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    async checkClick() {
      var request = {
        user_id: this.$store.state.user_id,
      };

      await axios({
        method: "POST",
        url: "/api/checkedat/" + this.item.id,
        data: request,
        withCredentials: true,
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
