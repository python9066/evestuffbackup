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

            <v-card tile max-width="1200px" min-height="200px">
                <v-card-title
                    class="d-flex justify-space-between align-center "
                >
                    <div>Table of Keys</div>
                    <v-card
                        width="1200px"
                        tile
                        flat
                        color="#121212"
                        class="align-start"
                    >
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Search"
                            single-line
                            filled
                            hide-details
                        ></v-text-field>
                    </v-card>
                    <div>
                        <v-menu
                            :close-on-content-click="false"
                            :value="addShown"
                            transition="fab-transition"
                            origin="100% -30%"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    text
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="addShown = true"
                                    color="success"
                                    ><v-icon left small>fas fa-plus</v-icon>
                                    Fleet</v-btn
                                >
                            </template>
                            <v-row no-gutters>
                                <div>
                                    <v-card class="pa-2" tile width="100%">
                                        <v-text-field
                                            v-model="newFleetName"
                                            label="Fleet Name"
                                            required
                                            autofocus
                                        ></v-text-field>

                                        <v-btn
                                            color="success"
                                            class="mr-4"
                                            @click="NewFleetSubmit()"
                                            >submit</v-btn
                                        >
                                        <v-btn
                                            color="warning"
                                            class="mr-4"
                                            @click="closeAdd()"
                                            >Close</v-btn
                                        >
                                        <!-- <v-btn @click="clear">clear</v-btn> -->
                                    </v-card>
                                </div>
                            </v-row>
                        </v-menu>
                    </div>
                </v-card-title>
                <v-card-text>
                    <v-data-table
                        :headers="headers"
                        :items="filteredItems"
                        :search="search"
                        height="500px"
                        item-key="id"
                        :sort-by="['name']"
                        disable-pagination
                        fixed-header
                        hide-default-footer
                        class="elevation-24"
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
                </v-card-text>
                <v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {},
    data() {
        return {
            headers: [
                { text: "Name", value: "name" },
                { text: "", value: "addRemove", align: "end" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            newFleetName: null,

            addShown: false,
            overlay: false,
            search: ""
        };
    },

    methods: {
        close() {
            this.overlay = false;
        },

        newFleetClose() {
            this.addShown = false;
            this.newFleetName = null;
        },

        async pillDelete(item) {
            await axios({
                method: "DELETE",
                url: "/api/fleetdelete/" + item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async NewFleetSubmit() {
            var request = {
                name: this.newFleetName
            };

            console.log(this.newFleetName);

            await axios({
                method: "PUT",
                url: "/api/fleetnew",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Contect-Type": "application/json"
                }
            });

            this.addShown = false;
            this.newFleetName = null;
        },

        async closeAdd() {
            this.addShown = false;
            this.newFleetName = null;
        }
    },

    computed: {
        ...mapState(["fleets"]),
        filteredItems() {
            return this.fleets;
        }
    }
};
</script>

<style></style>
