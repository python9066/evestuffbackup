<template>
    <div>
        this is a thing
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {},
    data() {
        return {};
    },

    async created() {},

    async mounted() {
        // await this.$store.dispatch("getCampaigns");
        // await this.$store.dispatch("getMultiCampaigns");
        await this.$store.dispatch(
            "getStartCampaignJoinDataByCampaign",
            this.$route.params.id
        );
    },

    methods: {},

    computed: {
        ...mapGetters(["getStartCampaignById"])

        // startCampaigns() {
        //     return this.getStartCampaignById(this.campaignId);
        // }
    },

    beforeDestroy() {}
};
</script>

<style></style>
