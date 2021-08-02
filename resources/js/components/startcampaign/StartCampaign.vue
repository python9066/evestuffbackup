<template>
    <div class=" d-flex align-items-center">
        <v-card-title>Campaigns</v-card-title>

        <v-btn
            :loading="loadingf"
            :disabled="loadingf"
            @click="overlay = !overlay"
            color="light-blue darken-4"
        >
            ADD INITIAL CAMPAIGN
        </v-btn>
    </div>
</template>
<script>
import Axios from "axios";
import moment, { now, unix, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

export default {
    data() {
        return {};
    },

    created() {},

    async mounted() {},
    methods: {},
    computed: {},
    beforeDestroy() {}
};
</script>
