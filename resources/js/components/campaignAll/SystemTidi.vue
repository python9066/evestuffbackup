<template>
    <div class=" pr-4">
        System TiDi:
        <span class="green--text">{{ CampaignSolaSystem[0]["tidi"] }}</span>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        CampaignSolaSystem: Array
    },
    data() {
        return {};
    },

    methods: {},

    computed: {}
};
</script>

<style></style>
