<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            persistent
            v-model="showDoneCoordOverlay"
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
                    {{ buttontext() }}
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

                <v-card-text class=" d-inline-flex">
                    <AddTimerFromDoneCoord
                        @timeropen="close()"
                        :item="item"
                    ></AddTimerFromDoneCoord>
                    <v-btn
                        color="orange darken-1"
                        class=" mx-4"
                        @click="statusUpdate(1)"
                    >
                        Online</v-btn
                    >
                    <v-btn color="red" class=" mx-4" @click="destroyed()">
                        DEAD</v-btn
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
            showDoneCoordOverlay: false
        };
    },

    watch: {},

    methods: {
        pillColor() {
            if (this.item.station_status_id == 4) {
                return "orange darken-1";
            }
            if (this.item.station_status_id == 18) {
                return "brown lighten-2";
            }
            if (this.item.station_status_id == 1) {
                return "green";
            }
            if (this.item.station_status_id == 7) {
                return "red";
            }
        },
        buttontext() {
            var ret = this.item.station_status_name.replace("Upcoming - ", "");
            return ret;
        },

        async open() {},

        close() {
            this.showDoneCoordOverlay = false;
        },

        showAddTimer() {
            if (
                this.item.station_status_id == 5 ||
                this.item.station_status_id == 8
            ) {
                return true;
            } else {
                return false;
            }
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
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async destroyed() {
            await axios({
                method: "delete",
                url: "/api/rcmovedonebad/" + this.item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
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
