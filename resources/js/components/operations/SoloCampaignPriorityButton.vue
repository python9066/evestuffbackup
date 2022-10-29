<template>
  <div>
    <v-btn icon>
      <font-awesome-icon :icon="text" shake size="xl" />
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
        return "fa-regular fa-bell";
      } else {
        return "fa-solid fa-bell";
      }
    },
  },
};
</script>
