<template>
    <div>
        things
        <v-row no-gutters class="pb-2" justify="space-around">
            <v-col md="10">
                <v-card class="pr-2 pb-2 pl-2" tile width="100%">
                    <v-card-title
                        align="center"
                        class="justify-center align-center"
                    >
                        <p class=" pt-5">
                            Inital Campaign page for the
                            {{ this.startName }}
                        </p>
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {},
    data() {
        return {
            campaignId: 0,
            campaign_id: ""
        };
    },

    async mounted() {},

    async created() {
        this.campaignId = this.$route.params.id;
        this.campaign_id = parseInt(this.$route.params.id);
    },

    methods: {},

    computed: {},

    beforeDestroy() {}
};
</script>

<style></style>
