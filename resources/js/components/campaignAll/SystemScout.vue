<template>
    <div class=" d-inline-flex align-items-md-center  pl-4 pb-4">
        <div>
            <span class="d-inline-flex align-items-md-center">
                System Scout:
                <span v-if="CampaignSolaSystem[0]['supervisor_id'] != null">
                    {{ CampaignSolaSystem[0]["supervier_user_name"] }} </span
                ><span v-else> None</span>
            </span>
        </div>
        <div>
            <v-btn
                class=""
                color="blue"
                x-small
                left
                outlined
                @click="checkClick()"
            >
                <v-icon x-small dark>
                    fas fa-plus
                </v-icon>
                Add</v-btn
            >
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
        async scoutAdd() {
            request = {
                supervisor_id: this.$store.state.user_id
            };

            await axios({
                method: "put", //you can set what request you want to be
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

            console.log(timeStamp);
            await this.$store.dispatch("getCampaignSolaSystems");
        },

        async scoutRemove() {
            request = {
                supervisor_id: null
            };

            await axios({
                method: "put", //you can set what request you want to be
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

            console.log(timeStamp);
            await this.$store.dispatch("getCampaignSolaSystems");
        }
    },

    computed: {}
};
</script>

<style></style>
