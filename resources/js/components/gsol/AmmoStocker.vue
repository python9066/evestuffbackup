<template>
    <div class=" d-inline-flex align-items-md-center  pl-4">
        <div>
            <span class="d-inline-flex align-items-md-center pr-2">
                <span class="pl-2" v-show="!showStockerButton">
                    {{ station.user_name }}
                </span>
            </span>
        </div>
        <div>
            <v-tooltip
                color="#121212"
                bottom
                :key="'stockertooltip' + station.id"
                :open-delay="2000"
                :disabled="$store.state.tooltipToggle"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        v-show="showStockerButton"
                        :key="'stockerbutton' + station.id"
                        class=""
                        color="blue"
                        x-small
                        outlined
                        @click="stockerAdd()"
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon x-small dark>
                            fas fa-plus
                        </v-icon>
                        Stocker</v-btn
                    >
                </template>
                <span>
                    Stocker can assign themselfs here
                </span>
            </v-tooltip>
            <v-icon
                v-show="!showStockerButton"
                color="orange darken-3"
                small
                @click="stockerRemove()"
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
        return {
            stockerName: null
        };
    },

    watch: {
        station: {
            handler() {
                this.showStockerButton;
            },
            deep: true
        }
    },

    mounted() {},

    methods: {
        async stockerAdd() {
            request = {
                user_id: this.$store.state.user_id
            };

            await axios({
                method: "post",
                url: "/api/ammorequestupdate/" + this.station.id,
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
                user_id: null
            };

            await axios({
                method: "post",
                url: "/api/ammorequestupdate/" + this.station.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },

    computed: {
        showStockerButton() {
            if (this.station.user_id == null) {
                return true;
            } else {
                return false;
            }
        }
    }
};
</script>

<style></style>
