<template>
  <v-dialog
    max-width="1200px"
    v-model="addShown"
    z-index="0"
    content-class="rounded-xl"
    persistent
  >
    <template v-slot:activator="{ on, attrs }">
      <v-badge
        color="warning"
        overlap
        :content="messageCount"
        :value="showMessageCount"
      >
        <v-btn
          fab
          x-small
          v-bind="attrs"
          v-on="on"
          @click="open()"
          class="elevation-10"
          color="green"
          ><font-awesome-icon icon="fa-regular fa-comments" size="xl"
        /></v-btn>
      </v-badge>
    </template>
    <v-row no-gutters>
      <v-col cols="12"
        ><v-card rounded="xl"
          ><v-card-title class="primary"
            ><v-row no-gutters justify="space-between"
              ><v-col cols="auto">Messages</v-col
              ><v-col cols="auto">
                <v-btn
                  fab
                  x-small
                  @click="close()"
                  class="elevation-10"
                  color="red"
                  ><font-awesome-icon
                    icon="fa-solid fa-xmark"
                    size="xl" /></v-btn></v-col></v-row></v-card-title
          ><v-card-text>
            <v-list
              key="dance"
              class="scroll"
              :height="heightList"
              :max-height="heightList"
            >
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
                    <span class="h5"> {{ item.message }}</span>

                    <v-list-item-action-text
                      >{{ textSub(item) }}
                      <v-btn
                        icon
                        color=" warning"
                        @click="deleteMessage(item.id)"
                        ><font-awesome-icon
                          icon="fa-solid fa-trash-can"
                        /> </v-btn
                    ></v-list-item-action-text>
                  </v-list-item-content>
                </v-list-item>
              </transition-group>
            </v-list>
            <v-text-field
              class="pt-5"
              rounded
              clearable
              autofocus
              outlined
              v-model="messageText"
              placeholder="ENTER NOTES HERE"
              @keyup.enter="submitMessage()"
              @keyup.esc="messageText = null"
            ></v-text-field>
            <!-- <v-btn
            v-if="messageText"
            rounded
            class="primary"
            @click="submitMessage()"
            >Submit</v-btn> -->
          </v-card-text></v-card
        >
      </v-col>
    </v-row>
  </v-dialog>
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
    windowSize: Object,
  },
  data() {
    return {
      messageText: null,
      addShown: false,
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

    textMessage(item) {
      return item;
    },

    open() {
      this.$store.dispatch("clearOperationInfoMessageCount");
      this.addShown = true;
    },

    close() {
      this.$store.dispatch("clearOperationInfoMessageCount");
      this.messageText = null;
      this.addShown = false;
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
        return "animate__animated animate__flash animate__faster";
      }
    },

    showLeave() {
      if (this.loaded == true) {
        return "animate__animated animate__flash animate__faster";
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

    messageCount() {
      return this.$store.state.operationInfoMessageCount;
    },

    showMessageCount() {
      if (this.messageCount > 0) {
        return true;
      } else {
        return false;
      }
    },

    heightCard() {
      let num = this.windowSize.y - 232;
      return num;
    },

    heightList() {
      let num = this.windowSize.y - 382;
      return num;
    },
  },
  beforeDestroy() {},
};
</script>

<style>
.scroll {
  overflow-y: scroll;
}
</style>
