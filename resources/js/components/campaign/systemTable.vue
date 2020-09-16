<template>
    <v-col cols="6" align-self="stretch">
        <v-card tile height="100%">
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="filteredItems"
                    :single-expand="singleExpand"
                    item-key="node"
                    :sort-desc="[true, false]"
                    show-expand
                    :expanded.sync="expanded"
                    hide-default-footer
                    disable-pagination
                    class="elevation-12"
                >
                    >
                    <template v-slot:top>
                        <v-toolbar
                        flat
                        elevation="24"
                        src="http://photos.napalm.net/clubsi/m-07.jpg"
                        >
                            <v-toolbar-title
                                >{{ system_name }} -
                                <v-menu transition="fade-transition">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            dark
                                            tile
                                            outlined
                                            color="teal"
                                            v-bind="attrs"
                                            v-on="on"
                                        >
                                            On the Way
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item
                                            v-for="(list, index) in chars"
                                            :key="index"
                                            @click="
                                                (charOnTheWay = list.id),
                                                    clickOnTheWay()
                                            "
                                        >
                                            <v-list-item-title>{{
                                                list.char_name
                                            }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>

                                <v-menu transition="fade-transition">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            dark
                                            tile
                                            outlined
                                            color="teal"
                                            v-bind="attrs"
                                            v-on="on"
                                        >
                                            Ready to go
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item
                                            v-for="(list, index) in chars"
                                            :key="index"
                                            @click="
                                                (charReadyToGo = list.id),
                                                    clickReadyToGo()
                                            "
                                        >
                                            <v-list-item-title>{{
                                                list.char_name
                                            }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                                <v-menu
                                    :close-on-content-click="false"
                                    :value="addShown"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            icon
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="addShown = true"
                                            color="success"
                                            fixed
                                            right
                                            ><v-icon>fas fa-plus</v-icon></v-btn
                                        >
                                    </template>
                                    <v-card tile min-height="150px">
                                        <v-card-title class=" pb-0">
                                            <v-text-field
                                                class=" mt-2"
                                                label="Node"
                                                placeholder="Enter Node"
                                                flat
                                                v-model="nodeText"
                                            ></v-text-field>
                                        </v-card-title>

                                        <v-card-text>
                                            <v-btn
                                                icon
                                                fixed
                                                left
                                                color="success"
                                                @click="addNode()"
                                                ><v-icon
                                                    >fas fa-check</v-icon
                                                ></v-btn
                                            >

                                            <v-btn
                                                fixed
                                                right
                                                icon
                                                color="warning"
                                                @click="
                                                    (addShown = false),
                                                        (nodeText = '')
                                                "
                                                ><v-icon
                                                    >fas fa-times</v-icon
                                                ></v-btn
                                            >
                                        </v-card-text>
                                    </v-card>
                                </v-menu>
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                    </template>

                    <template v-slot:item.status_name="{ item }">
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
                                        {{ item.status_name }}
                                    </v-chip>
                                </div>
                            </template>

                            <v-list>
                                <v-list-item
                                    v-for="(list, index) in dropdown_edit"
                                    :key="index"
                                    @click="
                                        (item.status_id = list.value),
                                            (item.status_name = list.title),
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
                    <template v-slot:item.user_name="{ item }">
                        <v-menu offset-y v-if="item.user_name == null">
                            <template v-slot:activator="{ on, attrs }">
                                <div>
                                    <v-chip
                                        v-bind="attrs"
                                        v-on="on"
                                        pill
                                        outlined
                                        small
                                        color="light-green accent-3"
                                    >
                                        Add
                                    </v-chip>
                                </div>
                            </template>

                            <v-list>
                                <v-list-item
                                    v-for="(list, index) in chars"
                                    :key="index"
                                    @click="
                                        (charAddNode = list.id),
                                            clickCharAddNode(item)
                                    "
                                >
                                    <v-list-item-title>{{
                                        list.char_name
                                    }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                        <span v-else
                            >{{ item.user_name }}
                            <v-btn
                                icon
                                @click="
                                    (item.user_name = null),
                                        (item.status_id = 1),
                                        (item.status_name = 'New'),
                                        (item.main_name = null),
                                        removeCharNode(item)
                                "
                                color="red darken-2"
                            >
                                <v-icon small>fas fa-trash-alt</v-icon></v-btn
                            ></span
                        >
                    </template>

                    <template v-slot:expanded-item="{ headers, item }">
                        <td :colspan="headers.length" align="center">
                            <div>
                                <v-col class="align-center">
                                    <v-textarea
                                        v-bind:value="item.text"
                                        label="Place for you all to stick notes"
                                        outlined
                                        shaped
                                        @change="
                                            (payload = $event),
                                                updatetext(payload, item)
                                        "
                                    ></v-textarea>
                                </v-col>
                            </div>
                        </td>
                    </template>
                    <template v-slot:item.actions="{ item }">

      <v-icon
        color="orange darken-3"
        small
        @click="deleteNode(item)"
      >
        fas fa-trash-alt
      </v-icon>
    </template>

                    <template slot="no-data">
                        No nodes have showen up here..... yet!!!!
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
export default {
    props: {
        system_name: String,
        system_id: Number,
        campaign_id: String
    },
    data() {
        return {
            headers: [
                { text: "NodeID", value: "node", width: "10%" },
                { text: "Pilot", value: "user_name", width: "20%" },
                { text: "Main", value: "main_name", width: "10%" },
                {
                    text: "Status",
                    value: "status_name",
                    width: "20%",
                    align: "center"
                },
                { text: "Finished", value: "count", width: "20%" },
                { text: '', value: 'actions', sortable: false, align: "end", width: "5%" },
                {
                    text: "",
                    value: "data-table-expand",
                    align: "end",
                    width: "5%"
                }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],

            dropdown_edit: [
                { title: "New", value: 1 },
                { title: "Warm Up", value: 2 },
                { title: "Hacking", value: 3 },
                { title: "Success", value: 4 },
                { title: "Failed", value: 5 },
                { title: "Contested", value: 6 },
                { title: "Hostile Hacking", value: 7 }
            ],
            charOnTheWay: 0,
            charReadyToGo: 0,
            nodeText: "",
            addShown: false,
            expanded: [],
            singleExpand: true,
            charAddNode: null
        };
    },

    methods: {
        clickOnTheWay() {
            var item = {
                id: this.charOnTheWay,
                status_id: 2,
                user_status_name: "On the way",
                system_id: this.system_id,
                system_name: this.system_name
            };

            this.$store.dispatch("updateCampaignUsers", item);
            var request = {
                status_id: 2,
                system_id: this.system_id
            };

            axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignusers/" + this.charOnTheWay + "/" + this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.charOnTheWay = null;
        },
        clickReadyToGo() {
            var item = {
                id: this.charReadyToGo,
                status_id: 3,
                user_status_name: "Ready To Go",
                system_id: this.system_id,
                system_name: this.system_name
            };

            this.$store.dispatch("updateCampaignUsers", item);
            var request = {
                status_id: 3,
                system_id: this.system_id
            };

            axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignusers/" + this.charReadyToGo + "/" + this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.charReadyToGo = null;
        },

      async  deleteNode(item){

            console.log(item);
           await axios({
                method: "DELETE", //you can set what request you want to be
                url: "/api/campaignsystems/" + item.id + "/" + this.$route.params.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.$store.dispatch("getCampaignSystemsRecords");
            this.$store.dispatch("getCampaignUsersRecords",this.$route.params.id);

        },

        clickCharAddNode(item) {
            var addChar = this.chars.find(user => user.id == this.charAddNode);
            // console.log(addChar, item);
            var data = {
                id: item.id,
                user_id: addChar.id,
                site_id: this.$store.state.user_id,
                user_name: addChar.char_name,
                main_name: addChar.main_name
            };

            var request = {
                campaigan_user_id: addChar.id
            };
            this.$store.dispatch("updateCampaignSystem", data);

            data = null;
            data = {
                id: addChar.id,
                campaign_system_id: item.id,
                node_id: item.node,
                system_id: item.system_id,
                system_id: item.system_name,
                status_id: 4,
                user_status_name: "Hacking"
            };
            var request1 = {
                campaign_system_id: item.id,
                system_id: item.system_id,
                status_id: 4
            };
            this.$store.dispatch("updateCampaignUsers", data);

            axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignsystems/" + item.id + "/" + this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignusers/" + addChar.id + "/" + this.$route.params.id,
                data: request1,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        statusClick(item) {
            var request = {
                campaigan_system_status_id: item.status_id
            };
            axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignsystems/" + item.id + "/" + this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        pillColor(item) {
            if (item.status_id == 1) {
                return "deep-orange lighten-1";
            }
            if (item.status_id == 2) {
                return "lime darken-4";
            }
            if (item.status_id == 3) {
                return "green darken-3";
            }
            if (item.status_id == 4) {
                return "green accent-4";
            }
            if (item.status_id == 5) {
                return "red darken-4";
            }
            if (item.status_id == 6) {
                return "deep-orange accent-3";
            }
            if (item.status_id == 7) {
                return "red darken-4";
            }
        },
        updatetext(payload, item) {
            // console.log(item);
            if (item.text != payload) {
                item.text = payload;
                var request = {
                    notes: item.text
                };
                // console.log(item);
                this.$store.dispatch("updateCampaignSystem", item);
                axios({
                    method: "put", //you can set what request you want to be
                    url: "/api/campaignsystems/" + item.id + "/" + this.$route.params.id,
                    data: request,
                    headers: {
                        Authorization: "Bearer " + this.$store.state.token,
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    }
                });
            }
        },

        async addNode() {
            var request = {
                campaigan_id: this.campaign_id,
                system_id: this.system_id,
                node_id: this.nodeText
            };
            this.nodeText = "";
            this.addShown = false;
            await axios({
                method: "POST", //you can set what request you want to be
                url: "/api/campaignsystems/" + this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.$store.dispatch("getCampaignSystemsRecords");
        },
        removeCharNode(item) {
            var userId = item.user_id;
            item.user_id = null;
            this.$store.dispatch("updateCampaignSystem", item);
            var data = {
                id: userId,
                node_id: null,
                status_id: 1,
                user_status_name: "None"
            };
            this.$store.dispatch("updateCampaignUsers", data);
            var request = {
                campaigan_user_id: null,
                campaigan_system_status_id: 1
            };

            axios({
                method: "PUT", //you can set what request you want to be
                url: "/api/campaignsystems/" + item.id + "/" + this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            var request1 = {
                campaign_system_id: null,
                status_id: 1,
            };
            axios({
                method: "PUT", //you can set what request you want to be
                url: "/api/campaignusers/" + userId + "/" + this.$route.params.id,
                data: request1,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },

    computed: {
        ...mapState(["campaignsystems"]),

        ...mapGetters(["getCampaignUsersByUserIdEntosis"]),

        filteredItems() {
            // var timers = this.$store.state.timers;
            if (this.statusflag == 1) {
                return this.campaignsystems.filter(
                    s => s.status_id == 1 && s.system_id == this.system_id
                );
            }
            if (this.statusflag == 3) {
                return this.campaignsystems.filter(
                    s => s.status_id == 3 && s.system_id == this.system_id
                );
            }
            if (this.statusflag == 5) {
                return this.campaignsystems.filter(
                    s => s.status_id == 5 && s.system_id == this.system_id
                );
            }
            if (this.statusflag == 6) {
                return this.campaignsystems.filter(
                    s => s.status_id == 6 && s.system_id == this.system_id
                );
            } else {
                return this.campaignsystems.filter(
                    s => s.status_id != 10 && s.system_id == this.system_id
                );
            }
        },

        chars() {
            return this.getCampaignUsersByUserIdEntosis(
                this.$store.state.user_id
            );
        }
    }
};
</script>

<style></style>
