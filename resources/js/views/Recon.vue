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
    date() {},

    created() {},

    beforeMount() {},

    beforeCreate() {},

    mounted() {},

    methods: {},

    computed: {},

    beforeDestroy() {}
};
</script>
