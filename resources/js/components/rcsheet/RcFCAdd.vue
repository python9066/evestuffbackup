<template>
    <div>
        <v-dialog
            v-model="overlay"
            max-width="500px"
            z-index="0"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    class="mr-4"
                    color="green lighten-1"
                    v-bind="attrs"
                    x-small
                    v-on="on"
                    >FC</v-btn
                >
            </template>

            <v-card tile max-width="500px" min-height="200px">
                <v-card-title
                    class="d-flex justify-space-between align-center "
                >
                    <div>Table of FCs</div>
                    <div>
                        <v-menu
                            :close-on-content-click="false"
                            :value="addShown"
                            transition="fab-transition"
                            origin="100% -30%"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    text
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="addShown = true"
                                    color="success"
                                    ><v-icon left small>fas fa-plus</v-icon>
                                    FC</v-btn
                                >
                            </template>
                            <v-row no-gutters>
                                <div>
                                    <v-card class="pa-2" tile width="100%">
                                        <v-form
                                            @submit.prevent="newFCFormClose()"
                                        >
                                            <v-text-field
                                                v-model="newCharName"
                                                label="FC Name"
                                                required
                                                autofocus
                                            ></v-text-field>

                                            <v-btn
                                                color="success"
                                                class="mr-4"
                                                type="submit"
                                                >submit</v-btn
                                            >
                                            <v-btn
                                                color="warning"
                                                class="mr-4"
                                                @click="
                                                    (addShown = false),
                                                        (this.newCharName = null)
                                                "
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
                            No FCs
                        </template>
                        <!-- :color="pillColor(item)" -->
                        <template v-slot:[`item.addRemove`]="{ item }">
                            <span>
                                <v-btn
                                    rounded
                                    :outlined="true"
                                    x-small
                                    @click="pillClick(item)"
                                >
                                    ADD
                                </v-btn>
                                <v-icon
                                    rounded
                                    :outlined="true"
                                    x-small
                                    @click="pillDelete(item)"
                                >
                                    fas fa-trash-alt
                                </v-icon>
                            </span>
                        </template>
                    </v-data-table>
                </v-card-text>
                <v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        station: Object
    },
    data() {
        return {
            headers: [
                { text: "Name", value: "name" },
                { text: "", value: "addRemove", align: "end" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            newCharName: null,

            addShown: false,
            overlay: false
        };
    },

    methods: {
        close() {
            this.overlay = false;
        },

        newFCFormClose() {
            this.addShown = false;
            this.newFCName = null;
        },

        async pillClick(item) {},

        async pilldelete(item) {},

        async newFCForm() {
            var request = {
                char_name: this.newCharName
            };

            await axios({
                method: "POST",
                url: "/api/campaignusers/" + this.campaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            //------logging Start-----//
            request = null;
            request = {
                user_id: this.$store.state.user_id,
                type: "added",
                char_name: this.newCharName
            };
        },

        async removeChar(item) {
            this.$store.dispatch("deleteUsersChars", item.id);
            this.$store.dispatch("deleteCampaignUser", item.id);
            await axios({
                method: "DELETE",
                url:
                    "/api/campaignusers/" +
                    item.id +
                    "/" +
                    this.campaign_id +
                    "/" +
                    this.$store.state.user_id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.$store.dispatch("getCampaignSystemsRecords");
        }
    },

    computed: {
        ...mapState(["rcfcs"]),
        filteredItems() {
            return this.rcfcs;
        }
    }
};
</script>

<style></style>
