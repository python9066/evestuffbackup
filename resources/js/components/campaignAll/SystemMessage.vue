<template>
    <div>
        <v-badge color="green" overlap :content="messageCount">
            <v-icon color="blue">
                fas fa-comment-alt
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
            showMessage: false
        };
    },

    methods: {},

    computed: {
        icon() {}
    }
};
</script>

<style></style>
