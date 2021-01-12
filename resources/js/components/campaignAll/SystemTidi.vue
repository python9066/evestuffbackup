<template>
    <div class=" d-flex justify-content-end">
        System TiDi: {{ CampaignSolaSystem[0]["tidi"] }}
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
