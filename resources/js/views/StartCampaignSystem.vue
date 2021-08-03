<template>
    <div>
        things
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
        <v-row no-gutters justify="center" :v-if="systemLoaded == true">
            <StartSystemTable
                class=" px-5 pt-5"
                v-for="(system, index) in systems"
                :system_name="system.system_name"
                :constellation_name="system.constellation_name"
                :system_id="system.id"
                :campaign_id="campaignId"
                :constellation_id="system.constellation_id"
                :index="index"
                :key="system.id"
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
    data() {
        return {
            campaignId: 0,
            campaign_id: ""
        };
    },

    async mounted() {},

    async created() {
        this.campaignId = this.$route.params.id;
        this.campaign_id = parseInt(this.$route.params.id);
        this.$store.dispatch("getStartCampaigns");
        this.$store.dispatch("getStartCampaignJoinData");
    },

    methods: {},

    computed: {
        ...mapGetters(["getStartCampaignsById", "getStartCampaignById"]),

        startcampaign() {
            return this.getStartCampaignsById(this.campaign_id);
        },

        startcampaignjoin() {
            return this.getStartCampaignById(this.campaign_id);
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
