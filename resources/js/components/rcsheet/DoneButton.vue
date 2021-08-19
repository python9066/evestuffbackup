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
                    :color="pillColor(item)"
                >
                    {{ buttontext(item) }} - Done
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
                    <v-btn color="green"> Reffed</v-btn>
                    <v-btn color="green"> Reffed</v-btn>
                    <v-btn color="amber accent-2"> Repaired</v-btn>
                    <v-btn color="red"> Destoryed</v-btn>
                    <v-btn color="brown lighten-2"> Unknown</v-btn>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn color="red"> Close</v-btn>
                </v-card-actions>
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
        pillColor(item) {
            if (item.status_id == 13) {
                return "red darken-4";
            }
            if (item.status_id == 5) {
                return "lime darken-4";
            }
            if (item.status_id == 14) {
                return "green accent-4";
            }
            if (item.status_id == 17) {
                return "#FF5EEA";
            }
        },
        buttontext(item) {
            var ret = item.status_name.replace("Upcoming - ", "");
            return ret;
        },

        async open() {},

        close() {
            this.showDoneOverlay = false;
        }
    },

    computed: {},

    beforeDestroy() {}
};
</script>

<style></style>
