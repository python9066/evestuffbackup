<template>
    <div>
        <v-card>
            <v-card-title>
                Make your Mulit-Campaign Here
            </v-card-title>
            <v-card-text>
                <v-text-field
                label="Multi-Campaign Name"
                :value ="name"
                hint="Enter The name of your Campaign here"
                filled>
                </v-text-field>
                <v-select
                    v-model="picked"
                    :items="campaignlist"
                    label="Select"
                    multiple
                    chips
                    hint="Which Campaigns do you want"
                    persistent-hint
        ></v-select>
            </v-card-text>
        </v-card>

    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    data() {
        return {

        name:"",
        picked:[],

        };
    },


    created() {
            this.$store.dispatch("getCampaignsList")
    },

    methods: {




    },



    computed: {

    }
};
</script>
