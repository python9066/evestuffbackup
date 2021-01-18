<template>
    <v-card
        tile
        max-width="1500px"
        max-height="1000px"
        class=" d-flex flex-column"
    >
        <v-card-title
            class=" d-lg-inline-block justify-space-between align-center "
        >
            <div>
                Add a Character to node
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
                :items="logging"
                item-key="id"
                disable-pagination
                height="700"
                hide-default-footer
                :search="search"
                class="elevation-24"
            >
                <template v-slot:item.created_at="{ item }">
                    <div>
                        <h1>{{ item.created_at }}</h1>
                    </div>
                </template>
                <template v-slot:item.logging_type_name="{ item }">
                    <div>
                        <h1>{{ item.logging_type_name }}</h1>
                    </div>
                </template>
                <template v-slot:item.user_name="{ item }">
                    <div>
                        <h1>{{ item.user_name }}</h1>
                    </div>
                </template>
                <template v-slot:item.text="{ item }">
                    <div>
                        <h1>{{ item.text }}</h1>
                    </div>
                </template>
                <template slot="no-data">
                    No Logs
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
        campaign_id: Number,
        campaign: Object
    },
    data() {
        return {
            overlay: true,
            headers: [
                { text: "Time Stamp", value: "created_at" },
                { text: "Type", value: "logging_type_name" },
                { text: "User", value: "user_name" },
                { text: "Text", value: "text" }
                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            search: ""
        };
    },

    methods: {
        close() {
            this.$emit("closeLog", "yo");
        }
    },

    computed: {
        ...mapGetters(["getLoggingCampaignByCampaign"]),
        logging() {
            if (this.$can("super")) {
                return this.getLoggingCampaignByCampaign(this.campaign_id);
            }
        }
    }
};
</script>

<style></style>
