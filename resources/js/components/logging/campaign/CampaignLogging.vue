<template>
    <v-overlay z-index="0" :value="overlay">
        <v-card
            tile
            max-width="1200px"
            max-height="500px"
            class=" d-flex flex-column"
        >
            <!-- <v-card-title
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
            > -->
        </v-card>
    </v-overlay>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        campaign_id: Number
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
            ]
            // search: ""
        };
    },

    methods: {
        // close() {
        //     this.$emit("closeAdd", "yo");
        // },
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
