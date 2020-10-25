<template>
    <div class=" d-inline-flex">
                                <div
                                    v-for="(system, index) in systems"
                                    :key="index"
                                    class=" pr-2"
                                >
                                    <v-chip
                                        pill
                                        dark
                                    >
                                        <span> {{ system.text }}</span>
                                    </v-chip>
                                </div>
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
            test:"",
            systems:[],
        };
    },

    created() {
    },
    async mounted() {
        await this.getSystems()
    },

    methods: {

        addCampaignClose() {
            this.picked = [];
            this.name = "";
            this.lists=[];
        },


        async getSystems() {

            let res = await axios({
                method: "get",
                url: "/api/campaignjoin/" + this.campaignID,
                // url: "/api/campaignjoin/1603574888917",
                data: this.picked,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.systems =  res.data.list
        },
    },

    computed: {


    }
};
</script>
