<template>
    <div class=" pr-16 pl-16 d-flex justify-center">

<div cols="8">
    <v-card>
        <v-card-title class=" text-center"><h1>Why am I on this page?</h1></v-card-title>
        <v-card-text>
            <span class=" body-1">
        <p>You do not have the required roles to access this page.  If in a fleet let your FC know and they should be able to give you roles</p>
        <p>If the FC can't give you roles have them contact a member of Recon, Coord or GSFOE.</p>
            </span>
        </v-card-text>
    </v-card>
</div>

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
        return {
            //timersAll: [],



        };
    },

    created() {
    },

    async mounted() {},
    methods: {},
    computed: {},
    beforeDestroy() {
    }
};
</script>
