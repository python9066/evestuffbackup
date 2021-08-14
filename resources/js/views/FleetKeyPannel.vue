<template>
    <div class="pr-16 pl-16">
        <errorMessage></errorMessage>
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="9">
                <v-card
                    tile
                    flat
                    color="#121212"
                    class="d-inline-flex align-content-start"
                >
                    <v-card-title>Add/Remove Keys</v-card-title>
                </v-card>
                <v-card
                    width="500"
                    tile
                    flat
                    color="#121212"
                    class="align-start"
                >
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search for Users"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="9">
                <v-spacer></v-spacer>
                <v-card tile flat color="#121212" class="align-end"> </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col
                class=" d-inline-flex justify-content-center w-auto"
                cols="9"
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
                        <template v-slot:[`item.keys`]="{ item }">
                            <div class=" d-inline-flex">
                                <v-menu>
                                    <template v-slot:activator="{ on, attrs }">
                                        <div>
                                            <v-btn
                                                icon
                                                v-bind="attrs"
                                                v-on="on"
                                                color="success"
                                                ><v-icon
                                                    >fas fa-plus</v-icon
                                                ></v-btn
                                            >
                                        </div>
                                    </template>

                                    <v-list>
                                        <v-list-item
                                            v-for="(list,
                                            index) in filterDropdownList(
                                                item.keys
                                            )"
                                            :key="index"
                                            @click="
                                                (userAddKeyText = list.id),
                                                    userAddKey(item)
                                            "
                                        >
                                            <v-list-item-title>{{
                                                list.name
                                            }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </div>

                            <div class=" d-inline-flex">
                                <div
                                    v-for="(key, index) in filterKeys(
                                        item.keys
                                    )"
                                    :key="index"
                                    class=" pr-2"
                                >
                                    <v-chip
                                        pill
                                        :class="mittin(item)"
                                        :close="pillClose(key.name)"
                                        dark
                                        @click:close="
                                            (userRemoveKeyText = key.id),
                                                userRemoveKey(item)
                                        "
                                    >
                                        <span> {{ key.name }}</span>
                                    </v-chip>
                                </div>
                            </div>
                        </template>
                        <template slot="no-data">
                            No Active or Upcoming Campaigns
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <v-overlay z-index="0" :value="logs">
            <AdminLogging
                v-if="$can('view_admin_logs')"
                @closeLog="logs = false"
            >
            </AdminLogging>
        </v-overlay>
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
        return `EVE — Fleet Keys`;
    },
    data() {
        return {
            //timersAll: [],

            headers: [
                { text: "Name", value: "name" },
                { text: "Keys", value: "keys", width: "80%" }
            ],
            loadingr: false,
            loadingf: false,
            loading: false,
            toggle_exclusive: 0,
            search: "",
            addShown: false,
            userAddKeyText: "",
            userRemoveKeyText: "",
            keyflag: 10,
            logs: false
        };
    },

    async created() {
        Echo.private("fleetkeys").listen("fleetkeysupdate", e => {
            this.refresh();
        });
    },

    async mounted() {
        await this.$store.dispatch("getUserKeys");
        await this.$store.dispatch("getKeys");
    },

    methods: {
        filterKeys(keys) {
            // console.log(roles);
            return keys;
        },

        async refresh() {
            await this.$store.dispatch("getUserKeys");
            await this.$store.dispatch("getKeys");
        },

        filterDropdownList(item) {
            let keyID = item.map(i => i.id);
            const filter = this.keysList.filter(r => !keyID.includes(r.id));
            if (this.$can("edit_fleet_keys")) {
                return filter;
            }
        },

        pillClose(name) {
            if (this.$can("edit_fleet_keys")) {
                return true;
            } else {
                return false;
            }
        },

        async userAddKey(item) {
            var request = {
                keyId: this.userAddKeyText,
                userId: item.id
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/rolesadd",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.$store.dispatch("getUsers");
            request = null;
            request = {
                keyId: this.userAddkeyText,
                userId: item.id,
                user_id: this.$store.state.user_id,
                type: 15
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/checkroleaddremove",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            if (this.$can("view_admin_logs")) {
                await this.$store.dispatch("getLoggingAdmin");
            }
        },

        mittin(item) {
            if (item.id == 92) {
                return "rainbow-2";
            } else {
                return;
            }
        },

        async userRemoveKey(item) {
            var request = {
                keyId: this.userRemoveKeyText,
                userId: item.id
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/rolesremove",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.$store.dispatch("getUsers");

            request = null;
            request = {
                keyId: this.userAddKeyText,
                userId: item.id,
                user_id: this.$store.state.user_id,
                type: 16
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/checkroleaddremove",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },

    computed: {
        ...mapState(["userkeys", "keysList"]),
        filteredItems() {
            return this.userkeys;
        }
    },
    beforeDestroy() {
        Echo.leave("fleetkeys");
    }
};
</script>
<style scoped>
.rainbow-2:hover {
    background-image: linear-gradient(
        to right,
        red,
        orange,
        yellow,
        green,
        blue,
        indigo,
        violet,
        red
    );
    animation: slidebg 2s linear infinite;
}

@keyframes slidebg {
    to {
        background-position: 20vw;
    }
}

.follow {
    margin-top: 40px;
}

.follow a {
    color: black;
    padding: 8px 16px;
    text-decoration: none;
}
</style>
