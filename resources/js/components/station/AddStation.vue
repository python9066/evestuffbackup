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
                    <transition>
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
                    </transition>
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
            stationNameEdit: "",
            state: 1,
            showStationTimer: false,
            stationName: null
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
                this.stationName = res.stationName;
                this.state = res.state;
            });
        }
    },

    computed: {
        ...mapGetters([]),

        stationNameNext() {
            if (this.stationNameEdit == null) {
                return true;
            } else {
                return false;
            }
        },

        systemList() {
            return this.$store.state("systemlist");
        },

        items() {
            return this.getStationItemsByStationID(this.station.id);
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
