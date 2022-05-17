<template>
  <div>
    <v-btn icon @click="click()">
      <v-icon>{{ text }}</v-icon>
    </v-btn>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    item: Object,
  },
  data() {
    return {};
  },

  methods: {
    click() {
      if (this.item.priority == 0) {
        var num = 1;
      } else {
        var num = 0;
      }
      this.item.priority = num;
      var request = {
        priority: num,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/campaignpriority/" + this.item.id,
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
    text() {
      if (this.item.priority == 0) {
        return "fas fa-bell";
      } else {
        return "far fa-bell";
      }
    },
  },
};
</script>
