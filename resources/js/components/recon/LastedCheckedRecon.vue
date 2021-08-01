<template>
    <div class=" d-inline-flex align-items-md-center ">
        <div>
            <!-- <span :v-if="item.user_id != null" class=" d-inline-flex mb-4"> -->
            <span class=" d-inline-flex mb-4">
                <VueCountUptimer
                    :start-time="moment.utc(item.updated_at).unix()"
                    :end-text="'Window Closed'"
                    :interval="1000"
                    ><template slot="countup" slot-scope="scope">
                        <span class="pl-2 pr-2"
                            >{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                    </template>
                </VueCountUptimer>
            </span>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        item: Array
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

            var data = {
                id: this.CampaignSolaSystem[0]["id"],
                last_checked_user_id: this.$store.state.user_id,
                last_checked_user_name: this.$store.state.user_name,
                last_checked: timeStamp
            };

            this.$store.dispatch("updateCampaignSolaSystem", data);

            var request = null;
            request = {
                last_checked_user_id: this.$store.state.user_id,
                last_checked: timeStamp
            };

            await axios({
                //adds user name of last checked
                method: "put",
                url:
                    "/api/campaignsolasystems/" +
                    this.CampaignSolaSystem[0]["id"] +
                    "/" +
                    this.CampaignSolaSystem[0]["campaign_id"],
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            //------logging start------//
            var request = null;
            request = {
                user_id: this.$store.state.user_id,
                campaign_sola_system_id: this.CampaignSolaSystem[0]["id"]
            };

            await axios({
                method: "put",
                url:
                    "/api/checklastedchecked/" +
                    this.CampaignSolaSystem[0]["campaign_id"],
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            //--------logging end------//

            // console.log(timeStamp);
        }
    },

    computed: {}
};
</script>

<style></style>
