<template>
    <div>
        <v-row no-gutters class="pb-2" justify="space-around">
            <v-col md="10">
                <v-card class="pr-2 pb-2 pl-2" tile width="100%">
                    <v-card-title
                        align="center"
                        class="justify-center align-center"
                    >
                        <p class=" pt-5">
                            Campaign
                            {{ this.startcampaign[0]["name"] }}
                        </p>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <StartSystemTable
                class=" px-5 pt-5"
                v-for="(startcampaignjoin, index) in startcampaignjoins"
                :data="startcampaignjoin"
                :size="size"
                :index="index"
                :key="startcampaignjoin.id"
                @openSolaLog="openSolaLog($event)"
            >
            </StartSystemTable>
        </v-row>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {},
    async created() {
        this.campaignId = this.$route.params.id;
        this.campaign_id = parseInt(this.$route.params.id);
        Echo.private("startcampaignsystem." + this.$route.params.id).listen(
            "StartCampaignSystemUpdate",
            e => {
                if (e.flag.message != null) {
                    this.$store.dispatch(
                        "updateStartCampaignSystem",
                        e.flag.message
                    );
                }
            }
        );

        await this.$store.dispatch("getStartCampaigns");
        await this.$store.dispatch("getStartCampaignJoinData");
    },
    data() {
        return {
            campaignId: 0,
            campaign_id: ""
        };
    },

    async mounted() {},

    methods: {},

    computed: {
        ...mapGetters(["getStartCampaignsById", "getStartCampaignById"]),

        startcampaign() {
            return this.getStartCampaignsById(this.campaign_id);
        },

        startcampaignjoins() {
            return this.getStartCampaignById(this.campaign_id);
        },

        size() {
            let count = this.getStartCampaignById(this.campaign_id).length;
            if (count > 1) {
                return 6;
            } else {
                return 10;
            }
        }
    },

    beforeDestroy() {
        Echo.leave("startcampaignsystem." + this.$route.params.id);
    }
};
</script>

<style></style>
