<template>
    <v-row no-gutters v-if="this.getCampaignsCount > 1" justify="space-around">
        <v-col md="10">
            <v-card class="pa-2 d-inline-flex full-width align-content-center" tile width="100%">
                <div>
                   <p> {{ this.campaign.item_name }} in
                    {{ this.campaign.system }} - {{ this.campaign.ticker }}</p>

                    <!-- <div>

                    </div> -->
                    <v-icon
                        v-if="
                            this.campaign.defenders_score >
                                this.campaign.defenders_score_old &&
                                this.campaign.defenders_score_old > 0
                        "
                        small
                        left
                        dark
                        color="blue darken-4"
                    >
                        fas fa-arrow-alt-circle-up
                    </v-icon>
                    <v-icon
                        v-if="
                            this.campaign.defenders_score <
                                this.campaign.defenders_score_old &&
                                this.campaign.defenders_score_old > 0
                        "
                        small
                        left
                        dark
                        color="blue darken-4"
                    >
                        fas fa-arrow-alt-circle-down
                    </v-icon>
                    <v-icon
                        v-if="
                            this.campaign.defenders_score ==
                                this.campaign.defenders_score_old ||
                                this.campaign.defenders_score_old === null
                        "
                        small
                        left
                        dark
                        color="grey darken-3"
                    >
                        fas fa-minus-circle
                    </v-icon>

                    <v-progress-linear
                        :color="this.barColor"
                        :value="this.barScoure"
                        height="20"
                        rounded
                        :active="this.barActive"
                        :reverse="this.barReverse"
                        :background-color="this.barBgcolor"
                        background-opacity="0.2"
                    >
                        <strong>
                            {{ this.campaign.defenders_score * 100 }} /
                            {{ this.campaign.attackers_score * 100 }}
                        </strong>
                    </v-progress-linear>

                    <v-icon
                        v-if="
                            this.campaign.attackers_score >
                                this.campaign.attackers_score_old &&
                                this.campaign.attackers_score_old > 0
                        "
                        small
                        right
                        dark
                        color="red darken-4"
                    >
                        fas fa-arrow-alt-circle-up
                    </v-icon>
                    <v-icon
                        v-if="
                            this.campaign.attackers_score <
                                this.campaign.attackers_score_old &&
                                this.campaign.attackers_score_old > 0
                        "
                        small
                        right
                        dark
                        color="red darken-4"
                    >
                        fas fa-arrow-alt-circle-down
                    </v-icon>
                    <v-icon
                        v-if="
                            this.campaign.attackers_score ==
                                this.campaign.attackers_score_old ||
                                this.campaign.attackers_score_old == null
                        "
                        small
                        right
                        dark
                        color="grey darken-3"
                    >
                        fas fa-minus-circle
                    </v-icon>
                </div>
                <div
                    class="d-flex full-width align-content-center"
                    v-if="this.campaign.status_id == 1"
                >
                    <CountDowntimer
                        :start-time="moment.utc(this.campaign.start).unix()"
                        :end-text="'Campaign Started'"
                        :interval="1000"
                        @campaignStart="campaignStart()"
                    >
                        <template slot="countdown" slot-scope="scope">
                            <span class="red--text pl-3 text-h3 justify-content"
                                >{{ scope.props.minutes }}:{{
                                    scope.props.seconds
                                }}</span
                            >
                        </template>
                    </CountDowntimer>
                </div>
            </v-card>
            <div
                v-if="campaign.total_node > 0"
                class=" ml-auto d-inline-flex align-center"
            >
                <v-divider class="mx-4 my-0" vertical></v-divider>
                <p class=" pt-4 pr-3">Finished Nodes -</p>
                <v-progress-circular
                    class=" pr-3"
                    :transitionDuration="5000"
                    :radius="25"
                    :strokeWidth="5"
                    :value="
                        (campaign.b_node / campaign.total_node) * 100 ||
                            0.000001
                    "
                >
                    <div class="caption">
                        {{ campaign.b_node }} /
                        {{ campaign.total_node }}
                    </div></v-progress-circular
                >
                <v-progress-circular
                    class=" pr-3"
                    :transitionDuration="5000"
                    :radius="25"
                    :strokeWidth="5"
                    strokeColor="#FF3D00"
                    :value="
                        (campaign.r_node / campaign.total_node) * 100 ||
                            0.000001
                    "
                >
                    <div class="caption">
                        {{ campaign.r_node }} /
                        {{ campaign.total_node }}
                    </div></v-progress-circular
                >
            </div>
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
        sCampaignID: Number
    },
    data() {
        return {};
    },

    created() {},

    methods: {},

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
        barColor() {
            var d =
                this.getCampaignById(this.sCampaignID).defenders_score * 100;
            if (d > 50) {
                return "blue darken-4";
            }

            return "red darken-4";
        },

        barScoure() {
            var d =
                this.getCampaignById(this.sCampaignID).defenders_score * 100;
            var a =
                this.getCampaignById(this.sCampaignID).attackers_score * 100;

            if (d > 50) {
                return d;
            }

            return a;
        },

        barActive() {
            if (this.getCampaignById(this.sCampaignID).status_id > 1) {
                return true;
            }
            return false;
        },

        barBgcolor() {
            var d =
                this.getCampaignById(this.sCampaignID).defenders_score * 100;
            var a =
                this.getCampaignById(this.sCampaignID).attackers_score * 100;

            if (d > 50) {
                return "red darken-4";
            }

            return "blue darken-4";
        },

        barReverse() {
            var d =
                this.getCampaignById(this.sCampaignID).defenders_score * 100;
            if (d > 50) {
                return false;
            }

            return true;
        },

        campaign() {
            return this.getCampaignById(this.sCampaignID);
        }
    }
};
</script>
