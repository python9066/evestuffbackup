<template>
    <v-menu
        :close-on-content-click="false"
        :value="editShown"
        transition="fab-transition"
        origin="100% -30%"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-icon
                color="orange darken-3"
                v-bind="attrs"
                v-on="on"
                small
                @click="(editShown = true), charEditForm($item)"
            >
                fas fa-edit
            </v-icon>
        </template>
        <!---edit/delete form------>
        <v-row no-gutters>
            <div>
                <v-card class="pa-2" tile width="100%">
                    <v-form @submit.prevent="editCharForm()">
                        <v-text-field
                            v-model="editCharName"
                            label="Name"
                            required
                            :placehoder="item.char_name"
                        ></v-text-field>
                        <v-select
                            v-model="editRole"
                            @change="roleEditForm($event)"
                            :items="dropdown_roles"
                            label="Role"
                            required
                            :placeholder="editTextRole"
                        ></v-select>
                        <v-text-field
                            v-model="editShip"
                            label="Ship"
                            required
                            :placeholder="editTextShip"
                        ></v-text-field>
                        <v-radio-group
                            v-model="editLink"
                            row
                            label="Entosis Link"
                            required
                            :placeholder="editTextLink"
                        >
                            <v-radio label="Tech 1" value="1"></v-radio>
                            <v-radio label="Tech 2" value="2"></v-radio>
                        </v-radio-group>

                        <v-btn class="mr-2" type="submit">submit</v-btn>
                        <v-btn class="mr-2" @click="editFormRemove()"
                            >Remove</v-btn
                        >
                        <v-btn class="mr-2" @click="editFormClose()"
                            >Close</v-btn
                        >
                        <!-- <v-btn @click="clear">clear</v-btn> -->
                    </v-form>
                    <!---edit/delete form------>
                </v-card>
            </div>
        </v-row>
    </v-menu>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        item: Object
    },
    data() {
        return {
            dropdown_roles: [
                { text: "Hacker", value: 1 },
                { text: "Scout", value: 2 },
                { text: "FC", value: 3 },
                { text: "Command", value: 4 }
            ],

            editShown: false,

            editCharName: null,
            editNameRules: [v => !!v || "Name is required"],
            editRole: null,
            editTextRole: null,
            editRoleRules: [v => !!v || "You need to pick a role"],
            editShip: null,
            editTextShip: null,
            editShipRules: [v => !!v || "Ship is required"],
            editLink: null,
            editTextLink: null,
            editLinkRules: [v => !!v || "T1 or T2?"],
            editUserForm: 1,
            editrole_name: null,
        };
    },

    methods: {},

    computed: {}
};
</script>

<style></style>
