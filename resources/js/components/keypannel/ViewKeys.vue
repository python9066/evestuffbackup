<template> </template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {},
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
