<template>
  <v-col cols="12"> {{ text }}</v-col>
</template>

<script>
import moment from "moment";
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
  props: { windowSize: Object, item: [Object, Array] },
  data() {
    return {};
  },

  methods: {},

  computed: {
    text() {
      if (this.item.log_name == "System Node") {
        if (this.item.event == "deleted") {
          return (
            "Deleted Node " +
            this.item.properties.old.name +
            " from system " +
            this.item.properties.old["system.system_name"]
          );
        }
        if (this.item.event == "created") {
          return (
            "Created Node " +
            this.item.properties.attributes.name +
            " in system " +
            this.item.properties.attributes["system.system_name"]
          );
        }

        if (this.item.event == "updated") {
          var t1 = null;
          if (
            this.item.properties.attributes.node_status !=
            this.item.properties.old.node_status
          ) {
            t1 =
              "Node status changed from " +
              this.item.properties.old["nodeStatus.name"] +
              " to " +
              this.item.properties.attributes["nodeStatus.name"];
          }

          return t1;
        }
      }
    },
  },
};
</script>

<style></style>
