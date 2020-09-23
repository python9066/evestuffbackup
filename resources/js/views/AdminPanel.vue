<template>
    <div class="pr-16 pl-16">
        <v-row no-gutters justify="center">
            <v-col class=" d-inline-flex" cols="8">
                <v-card
                    tile
                    flat
                    color="#121212"
                    class="d-inline-flex align-content-start"
                >
                    <v-card-title>Add/Remove Roles</v-card-title>
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
                <v-spacer></v-spacer>
                <v-card tile flat color="#121212" class="align-end">
                    <v-btn-toggle
                        right
                        v-model="toggle_exclusive"
                        mandatory
                        :value="1"
                    >
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 10"
                        >
                            All
                        </v-btn>
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 2"
                        >
                            Recon
                        </v-btn>
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 5"
                        >
                            Ops
                        </v-btn>
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 4"
                        >
                            Cord
                        </v-btn>
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 8"
                        >
                            Hacker
                        </v-btn>
                    </v-btn-toggle>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters justify="center">
            <v-col
                class=" d-inline-flex justify-content-center w-auto"
                cols="8"
            >
                <v-card width="100%">
                    <v-data-table
                        :headers="headers"
                        :items="filteredItems"
                        item-key="id"
                        :loading="loading"
                        :items-per-page="25"
                        :search="search"
                        :footer-props="{
                            'items-per-page-options': [15, 25, 50, 100, -1]
                        }"
                        :sort-by="['name']"
                        :sort-desc="[false, true]"
                        multi-sort
                        class="elevation-1"
                    >
                        <template v-slot:item.roles="{ item }">
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
                                                item.roles
                                            )"
                                            :key="index"
                                            @click="
                                                (userAddRoleText = list.id),
                                                    userAddRole(item)
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
                                    v-for="(role, index) in filterRoles(
                                        item.roles
                                    )"
                                    :key="index"
                                    class=" pr-2 rainbow rainbow-2"
                                >
                                    <v-chip
                                        pill
                                        class="rainbow-button"
                                        :close="pillClose(role.name)"
                                        dark
                                        @click:close="
                                            (userRemoveRoleText = role.id),
                                                userRemoveRole(item)
                                        "
                                    >
                                        <span> {{ role.name }}</span>
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
                { text: "Roles", value: "roles", width: "80%" }
            ],
            loadingr: false,
            loadingf: false,
            loading: false,
            toggle_exclusive: 0,
            search: "",
            addShown: false,
            userAddRoleText: "",
            userRemoveRoleText: "",
            roleflag: 10
        };
    },

    async created() {},

    async mounted() {
        await this.$store.dispatch("getUsers");
        await this.$store.dispatch("getRoles");
    },
    methods: {
        test(item) {
            // console.log(item)
        },

        filterRoles(roles) {
            // console.log(roles);
            return roles.filter(r => r.name != "Super Admin");
        },

        filerRolesByUser(item) {
            return this.roles.filter(
                roles =>
                    roles.user_id == item.id && roles.role_name != "Super Admin"
            );
        },

        filterDropdownList(item) {
            let roleID = item.map(i => i.id);
            const filter = this.rolesList.filter(r => !roleID.includes(r.id));
            return filter;
        },

        pillClose(name){
            if(name == "Wizhard"){
                return false
            }else{
                return true
            }
        },

        async userAddRole(item) {
            var request = {
                roleId: this.userAddRoleText,
                userId: item.id
            };

            console.log(request);

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
        },

        async userRemoveRole(item) {
            var request = {
                roleId: this.userRemoveRoleText,
                userId: item.id
            };

            console.log(request);

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
        }
    },

    computed: {
        ...mapState(["users", "rolesList"]),
        filteredItems() {
            var timers = this.$store.state.timers;
            if (this.roleflag == 2) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 2;
                    });
                });
            }
            if (this.roleflag == 5) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 5;
                    });
                });
            }
            if (this.roleflag == 4) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 4;
                    });
                });
            }

            if (this.roleflag == 8) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 8;
                    });
                });
            } else {
                return this.users;
            }
        }
    },
    beforeDestroy() {}
};
</script>
<style>
.rainbow-2:hover{
  background-image: linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet, red);
  animation:slidebg 2s linear infinite;
}
</style>
