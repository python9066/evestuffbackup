<template>
    <v-card
        tile
        min-width="700px"
        min-height="200px"
        class=" d-flex flex-column"
    >
        <v-card-title
            >Notes for node {{ nodeNoteItem.node }}. Campaign
            {{ nodeNoteItem.text }}
        </v-card-title>
        <v-card-text>
            <v-textarea
                :vlaue="nodeNoteItem.notes"
                auto-grow
                outlined
                placeholder="No notes"
                disabled
            ></v-textarea
            ><v-divider></v-divider>
            <div>
                <v-textarea
                    :value="editText"
                    auto-grow
                    filled
                    label="Enter New Nodes Here"
                ></v-textarea>
            </div>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions
            ><v-btn class="white--text" color="teal" @click="close()">
                Close
            </v-btn></v-card-actions
        >
    </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        campaign_id: Number,
        nodeNoteItem: Object
    },
    data() {
        return {
            editText: null
        };
    },

    methods: {
        close() {
            this.$emit("closeMessage", "yo");
        }
    },

    computed: {}
};
</script>

<style></style>
