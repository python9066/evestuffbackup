<template>
    <div>
        <v-card>
            <v-list>
                <v-list-item
                    v-for="list in lists"
                    :key="list.text"
                    @click="(userAddRoleText = list.id), userAddRole(item)"
                >
                    <v-list-item-title>{{ list.text }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-card>
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
            list:[],
        };
    },

    created() {
    },
    async mounted() {
        await this.getlist()
    },

    methods: {

        addCampaignClose() {
            this.picked = [];
            this.name = "";
        },

       async getlist() {

            let res = await axios({
                method: "get",
                // url: "/api/campaignjoin/" + campaignID,
                url: "/api/campaignjoin/1603574888917",
                data: this.picked,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.list =  res.data
        },
    },

    computed: {



    }
};
</script>
