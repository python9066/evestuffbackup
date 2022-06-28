<template>
  <v-row no-gutters>
    <v-col cols="auto"
      ><v-card rounded="xl"
        ><v-card-title class="primary">Messages</v-card-title
        ><v-card-text
          ><v-list>
            <v-list-item v-for="item in opInfo.messages" :key="item.id">
              <!-- <v-list-item-icon>
                <v-icon v-if="item.icon" color="pink"> mdi-star </v-icon>
              </v-list-item-icon> -->

              <v-list-item-content>
                <v-list-item-title v-text="item.message"></v-list-item-title>
              </v-list-item-content>

              <v-list-item-avatar>
                <v-img :src="url(item.user.id)"></v-img>
              </v-list-item-avatar>
            </v-list-item> </v-list></v-card-text
      ></v-card>
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
    url(id) {
      return (
        "https://images.evetech.net/characters/" +
        id +
        "/portrait?tenant=tranquility&size=64"
      );
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
  },
  beforeDestroy() {},
};
</script>
