<template>
  <v-row no-gutters>
    <v-col cols="auto"
      ><v-card rounded="xl"
        ><v-card-title class="primary">Messages</v-card-title
        ><v-card-text>
          <v-list key="dance">
            <transition-group
              mode="out-in"
              :enter-active-class="showEnter"
              :leave-active-class="showLeave"
            >
              <v-list-item v-for="item in opInfo.messages" :key="item.id">
                <v-list-item-avatar>
                  <v-img :src="url(item.user.eve_user_id)"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                  {{ item.message }}
                  <v-list-item-action-text
                    >{{ textSub(item) }}
                    <v-btn icon color=" warning" @click="deleteMessage(item.id)"
                      ><font-awesome-icon
                        icon="fa-solid fa-trash-can"
                      /> </v-btn
                  ></v-list-item-action-text>
                </v-list-item-content>
              </v-list-item>
            </transition-group>
          </v-list>
          <v-textarea
            rounded
            clearable
            outlined
            v-model="messageText"
            placeholder="ENTER NOTES HERE"
          ></v-textarea></v-card-text
        ><v-card-actions v-if="messageText"
          ><v-btn rounded class="primary" @click="submitMessage()"
            >Submit</v-btn
          ></v-card-actions
        ></v-card
      >
    </v-col>
  </v-row>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../../app";
// import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {},
  props: {
    loaded: Boolean,
  },
  data() {
    return {
      messageText: null,
    };
  },

  async created() {},

  beforeMonunt() {},

  async beforeCreate() {},

  async mounted() {},
  methods: {
    url(id) {
      return (
        "https://images.evetech.net/characters/" +
        id +
        "/portrait?tenant=tranquility&size=64"
      );
    },

    textSub(item) {
      var name = item.user.name;
      var time = moment(item.created_at).format("YYYY-MM-DD HH:mm:ss");
      return name + " " + time;
    },

    async submitMessage() {
      var request = { message: this.messageText };
      await axios({
        method: "put", //you can set what request you want to be
        url: "/api/operationinfopagemessage/" + this.opInfo.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((this.messageText = null));
    },

    async deleteMessage(id) {
      await axios({
        method: "delete", //you can set what request you want to be
        url: "/api/operationinfopagemessage/" + id,
        withCredentials: true,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then((this.messageText = null));
    },
  },

  computed: {
    ...mapGetters([]),

    ...mapState["operationInfoPage"],

    showEnter() {
      if (this.loaded == true) {
        return "animate__animated animate__bounceInRight animate__faster";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__bounceOutLeft animate__faster";
      }
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
