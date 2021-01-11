<template>
    <div class=" d-inline-flex">
        <div v-if="$can('super')">
            <v-btn class="mr-4 ml-4" color="green" small @click="checkClick()">
                <v-icon small left dark>
                    fas fa-search-location
                </v-icon>
                System Checked</v-btn
            >
        </div>
        <div class=" d-inline-flex">
            <span :v-if="showCounter()">
                Checked by {{ CampaignSolaSystem[0]["last_checked_user_name"] }}
                <VueCountUptimer
                    :start-time="
                        moment.utc(CampaignSolaSystem[0]['last_checked']).unix()
                    "
                    :end-text="'Window Closed'"
                    :interval="1000"
                    ><template slot="countup" slot-scope="scope"
                        ><span
                            v-if="scope.props.minutes < 5"
                            class="green--text pl-3"
                            >{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                        <span v-else class="red--text pl-3"
                            >{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                    </template>
                </VueCountUptimer>
                ago
            </span>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        CampaignSolaSystem: Array
    },
    data() {
        return {
            test1: ""
        };
    },

    methods: {
        async checkClick() {
            this.test1 = this.CampaignSolaSystem[0]["id"];
            var timeStamp = moment.utc().format("YYYY-MM-DD HH:mm:ss");

            var request = null;
            request = {
                last_checked_user_id: this.$store.state.user_id,
                last_checked: timeStamp
            };

            await axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignsolasystems/" +
                    this.CampaignSolaSystem[0]["id"],
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            console.log(timeStamp);
            await this.$store.dispatch("getCampaignSolaSystems");
        },
        showCounter() {
            if (this.CampaignSolaSystem[0]["last_checked_user_name"] == null) {
                return false;
            } else {
                return true;
            }
        }
    },

    computed: {}
};
</script>

<style></style>
