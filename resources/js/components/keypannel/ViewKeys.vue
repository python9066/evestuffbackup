<template>
    <v-card>
        <v-card-title>
            {{ item.name }}
        </v-card-title>

        <v-card-text> </v-card-text>
    </v-card>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        item: Object
    },
    data() {},

    methods: {},

    computed: {
        ...mapState(["keyfleets", "fleets"]),
        filteredItems() {
            return this.keyfleets;
        }
    }
};
</script>

<style></style>
