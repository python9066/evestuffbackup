<template>
    <div>
        <v-data-table
            v-if="showTable"
            :headers="headers"
            :items="filteredItems"
            item-key="node"
            disable-sort
            hide-default-footer
            :hide-default-header="true"
            disable-pagination
            class="pl-15"
        >
            <template v-slot:item.charname="{ item }">
                <div class=" d-inline-flex align-items-center">
                    <div class=" d-inline-flex align-items-center">
                        {{ item.charname }}
                        <v-btn
                            v-if="checkShowAddRemove(item)"
                            icon
                            @click="
                                (item.charname = null),
                                    (item.main_name = null),
                                    removeCharNode(item)
                            "
                            color="orange darken-3"
                        >
                            <v-icon small>fas fa-trash-alt</v-icon></v-btn
                        >
                        <NodeExtraChar
                            v-if="$can('super')"
                            :item="item"
                        ></NodeExtraChar>
                    </div>
                    <AdminHack
                        v-if="$can('campaigns_admin_access')"
                        :item="item"
                        @openAdd="openAdd($event)"
                    ></AdminHack>
                </div>
            </template>
            <template v-slot:item.satatusName="{ item }">
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <div>
                            <v-chip
                                v-bind="attrs"
                                v-on="on"
                                pill
                                small
                                :color="pillColor(item)"
                            >
                                {{ item.satatusName }}
                            </v-chip>
                        </div>
                    </template>

                    <v-list>
                        <v-list-item
                            v-for="(list, index) in dropdown_edit"
                            :key="index"
                            @click="
                                (item.status_id = list.value),
                                    (item.satatusName = list.title),
                                    statusClick(item)
                            "
                        >
                            <v-list-item-title>{{
                                list.title
                            }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
            <template v-slot:item.ship="{ item }">
                <span v-if="item.charname != null">
                    {{ item.ship }} - T{{ item.user_link }}
                </span>
            </template>
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
                    width: "5%",
                    align: "start"
                },
                { text: "Ship", value: "ship", width: "15%", align: "start" },
                {
                    text: "Status",
                    value: "statusName",
                    width: "20%",
                    align: "center"
                },
                { text: "", value: "actions", width: "5%", align: "end" }
            ],

            dropdown_edit: [
                { title: "New", value: 1 },
                { title: "Warm up", value: 2 },
                { title: "Hacking", value: 3 },
                { title: "Friendly Hacking", value: 8 },
                { title: "Pushed off", value: 6 }
            ],
            expanded: [],
            singleExpand: false
        };
    },

    methods: {
        pillColor(item) {
            if (item.status_id == 1) {
                return "deep-orange lighten-1";
            }
            if (item.status_id == 2) {
                return "lime darken-4";
            }
            if (item.status_id == 3 || item.status_id == 8) {
                return "green darken-3";
            }
            if (item.status_id == 4) {
                return "green accent-4";
            }
            if (item.status_id == 5) {
                return "red darken-4";
            }
            if (item.status_id == 6) {
                return "#FF5EEA";
            }
            if (item.status_id == 7) {
                return "#801916";
            }
            if (item.status_id == 9) {
                return "#9C9C9C";
            }
        },

        checkShowAddRemove(item) {
            if (
                item.charname != null &&
                this.charCount != 0 &&
                item.status_id != 4 &&
                item.status_id != 5 &&
                item.status_id != 7 &&
                item.status_id != 8
            ) {
                return true;
            } else if (this.$can("campaigns_admin_access")) {
                return true;
            } else {
                return false;
            }
        },

        removeCharNode(item) {
            // var userId = item.user_id;
            // item.user_id = null;
            // item.ship = null;
            // item.user_link = null;
            // this.$store.dispatch("updateCampaignSystem", item);
            // var data = {
            //     id: userId,
            //     node_id: null,
            //     status_id: 3,
            //     user_satatusName: "Ready to go"
            // };
            // if (userId != null) {
            //     this.$store.dispatch("updateCampaignUsers", data);
            // }
            // var request = null;
            // if (item.status_id == 4 || item.status_id == 5) {
            //     request = {
            //         campaign_user_id: null,
            //         campaign_system_status_id: item.status_id
            //     };
            // } else if (
            //     item.status_id == 1 ||
            //     item.status_id == 7 ||
            //     item.status_id == 9 ||
            //     item.status_id == 8
            // ) {
            //     request = {
            //         campaign_user_id: null,
            //         campaign_system_status_id: item.status_id,
            //         end_time: null
            //     };
            // } else {
            //     request = {
            //         campaign_user_id: null
            //     };
            // }
            // console.log(request);
            // axios({
            //     method: "PUT", //you can set what request you want to be
            //     url:
            //         "/api/removecharfromnode/" +
            //         item.id +
            //         "/" +
            //         this.campaign_id,
            //     data: request,
            //     headers: {
            //         Authorization: "Bearer " + this.$store.state.token,
            //         Accept: "application/json",
            //         "Content-Type": "application/json"
            //     }
            // });
        }
    },

    computed: {
        ...mapGetters([
            "getNodeJoinByNode",
            "getNodeJoinByNodeCount",
            "getCampaignUsersByUserIdEntosisCount"
        ]),

        filteredItems() {
            console.log(this.sysid);
            return this.getNodeJoinByNode(this.sysid);
        },

        showTable() {
            if (this.getNodeJoinByNodeCount(this.sysid) > 0) {
                return true;
            } else {
                return false;
            }
        },

        charCount() {
            return this.getCampaignUsersByUserIdEntosisCount(
                this.$store.state.user_id
            );
        }
    }
};
</script>

<style></style>
