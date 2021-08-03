<template>
    <div>
        <span v-for="(StartCampaign, index) in StartCampaigns" :key="index">
            <TitleBar
                :sCampaignID="sCampaign.campaign_id"
                :sCampaign="sCampaigns"
            >
            </TitleBar>
        </span>
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

    async mounted() {},

    async created() {
        this.campaignId = this.$route.params.id;
        this.campaign_id = parseInt(this.$route.params.id);
    },

    methods: {},

    computed: {
        ...mapGetters([
            "getsCampaignById",
            "getActiveCampaigns",
            "getCampaignsCount",
            "getCampaignUsersByUserId",
            "getCampaignUsersByUserIdCount",
            "getTotalNodeCountByMultiCampaign",
            "getHackingNodeCountByMultiCampaign",
            "getRedHackingNodeCountByMultiCampaign",
            "getMultiCampaignName"
        ]),

        sCampaigns() {
            return this.getsCampaignById(this.campaignId);
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
