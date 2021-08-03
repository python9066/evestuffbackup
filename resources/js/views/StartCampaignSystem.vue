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
        return {
            campaignId: 0,
            campaign_id: ""
        };
    },

    async created() {
        this.campaignId = this.$route.params.id;
        this.campaign_id = parseInt(this.$route.params.id);
    },

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
        ...mapGetters(["getStartCampaignById"]),

        startCampaigns() {
            return this.getStartCampaignById(this.campaignId);
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
