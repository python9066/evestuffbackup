<template>
    <div>
        <v-row no-gutters class="pt-5" justify="space-around">
            <v-col md="10">
                <v-card class="pa-2" title width="100%">
                    <v-card-title align="center" class="justify-center">
                        <h1>
                            Here you will find your daily tasks
                        </h1>
                    </v-card-title>
                    <v-card-text align="center" class="justify-center">
                        Below you will windows for each task. All you have to do
                        is press the button next to each system after completing
                        the task. This is just so we know when the task was last
                        done.</v-card-text
                    ><v-card-actions align="center" class="justify-center">
                        <AddReconTask
                            v-if="$can('super')"
                        ></AddReconTask> </v-card-actions
                ></v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center" :v-if="taskLoaded == true">
            <ReconTaskTable
                class=" px-5 pt-5"
                v-for="(task, index) in tasks"
                :created_at="task.created_at"
                :id="task.id"
                :edited_by_user_id="task.edited_by_user_id"
                :info="task.info"
                :made_by_user_id="task.made_by_user_id"
                :title="task.title"
                :updated_at="task.updated_at"
                :index="index"
                :key="task.id"
                :size="6"
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
    return new Promise(resolve => setTimeout(resolve, ms));
}

export default {
    data() {
        return {
            tasks: [],
            taskLoaded: false
        };
    },

    async created() {
        Echo.private("recon").listen("ReconTimerUpdate", e => {
            this.updateTasks();
        });
    },

    beforeMount() {},

    beforeCreate() {},

    async mounted() {
        await this.getTasks();
        await this.$store.dispatch("getReconTaskSystemsRecords");
    },

    methods: {
        async getTasks() {
            // console.log(id, this.$store.state.token);
            let res = await axios({
                method: "get",
                url: "/api/recontask",
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.tasks = res.data.tasks;
            this.taskLoaded = true;
        },

        async updateTasks() {
            await this.getTasks();
            await this.$store.dispatch("getReconTaskSystemsRecords");
        }
    },

    computed: {
        size() {
            return 12;
        }
    },

    beforeDestroy() {
        Echo.leave("recon");
    }
};
</script>
