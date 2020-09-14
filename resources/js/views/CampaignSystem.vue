<template>
  <div class="indigo accent-2">

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
<v-row no-gutters v-if="this.getCampaignsCount > 1">
      <v-col lg="1"></v-col>
      <v-col md="10">
        <v-card
          class="pa-2"
          tile
          width = "100%"
        >

    <form
    v-if="this.userForm == 1"
    @submit.prevent="newCharForm()">
    <v-text-field
    v-model="newCharName"
      label="Char Name"
      required
      :rules="newNameRules"
    ></v-text-field>
    <v-select
    v-model="newRole"
     @change="roleForm($event)"
     :rules="newRoleRules"
      :items="dropdown_roles"
      label="Role"
      required

    ></v-select>
    <v-text-field
    v-model="newShip"
    :rules="newShipRules"
    v-if="this.role == 1"
      label="Ship"
      required
    ></v-text-field>
    <v-radio-group
    v-model="newLink"
    :rules="newLinkRules"
    v-if="this.role == 1"
    row
    label="Entosis Link"
    required
    >
      <v-radio label="Tech 1" value=1></v-radio>
      <v-radio label="Tech 2" value=2></v-radio>
    </v-radio-group>

    <v-btn class="mr-4" type="submit">submit</v-btn>
    <v-btn class="mr-4" @click="newCharFormClose()">Close</v-btn>
    <!-- <v-btn @click="clear">clear</v-btn> -->
  </form>
<!---edit/delete form------>
<form
    v-if="this.editUserForm == 1"
    @submit.prevent="editCharForm()">
    <v-select
     v-model="editCharName"
      :items="userCharsDrop"
      label="Pick the char you want to edit"
      name="editChars"
      :item-text ="'char_name'"
      :item-value ="'id'"
      @change="charEditForm($event)"
    ></v-select>
    <v-select
    v-model="editRole"
     @change="roleEditForm($event)"
      :items="dropdown_roles"
      label="Role"
      required
      :placeholder ='editTextRole'

    ></v-select>
    <v-text-field
    v-model="editShip"
    v-if="this.editrole == 1"
      label="Ship"
      required
      :placeholder ='editTextShip'
    ></v-text-field>
    <v-radio-group
    v-model="editLink"
    v-if="this.editrole == 1"
    row
    label="Entosis Link"
    required
    :placeholder ='editTextLink'
    >
      <v-radio label="Tech 1" value=1></v-radio>
      <v-radio label="Tech 2" value=2></v-radio>
    </v-radio-group>

    <v-btn class="mr-4" type="submit">submit</v-btn>
    <v-btn class="mr-4" @click="editFormRemove()">Remove</v-btn>
    <v-btn class="mr-4" @click="editFormClose()">Close</v-btn>
    <!-- <v-btn @click="clear">clear</v-btn> -->
  </form>
  <!---edit/delete form------>
</v-card>
</v-col>
      <v-col lg="1"></v-col>
</v-row>


<ul id="example-1">
  <li v-for="system in systems" :key="system.id">
    {{ system.system_name }}
  </li>
</ul>


<v-row
 no-gutters
 class="blue"
 justify ="space-around"
 >

    <userTable
    :campaign_id ='$route.params.id'
    >
    </userTable>
</v-row>



