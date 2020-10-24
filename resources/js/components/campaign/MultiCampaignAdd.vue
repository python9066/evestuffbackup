<template>
    <div>
        <v-card>
            <v-card-title>
                Make your Mulit-Campaign Here
            </v-card-title>
            <v-card-text>
                <v-text-field
                    label="Multi-Campaign Name"
                    :value="name"
                    hint="Enter The name of your Campaign here"
                    filled
                >
                </v-text-field>
                <v-select
                    v-model="picked"
                    :items="list"
                    label="Select"
                    multiple
                    chips
                    deletable-chips
                    hint="Which Campaigns do you want"
                    persistent-hint
                ></v-select>
            </v-card-text>
            <v-card-actions>
                <v-btn color="success" class="mr-4" @click="addCampaignDone()"
                    >Done</v-btn
                >
                <v-btn color="warning" class="mr-4" @click="addCampaignClose()"
                    >Close</v-btn
                >
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    data() {
        return {
            name: "",
            picked: []
        };
    },

    created() {
        this.$store.dispatch("getCampaignsList");
    },

    methods: {

        addCampaignDone(){
            console.log(moment().unix())
        },

        addCampaignClose(){
            this.picked = []
            this.name = ""

        },

    },

    computed: {
        ...mapState(["campaignslist"]),

        list() {
            return this.campaignslist;
        }
    }
};
</script>
