<template>
  <div class="grey lighten-5">

    <v-row no-gutters v-if="this.getCampaignsCount > 1">
      <v-col lg="1"></v-col>
      <v-col md="10">
        <v-card
          class="pa-2"
          tile
          width = "100%"
        >
        <v-card-title
        align="center"
        class="justify-center"
        >
          Campaign page for the {{this.campaign.item_name}} in {{this.campaign.system}}
        </v-card-title>
        </v-card>
      </v-col>
      <v-col lg="1"></v-col>
    </v-row>
    <v-row no-gutters v-if="this.getCampaignsCount > 1" class="pt-5">
      <v-col lg="1"></v-col>
      <v-col md="10">
        <v-card
          class="pa-2"
          tile
          width = "100%"
        >
        <v-card-title
        align="center"
        class="justify-center"
        >
        {{this.campaign.region}} -
        {{this.campaign.constellation}}
        {{this.campaign.system}} -
        <v-avatar size="35"><img :src="this.campaign.url"/></v-avatar> -
        {{this.campaign.alliance}}
        </v-card-title>
    <div class="d-flex full-width align-content-center">
        <v-icon
            v-if="this.campaign.defenders_score > this.campaign.defenders_score_old && this.campaign.defenders_score_old > 0"
            small
            left
            dark
            color="blue darken-4"
        >
        fas fa-arrow-alt-circle-up
        </v-icon>
        <v-icon
            v-if="this.campaign.defenders_score < this.campaign.defenders_score_old && this.campaign.defenders_score_old > 0"
            small
            left
            dark
            color="blue darken-4"
        >
        fas fa-arrow-alt-circle-down
        </v-icon>
        <v-icon
            v-if="this.campaign.defenders_score == this.campaign.defenders_score_old  || this.campaign.defenders_score_old === null"
            small
            left
            dark
            color="grey darken-3"
        >
        fas fa-minus-circle
        </v-icon>


    <v-progress-linear

            :color="this.barColor"
            :value="this.barScoure"
            height = 20
            rounded
            :active="this.barActive"
            :reverse ="this.barReverse"
            :background-color="this.barBgcolor"
            background-opacity="0.2"
            >
            <strong> {{this.campaign.defenders_score * 100}} / {{this.campaign.attackers_score * 100}} </strong>
        </v-progress-linear>

        <v-icon
            v-if="this.campaign.attackers_score > this.campaign.attackers_score_old && this.campaign.attackers_score_old > 0"
            small
            right
            dark
            color="red darken-4"
        >
        fas fa-arrow-alt-circle-up
        </v-icon>
        <v-icon
            v-if="this.campaign.attackers_score < this.campaign.attackers_score_old && this.campaign.attackers_score_old > 0"
            small
            right
            dark
            color="red darken-4"
        >
        fas fa-arrow-alt-circle-down
        </v-icon>
        <v-icon
            v-if="this.campaign.attackers_score == this.campaign.attackers_score_old  || this.campaign.attackers_score_old == null"
            small
            right
            dark
            color="grey darken-3"
        >
        fas fa-minus-circle
        </v-icon>
    </div>
        </v-card>
      </v-col>
      <v-col lg="1"></v-col>
    </v-row>
  </div>
</template>
<!-- {{ $route.params.id }} - {{ test }} -  -->
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from "../service/apil";
import { mapGetters } from "vuex";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

export default {
    data() {
        return {
            test: 1,
            test2: "",
            systems: [],
      }
    },

    beforeMonunt() {},

    beforeCreate() {},

    async mounted() {
        if (this.$store.getters.getCampaignsCount == 0) {
            await this.$store.dispatch("getCampaigns");
        }
        await this.getSystems(this.campaign.constellation_id);
    },
    methods: {

      async  getSystems(id){
            console.log(id, this.$store.state.token);
          let res = await axios({
                method: 'get', //you can set what request you want to be
                url: "/api/constellation/" + id,
                headers: {
                    Authorization: 'Bearer ' + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
          })



            this.systems = res.data.systems

        }

        },

    async created() {
        this.test = 2;
        this.test2 = 1;
        this.navdrawer = true;

    },

    computed: {
        ...mapGetters(["getCampaignById",
            "getActiveCampaigns",
            "getCampaignsCount"
        ]),

        campaign() {
            return this.getCampaignById(this.$route.params.id)
        },
            barScoure(){

                var d = this.getCampaignById(this.$route.params.id).defenders_score *100;
                var a = this.getCampaignById(this.$route.params.id).attackers_score *100;

                if( d > 50){
                    return d;
                }

                return a;

            },

            barBgcolor(){

                var d = this.getCampaignById(this.$route.params.id).defenders_score *100;
                var a = this.getCampaignById(this.$route.params.id).attackers_score *100;

                if( d > 50){
                    return "red darken-4";
                }

                return "blue darken-4";

            },

            barColor(){
                var d = this.getCampaignById(this.$route.params.id).defenders_score *100;
                if( d > 50){
                    return "blue darken-4";
                }

                return "red darken-4";

            },

            barReverse(){
                var d = this.getCampaignById(this.$route.params.id).defenders_score *100;
                if( d > 50){
                    return false;
                }

                return true;
            },

            barActive(){

                if(this.getCampaignById(this.$route.params.id).status_id > 1){
                    return true;
                }
                return false

            },
    }
};
</script>
