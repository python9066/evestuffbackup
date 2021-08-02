<template>
    <div class=" pr-16 pl-16">
        <messageComponent></messageComponent>
        <MultiCampaigns></MultiCampaigns>
        <StartCampaign></StartCampaign>
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

    created() {
        this.$store.dispatch("getConstellationList");
    },

    async mounted() {},
    methods: {},
    computed: {},
    beforeDestroy() {}
};
</script>
