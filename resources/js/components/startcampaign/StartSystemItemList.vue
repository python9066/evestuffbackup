<template>
    <div class=" d-inline-flex">
        <span v-if="systemcount">
            <span v-for="(system, index) in systems" :key="index" class=" pr-2">
                <v-chip pill color="Red" dark>
                    <span> {{ system.text }}</span>
                </v-chip>
            </span>
        </span>
        <span v-else>
            <div>
                All Campaigns have finished
            </div>
        </span>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        campaignID: Number
    },
    data() {
        return {
            test: ""
        };
    },

    created() {},
    async mounted() {},

    methods: {},

    computed: {
        ...mapGetters(["getCampaignJoinById"]),

        systems() {
            return this.getCampaignJoinById(this.campaignID);
        },

        systemcount() {
            let count = this.getCampaignJoinById(this.campaignID).length;
            if (count == 0) {
                return false;
            } else {
                return true;
            }
        }
    }
};
</script>
