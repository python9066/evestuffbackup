<template>
    <v-card
        tile
        max-width="1200px"
        max-height="500px"
        class=" d-flex flex-column"
        color="red"
    >
        <v-card-title
            class=" d-lg-inline-block justify-space-between align-center "
        >
            <div>
                Add a Character to node {{ nodeItem.node }} in
                {{ nodeItem.system_name }}
            </div>
            <div>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </div>
        </v-card-title>
        <v-card-text>
            <v-data-table
                :headers="headers"
                :items="filteredItems"
                item-key="id"
                disable-pagination
                hide-default-footer
                :search="search"
                class="elevation-24"
                dense
            >
                <template v-slot:item.actions="{ item }">
                    <v-btn
                        rounded
                        :outlined="true"
                        x-small
                        color="green"
                        @click="addChar(item)"
                    >
                        <v-icon x-small left dark>
                            fas fa-plus
                        </v-icon>
                        Add
                    </v-btn></template
                >
                <template slot="no-data">
                    No Free Characters
                </template>
            </v-data-table>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions
            ><v-btn class="white--text" color="teal" @click="close()">
                Close
            </v-btn></v-card-actions
        >
    </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        nodeItem: Object
    },
    data() {
        return {
            headers: [
                { text: "Name", value: "char_name" },
                { text: "Main", value: "main_name" },
                { text: "", value: "actions" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            search: ""
        };
    },

    methods: {
        close() {
            this.$emit("closeAdd", "yo");
        },

        addChar(item) {
            var data = {
                id: this.nodeItem.id,
                user_id: item.id,
                site_id: this.$store.state.user_id,
                user_name: item.char_name,
                main_name: item.main_name,
                user_ship: item.ship,
                user_link: item.link
            };

            var request = {
                campaign_user_id: item.id
            };
            this.$store.dispatch("updateCampaignSystem", data);

            data = null;
            data = {
                id: item.id,
                campaign_system_id: this.nodeItem.id,
                node_id: this.nodeItem.node,
                system_id: this.nodeItem.system_id,
                system_name: this.nodeItem.system_name,
                status_id: 4,
                user_status_name: "Hacking"
            };

            var request1 = {
                campaign_system_id: this.nodeItem.id,
                system_id: this.nodeItem.system_id,
                status_id: 4
            };
            this.$store.dispatch("updateCampaignUsers", data);

            axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignsystems/" +
                    this.nodeItem.id +
                    "/" +
                    this.nodeItemcampaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignusers/" +
                    item.id +
                    "/" +
                    this.nodeItemcampaign_id,
                data: request1,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },

    computed: {
        ...mapState(["campaignusers"]),
        filteredItems() {
            return this.campaignusers.filter(
                campaignusers =>
                    campaignusers.role_id == 1 &&
                    campaignusers.campaign_id == this.nodeItem.campaign_id &&
                    campaignusers.node_id == null
            );
        }
    }
};
</script>

<style></style>
