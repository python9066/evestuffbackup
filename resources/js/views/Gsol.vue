<template>
    <div class=" pr-16 pl-16">
        <AmmoRequestTable v-if="$can('view_ammo_requests')"></AmmoRequestTable>
    </div>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
import ApiL from "../service/apil";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
export default {
    title() {
        return `EVE`;
    },
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
