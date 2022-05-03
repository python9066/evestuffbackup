<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-list>
        <v-list-item v-for="(node, index) in item.new_nodes" :key="index">
          <v-list-item-title>{{ node.name }}</v-list-item-title>
          <v-list-item-action>
            <v-btn @click="removenode(node)">Delete</v-btn>
          </v-list-item-action>
        </v-list-item>
      </v-list>
    </v-col>
  </v-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    item: Object,
    operationID: Number,
  },
  data() {
    return {};
  },

  methods: {
    async removenode(item) {
      var id = item.id;

      await axios({
        method: "DELETE",
        url: "/api/deletenode/" + id,
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
    ...mapState([]),

    ...mapGetters([]),
  },
};
</script>

<style>
.style-4 {
  background-color: rgba(255, 153, 0, 0.199);
}
.style-3 {
  background-color: rgb(255, 172, 77);
}
.style-2 {
  background-color: rgb(30, 30, 30, 1);
}
.style-1 {
  background-color: rgb(184, 22, 35);
}
</style>
