<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            :value="showNodeNotes"
            @click:outside="closeNodeMessage()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-badge
                    color="green"
                    overlap
                    :content="messageCount"
                    :value="showNumber"
                >
                    <v-icon color="blue" v-bind="attrs" v-on="on">
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
                    >Notes for node {{ nodeNoteItem.node }}. Campaign
                    {{ nodeNoteItem.text }}
                </v-card-title>
                <v-card-text>
                    <v-textarea
                        height="400px"
                        readonly
                        no-resize
                        v-model="nodeNoteItem.notes"
                        outlined
                        placeholder="No Nodes"
                    ></v-textarea>
                    <v-divider></v-divider>
                    <div>
                        <v-text-field
                            v-model="editText"
                            auto-grow
                            filled
                            label="Enter New Nodes Here"
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

            <!-- <ShowNodeNotes
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showNodeNotes = false"
            >
            </ShowNodeNotes> -->
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
    data() {
        return {
            messageCount: 0,
            showNumber: false
        };
    },

    methods: {
        showMessage(item) {
            this.$emit("openMessage", item);
        }
    },

    computed: {
        icon() {
            if (this.item.notes == null) {
                return "far fa-comment-alt";
            } else {
                return "fas fa-comment-alt";
            }
        }
    }
};
</script>

<style></style>
