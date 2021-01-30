<template>
    <div>
        <v-btn
            v-if="showAddIcon"
            icon
            color="light-blue darken-4"
            @click="showAdd"
        >
            <v-icon small>fas fa-plus</v-icon></v-btn
        >
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {},
    data() {
        return {};
    },

    methods: {},

    computed: {}
};
</script>

<style></style>
