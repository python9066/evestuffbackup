<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showInfo"
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
                    <div v-if="stage == 1">
                        <v-text-field
                            v-model="stationName"
                            outlined
                            label="Enter Structure Name here"
                            prepend-icon="faSvg fa-home"
                        ></v-text-field>
                        <v-btn :disabled="stationNameNext">
                            Next
                        </v-btn>
                    </div>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn></v-card-actions
                >
            </v-card>

            <!-- <ShowInfo
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showInfo = false"
            >
            </ShowInfo> -->
        </v-dialog>
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
            systems: [],
            stationName: null,
            stage: 1
        };
    },

    methods: {
        close() {
            this.showInfo = false;
        },

        async open() {
            await this.$store.dispatch("getSystemList");
        }
    },

    computed: {
        ...mapGetters([]),

        stationNameNext() {
            if (this.stationName != null) {
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
