<template>
    <div>
        <v-icon color="red" @click="updateTimer()">
            {{ icon }}
        </v-icon>

        <!-- <ShowAttackNodeNotes
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showAttackNodeNotes = false"
            >
            </ShowAttackNodeNotes> -->
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
        return {};
    },

    async created() {},

    methods: {
        updateTimer() {}
    },

    computed: {
        icon() {
            return "fas fa-crosshairs";
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
