<template>
    <v-card
        tile
        max-width="700px"
        max-height="500px"
        class=" d-flex flex-column"
        color="red"
    >
        <v-card-title class="d-flex justify-space-between align-center ">
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
