<template>
    <div class="pr-16 pl-16">
        <v-row no-gutters
        justify="center">
            <v-col class=" d-inline-flex justify-content-end align-content-end"
            cols="8">
            <v-card tile flat color="#121212" class="mr-auto d-inline-flex align-content-start">
                <v-card-title>Add/Remove Roles</v-card-title>
                <v-btn
                    :loading="loadingr"
                    :disabled="loadingr"
                    color="primary"
                    class="ma-2 white--text"
                    @click="
                        loadingr = true;
                        loadcampaigns();
                    "
                >
                    Update
                    <v-icon right dark>fas fa-sync-alt fa-xs</v-icon>
                </v-btn>
            </v-card>
            <v-card tile flat color="#121212">
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </v-card>
            <v-card tile flat color="#121212" class="ml-auto align-end">
                <v-btn-toggle v-model="toggle_exclusive" mandatory :value="1">
                    <v-btn
                        :loading="loadingf"
                        :disabled="loadingf"
                        @click="colorflag = 4"
                    >
                        All
                    </v-btn>
                    <v-btn
                        :loading="loadingf"
                        :disabled="loadingf"
                        @click="colorflag = 3"
                    >
                        Goons
                    </v-btn>
                    <v-btn
                        :loading="loadingf"
                        :disabled="loadingf"
                        @click="colorflag = 2"
                    >
                        Friendly
                    </v-btn>
                    <v-btn
                        :loading="loadingf"
                        :disabled="loadingf"
                        @click="colorflag = 1"
                    >
                        Hostile
                    </v-btn>
                    <v-btn
                        :loading="loadingf"
                        :disabled="loadingf"
                        @click="colorflag = 5"
                    >
                        Active
                    </v-btn>
                </v-btn-toggle>
            </v-card>
            </v-col>
        </v-row>
        <v-data-table
            :headers="headers"
            :items="users"
            item-key="id"
            :loading="loading"
            :items-per-page="25"
            :footer-props="{ 'items-per-page-options': [15, 25, 50, 100, -1] }"
            :sort-by="['start']"
            :search="search"
            :sort-desc="[false, true]"
            multi-sort
            class="elevation-1"
        >
            <template slot="no-data">
                No Active or Upcoming Campaigns
            </template>
        </v-data-table>
        <errorMessage></errorMessage>
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
    data() {
        return {
            //timersAll: [],

            headers: [
                { text: "Name", value: "name" },
                { text: "Roles", value: "roles" }
            ],
            users: [],
            loadingr: false,
            loadingf: false,
            loading: false,
            toggle_exclusive: 0
        };
    },

    created() {},

    async mounted() {
        await this.getUsers();
    },
    methods: {
        async getUsers() {
            let res = await axios({
                method: "get",
                url: "/api/users",
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.users = res.data.users;
        }
    },
    computed: {
        // filteredItems() {
        //     // var timers = this.$store.state.timers;
        //     if (this.roleflag == 1) {
        //         return this.users.filter(users => users.color == 1);
        //     }
        //     if (this.roleflag == 2) {
        //         return this.users.filter(users => users.color > 1);
        //     }
        //     if (this.roleflag == 3) {
        //         return this.users.filter(users => users.color == 3);
        //     }
        //     if (this.roleflag == 5) {
        //         return this.users.filter(
        //             users => users.status_id == 2
        //         );
        //     } else {
        //         return this.users.filter(
        //             users => users.status_id != 10
        //         );
        //     }
        // }
    },
    beforeDestroy() {}
};
</script>