<v-row no-gutters class="red" justify ="center">

    <systemTable class=" px-5 pb-5"
        v-for="(system, index) in systems"
        :system_name="system.system_name"
        :system_id='system.id'
        :campaign_id ='$route.params.id'
        :index="index"
        :key="system.id"
        >
    </systemTable>

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
            dropdown_roles: [
                { text: "Hacker", value: 1 },
                { text: "Scout", value: 2 },
                { text: "FC", value: 3 },
                { text: "Command", value: 4 }
            ],
            dropdown_status: [
                { text: "None", value: 1 },
                { text: "On the way", value: 2 },
                { text: "Ready to go", value: 3 },
            ],

            newCharName:null,
            newNameRules: [
                v => !!v || 'Name is required',
            ],
            newRole:null,
            newRoleRules: [
                v => !!v || 'You need to pick a role',
            ],
            newShip:null,
            newShipRules: [
                v => !!v || 'Ship is required',
            ],
            newLink:null,
            newLinkRules: [
                v => !!v || 'T1 or T2?',
            ],

            editCharName:null,
            editNameRules: [
                v => !!v || 'Name is required',
            ],
            editRole:null,
            editTextRole:null,
            editRoleRules: [
                v => !!v || 'You need to pick a role',
            ],
            editShip:null,
            editTextShip:null,
            editShipRules: [
                v => !!v || 'Ship is required',
            ],
            editLink:null,
            editTextLink:null,
            editLinkRules: [
                v => !!v || 'T1 or T2?',
            ],
            editUserForm:0,

            oldChar:[],
            role:0,
            editrole:0,
            systems: [],
            test: 1,
            test2: "",
            userCharsDrop:null,
            userForm:1,
            valid: false,

      }
    },

    beforeMonunt() {},

    beforeCreate() {},

    async mounted() {
        if (this.$store.getters.getCampaignsCount == 0) {
            await this.$store.dispatch("getCampaigns");
        }
        // console.log(this.$route.params.id)
        await this.getSystems(this.campaign.constellation_id);
        await this.$store.dispatch('getCampaignUsersRecrods',this.$route.params.id);
        await this.getusersChars();

    },
    methods: {

      async  getSystems(id){
            console.log(id, this.$store.state.token);
          let res = await axios({
                method: 'get', //you can set what request you want to be
                url: "/api/systemsinconstellation/" + id,
                headers: {
                    Authorization: 'Bearer ' + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
          })



            this.systems = res.data.systems

        },

  async getusersChars(){
               this.userCharsDrop = this.$store.getters.getCampaignUsersByUserId(this.$store.state.user_id)
            },


        roleForm(a){
            this.role = a
            console.log("LALAL")
            console.log(a)
        },

        newCharFormClose(){
            this.newCharName = null
            this.newRole = null
            this.newShip = null
            this.newLink = null

        },

        roleEditForm(a){
            this.editrole = a
            console.log("LALAL")
            console.log(a)
        },


        charEditForm($event){
            this.oldChar = this.userCharsDrop.find(user => user.id == $event)
            this.editTextRole = this.oldChar.role_name;
            this.editTextShip = this.oldChar.ship
            this.editTextLink = this.oldChar.link
            if(this.oldChar.role_id == 1){
                this.editrole = 1;
            }else{this.editrole = 0;}


        },

        editFormClose(){
            this.editTextRole = null
            this.editTextShip = null
            this.editTextLink = null
            this.oldCha = null
            this.editrole = 0
            this.editUserForm = 0

            },


        newCharForm(){

             var request = {
                site_id: this.$store.state.user_id,
                campaign_id: this.$route.params.id,
                char_name: this.newCharName,
                link: this.newLink,
                ship: this.newShip,
                campaign_role_id: this.newRole,

            };

            axios({
                method: 'POST', //you can set what request you want to be
                url: "/api/campaignusers",
                data: request,
                headers: {
                    Authorization: 'Bearer ' + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
                })

                this.role = null;

            },

        editCharForm(){

            if(this.oldChar.role_id != this.editRole){
                var role = this.editRole
            }
            if(this.oldChar.ship != this.editShip){
                var ship = this.editShip
            }
            if(this.oldChar.link != this.editLink){
                var link = this.editLink
            }
            var request = {
                link: link,
                ship: ship,
                campaign_role_id: role,
                 };

            axios({
                method: 'PUT', //you can set what request you want to be
                url: "/api/campaignusers/" + this.oldChar.id,
                data: request,
                headers: {
                    Authorization: 'Bearer ' + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
                })

        },

        editFormRemove(){

            axios({
                method: 'DELETE', //you can set what request you want to be
                url: "/api/campaignusers/" + this.oldChar.id,
                headers: {
                    Authorization: 'Bearer ' + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
                })

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
            "getCampaignsCount",
            "getCampaignUsersByUserId",
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
