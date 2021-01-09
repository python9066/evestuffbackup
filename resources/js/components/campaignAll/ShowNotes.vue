<template>
    <v-card tile min-width="1400px" min-height="700px">
        <v-card-title>NOTES FOR ALL THE THINGS </v-card-title>
        <v-card-text> I HAVE NO IDEA</v-card-text
        ><v-card-actions align-end
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
        campaign_id: Number
    },
    data() {
        return {};
    },

    methods: {
        close() {
            this.$emit("closeNotes", "yo");
        }
    },

    computed: {}
};
</script>

<style></style>
