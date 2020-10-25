<template>
    <div class=" d-inline-flex">
        <span v-if="systemcount()">
        <span  v-for="(system, index) in systems" :key="index" class=" pr-2">
            <v-chip pill dark>
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
            test: "",
            systems: []
        };
    },

    created() {},
    async mounted() {
        await this.getSystems();
    },

    methods: {
        addCampaignClose() {
            this.picked = [];
            this.name = "";
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

            this.systems = res.data.value;
        },

        systemcount(){
            let count = this.systems.length
            if(count == 0){

                return false
            }else{

                return true
            }
        }
    },

    computed: {}
};
</script>
