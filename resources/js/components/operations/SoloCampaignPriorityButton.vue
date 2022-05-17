<template>
  <div>
    <v-btn icon>
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

  methods: {},

  computed: {
    text() {
      if (this.item) {
        return "fas fa-bell";
      } else {
        return "far fa-bell";
      }
    },
  },
};
</script>
