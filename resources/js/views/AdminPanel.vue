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
                            @click="roleflag = 5"
                        >
                            Recon
                        </v-btn>
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 6"
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
                            @click="roleflag = 12"
                        >
                            FC
                        </v-btn>
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 9"
                        >
                            GSFOE FC
                        </v-btn>
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 8"
                        >
                            GSFOE
                        </v-btn>
                        <v-btn
                            :loading="loadingf"
                            :disabled="loadingf"
                            @click="roleflag = 7"
                        >
                            Scouts
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
                                    class=" pr-2"
                                >
                                    <v-chip
                                        pill
                                        :class="mittin(item)"
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

    async created() {
        Echo.private('userupdate')
        .listen('UserUpdate', (e) => {
        this.refresh()
    })


    },

    async mounted() {
        await this.$store.dispatch("getUsers");
        await this.$store.dispatch("getRoles");
    },



    methods: {

        filterRoles(roles) {
            // console.log(roles);
            return roles.filter(r => r.name != "Super Admin");
        },

    async refresh(){
            await this.$store.dispatch("getUsers");
            await this.$store.dispatch("getRoles");
        },

        filterDropdownList(item) {
            let roleID = item.map(i => i.id);
            const filter = this.rolesList.filter(r => !roleID.includes(r.id));
            if(this.$can("edit_all_users")){
                return filter;
            }else if(this.$can("edit_recon_users") && this.$can("edit_scout_users")){
                return filter.filter(f => f.name == "Recon" || f.name == "Scout")
            }else if(this.$can("edit_gsfoe_fc")){
                return filter.filter(f => f.name == "GSFOE FC")
            }else if(this.$can("edit_scout_users")){
                return filter.filter(f => f.name == "Scout")
            }

        },

        pillClose(name) {
            if (this.$can("edit_all_users")) {
                if (name == "Wizard") {
                    return false;
                } else {
                    return true;
                }
            }else if(this.$can("edit_recon_users") && this.$can("edit_scout_users")) {
                if (name == "Recon" || name == "Scout") {
                    return true;
                } else {
                    return false;
                }
            }else if(this.$can("edit_gsfoe_fc")){
                if (name == "GSFOE FC") {
                    return true;
                } else {
                    return false;
                }
            }else if (this.$can("edit_scout_users")) {
                if (name == "Scout") {
                    return true;
                } else {
                    return false;
                }
            }

            // if(this.$can("edit_all_users")){

            //     if(name == "Wizhard"){

            //     }else{

            //     }

            // }else if(this.$can("edit_scout_users")){

            //     if(name == "Scout"){

            //     }else{

            //     }
            // }else if(this.$can("edit_hack_users")){

            //     if(name == "Hacker"){

            //     }else
            // }



        },

        async userAddRole(item) {
            var request = {
                roleId: this.userAddRoleText,
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
        },

        mittin(item) {
            if (item.id == 92) {
                return "rainbow-2";
            } else {
                return;
            }
        },

        async userRemoveRole(item) {
            var request = {
                roleId: this.userRemoveRoleText,
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
        }
    },

    computed: {
        ...mapState(["users", "rolesList"]),
        filteredItems() {
            if (this.roleflag == 5) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 5;
                    });
                });
            }
            if (this.roleflag == 6) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 6;
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
            if (this.roleflag == 7) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 7;
                    });
                });
            }
            if (this.roleflag == 9) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 9;
                    });
                });
            }
            if (this.roleflag == 12) {
                return this.users.filter(function(u) {
                    return u.roles.some(function(role) {
                        return role.id == 12;
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
    beforeDestroy() {
        Echo.leave('userupdate');
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
