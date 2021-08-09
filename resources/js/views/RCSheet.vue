<template>
    <div class="pr-16 pl-16">
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="12">
                more to come
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="12">
                <v-spacer></v-spacer>
                <v-card tile flat color="#121212" class="align-end">
                    mroe to come
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col
                class=" d-inline-flex justify-content-center w-auto"
                cols="12"
            >
                <v-card width="100%">
                    <v-data-table
                        :headers="headers"
                        :items="filteredItems"
                        item-key="id"
                        :loading="loading"
                        :sort-by="['name']"
                        :items-per-page="20"
                        :search="search"
                        :footer-props="{
                            'items-per-page-options': [10, 20, 50, 100, -1]
                        }"
                        class="elevation-5"
                    >
                        <template slot="no-data">
                            No Active or Upcoming Campaigns
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
import Axios from "axios";
import moment, { now, utc } from "moment";
import { stringify } from "querystring";
import { mapState } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

export default {
    title() {
        return `EVE`;
    },
    data() {
        return {
            headers: [
                { text: "System", value: "name" },
                { text: "Name", value: "name" },
                { text: "Type", value: "item_name" },
                { text: "Timer", value: "status_name" },
                { text: "Ticker", value: "alliance_ticker" },
                { text: "Expires", value: "end_time" },
                { text: "CoutDown", value: "" },
                { text: "FC", value: "fc_name" },
                { text: "Recon", value: "recon_name" },
                { text: "GSOL", value: "gsol_name" },
                { text: "", value: "actions" }
            ]
        };
    },

    async created() {},

    async mounted() {
        await this.$store.dispatch("getRcStationRecords");
    },

    methods: {},

    computed: {
        ...mapState(["rcstations"]),
        filteredItems() {
            return this.rcstations;
        }
    },
    beforeDestroy() {}
};
</script>
<style scoped></style>
