<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="filteredItems"
            :single-expand="singleExpand"
            item-key="node"
            show-expand
            disable-sort
            :expanded.sync="expanded"
            :item-class="itemRowBackground"
            hide-default-footer
            disable-pagination
            class="elevation-12"
            expand-icon="fas fa-pen green--text"
        >
        </v-data-table>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        sysid: Number
    },
    data() {
        return {
            headers: [
                { text: "Pilot", value: "charname" },
                { text: "Main", value: "mainname" },
                { text: "Ship", value: "ship" },
                { text: "Status", value: "statusName" },
                { text: "", value: "actions" }
            ]
        };
    },

    expanded: [],
    singleExpand: false,

    methods: {},

    computed: {
        ...mapGetters(["getNodeJoinByNode", "getNodeJoinByNodeCount"]),

        filteredItems() {
            return this.getNodeJoinByNode(this.sysid);
        }
    }
};
</script>

<style></style>
