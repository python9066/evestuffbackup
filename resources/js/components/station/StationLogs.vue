<template>
    <div>
        <div class=" d-flex align-items-center"></div>
        <v-data-table
            :headers="headers"
            :items="filteredItems"
            :item-class="itemRowBackground"
            item-key="id"
            :loading="loadingt"
            :items-per-page="10"
            :sort-by.sync="sortby"
            class="elevation-1"
        >
            >
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title
                        >Logs for {{ station.name }}</v-toolbar-title
                    >
                    <v-spacer></v-spacer>
                </v-toolbar>
            </template>

            <template slot="no-data">
                No logs for this
            </template>
        </v-data-table>
    </div>
</template>
<script>
import Axios from "axios";
import moment from "moment";
import { stringify } from "querystring";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
export default {
    props: {
        station: Object
    },
    title() {
        return `EveStuff - Chilled Timers`;
    },
    data() {
        return {
            headers: [
                {
                    text: "Region",
                    value: "text"
                }
            ]
        };
    },

    async created() {},

    async mounted() {},
    methods: {},

    computed: {
        ...mapGetters(["getStationLogsByID"]),

        filterItems() {
            return this.getStationLogsByID(this.station.station_id);
        }
    },
    beforeDestroy() {}
};
</script>

<style></style>
