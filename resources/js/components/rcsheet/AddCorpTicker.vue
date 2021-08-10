<template>
    <div>
        <v-dialog
            v-model="overlay"
            max-width="500px"
            z-index="0"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    class="mr-4"
                    color="green lighten-1"
                    v-bind="attrs"
                    x-small
                    v-on="on"
                    >Corp</v-btn
                >
            </template>

            <v-card tile max-width="500px" min-height="200px">
                <v-card-title
                    class="d-flex justify-space-between align-center "
                >
                    <div>Corp List</div>
                    <v-card
                        width="500"
                        tile
                        flat
                        color="#121212"
                        class="align-start"
                    >
                    </v-card>
                </v-card-title>
                <v-card-text>
                    <v-autocomplete
                        v-model="value"
                        :items="items"
                        dense
                        filled
                        label="Corp Tickers"
                    ></v-autocomplete>
                </v-card-text>
                <v-card-actions>
                    <v-btn class="white--text" color="teal" @click="done()">
                        Done
                    </v-btn>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        station: Object
    },
    data() {
        return {
            value: null
        };
    },

    methods: {
        close() {
            this.overlay = false;
        },

        newFCFormClose() {
            this.addShown = false;
            this.newFCName = null;
        },

        async pillClick(item) {
            var data = {
                id: this.station.id,
                fc_user_id: item.id,
                fc_user_name: item.name
            };

            this.$store.dispatch("updateRcStation", data);

            var request = {
                rc_fc_id: item.id
            };

            await axios({
                method: "post",
                url: "/api/rcfcadd/" + this.station.id,
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
                url: "/api/rcfcdelete/" + item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.$store.dispatch("getRcFcs");
        },

        async newFCForm() {
            var request = {
                char_name: this.newCharName
            };

            await axios({
                method: "PUT",
                url: "/api/rcfcnew",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.$store.dispatch("getRcFcs");
            this.addShown = false;
            this.newFCName = null;
        },

        async removeChar(item) {
            await axios({
                method: "DELETE",
                url:
                    "/api/campaignusers/" +
                    item.id +
                    "/" +
                    this.campaign_id +
                    "/" +
                    this.$store.state.user_id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.$store.dispatch("getCampaignSystemsRecords");
        }
    },

    computed: {
        ...mapState(["rcfcs", "ticklist"]),
        filteredItems() {
            return this.rcfcs;
        },
        items() {
            return this.ticklist;
        }
    }
};
</script>

<style></style>
