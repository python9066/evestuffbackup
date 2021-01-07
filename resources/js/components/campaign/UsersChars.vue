<template>
    <v-col cols="10">
        <v-card tile min-width="700px">
            <v-card-title class="d-flex justify-space-between align-center ">
                <div>Table of all your saved Charaters</div>
                <v-divider class="mx-4 my-0" vertical></v-divider>
                            <div>
                                <v-menu
                                    :close-on-content-click="false"
                                    :value="addShown"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            text
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="addShown = true"
                                            color="success"
                                            ><v-icon left small
                                                >fas fa-plus</v-icon
                                            >
                                            Char</v-btn
                                        >
                                    </template>
                                    <v-card tile min-height="150px">
                                        <v-card-title class=" pb-0">
                                            <v-text-field
                                                class=" mt-2"
                                                label="Node"
                                                placeholder="Enter Node"
                                                flat
                                                v-mask="'AA##'"
                                                autofocus
                                                v-model="nodeText"
                                                @keyup.enter="addNode()"
                                                @keyup.esc="
                                                    (addShown = false),
                                                        (nodeText = '')
                                                "
                                            ></v-text-field>
                                        </v-card-title>

                                        <v-card-text>
                                            <v-btn
                                                icon
                                                fixed
                                                left
                                                color="success"
                                                @click="addNode()"
                                                ><v-icon
                                                    >fas fa-check</v-icon
                                                ></v-btn
                                            >

                                            <v-btn
                                                fixed
                                                right
                                                icon
                                                color="warning"
                                                @click="
                                                    (addShown = false),
                                                        (nodeText = '')
                                                "
                                                ><v-icon
                                                    >fas fa-times</v-icon
                                                ></v-btn
                                            >
                                        </v-card-text>
                                    </v-card>
                                </v-menu>
                            </div>
            </v-card-title>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="filteredItems"
                    item-key="id"
                    disable-pagination
                    fixed-header
                    hide-default-footer
                    class="elevation-24"

                >
                    <template slot="no-data">
                        You have no saved Chars
                    </template>
                </v-data-table>
            </v-card-text>
            <v-card-actions>
                    <v-btn
                        class="white--text"
                        color="teal"
                        @click="(overlay = false), (addShown = true)"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
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
                { text: "Name", value: "char_name", width: "10%" },
                { text: "Role", value: "role_name" },
                { text: "Ship", value: "ship" },
                { text: "Entosis", value: "link" },
                { text: "", value: "actions", width: "5%", align:"end" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            statusflag: 0,
            toggle_exclusive: 0
        };
    },

    computed: {
        // ...mapState(["campaignusers"]),

        // filteredItems() {
        //     // var timers = this.$store.state.timers;
        //     if (this.statusflag == 1) {
        //         return this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id == 1 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         );
        //     }
        //     if (this.statusflag == 2) {
        //         return this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id == 2 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         );
        //     }
        //     if (this.statusflag == 3) {
        //         return this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id == 3 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         );
        //     }
        //     if (this.statusflag == 4) {
        //         return this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id == 4 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         );
        //     } else {
        //         return this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id != 10 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         );
        //     }
        // },
        // tableHight() {
        //     // var timers = this.$store.state.timers;
        //     if (this.statusflag == 1) {
        //         var count = this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id == 1 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         ).length;
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
        //         var count = this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id == 2 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         ).length;
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
        //         var count = this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id == 3 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         ).length;
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
        //         var count = this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id == 4 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         ).length;
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
        //         var count = this.campaignusers.filter(
        //             campaignusers =>
        //                 campaignusers.role_id != 10 &&
        //                 campaignusers.campaign_id == this.campaign_id
        //         ).length;
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
    }
};
</script>

<style></style>
