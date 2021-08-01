<template>
    <div>
        <v-icon color="blue" @click="updateTimer()">
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
        updateTimer() {
            let request = {
                user_id: this.$store.state.user_id
            };
            axios({
                method: "put",
                url: "/api/recontasksystemtimeupdate/" + this.item.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        }
    },

    computed: {
        icon() {
            return "fas fa-check-circle";
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
