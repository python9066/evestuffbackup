<template>
  <div>
    <v-row no-gutters class="pt-5" justify="space-around">
      <v-col md="10">
        <v-card class="pa-2" title width="100%">
          <v-card-title align="center" class="justify-center">
            <h1>Here you will find your daily tasks</h1>
          </v-card-title>
          <v-card-text align="center" class="justify-center">
            Below you will windows for each task. All you have to do is press
            the button next to each system after completing the task. This is
            just so we know when the task was last done.</v-card-text
          ><v-card-actions align="center" class="justify-center">
            <AddReconTask
              v-if="$can('edit_recon_task')"
            ></AddReconTask> </v-card-actions
        ></v-card>
      </v-col>
    </v-row>
    <v-row no-gutters justify="center" :v-if="taskLoaded == true">
      <ReconTaskTable
        class="px-5 pt-5"
        v-for="(task, index) in tasks"
        :data="task"
        :index="index"
        :key="task.id"
        :size="size"
      >
      </ReconTaskTable>
    </v-row>
  </div>
</template>

<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters, mapState } from "vuex";
import moment from "moment";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `EveStuff - Recon`;
  },
  data() {
    return {
      tasks: [],
      taskLoaded: false,
      taskcount: 0,
    };
  },

  async created() {
    Echo.private("recon").listen("ReconTaskNew", (e) => {
      console.log("New task");
      this.getTasks();
      this.$store.dispatch("getReconTaskSystemsRecords");
    });
  },

  beforeMount() {},

  beforeCreate() {},

  async mounted() {
    this.log();
    await this.getTasks();
    await this.$store.dispatch("getReconTaskSystemsRecords");
  },

  methods: {
    log() {
      var request = {
        url: this.$route.path,
      };

      axios({
        method: "post", //you can set what request you want to be
        url: "api/url",
        data: request,
        headers: {
          Authorization: "Bearer " + this.$store.state.token,
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
    async getTasks() {
      // console.log(id, this.$store.state.token);  dddddd
      let res = await axios({
        method: "get",
        url: "/api/recontask",
        headers: {
          Authorization: "Bearer " + this.$store.state.token,
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      this.tasks = res.data.tasks;
      this.taskLoaded = true;
      this.taskcount = res.data.tasks.length;
    },
  },

  computed: {
    size() {
      if (this.taskcount > 1) {
        return 6;
      } else {
        return 10;
      }
    },
  },

  beforeDestroy() {
    Echo.leave("recon");
  },
};
</script>
