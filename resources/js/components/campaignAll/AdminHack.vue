<template>
    <div>
        <v-btn :v-if="showAddIcon()" icon color="green darken-3">
            <v-icon small>fas fa-plus</v-icon></v-btn
        >
        <v-btn :v-if="showRemoveIcon()" icon color="red darken-3">
            <v-icon small>fas fa-trash-alt</v-icon></v-btn
        >
        <!-- <v-card
        tile
        min-width="1400px"
        min-height="700px"
        class=" d-flex flex-column"
    >
        <v-card-title>NOTES FOR ALL THE THINGS </v-card-title>
        <v-card-text> I HAVE NO IDEA</v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions
            ><v-btn class="white--text" color="teal" @click="close()">
                Close
            </v-btn></v-card-actions
        >
    </v-card> -->
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        item: Object
    },
    data() {
        return {};
    },

    methods: {
        close() {
            this.$emit("closeNotes", "yo");
        },

        showAddIcon() {
            if (this.item.user_id == null) {
                console.log("addTrue");
                return true;
            } else {
                console.log("addFalse");
                return false;
            }
        },

        showRemoveIcon() {
            if (this.item.user_id != null) {
                console.log("removeTrue");
                return true;
            } else {
                console.log("removeFalse");
                return false;
            }
        }
    },

    computed: {}
};
</script>

<style></style>
