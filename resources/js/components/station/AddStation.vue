<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showStationTimer"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="green"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="open()"
                    ><v-icon>
                        faSvg fa-plus
                    </v-icon>
                    Add Timer
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
                    Enter Structure Details
                </v-card-title>
                <v-card-text>
                    <v-fade-transition mode="in-out">
                        <div v-if="state == 1">
                            <v-text-field
                                v-model="stationNameEdit"
                                outlined
                                label="Enter FULL Structure Name here"
                                prepend-icon="faSvg fa-home"
                            ></v-text-field>
                            <v-chip
                                pill
                                class="ml-10"
                                :disabled="stationNameNext"
                                color="green"
                                @click="stationNameAdd()"
                            >
                                Next
                            </v-chip>
                        </div>
                    </v-fade-transition>

                    <v-fade-transition mode="out-in">
                        <div v-if="state == 2">
                            <v-text-field
                                v-model="stationName"
                                readonly
                                prepend-icon="faSvg fa-home"
                            ></v-text-field>
                            <v-autocomplete
                                v-model="systemEdit"
                                :items="systemList"
                                label="Filled"
                                outlined
                                prepend-icon="faSvg fa-home"
                            ></v-autocomplete>
                            <v-text-field
                                v-model="tickerEdit"
                                outlined
                                label="Corp Ticker"
                                prepend-icon="faSvg fa-home"
                            ></v-text-field>
                            <v-chip
                                pill
                                class="ml-10"
                                :disabled="stationNameNext"
                                color="green"
                                @click="stationNameAdd()"
                            >
                                Next
                            </v-chip>
                        </div>
                    </v-fade-transition>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn></v-card-actions
                >
            </v-card>

            <!-- <showStationTimer
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showStationTimer = false"
            >
            </showStationTimer> -->
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {},
    data() {
        return {
            systems: [],
            stationNameEdit: null,
            state: 1,
            showStationTimer: false,
            stationName: null,
            systemEdit: null,
            tickerEdit: null
        };
    },

    methods: {
        close() {
            this.showStationTimer = false;
        },

        async open() {
            await this.$store.dispatch("getSystemList");
        },

        async stationNameAdd() {
            var request = {
                stationName: this.stationNameEdit
            };
            await axios({
                method: "put", //you can set what request you want to be
                url: "api/stationname",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            }).then(response => {
                let res = response.data;
                this.stationName = res.station_name;
                this.state = res.state;
            });
        }
    },

    computed: {
        ...mapGetters([]),
        ...mapState(["systemlist"]),

        stationNameNext() {
            if (this.stationNameEdit == null) {
                return true;
            } else {
                return false;
            }
        },

        systemList() {
            var list = this.systemlist;
            return list.filter(list => list.text == this.systemEdit);
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
