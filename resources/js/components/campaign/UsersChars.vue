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
                                    <v-row no-gutters>
                                <div>
                                    <v-card class="pa-2" tile width="100%">
                                        <v-form @submit.prevent="newCharForm()">
                                            <v-text-field
                                                v-model="newCharName"
                                                label="Char Name"
                                                required
                                                autofocus
                                                :rules="newNameRules"
                                            ></v-text-field>
                                            <v-select
                                                v-model="newRole"
                                                @change="roleForm($event)"
                                                :rules="newRoleRules"
                                                :items="dropdown_roles"
                                                label="Role"
                                                required
                                            ></v-select>
                                            <v-text-field
                                                v-model="newShip"
                                                :rules="newShipRules"
                                                v-if="this.role == 1"
                                                label="Ship"
                                                required
                                            ></v-text-field>
                                            <v-radio-group
                                                v-model="newLink"
                                                :rules="newLinkRules"
                                                v-if="this.role == 1"
                                                row
                                                label="Entosis Link"
                                                required
                                            >
                                                <v-radio
                                                    label="Tech 1"
                                                    value="1"
                                                ></v-radio>
                                                <v-radio
                                                    label="Tech 2"
                                                    value="2"
                                                ></v-radio>
                                            </v-radio-group>

                                            <v-btn
                                                color="success"
                                                class="mr-4"
                                                type="submit"
                                                >submit</v-btn
                                            >
                                            <v-btn
                                                color="warning"
                                                class="mr-4"
                                                @click="newCharFormClose()"
                                                >Close</v-btn
                                            >
                                            <!-- <v-btn @click="clear">clear</v-btn> -->
                                        </v-form>
                                    </v-card>
                                </div>
                            </v-row>
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
                        @click="close()"
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
                { text: "Name", value: "char_name",},
                { text: "Role", value: "role_name" },
                { text: "Ship", value: "ship" },
                { text: "Entosis", value: "link" },
                { text: "", value: "actions", width: "5%", align:"end" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            statusflag: 0,
            toggle_exclusive: 0,
            newCharName: null,
            newNameRules: [v => !!v || "Name is required"],
            newRole: null,
            newRoleRules: [v => !!v || "You need to pick a role"],
            newShip: null,
            newShipRules: [v => !!v || "Ship is required"],
            newLink: null,
            newLinkRules: [v => !!v || "T1 or T2?"],

            editCharName: null,
            editNameRules: [v => !!v || "Name is required"],
            editRole: null,
            editTextRole: null,
            editRoleRules: [v => !!v || "You need to pick a role"],
            editShip: null,
            editTextShip: null,
            editShipRules: [v => !!v || "Ship is required"],
            editLink: null,
            editTextLink: null,
            editLinkRules: [v => !!v || "T1 or T2?"],
            editUserForm: 1,
            editrole_name: null,
        };
    },

    methods: {

        close(){
            this.$emit('closeAddChar','yo')
        }

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
