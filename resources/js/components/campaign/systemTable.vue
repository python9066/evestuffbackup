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
                    :item-class="itemRowBackground"
                    hide-default-footer
                    disable-pagination
                    class="elevation-12"
                >
                    >
                    <template v-slot:top>
                        <v-toolbar
                            flat
                            max-width
                            elevation="24"
                            color="grey darken-4"
                        >
                            <v-toolbar-title
                                max-width
                                class="d-flex justify-space-between align-center"
                                style=" width: 100%;"
                            >
                                <div>{{ system_name }} -</div>
                                <v-spacer></v-spacer>
                                <div>
                                    <v-progress-circular
                                        v-if="nodeCount > 0"
                                        :transitionDuration="5000"
                                        :radius="20"
                                        :strokeWidth="4"
                                        :value="
                                            (nodeCountHackingCount /
                                                nodeCount) *
                                                100 || 0.000001
                                        "
                                    >
                                        <div class="caption">
                                            {{ nodeCountHackingCount }} /
                                            {{ nodeCount }}
                                        </div></v-progress-circular
                                    >

                                    <v-progress-circular
                                        v-if="nodeCount > 0"
                                        :transitionDuration="5000"
                                        :radius="20"
                                        :strokeWidth="4"
                                        strokeColor="#FF3D00"
                                        :value="
                                            (nodeRedCountHackingCount /
                                                nodeCount) *
                                                100 || 0.000001
                                        "
                                    >
                                        <div class="caption">
                                            {{ nodeRedCountHackingCount }} /
                                            {{ nodeCount }}
                                        </div></v-progress-circular
                                    >
                                </div>
                                <v-spacer></v-spacer>
                                <div class=" ml-auto">
                                    <v-menu
                                        transition="fade-transition"
                                        v-if="charCount != 0"
                                    >
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-chip
                                                dark
                                                :color="filterCharsOnTheWay"
                                                v-bind="attrs"
                                                v-on="on"
                                                small
                                            >
                                                On the Way
                                            </v-chip>
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

                                    <v-menu
                                        transition="fade-transition"
                                        v-if="charCount != 0"
                                    >
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-chip
                                                dark
                                                :color="filterCharsReadyToGo"
                                                v-bind="attrs"
                                                v-on="on"
                                                small
                                            >
                                                Ready to go
                                            </v-chip>
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
                                </div>
                                <div>
                                    <v-menu
                                        :close-on-content-click="false"
                                        :value="addShown"
                                    >
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                icon
                                                v-bind="attrs"
                                                v-on="on"
                                                @click="addShown = true"
                                                color="success"
                                                ><v-icon
                                                    >fas fa-plus</v-icon
                                                ></v-btn
                                            >
                                        </template>
                                        <v-card tile min-height="150px">
                                            <v-card-title class=" pb-0">
                                                <v-text-field
                                                    class=" mt-2"
                                                    label="Node"
                                                    placeholder="Enter Node"
                                                    flat
                                                    v-mask="'AA##'"
                                                    autofocus
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
                                </div>
                            </v-toolbar-title>
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
                                        :outlined="pillOutlined(item)"
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
                        <v-menu offset-y v-if="checkShowAdd(item)">
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
                                    v-for="(list, index) in charsFree"
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
                        <span v-else-if="item.user_name != null"
                            >{{ item.user_name }}
                            <v-btn
                                icon
                                @click="
                                    (item.user_name = null),
                                        (item.main_name = null),
                                        removeCharNode(item)
                                "
                                color="orange darken-3"
                            >
                                <v-icon small>fas fa-trash-alt</v-icon></v-btn
                            ></span
                        >
                    </template>
                    <template v-slot:item.count="{ item }">
                        <VueCountUptimer
                            v-if="item.status_id < 3 && item.end_time == null"
                            :start-time="moment.utc(item.start).unix()"
                            :end-text="'Window Closed'"
                            :interval="1000"
                        >
                            <template slot="countup" slot-scope="scope">
                                <span
                                    v-if="scope.props.minutes < 5"
                                    class="green--text pl-3"
                                    >{{ scope.props.hours }}:{{
                                        scope.props.minutes
                                    }}:{{ scope.props.seconds }}</span
                                >
                                <span v-else class="red--text pl-3"
                                    >{{ scope.props.hours }}:{{
                                        scope.props.minutes
                                    }}:{{ scope.props.seconds }}</span
                                >
                            </template>
                        </VueCountUptimer>
                        <v-menu
                            :close-on-content-click="false"
                            :value="timerShown"
                            v-else-if="checkHackUser(item)"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-chip
                                    v-bind="attrs"
                                    v-on="on"
                                    pill
                                    :outlined="pillOutlined(item)"
                                    @click="timerShown = true"
                                    small
                                    color="warning"
                                >
                                    Add Time
                                </v-chip>
                            </template>

                            <template>
                                <v-card tile min-height="150px">
                                    <v-card-title class=" pb-0">
                                        <v-text-field
                                            v-model="hackTime"
                                            label="Hack Time mm:ss"
                                            v-mask="'##:##'"
                                            autofocus
                                            placeholder="mm:ss"
                                        ></v-text-field>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-btn
                                            icon
                                            fixed
                                            left
                                            color="success"
                                            @click="
                                                (timerShown = false),
                                                    addHacktime(item)
                                            "
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
                                                (timerShown = false),
                                                    (hackTime = null)
                                            "
                                            ><v-icon
                                                >fas fa-times</v-icon
                                            ></v-btn
                                        >
                                    </v-card-text>
                                </v-card>
                            </template>
                        </v-menu>
                        <CountDowntimer
                            v-else
                            :start-time="moment.utc(item.end).unix()"
                            :end-text="endText(item)"
                            :interval="1000"
                        >
                            <template slot="countdown" slot-scope="scope">
                                <span :class="hackCountDownTextColor(item)"
                                    >{{ scope.props.minutes }}:{{
                                        scope.props.seconds
                                    }}</span
                                >
                                <v-menu
                            :close-on-content-click="false"
                            :value="timerShown"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-if="checkHackUserEdit(item)"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="timerShown = true, hackTime = null"
                                    icon
                                    color="warning"
                                >
                                    <v-icon x-small>fas fa-edit</v-icon>
                                </v-btn>
                            </template>

                            <template>
                                <v-card tile min-height="150px">
                                    <v-card-title class=" pb-0">
                                        <v-text-field
                                            v-model="hackTime"
                                            label="Hack Time mm:ss"
                                            autofocus
                                            v-mask="'##:##'"
                                            placeholder="mm:ss"
                                        ></v-text-field>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-btn
                                            icon
                                            fixed
                                            left
                                            color="success"
                                            @click="
                                                (timerShown = false),
                                                    addHacktime(item)
                                            "
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
                                                (timerShown = false),
                                                    (hackTime = null)
                                            "
                                            ><v-icon
                                                >fas fa-times</v-icon
                                            ></v-btn
                                        >
                                    </v-card-text>
                                </v-card>
                            </template>
                        </v-menu>
                            </template>
                            <template slot="end-text" slot-scope="scope">
                                <span :style="hackTextColor(item)">{{
                                    scope.props.endText
                                }}</span>
                            </template>
                        </CountDowntimer>

                    </template>

                    <template v-slot:expanded-item="{ headers, item }">
                        <td :colspan="headers.length" align="center">
                            <div>
                                <v-col class="align-center">
                                    <v-textarea
                                        v-bind:value="item.text"
                                        label="Where you can put any notes"
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
        system_name: String,
        system_id: Number,
        campaign_id: String
    },

    async mounted() {

        await this.setTimerShow();
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
                { text: "Age/Hack", value: "count", width: "20%" },
                {
                    text: "",
                    value: "actions",
                    sortable: false,
                    align: "end",
                    width: "5%"
                },
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
            OnTheWayColor: "teal",
            nodeText: "",
            addShown: false,
            timerShown: [],
            expanded: [],
            singleExpand: true,
            charAddNode: null,
            hackTime: {
                mm: "",
                ss: ""
            }
        };
    },

    methods: {

        setTimerShow(){
            console.log(this.campaignsystems)
        },

        async addHacktime(item) {
            var min = parseInt(this.hackTime.substr(0, 2));
            var sec = parseInt(this.hackTime.substr(3, 2));
            var finishTime = moment
                .utc()
                .add(sec, "seconds")
                .add(min, "minutes")
                .format("YYYY-MM-DD HH:mm:ss");
            item.end = finishTime;
            this.$store.dispatch("updateCampaignSystem", item);
            var request = {
                end_time: finishTime
            };

            await axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignsystems/" +
                    item.id +
                    "/" +
                    this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.$store.dispatch("getCampaignSystemsRecords");
        },

        clickOnTheWay() {
            this.OnTheWayColor = "green";
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
                url:
                    "/api/campaignusers/" +
                    this.charOnTheWay +
                    "/" +
                    this.$route.params.id,
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
                url:
                    "/api/campaignusers/" +
                    this.charReadyToGo +
                    "/" +
                    this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.charReadyToGo = null;
        },

        async deleteNode(item) {
            await axios({
                method: "DELETE", //you can set what request you want to be
                url:
                    "/api/campaignsystems/" +
                    item.id +
                    "/" +
                    this.$route.params.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.$store.dispatch("getCampaignSystemsRecords");
            this.$store.dispatch(
                "getCampaignUsersRecords",
                this.$route.params.id
            );
        },

        clickCharAddNode(item) {
            var addChar = this.chars.find(user => user.id == this.charAddNode);
            console.log(addChar, item);
            var data = {
                id: item.id,
                user_id: addChar.id,
                site_id: this.$store.state.user_id,
                user_name: addChar.char_name,
                main_name: addChar.main_name
            };

            var request = {
                campaign_user_id: addChar.id
            };
            this.$store.dispatch("updateCampaignSystem", data);

            data = null;
            data = {
                id: addChar.id,
                campaign_system_id: item.id,
                node_id: item.node,
                system_id: item.system_id,
                system_name: item.system_name,
                status_id: 4,
                user_status_name: "Hacking"
            };

            console.log(data);
            var request1 = {
                campaign_system_id: item.id,
                system_id: item.system_id,
                status_id: 4
            };
            this.$store.dispatch("updateCampaignUsers", data);

            axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignsystems/" +
                    item.id +
                    "/" +
                    this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignusers/" +
                    addChar.id +
                    "/" +
                    this.$route.params.id,
                data: request1,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        itemRowBackground: function(item) {
            return item.status_id == 7 ? "style-1" : "style-2";
        },

        statusClick(item) {
            var request = null;
            if (item.status_id == 2) {
                item.end = null;

                request = {
                    campaign_system_status_id: item.status_id,
                    end_time: null
                };
            } else if (item.status_id == 4) {
                this.removeCharNode(item);
                item.user_name = null;
                item.main_name = null;
                return;
            } else {
                request = {
                    campaign_system_status_id: item.status_id
                };
            }

            axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignsystems/" +
                    item.id +
                    "/" +
                    this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        checkShowAdd(item) {
            if (
                item.user_name == null &&
                this.charCount != 0 &&
                item.status_id != 4 &&
                item.status_id != 5 &&
                item.status_id != 7
            ) {
                return true;
            } else {
                return false;
            }
        },
        pillOutlined(item){

            if (item.status_id == 7){
                return false
            }else{
                return true
            }
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
                return "#801916";
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
                    url:
                        "/api/campaignsystems/" +
                        item.id +
                        "/" +
                        this.$route.params.id,
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
                campaign_id: this.campaign_id,
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

        hackTextColor(item){
            if(item.status_id == 7){
                return "color: while"
            }else{
                return "color: green"
            }
        },

        endText(item){
            if(item.status_id == 7){
                return "Do they Finish?"
            }else{
                return "Do you Finish?"
            }
        },

        hackCountDownTextColor(item){
            if(item.status_id == 7){
                return "white--text pl-3"
            }else{
                return "blue--text pl-3"
            }

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
            var request = null;
            if (item.status_id == 4) {
                request = {
                    campaign_user_id: null,
                    campaign_system_status_id: item.status_id
                };
            } else {
                request = {
                    campaign_user_id: null
                };
            }

            axios({
                method: "PUT", //you can set what request you want to be
                url:
                    "/api/campaignsystems/" +
                    item.id +
                    "/" +
                    this.$route.params.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            var request1 = {
                campaign_system_id: null
            };
            axios({
                method: "PUT", //you can set what request you want to be
                url:
                    "/api/campaignusers/" +
                    userId +
                    "/" +
                    this.$route.params.id,
                data: request1,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        checkHackUser(item) {
            if (
                item.site_id == this.$store.state.user_id &&
                item.end == null &&
                item.status_id == 3
            ) {
                return true;
            } else if (item.end == null && item.status_id == 7) {
                return true;
            } else {
                return false;
            }
        },

        checkHackUserEdit(item) {
            if (
                item.site_id == this.$store.state.user_id &&
                item.status_id == 3
            ) {
                return true;
            } else if (item.status_id == 7) {
                return true;
            } else {
                return false;
            }
        }

        // if (item.site_id == null) {
        //     return false;
        // }

        // if (
        //     item.site_id == this.$store.state.user_id && item.end_time == null
        // ) {
        //     if (item.status_id == 2 || item.status_id == 3) {
        //         return true;
        //     }
        //     return false;
        // } else {
        //     return false;
        // }
    },

    computed: {
        ...mapState(["campaignsystems", "user_id"]),

        ...mapGetters([
            "getCampaignUsersByUserIdEntosis",
            "getCampaignUsersByUserIdEntosisCount",
            "getCampaignUsersByUserIdEntosisFree",
            "getTotalNodeCountBySystem",
            "getHackingNodeCountBySystem",
            "getNodeValue",
            "getRedHackingNodeCountBySystem"
        ]),

        filteredItems() {
            // var timers = this.$store.state.timers;

                return this.campaignsystems.filter(
                    s =>  s.system_id == this.system_id &&
                        s.campaign_id == this.$route.params.id)

            }
        },

        chars() {
            return this.getCampaignUsersByUserIdEntosis(
                this.$store.state.user_id
            );
        },

        charsFree() {
            return this.getCampaignUsersByUserIdEntosisFree(
                this.$store.state.user_id
            );
        },

        charCount() {
            return this.getCampaignUsersByUserIdEntosisCount(
                this.$store.state.user_id
            );
        },

        nodeCount() {
            return this.getTotalNodeCountBySystem(this.system_id);
        },

        nodeCountHackingCount() {
            let payload = {
                system_id: this.system_id,
                campaign_id: this.$route.params.id
            };
            return this.getHackingNodeCountBySystem(payload);
        },

        nodeRedCountHackingCount() {
            let payload = {
                system_id: this.system_id,
                campaign_id: this.$route.params.id
            };
            return this.getRedHackingNodeCountBySystem(payload);
        },

        nodeValue() {
            let payload = {
                system_id: this.system_id,
                campaign_id: this.$route.params.id
            };
            return this.getNodeValue(payload);
        },

        filterCharsOnTheWay() {
            var count = this.getCampaignUsersByUserIdEntosis(
                this.$store.state.user_id
            ).filter(
                char => char.status_id == 2 && char.system_id == this.system_id
            ).length;

            if (count > 0) {
                return "green";
            } else {
                return "red";
            }
        },

        filterCharsReadyToGo() {
            var count = this.getCampaignUsersByUserIdEntosis(
                this.$store.state.user_id
            ).filter(
                char => char.status_id == 3 && char.system_id == this.system_id
            ).length;

            if (count > 0) {
                return "green";
            } else {
                return "red";
            }
        }
    }
};
</script>

<style>

.style-2 {
  background-color: rgb(30,30,30,1)
}
.style-1 {
  background-color: rgb(184, 22, 35)
}</style>
