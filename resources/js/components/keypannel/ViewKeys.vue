<template>
    <div>
        <v-card>
            <v-card-text>
                {{ item.name }}
            </v-card-text>
        </v-card>
    </div>
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
