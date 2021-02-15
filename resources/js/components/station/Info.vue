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
                <v-card-title
                    >{{ station.station_name }} - Cored: {{ core }}
                </v-card-title>
                <v-card-text>
                    <v-chip class=" ma-2"> </v-chip>
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
            editAdashLink: null
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
        ...mapGetters(["getStationItemsByStationID", "getCoreByStationID"]),

        items() {
            return this.getStationItemsByStationID(this.station.id);
        },

        core() {
            var core = this.getCoreByStationID(this.station.id);
            var count = this.getCoreByStationID(this.station.id).length;
            if (count == 0) {
                return '<strong class="red--text"> No </strong>';
            }

            if (core.cored == "Yes") {
                return '<strong class="green--text"> Yes </strong>';
            } else {
                return '<strong class="red--text"> No </strong>';
            }
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
