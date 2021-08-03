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
                        {{ this.startCampaign }}
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

    async created() {},

    methods: {
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

        startCampaign() {
            return this.getCampaignById(this.startCampaignID);
        }
    },
    beforeDestroy() {
        this.leaving();
    }
};
</script>
