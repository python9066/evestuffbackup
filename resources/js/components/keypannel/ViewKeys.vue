<template>
    <div>
        <v-card tile max-width="1200px" min-height="200px">
            <v-card-title class="d-flex justify-space-between align-center ">
                <div>Table of Keys fefefe</div>
                <v-card
                    width="1200px"
                    tile
                    flat
                    color="#121212"
                    class="align-start"
                >
                </v-card>
            </v-card-title>
            <v-card-text> </v-card-text>
        </v-card>
    </div>
</template>

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
