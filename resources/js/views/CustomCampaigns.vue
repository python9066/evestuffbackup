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
    title() {
        return `EveStuff - MultiCampaigns`;
    },
    data() {
        return {};
    },

    created() {
        this.$store.dispatch("getConstellationList");
    },

    async mounted() {
        this.log();
    },
    methods: {
        log() {
            var request = {
                url: this.$route.path
            };

            axios({
                method: "post", //you can set what request you want to be
                url: "api/url",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },
    computed: {},
    beforeDestroy() {}
};
</script>
