<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showStationNotes"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-badge
                    color="green"
                    overlap
                    :content="messageCount"
                    :value="showNumber"
                >
                    <v-icon
                        color="blue"
                        v-bind="attrs"
                        v-on="on"
                        @click="open()"
                    >
                        {{ icon }}
                    </v-icon>
                </v-badge>
            </template>

            <v-card
                tile
                max-width="700px"
                min-height="200px"
                max-height="700px"
                class=" d-flex flex-column"
            >
                <v-card-title
                    >Notes for Station {{ station.name }}.
                </v-card-title>
                <v-card-text>
                    <v-textarea
                        height="400px"
                        readonly
                        no-resize
                        v-model="station.notes"
                        outlined
                        placeholder="No Notes"
                    ></v-textarea>
                    <v-divider></v-divider>
                    <div>
                        <v-text-field
                            v-model="editText"
                            auto-grow
                            filled
                            autofocus
                            label="Enter New Notes Here"
                        ></v-text-field>
                    </div>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn
                        class="white--text"
                        color="green"
                        @click="updatetext()"
                        :disabled="submitActive"
                    >
                        Submit
                    </v-btn>

                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn></v-card-actions
                >
            </v-card>

            <!-- <ShowStationNotes
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showStationNotes = false"
            >
            </ShowStationNotes> -->
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
            messageCount: 0,
            showNumber: false,
            showStationNotes: false,
            editText: null
        };
    },

    async created() {
        Echo.private("rcsheet").listen("RcSheetMessageUpdate", e => {
            if (e.flag.id == this.station.id) {
                this.$store.dispatch("updateRcStation", e.flag.message);
                this.showNumber = true;
                if (this.showStationNotes == false) {
                    this.messageCount = this.messageCount + 1;
                }
            }
        });
    },

    methods: {
        close() {
            this.editText = null;
            this.showStationNotes = false;
        },

        open() {
            (this.showNumber = false), (this.messageCount = 0);
        },

        updatetext() {
            this.editText = this.editText + "\n";
            if (this.station.notes == null) {
                var note =
                    moment.utc().format("HH:mm:ss") +
                    " - " +
                    this.$store.state.user_name +
                    ": " +
                    this.editText;
            } else {
                var note =
                    moment.utc().format("HH:mm:ss") +
                    " - " +
                    this.$store.state.user_name +
                    ": " +
                    this.editText +
                    this.station.notes;
            }

            this.station.notes = note;
            let request = {
                notes: note
            };
            this.$store.dispatch("updateStationNotification", this.station);
            axios({
                method: "put",
                url: "/api/sheetmessage/" + this.station.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            this.editText = null;
        }
    },

    computed: {
        icon() {
            if (this.station.notes == null) {
                return "far fa-comment-alt";
            } else {
                return "fas fa-comment-alt";
            }
        },

        submitActive() {
            if (this.editText != null) {
                return false;
            } else {
                return true;
            }
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
