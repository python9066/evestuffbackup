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
                <EditKeys v-if="$can('edit_fleet_keys')"></EditKeys>
                <EditFleets v-if="$can('edit_fleet_keys')"></EditFleets>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="9">
                <v-spacer></v-spacer>
                <v-card tile flat color="#121212" class="align-end">
                    <v-btn-toggle
                        right
                        v-model="toggle_exclusive"
                        mandatory
                        :value="0"
                    >
                        <v-btn
                            v-for="(list, index) in buttonList"
                            :key="index"
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="
                                (keyflag = list.id),
                                    (toggle_exclusive = list.id)
                            "
                        >
                            {{ list.name }}
                        </v-btn>
                    </v-btn-toggle>
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
                            'items-per-page-options': [10, 20, 50, 100, -1]
                        }"
                        class="elevation-5"
                    >
                        <template v-slot:[`item.keys`]="{ item }">
                            <div
                                class=" d-inline-flex"
                                v-if="$can('edit_fleet_keys')"
                            >
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
                            Nothing matches your filters
                        </template>
                    </v-data-table>
                </v-card>
                <v-card tile flat color="#121212" class="align-end">
                    <v-card v-for="(list, index) in buttonList" :key="index">
                        <v-card-text> {{ list.name }}</v-card-text>
                    </v-card>
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
            keyflag: 0,
            logs: false
        };
    },

    async created() {
        Echo.private("fleetkeys").listen("FleetKeysUpdate", e => {
            this.refresh();
        });
    },

    async mounted() {
        await this.$store.dispatch("getUserKeys");
        await this.$store.dispatch("getKeys");
        await this.$store.dispatch("getFleets");
        await this.$store.dispatch("getKeyFleets");
    },

    methods: {
        filterKeys(keys) {
            // console.log(roles);
            return keys;
        },

        async refresh() {
            await this.$store.dispatch("getUserKeys");
            await this.$store.dispatch("getKeys");
            await this.$store.dispatch("getFleets");
            await this.$store.dispatch("getKeyFleets");
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
