<template>
    <div class=" d-inline-flex align-items-md-center  pl-4">
        <div>
            <span class="d-inline-flex align-items-md-center pr-2">
                <span class="pl-2" v-show="showRcGsolButton()">
                    {{ station.gsol_name }}
                </span>
            </span>
        </div>
        <div>
            <v-btn
                v-show="!showRcGsolButton()"
                :key="'gunnerbutton' + station.gunner_id"
                class=""
                color="blue"
                x-small
                outlined
                @click="gsolAdd()"
            >
                <v-icon x-small dark left>
                    fas fa-plus
                </v-icon>
                GSOL</v-btn
            >
            <v-icon
                v-show="showRcGsolButton()"
                color="orange darken-3"
                small
                @click="gsolRemove()"
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

    mounted() {
        this.showName;
    },

    methods: {
        showRcGsolButton() {
            if (this.station.gsol) {
                return true;
            } else {
                return false;
            }
        },

        async gsolAdd() {
            var data = {
                id: this.station.id,
                gsol_user_id: this.$store.state.user_id,
                gsol_name: this.$store.state.user_name
            };

            this.$store.dispatch("updateRcStation", data);

            var request = null;
            request = {
                user_id: this.$store.state.user_id
            };

            await axios({
                method: "put",
                url: "/api/rcgsoluseradd/" + this.station.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        async gsolRemove() {
            var data = {
                id: this.station.id,
                gsol_user_id: null,
                gsol_name: null
            };

            this.$store.dispatch("updateRcStation", data);

            await axios({
                method: "put",
                url: "/api/rcgsoluserremove/" + this.station.id,
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
