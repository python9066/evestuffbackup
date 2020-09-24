<template>
    <div class=" pr-16 pl-16 d-flex justify-center">

<div cols="8">
    <v-card>
        <v-card-title elevation=10><h1> Why am I on this page? </h1></v-card-title>
        <v-card-text>

        <body-1>You do not have the required roles to access this page.  If in a fleet let your FC know and they should be able to give you roles</body-1>
        <body-1>If the FC can't give you roles have them contact a member of Recon, Coord or GSFOE.</body-1>

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
