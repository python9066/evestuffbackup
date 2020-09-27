<template>
    <v-col cols="10">
        <v-card tile>
            <v-card-title class="d-flex justify-space-between align-center ">
                <div>Table of all Users on this page</div>
            </v-card-title>
            <v-card-text>
                 <!-- :height="tableHight" -->
                <v-data-table
                    :headers="headers"
                    :items="campaignMembers"
                    item-key="id"
                    disable-pagination
                    fixed-header
                    hide-default-footer
                    class="elevation-24"
                    dense
                >
                    <template slot="no-data">
                        No one is here
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </v-col>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        campaign_id: String
    },
    data() {
        return {
            headers: [
                { text: "Name", value: "user_name", width: "80%" },
                { text: "Has Char", value: "check" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            statusflag: 0,
            toggle_exclusive: 0
        };
    },

    async created(){
        await this.userViewTable();
        Echo.private("campaignsystemmembers." + campaign_id).listen(
            "CampaignUsersChanged",
            e => {
                 if(this.$can('view_campaign_members')){
                updateUserViewTable()
            }
            })
    },

    mounted(){},

    methods: {

        userViewTable() {
            if(this.$can('view_campaign_members')){
                this.$store.dispatch('getCampaignMembers',this.campaign_id)
            }

        },

        updateUserViewTable(){
            this.$store.dispatch('getCampaignMembers',this.campaign_id)

        }

    },




    computed: {
        ...mapGetters(["getCampaignMembersByCampagin"]),

        campaignMembers() {

                return this.getCampaignMembersByCampagin(this.campaign_id)
        },


        // tableHight() {
        //     // var timers = this.$store.state.timers;
        //     if (this.statusflag == 1) {
        //         var count = this.getCampaignMembersByCampagin(this.campaign_id).length;
        //         var sum = count *32;
        //         if (sum >= 320) {
        //             return 352;
        //         } else if(count == 1){
        //             return 64
        //         } else if(count == 2){
        //             return 96
        //         }else {
        //             return sum +32;
        //         }
        //     }
        //     if (this.statusflag == 2) {
        //         var count = this.getCampaignMembersByCampagin(this.campaign_id).length;
        //         var sum = count *32;
        //         if (sum >= 320) {
        //             return 352;
        //         } else if(count == 1){
        //             return 64
        //         } else if(count == 2){
        //             return 96
        //         }else {
        //             return sum +32;
        //         }
        //     }
        //     if (this.statusflag == 3) {
        //         var count = this.getCampaignMembersByCampagin(this.campaign_id).length;
        //         var sum = count *32;
        //         if (sum >= 320) {
        //             return 352;
        //         } else if(count == 1){
        //             return 64
        //         } else if(count == 2){
        //             return 96
        //         }else {
        //             return sum +32;
        //         }
        //     }
        //     if (this.statusflag == 4) {
        //         var count = this.getCampaignMembersByCampagin(this.campaign_id).length;
        //         var sum = count *32;
        //         if (sum >= 320) {
        //             return 352;
        //         } else if(count == 1){
        //             return 64
        //         } else if(count == 2){
        //             return 96
        //         }else {
        //             return sum +32;
        //         }
        //     } else {
        //         var count = this.getCampaignMembersByCampagin(this.campaign_id).length;
        //         var sum = count *32;
        //         if (sum >= 320) {
        //             return 352;
        //         } else if(count == 1){
        //             return 64
        //         } else if(count == 2){
        //             return 96
        //         }else {
        //             return sum +32;
        //         }
        //     }
        // }
    },

    beforeDestroy() {
        Echo.leave("campaignsystemmembers." + this.campaignId)
    },

};
</script>

<style></style>
