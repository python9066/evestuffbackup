<template>
    <v-card
        tile
        min-width="700px"
        min-height="700px"
        class=" d-flex flex-column"
    >
        <v-card-title
            >Notes for node {{ nodeNoteItem.node }} from the
            {{ nodeNoteItem.text }}
            Campaign
        </v-card-title>
        <v-card-text> I HAVE NO IDEA</v-card-text>
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
        return {};
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
