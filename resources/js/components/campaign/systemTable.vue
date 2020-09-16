<template>
    <v-col cols="6" align-self="stretch">
        <v-card tile height="100%">
            <v-card-title>
                INFO GOS HERE {{ this.system_name }}
                <v-menu transition="fade-transition">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn dark color="primary" v-bind="attrs" v-on="on">
                            On the Way
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item
                            v-for="(list, index) in chars"
                            :key="index"
                            @click="(charOnTheWay = list.id), clickOnTheWay()"
                        >
                            <v-list-item-title>{{
                                list.char_name
                            }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <v-menu transition="fade-transition">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn dark color="primary" v-bind="attrs" v-on="on">
                            Ready to go
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item
                            v-for="(list, index) in chars"
                            :key="index"
                            @click="(charReadyToGo = list.id), clickReadyToGo()"
                        >
                            <v-list-item-title>{{
                                list.char_name
                            }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>

                <v-menu :close-on-content-click="false" :value="addShown">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" @click="addShown= true" color="success"
                            ><v-icon>fas fa-plus</v-icon></v-btn
                        >
                    </template>
                    <v-card
                    tile
                    min-height="150px"
                    >
                        <v-card-title class=" pb-0">
                            <v-text-field
                                class=" mt-2"
                                label="Node"
                                placeholder="Enter Node"
                                flat
                                v-model="nodeText"
                            ></v-text-field>
                        </v-card-title>

                        <v-card-text>
                            <v-btn

                            icon
                            fixed
                            left
                            color="success"
                            @click="addNode()"
                                ><v-icon>fas fa-check</v-icon></v-btn
                            >

                            <v-btn

                            fixed
                            right
                            icon
                            color="warning"
                            @click="addShown = false, nodeText ='' "
                                ><v-icon>fas fa-times</v-icon></v-btn
                            >
                        </v-card-text>
                    </v-card>
                </v-menu>
            </v-card-title>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="filteredItems"
                    item-key="id"
                    :items-per-page="10"
                    :sort-desc="[true, false]"
                    multi-sort
                    hide-default-footer
                    disable-pagination
                    class="elevation-12"
                >
                    >

                    <template slot="no-data">
                        No nodes have showen up here..... yet!!!!
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
export default {
    props: {
        system_name: String,
        system_id: Number,
        campaign_id: String
    },
    data() {
        return {
            headers: [
                { text: "System", value: "system_name", width: "10%" },
                { text: "NodeID", value: "node" },
                { text: "Pilot", value: "pilot_name" },
                { text: "Status", value: "status_name" },
                { text: "Finished", value: "count" },
                { text: "Notes", value: "notes" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            charOnTheWay: 0,
            charReadyToGo: 0,
            nodeText: "",
            addShown:false,
        };
    },

    methods: {
        clickOnTheWay() {
            var item = {
                id: this.charOnTheWay,
                status_id: 1,
                status: "On the way",
                system_id: this.system_id,
                system_name: this.system_name
            };

            this.$store.dispatch("updateCampaignUsers", item);
            var request = {
                status_id: 1,
                system_id: this.system_id
            };

            axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignusers/" + this.charOnTheWay,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },
        clickReadyToGo() {
            var item = {
                id: this.charReadyToGo,
                status_id: 2,
                status: "Ready To Go",
                system_id: this.system_id,
                system_name: this.system_name
            };

            this.$store.dispatch("updateCampaignUsers", item);
            var request = {
                status_id: 2,
                system_id: this.system_id
            };

            axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignusers/" + this.charReadyToGo,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

      async addNode(){

            var request ={
                campaigan_id: this.campaign_id,
                system_id: this.system_id,
                node_id: this.nodeText
            }
            this.nodeText = ""
            this.addShown = false
           await axios({
                method: "POST", //you can set what request you want to be
                url: "/api/campaignsystems",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            })

            this.$store.dispatch('getCampaignSystemsRecrods');


        }
    },

    computed: {
        ...mapState(["campaignsystems"]),

        ...mapGetters(["getCampaignUsersByUserIdEntosis"]),

        filteredItems() {
            // var timers = this.$store.state.timers;
            if (this.statusflag == 1) {
                return this.campaignsystems.filter(
                    s => s.status_id == 1 && s.system_id == this.system_id
                );
            }
            if (this.statusflag == 3) {
                return this.campaignsystems.filter(
                    s => s.status_id == 3 && s.system_id == this.system_id
                );
            }
            if (this.statusflag == 5) {
                return this.campaignsystems.filter(
                    s => s.status_id == 5 && s.system_id == this.system_id
                );
            }
            if (this.statusflag == 6) {
                return this.campaignsystems.filter(
                    s => s.status_id == 6 && s.system_id == this.system_id
                );
            } else {
                return this.campaignsystems.filter(
                    s => s.status_id != 10 && s.system_id == this.system_id
                );
            }
        },

        chars() {
            return this.getCampaignUsersByUserIdEntosis(
                this.$store.state.user_id
            );
        },


    }
};
</script>

<style></style>
