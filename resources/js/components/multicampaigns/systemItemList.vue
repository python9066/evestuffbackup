<template>
    <div class=" d-inline-flex">
        <span v-if="systemcount">
            <span v-for="(system, index) in systems" :key="index" class=" pr-2">
                <v-chip pill :color="pillcolor(system)" dark>
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

    methods: {
        addCampaignClose() {
            this.picked = [];
            this.name = "";
        },

        pillcolor(system) {
            if (system.color == 1) {
                return "red darken-4";
            } else {
                return "blue darken-4";
            }
        }
    },

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
