<template>
    <span>
        {{ CampaignSolaSystem[0]["last_checked_user_name"] }}
        checked [hh:mm:ss] ago
        <!-- {{ CampaignSolaSystem[0]["id"] }} -->
    </span>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        CampaignSolaSystem: Object
    },
    data() {
        return {};
    },

    methods: {},

    computed: {}
};
</script>

<style></style>
