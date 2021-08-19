<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showDoneOverlay"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    small
                    v-bind="attrs"
                    v-on="on"
                    @click="open()"
                    :color="pillColor()"
                >
                    {{ buttontext() }} - Done
                    <v-icon right> faSvg fa-check-circle</v-icon>
                </v-btn>
            </template>

            <v-card
                tile
                max-width="700px"
                min-height="200px"
                max-height="1000px"
                class=" d-flex flex-column"
            >
                <v-card-title class="justify-center">
                    <p>What is the Status of {{ item.name }}</p>
                </v-card-title>

                <v-card-text>
                    <AddTimerFromDone @timeropen="close()"></AddTimerFromDone>
                    <v-btn color="amber accent-2" @click="statusUpdate(4)">
                        Repaired</v-btn
                    >
                    <v-btn color="red" @click="softDestroyed()">
                        Destoryed</v-btn
                    >
                    <v-btn color="brown lighten-2" @click="statusUpdate(18)">
                        Unknown</v-btn
                    >
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn color="red" @click="close()"> Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        item: Object
    },

    async created() {},
    data() {
        return {
            showDoneOverlay: false
        };
    },

    watch: {},

    methods: {
        pillColor() {
            if (this.item.status_id == 13) {
                return "red darken-4";
            }
            if (this.item.status_id == 5) {
                return "lime darken-4";
            }
            if (this.item.status_id == 14) {
                return "green accent-4";
            }
            if (this.item.status_id == 17) {
                return "#FF5EEA";
            }
        },
        buttontext() {
            var ret = this.item.status_name.replace("Upcoming - ", "");
            return ret;
        },

        async open() {},

        close() {
            this.showDoneOverlay = false;
        },

        async statusUpdate(statusID) {
            var data = {
                id: this.item.id,
                show_on_rc: 0
            };

            this.$store.dispatch("updateRcStation", data);

            var request = null;
            request = {
                station_status_id: statusID,
                show_on_rc: 0,
                show_on_coord: 1
            };

            await axios({
                method: "put",
                url: "/api/updatestationnotification/" + this.item.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + $store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async softDestroyed() {
            var data = {
                id: this.item.id,
                show_on_rc: 0
            };

            this.$store.dispatch("updateRcStation", data);

            await axios({
                method: "put",
                url: "/api/softdestory/" + this.item.id,
                headers: {
                    Authorization: "Bearer " + $store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },

    computed: {},

    beforeDestroy() {}
};
</script>

<style></style>
