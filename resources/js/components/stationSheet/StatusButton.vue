<template>
  <div>
    <v-menu transition="slide-y-transition" bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-chip pill :color="pillColor(item)" v-bind="attrs" v-on="on">
          {{ buttontext(item) }}
        </v-chip>
      </template>
      <v-list>
        <v-list-item><v-chip pill @click="test(1)"> One </v-chip></v-list-item>
        <v-list-item><v-chip pill @click="test(2)"> Two </v-chip></v-list-item>
        <v-list-item
          ><v-chip pill @click="test(3)"> Three </v-chip></v-list-item
        >
        <v-list-item><v-chip pill @click="test(4)"> Four </v-chip></v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { EventBus } from "../../app";
import moment from "moment";

export default {
  props: {
    item: Object,
  },

  async created() {},
  data() {
    return {};
  },

  methods: {
    test(n) {
      console.log(n);
    },

    buttontext() {
      var ret = this.item.status.name.replace("Upcoming - ", "");
      return ret;
    },

    pillColor() {
      if (this.item.status.id == 4) {
        return "orange darken-1";
      }
      if (this.item.status.id == 18) {
        return "brown lighten-2";
      }
      if (this.item.status.id == 16) {
        return "green";
      }
      if (this.item.status.id == 7) {
        return "red";
      }
    },
  },

  computed: {},

  beforeDestroy() {},
};
</script>

<style></style>


