<template>
    <div>
        <v-container>
            <v-icon class="mr-2 grey--text lighten-1">fas fa-sync-alt</v-icon
            >button testing
        </v-container>
    </div>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
export default {
    data() {
        return {
            loading2: null,
            auth:null,
        };
    },
   async mounted() {

  await this.checktoken();
  await this.updatetoken();


    },
    methods: {

      async checktoken() {
            this.auth=null;
            const res = await axios.get("/getAuthCheck").then(res => {
                if (res.status == 200) {
                    console.log(res);
                     console.log("hi");
                this.auth = res.data[0];
                }
            });
        },

        // async updatetoken() {
        //     if(auth=false){
        //         const res = await axios.get("/getAuthURL");
        //         // const token = await axios.get(res.data[0]){

        //             headers:{
        //                 Content-Type: application/x-www-form-urlencoded
        //                 Host: login.eveonline.com
        //                 Authorization: Basic res.data[1]



        //             }
        //         }


        //     }
        },
    };

</script>
