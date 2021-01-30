<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="filteredItems"
            item-key="node"
            disable-sort
            hide-default-footer
            :hide-default-header="true"
            disable-pagination
            class="elevation-12 pl-6"
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
                {
                    text: "Pilot",
                    value: "charname",
                    width: "25%",
                    align: "start"
                },
                {
                    text: "Main",
                    value: "mainname",
                    width: "10%",
                    align: "start"
                },
                { text: "Ship", value: "ship,", width: "15%", align: "start" },
                {
                    text: "Status",
                    value: "statusName",
                    width: "20%",
                    align: "center"
                },
                { text: "", value: "actions", width: "5%", align: "end" }
            ]
        };
    },

    expanded: [],
    singleExpand: false,

    methods: {},

    computed: {
        ...mapGetters(["getNodeJoinByNode", "getNodeJoinByNodeCount"]),

        filteredItems() {
            console.log(this.sysid);
            return this.getNodeJoinByNode(this.sysid);
        }
    }
};
</script>

<style></style>
