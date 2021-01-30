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
                    </div>
                </div>
            </template>
            <template v-slot:item.statusName="{ item }">
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <div>
                            <v-chip
                                v-bind="attrs"
                                v-on="on"
                                pill
                                outlined
                                small
                                :color="pillColor(item)"
                            >
                                {{ item.statusName }}
                            </v-chip>
                        </div>
                    </template>

                    <v-list>
                        <v-list-item
                            v-for="(list, index) in dropdown_edit"
                            :key="index"
                            @click="
                                (item.campaign_system_status_id = list.value),
                                    (item.statusName = list.title),
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
                    {{ item.ship }} - T{{ item.link }}
                </span>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    v-if="item.status_id != 4 && item.status_id != 5"
                    color="orange darken-3"
                    small
                    @click="deleteNode(item)"
                >
                    fas fa-trash-alt
                </v-icon>
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
            if (item.campaign_system_status_id == 1) {
                return "deep-orange lighten-1";
            }
            if (item.campaign_system_status_id == 2) {
                return "lime darken-4";
            }
            if (
                item.campaign_system_status_id == 3 ||
                item.campaign_system_status_id == 8
            ) {
                return "green darken-3";
            }
            if (item.campaign_system_status_id == 4) {
                return "green accent-4";
            }
            if (item.campaign_system_status_id == 5) {
                return "red darken-4";
            }
            if (item.campaign_system_status_id == 6) {
                return "#FF5EEA";
            }
            if (item.campaign_system_status_id == 7) {
                return "#801916";
            }
            if (item.campaign_system_status_id == 9) {
                return "#9C9C9C";
            }
        },

        checkShowAddRemove(item) {
            if (
                item.charname != null &&
                this.charCount != 0 &&
                item.campaign_system_status_id != 4 &&
                item.campaign_system_status_id != 5 &&
                item.campaign_system_status_id != 7 &&
                item.campaign_system_status_id != 8
            ) {
                return true;
            } else if (this.$can("campaigns_admin_access")) {
                return true;
            } else {
                return false;
            }
        },

        deleteNode(item) {
            console.log(item);
            // await axios({

            //     method: "PUT", //you can set what request you want to be
            //     url: "/api/deleteextranode/" + item.id + "/" + this.campaign_id,
            //     headers: {
            //         Authorization: "Bearer " + this.$store.state.token,
            //         Accept: "application/json",
            //         "Content-Type": "application/json"
            //     }
            // });
        },

        removeCharNode(item) {
            // var userId = item.user_id;
            // item.user_id = null;
            // item.ship = null;
            // item.link = null;
            // this.$store.dispatch("updateCampaignSystem", item);
            // var data = {
            //     id: userId,
            //     node_id: null,
            //     campaign_system_status_id: 3,
            //     user_statusName: "Ready to go"
            // };
            // if (userId != null) {
            //     this.$store.dispatch("updateCampaignUsers", data);
            // }
            // var request = null;
            // if (item.campaign_system_status_id == 4 || item.campaign_system_status_id == 5) {
            //     request = {
            //         campaign_user_id: null,
            //         campaign_system_campaign_system_status_id: item.campaign_system_status_id
            //     };
            // } else if (
            //     item.campaign_system_status_id == 1 ||
            //     item.campaign_system_status_id == 7 ||
            //     item.campaign_system_status_id == 9 ||
            //     item.campaign_system_status_id == 8
            // ) {
            //     request = {
            //         campaign_user_id: null,
            //         campaign_system_campaign_system_status_id: item.campaign_system_status_id,
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
        },

        async statusClick(item) {
            var request = [];

            if (
                item.status_id == 1 ||
                item.status_id == 7 ||
                item.status_id == 9 ||
                item.status_id == 8
            ) {
                item.end = null;
                this.removeCharNode(item);
                item.user_name = null;
                item.main_name = null;
                return;
            }
            if (
                item.status_id == 2 ||
                item.status_id == 3 ||
                item.status_id == 8 ||
                item.status_id == 9 ||
                item.status_id == 6
            ) {
                item.end = null;
                request = {
                    campaign_system_status_id: item.status_id,
                    end_time: null
                };
            }
            if (item.status_id == 4 || item.status_id == 5) {
                await this.removeCharNode(item);
                item.user_name = null;
                item.main_name = null;
                return;
            }

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignsystems/" + item.id + "/" + this.campaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.$store.dispatch("getCampaignSystemsRecords");
            this.$store.dispatch("getCampaignUsersRecords", this.campaign_id);
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
