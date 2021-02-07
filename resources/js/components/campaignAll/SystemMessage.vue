<template>
    <div>
        <v-badge
            color="green"
            overlap
            :content="messageCount"
            :value="showNumber"
        >
            <v-icon color="blue" @click="showMessage(item)">
                {{ icon }}
            </v-icon>
        </v-badge>
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
