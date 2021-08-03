<template>
    <v-row no-gutters class="pb-2" justify="space-around">
        <v-col md="10">
            <v-card class="pr-2 pb-2 pl-2" tile width="100%">
                <v-card-title
                    align="center"
                    class="justify-center align-center"
                >
                    <p class=" pt-5">
                        Inital Campaign page for the
                        {{ this.startCampaign.constellation_name }}
                    </p>
                </v-card-title>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
export default {
    props: {
        startCampaignID: Number
    },
    data() {
        return {};
    },

    async created() {
        this.campaignId = this.startCampaignID;
        Echo.private("campaignsystem." + this.startCampaignID).listen(
            "CampaignSystemUpdate",
            e => {
                if (e.flag.flag == 4) {
                    this.$store.dispatch("getCampaigns");
                    this.$store.dispatch("getCampaignSystemsRecords");
                    this.$store.dispatch("getCampaignJoinData");
                }
            }
        );
        this.channel = "campaignsystem." + this.campaignId;
    },

    methods: {
        async leaving() {
            Echo.leave(this.channel);
        },

        loadCampaignSystemRecords() {
            this.$store.dispatch("getCampaignSystemsRecords");
        }
    },

    computed: {
        ...mapGetters([
            "getCampaignById",
            "getActiveCampaigns",
            "getCampaignsCount",
            "getCampaignUsersByUserId",
            "getCampaignUsersByUserIdCount",
            "getTotalNodeCountByCampaign",
            "getHackingNodeCountByCampaign",
            "getRedHackingNodeCountByCampaign"
        ]),

        campaign() {
            return this.getCampaignById(this.startCampaignID);
        }
    },
    beforeDestroy() {
        this.leaving();
    }
};
</script>
