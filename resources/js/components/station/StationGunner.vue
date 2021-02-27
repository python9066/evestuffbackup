<template>
    <div class=" d-inline-flex align-items-md-center  pl-4">
        <div>
            <span class="d-inline-flex align-items-md-center pr-2">
                <span class="pl-2" v-show="station.gunner_id > 0">
                    {{ station.gunner_name }}
                </span>
            </span>
        </div>
        <div>
            <v-tooltip
                color="#121212"
                bottom
                :open-delay="2000"
                :disabled="$store.state.tooltipToggle"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        v-show="station.gunner_id < 1"
                        :key="'gunnerbutton' + station.gunner_id"
                        class=""
                        color="blue"
                        x-small
                        outlined
                        @click="gunnerAdd()"
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon x-small dark>
                            fas fa-plus
                        </v-icon>
                        Gunner</v-btn
                    >
                </template>
                <span>
                    Gunners can assign themselfs here
                </span>
            </v-tooltip>
            <v-icon
                v-show="station.gunner_id > 0 && $can('gunner')"
                color="orange darken-3"
                small
                @click="gunnerRemove()"
            >
                fas fa-trash-alt
            </v-icon>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        station: Object
    },
    data() {
        return {};
    },

    methods: {
        async gunnerAdd() {
            var data = {
                id: this.station.id,
                gunner_id: this.$store.state.user_id,
                gunner_name: this.$store.state.user_name
            };

            this.$store.dispatch("updateStationNotification", data);

            var request = null;
            request = {
                gunner_id: this.$store.state.user_id
            };

            await axios({
                method: "put",
                url: "/api/updatestationnotification/" + this.station.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async gunnerRemove() {
            var request = {
                gunner_id: null
            };

            await axios({
                method: "put",
                url: "/api/updatestationnotification/" + this.station.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },

    computed: {}
};
</script>

<style></style>
