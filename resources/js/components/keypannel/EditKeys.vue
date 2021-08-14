<template>
    <div>
        <v-dialog
            v-model="overlay"
            max-width="1200px"
            max-hight="1200px"
            z-index="0"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    class="mr-4"
                    color="green lighten-1"
                    v-bind="attrs"
                    v-on="on"
                    >Edit Keys</v-btn
                >
            </template>

            <div>
                <errorMessage></errorMessage>
                <v-row no-gutters justify="center">
                    <v-col class=" d-inline-flex">
                        <v-card
                            tile
                            flat
                            color="#121212"
                            class="d-inline-flex align-content-start"
                        >
                            <v-card-title>Edit Keys</v-card-title>
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
                                    'items-per-page-options': [
                                        10,
                                        20,
                                        50,
                                        100,
                                        -1
                                    ]
                                }"
                                class="elevation-5"
                            >
                                <template v-slot:[`item.keys`]="{ item }">
                                    <div class=" d-inline-flex">
                                        <v-menu>
                                            <template
                                                v-slot:activator="{ on, attrs }"
                                            >
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
                                                        (userAddKeyText =
                                                            list.id),
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
                                                :close="pillClose(key.name)"
                                                dark
                                                @click:close="
                                                    (userRemoveKeyText =
                                                        key.id),
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
            </div>
        </v-dialog>
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
            overlay: false,
            loading: false,
            toggle_exclusive: 0,
            search: "",
            addShown: false,
            userAddKeyText: "",
            userRemoveKeyText: "",
            keyflag: 0,
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
        await this.$store.dispatch("getFleets");
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

            var filter = this.keysList.filter(r => !keyID.includes(r.id));
            filter = filter.filter(r => r.name != "All");
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
                key_type_id: this.userAddKeyText,
                user_id: item.id
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/keysadd",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async userRemoveKey(item) {
            var request = {
                key_type_id: this.userRemoveKeyText,
                user_id: item.id
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/keysremove",
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
            var keyid = this.keyflag;
            if (this.keyflag != 0) {
                return this.userkeys.filter(function(u) {
                    return u.keys.some(function(key) {
                        return key.id == keyid;
                    });
                });
            } else {
                return this.userkeys;
            }
        },

        buttonList() {
            var list = this.keysList;
            var data = {
                id: 0,
                name: "All"
            };
            list.push(data);
            list.sort(function(a, b) {
                return a.id - b.id || a.name.localeCompare(b.name);
            });

            return list;
        }
    },
    beforeDestroy() {
        Echo.leave("fleetkeys");
    }
};
</script>

<style></style>
