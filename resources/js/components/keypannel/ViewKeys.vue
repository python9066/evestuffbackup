<template>
    <div>
        <v-card tile max-width="1200px" min-height="200px">
            <v-card-title class="d-flex justify-space-between align-center ">
                <div>Table of Keys fefefe</div>
                <v-card
                    width="1200px"
                    tile
                    flat
                    color="#121212"
                    class="align-start"
                >
                </v-card>
            </v-card-title>
            <v-card-text> </v-card-text>
        </v-card>
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
                { text: "Fleets", value: "fleets", width: "80%" },
                { text: "", value: "addRemove", align: "end" }

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            newKeyName: null,
            loadingr: false,
            loadingf: false,
            loading: false,
            toggle_exclusive: 0,
            addShown: false,
            overlay: false,
            search: "",
            keyAddFleetText: "",
            keyRemoveFleetText: ""
        };
    },

    methods: {
        filterDropdownList(item) {
            let fleetID = item.map(i => i.id);

            var filter = this.fleets.filter(r => !fleetID.includes(r.id));
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

        fliterFleets(fleets) {
            // console.log(roles);
            return fleets;
        },
        close() {
            this.overlay = false;
        },

        async keyAddFleet(item) {
            var request = {
                fleet_type_id: this.keyAddFleetText,
                key_type_id: item.id
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/fleetssadd",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        newKeyClose() {
            this.addShown = false;
            this.newFleetName = null;
        },

        async keyRemoveFleet(item) {
            var request = {
                fleet_type_id: this.keyRemoveFleetText,
                key_type_id: item.id
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "/api/fleetsremove",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async pillDelete(item) {
            await axios({
                method: "DELETE",
                url: "/api/keydelete/" + item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async NewKeySubmit() {
            var request = {
                name: this.newKeyName
            };

            await axios({
                method: "PUT",
                url: "/api/keynew",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Contect-Type": "application/json"
                }
            });

            this.addShown = false;
            this.newKeyName = null;
        },

        async closeAdd() {
            this.addShown = false;
            this.newFleetName = null;
        }
    },

    computed: {
        ...mapState(["keyfleets", "fleets"]),
        filteredItems() {
            return this.keyfleets;
        }
    }
};
</script>

<style></style>
