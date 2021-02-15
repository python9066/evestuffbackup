<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showInfo"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-icon color="blue" v-bind="attrs" v-on="on" @click="open()">
                    faSvg fa-info-circle
                </v-icon>
            </template>

            <v-card
                tile
                max-width="700px"
                min-height="200px"
                max-height="700px"
                class=" d-flex flex-column"
            >
                <v-card-title class="justify-center"
                    ><p>
                        {{ station.station_name }}
                    </p>
                </v-card-title>
                <v-card-subtitle>
                    <p>
                        Cored: <strong :class="textcolor"> {{ core }} </strong>
                    </p>
                </v-card-subtitle>
                <v-card-text>
                    <div v-if="fitted == true">
                        <v-chip class=" ma-2"> anti cap </v-chip>
                        <v-chip class=" ma-2"> anti subcap </v-chip>
                        <v-chip class=" ma-2"> biochemical </v-chip>
                        <v-chip class=" ma-2"> campital shipyard </v-chip>
                        <v-chip class=" ma-2"> cloning </v-chip>
                        <v-chip class=" ma-2"> composite </v-chip>
                        <v-chip class=" ma-2"> dooms day </v-chip>
                        <v-chip class=" ma-2"> guide bombs </v-chip>
                        <v-chip class=" ma-2"> hyasyoda </v-chip>
                        <v-chip class=" ma-2"> invention </v-chip>
                        <v-chip class=" ma-2"> manufacturing </v-chip>
                        <v-chip class=" ma-2"> moon drilling </v-chip>
                        <v-chip class=" ma-2"> point defense </v-chip>
                        <v-chip class=" ma-2"> reprocessing </v-chip>
                        <v-chip class=" ma-2"> research </v-chip>
                        <v-chip class=" ma-2"> supercapital shipyard </v-chip>
                        <v-chip class=" ma-2"> t2 rigged </v-chip>
                    </div>
                    <div v-if="fitted == false">
                        No Info
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
            showInfo: false,
            editText: null,
            editAdashLink: null,
            fitted: false
        };
    },

    methods: {
        close() {
            this.showInfo = false;
            console.log("close");
        },

        openAdash(url) {
            var win = window.open(url, "_blank");
            win.focus();
        },

        open() {}
    },

    computed: {
        ...mapGetters([
            "getStationItemsByStationID",
            "getCoreByStationID",
            "getStationFitByStationID"
        ]),

        items() {
            return this.getStationItemsByStationID(this.station.id);
        },

        fit() {
            var fit = this.getStationFitByStationID(this.station.id);

            if (fit != "NO") {
                this.fitted = true;
            }

            return fit;
        },

        core() {
            var core = this.getCoreByStationID(this.station.id);
            var count = this.getCoreByStationID(this.station.id).length;

            if (count == 0) {
                return "No";
            }

            if (core.cored == "Yes") {
                return "Yes";
            } else {
                return "No";
            }
        },

        textcolor() {
            if (this.core == "Yes") {
                return "green--text";
            } else {
                return "red--text";
            }
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
