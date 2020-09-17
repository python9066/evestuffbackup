<template>
<v-col cols="10">
      <v-card
      tile
      >
    <v-card-text>
        <v-data-table
            :headers="headers"
            :items="filteredItems"
            item-key="id"
            :items-per-page="10"
            class="elevation-10"
        >
            >
        <template v-slot:top>
                        <v-toolbar
                        flat
                        elevation="24"
                        color='grey darken-4'
                        >
                            <v-toolbar-title
                                >YAY -

                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                    </template>


        </v-data-table>
    </v-card-text>
      </v-card>
</v-col>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
   campaign_id: String,
},
    data() {
        return {
            headers: [
                { text: "Name", value: "char_name", width: "10%" },
                { text: "Main",value: "main_name"},
                { text: "Ship", value: "ship" },
                { text: "Entosic", value: "link" },
                { text: "System", value: "system_name" },
                { text: "Node", value: "node_id" },
                { text: "Status", value: "user_status_name" },
                { text: "Role", value: "role_name" },

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ],
            statusflag: 0,
        }
    },








    computed: {

        ...mapState([
            "campaignusers",
        ]),

        filteredItems() {
            // var timers = this.$store.state.timers;
            if (this.statusflag == 1) {
                return this.campaignusers.filter(
                    campaignusers => campaignusers.status_id == 1  && campaignusers.campaign_id == this.campaign_id
                );
            }
            if (this.statusflag == 3) {
                return this.campaignusers.filter(
                    campaignusers => campaignusers.status_id == 3  && campaignusers.campaign_id == this.campaign_id
                );
            }
            if (this.statusflag == 5) {
                return this.campaignusers.filter(
                    campaignusers => campaignusers.status_id == 5  && campaignusers.campaign_id == this.campaign_id
                );
            }
            if (this.statusflag == 6) {
                return this.campaignusers.filter(
                    campaignusers => campaignusers.status_id == 6  && campaignusers.campaign_id == this.campaign_id
                );
            }
            else {
                return this.campaignusers.filter(
                    campaignusers => campaignusers.status_id != 10  && campaignusers.campaign_id == this.campaign_id
                );
            }
        },


    },

}
</script>

<style>

</style>
