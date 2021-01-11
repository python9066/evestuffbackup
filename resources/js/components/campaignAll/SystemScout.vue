<template>
    <div class=" d-inline-flex align-items-md-center  pl-4 pb-4">
        <div>
            <span class="d-inline-flex align-items-md-center pr-2">
                System Scout:
                <span
                    class="pl-2"
                    v-if="CampaignSolaSystem[0]['supervisor_id'] != null"
                >
                    {{ CampaignSolaSystem[0]["supervier_user_name"] }}
                </span>
            </span>
        </div>
        <div>
            <v-btn
                v-if="CampaignSolaSystem[0]['supervisor_id'] == null"
                class=""
                color="blue"
                x-small
                left
                outlined
                @click="scoutAdd()"
            >
                <v-icon x-small dark>
                    fas fa-plus
                </v-icon>
                Add</v-btn
            >
            <v-icon
                v-if="
                    $can('super') &&
                        CampaignSolaSystem[0]['supervisor_id'] != null
                "
                color="orange darken-3"
                small
                @click="scoutRemove()"
            >
                fas fa-trash-alt
            </v-icon>
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
        return {};
    },

    methods: {
        async scoutAdd() {
            var request = null;
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

            await this.$store.dispatch("getCampaignSolaSystems");
        },

        async scoutRemove() {
            var request = null;
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

            await this.$store.dispatch("getCampaignSolaSystems");
        }
    },

    computed: {}
};
</script>

<style></style>
