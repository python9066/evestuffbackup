<template>
  <div>
    <v-menu transition="slide-y-transition" bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn class="purple" color="primary" dark v-bind="attrs" v-on="on">
          Slide Y Transition
        </v-btn>
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
  data() {},

  methods: {
    test(n) {
      console.log(n);
    },
  },

  computed: {},

  beforeDestroy() {},
};
</script>

<style></style>


