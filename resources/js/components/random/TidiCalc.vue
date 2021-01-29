<template>
    <v-card
        tile
        max-width="1500px"
        max-height="1000px"
        class=" d-flex flex-column"
    >
        <v-card-title
            class=" d-lg-inline-block justify-space-between align-center "
        >
            <div>
                Logs for Roles Changes
            </div>
            <div>
                <v-text-field
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </div>
        </v-card-title>
        <v-card-text> </v-card-text>
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
    props: {},
    data() {
        return {
            overlay: true
        };
    },

    methods: {
        close() {
            this.$emit("closeCalc", "yo");
        }
    },

    computed: {}
};
</script>

<style></style>
