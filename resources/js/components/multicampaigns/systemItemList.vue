<template>
    <div>
        <!-- <v-card>
            <v-list>
                <v-list-item
                    v-for="(list, index) in filterDropdownList(item.roles)"
                    :key="index"
                    @click="(userAddRoleText = list.id), userAddRole(item)"
                >
                    <v-list-item-title>{{ list.name }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-card> -->
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
        };
    },

    created() {
    },

    methods: {

        addCampaignClose() {
            this.picked = [];
            this.name = "";
        }
    },

    computed: {

        getlist() {

             axios({
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

            return res.data.list
        },


    }
};
</script>
