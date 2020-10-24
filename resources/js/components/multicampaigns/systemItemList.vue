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
        campaignID
    },
    data() {
        return {
        };
    },

    created() {
        this.getlist
    },

    methods: {
        async getlist() {

            await axios({
                method: "get",
                url: "/api/campaignjoin/" + campaignID,
                data: this.picked,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        addCampaignClose() {
            this.picked = [];
            this.name = "";
        }
    },

    computed: {
    }
};
</script>
