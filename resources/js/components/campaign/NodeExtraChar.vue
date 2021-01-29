<template>
    <div>
        <v-menu offset-y v-if="checkShowAdd(item)">
            <template v-slot:activator="{ on, attrs }">
                <div>
                    <v-btn v-bind="attrs" v-on="on" icon color="green darken-3">
                        <v-icon small>fas fa-plus</v-icon>
                    </v-btn>
                </div>
            </template>

            <v-list>
                <v-list-item
                    v-for="(list, index) in charsFree"
                    :key="index"
                    @click="(charAddNode = list.id), clickCharAddNode(item)"
                >
                    <v-list-item-title>{{ list.char_name }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        item: Object
    },
    data() {
        return {};
    },

    methods: {
        showAdd() {
            this.$emit("openAdd", this.item);
        },

        clickCharAddNode(item) {
            var addChar = this.chars.find(user => user.id == this.charAddNode);
            var request = {
                campaign_id: item.campaign_id,
                campaign_system_id: item.id,
                campaign_user_id: addChar.id,
                campaign_system_status_id: item.status_id
            };

            axios({
                method: "post", //you can set what request you want to be
                url: "/api/nodejoin/" + item.campaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            request = null;
            request = {
                campaign_system_id: item.id,
                status_id: 4,
                system_id: item.system_id
            };

            axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignusersrecords/" +
                    addChar.id +
                    "/" +
                    item.campaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            console.log(request);
            console.log(item);
            console.log(addChar);
            // this.$store.dispatch("updateCampaignSystem", data);

            // data = null;
            // data = {
            //     id: addChar.id,
            //     campaign_system_id: item.id,
            //     node_id: item.node,
            //     system_id: item.system_id,
            //     system_name: item.system_name,
            //     status_id: 4,
            //     user_status_name: "Hacking"
            // };

            // var request1 = {
            //     campaign_system_id: item.id,
            //     system_id: item.system_id,
            //     status_id: 4
            // };
            // this.$store.dispatch("updateCampaignUsers", data);

            // axios({
            //     method: "put", //you can set what request you want to be
            //     url: "/api/campaignsystems/" + item.id + "/" + this.campaign_id,
            //     data: request,
            //     headers: {
            //         Authorization: "Bearer " + this.$store.state.token,
            //         Accept: "application/json",
            //         "Content-Type": "application/json"
            //     }
            // });

            // axios({
            //     method: "put", //you can set what request you want to be
            //     url:
            //         "/api/campaignusers/" + addChar.id + "/" + this.campaign_id,
            //     data: request1,
            //     headers: {
            //         Authorization: "Bearer " + this.$store.state.token,
            //         Accept: "application/json",
            //         "Content-Type": "application/json"
            //     }
            // });
        },

        checkShowAdd(item) {
            if (
                item.user_name != null &&
                this.freecharCount != 0 &&
                item.status_id != 4 &&
                item.status_id != 5 &&
                item.status_id != 7 &&
                item.status_id != 8
            ) {
                return true;
            } else {
                return false;
            }
        }
    },

    computed: {
        ...mapGetters([
            "getCampaignUsersByUserIdEntosisFree",
            "getCampaignUsersByUserIdEntosisCount",
            "getCampaignUsersByUserIdEntosisFreeCount",
            "getCampaignUsersByUserIdEntosis"
        ]),

        charsFree() {
            return this.getCampaignUsersByUserIdEntosisFree(
                this.$store.state.user_id
            );
        },

        charCount() {
            return this.getCampaignUsersByUserIdEntosisCount(
                this.$store.state.user_id
            );
        },

        freecharCount() {
            return this.getCampaignUsersByUserIdEntosisFreeCount(
                this.$store.state.user_id
            );
        },

        chars() {
            return this.getCampaignUsersByUserIdEntosis(
                this.$store.state.user_id
            );
        }
    }
};
</script>

<style></style>
