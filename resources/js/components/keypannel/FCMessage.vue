<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showUserNotes"
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
                <v-card-title>Notes for {{ user.name }}. </v-card-title>
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

            <!-- <ShowUserNotes
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showUserNotes = false"
            >
            </ShowUserNotes> -->
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        user: Object
    },
    data() {
        return {
            messageCount: 0,
            showNumber: false,
            showUserNotes: false,
            editText: null
        };
    },

    async created() {
        Echo.private("fleetkeys").listen("RcMoveMessageUpdate", e => {
            if (e.flag.id == this.user.id) {
                this.$store.dispatch("updateKeyMessage", e.flag.message);
                this.showNumber = true;
                if (this.showUserNotes == false) {
                    this.messageCount = this.messageCount + 1;
                }
            }
        });
    },

    methods: {
        close() {
            this.editText = null;
            this.showUserNotes = false;
        },

        open() {
            (this.showNumber = false), (this.messageCount = 0);
        },

        updatetext() {
            this.editText = this.editText + "\n";
            if (this.user.notes == null) {
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
                    this.user.notes;
            }

            this.user.notes = note;
            let request = {
                fc_notes: note
            };
            this.$store.dispatch("updateKeyMessage", this.user);
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
            if (this.user.notes == null) {
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

    beforeDestroy() {
        Echo.leave("fleetkeys");
    }
};
</script>

<style></style>
