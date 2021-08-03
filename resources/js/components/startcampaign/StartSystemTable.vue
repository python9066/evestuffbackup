<template>
    <v-col :cols="size" align-self="stretch">
        <v-card tile height="100%" class="d-flex flex-column">
            <v-card-text>
                <template>
                    <v-card flat max-width elevation="24" color="grey darken-4">
                        <v-card-title
                            max-width
                            class="d-flex justify-space-between align-center"
                            style=" width: 100%;"
                        >
                            <div>{{ data.constellation_name }}</div>
                        </v-card-title>
                    </v-card>
                </template>
                <v-data-table
                    :headers="headers"
                    :items="filteredItems"
                    item-key="node"
                    hide-default-footer
                    disable-pagination
                    class="elevation-12"
                >
                    <template v-slot:[`item.updated_at`]="{ item }"
                        ><span v-if="item.user_id != null">{{
                            data.last_edit
                        }}</span>
                        <span v-else> N/A </span>
                    </template>

                    <template v-slot:[`item.main_name`]="{ item }">
                        <div class=" d-inline-flex align-items-center">
                            <div v-if="item.site_id != null">
                                {{ item.main_name }}
                                <v-icon
                                    color="orange darken-3"
                                    small
                                    @click="clickremovechar(item)"
                                >
                                    fas fa-trash-alt
                                </v-icon>
                            </div>
                            <div v-else>
                                <v-btn
                                    pill
                                    outlined
                                    rounded
                                    @click="clickaddchar(item)"
                                    small
                                    color="light-green accent-3"
                                >
                                    Add
                                </v-btn>
                            </div>
                        </div>
                    </template>

                    <template slot="no-data">
                        No nodes have shown up here..... yet!!!!
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        data: Object,
        size: Number
    },
    data() {
        return {
            campaignId: 0,
            campaign_id: "",

            headers: [
                { text: "System", value: "system_name", width: "10%" },
                { text: "Pilot", value: "main_name", align: "start" },

                {
                    text: "",
                    value: "actions",
                    sortable: false,
                    align: "end",
                    width: "5%"
                }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ]
        };
    },

    async mounted() {
        this.$store.dispatch("getStartCampaignSystemsRecords");
    },

    async created() {},

    methods: {
        async clickaddchar(item) {
            var data = {
                id: item.id,
                main_name: this.$store.state.user_name,
                site_id: this.$store.state.user_id
            };

            var request = {
                user_id: this.$store.state.user_id,
                sys: item.system_id
            };

            this.$store.dispatch("updateStartCampaignSystem", data);

            await axios({
                method: "put",
                url:
                    "/api/startcampaignsystemupdate/" +
                    item.id +
                    "/" +
                    data.start_campaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async clickremovechar(item) {
            var data = {
                id: item.id,
                main_name: null,
                site_id: null
            };
            this.$store.dispatch("updateStartCampaignSystem", data);

            await axios({
                method: "delete",
                url:
                    "/api/startcampaignsystemremovechar/" +
                    item.id +
                    "/" +
                    item.user_id +
                    "/" +
                    data.start_campaign_id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },

    computed: {
        ...mapState(["startcampaignsystems", "user_id"]),

        filteredItems() {
            return this.startcampaignsystems.filter(
                s =>
                    s.constellation_id == this.data.constellation_id &&
                    s.start_campaign_id == this.data.start_campaign_id
            );
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
