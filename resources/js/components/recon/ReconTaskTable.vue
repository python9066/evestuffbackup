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
                            <div>{{ title }}</div>
                            <v-divider class="mx-4 my-0" vertical></v-divider>
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
                    <template v-slot:[`item.count`]="{ item }"
                        ><LastedCheckedTimerRecon :item="item">
                        </LastedCheckedTimerRecon>
                    </template>

                    <template v-slot:[`item.updated_at`]="{ item }"
                        ><span v-if="item.user_id != null">{{
                            item.updated_at
                        }}</span>
                        <span v-else> N/A </span>
                    </template>

                    <template v-slot:[`item.actions`]="{ item }">
                        <div class=" d-inline-flex">
                            <ReconSystemCheckedButton
                                class=" pr-3"
                                :item="item"
                            ></ReconSystemCheckedButton>
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
        created_at: String,
        edited_by_user_id: Number,
        info: String,
        made_by_user_id: Number,
        title: String,
        updated_at: String,
        size: Number,
        id: Number
    },
    data() {
        return {
            headers: [
                { text: "Region", value: "region_name", width: "10%" },
                { text: "Constellation", value: "constellation_name" },
                { text: "System", value: "system_name" },
                { text: "Last Checked (date)", value: "updated_at" },
                {
                    text: "Last Checked (time)",
                    value: "count",
                    width: "20%",
                    align: "center"
                },
                { text: "Checked by", value: "user_name" },
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

    async created() {},

    async mounted() {},

    methods: {},

    computed: {
        ...mapState(["recontasksystems", "user_id"]),

        ...mapGetters([]),

        filteredItems() {
            // var timers = this.$store.state.timers;

            return this.recontasksystems.filter(
                s => s.recon_task_id == this.id
            );
        }
    }
};
</script>

<style></style>
