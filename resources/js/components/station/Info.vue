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
                    far fa-info--circle
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
                    >Information about {{ station.name }}
                </v-card-title>
                <v-card-text>
                    erthwhteehtrehtrtehethsthesthse
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

    async created() {
        Echo.private("stationinfo." + this.station.id).listen(
            "NodeAttackMessageUpdate",
            e => {
                if (e.flag.type == 1) {
                    this.showAttackNumber = true;
                    this.messageAttackCount = this.messageAttackCount + 1;
                    this.$store.dispatch(
                        "updateCampaignSystem",
                        e.flag.message
                    );
                } else {
                    this.showAttackNumber = false;
                    this.messageAttackCount = 0;
                    this.$store.dispatch(
                        "updateCampaignSystem",
                        e.flag.message
                    );
                }
            }
        );
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

    computed: {},

    beforeDestroy() {
        Echo.leave("stationinfo." + this.station.id);
    }
};
</script>

<style></style>
