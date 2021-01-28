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
            var data = {
                id: item.id,
                user_id: addChar.id,
                site_id: this.$store.state.user_id,
                user_name: addChar.char_name,
                main_name: addChar.main_name,
                user_ship: addChar.ship,
                user_link: addChar.link
            };

            var request = {
                campaign_user_id: addChar.id
            };
            this.$store.dispatch("updateCampaignSystem", data);

            data = null;
            data = {
                id: addChar.id,
                campaign_system_id: item.id,
                node_id: item.node,
                system_id: item.system_id,
                system_name: item.system_name,
                status_id: 4,
                user_status_name: "Hacking"
            };

            var request1 = {
                campaign_system_id: item.id,
                system_id: item.system_id,
                status_id: 4
            };
            this.$store.dispatch("updateCampaignUsers", data);

            axios({
                method: "put", //you can set what request you want to be
                url: "/api/campaignsystems/" + item.id + "/" + this.campaign_id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            axios({
                method: "put", //you can set what request you want to be
                url:
                    "/api/campaignusers/" + addChar.id + "/" + this.campaign_id,
                data: request1,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
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
            "getCampaignUsersByUserIdEntosisFreeCount"
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
        }
    }
};
</script>

<style></style>
