<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="primary"
          dark
          v-bind="attrs"
          v-on="on"
        >
          Open Dialog
        </v-btn>
      </template>
      <v-card

      >
      <v-toolbar dark color="primary">
          <v-btn icon dark @click="dialog = false">
            <v-icon>fas fa-times-circle</v-icon>
          </v-btn>
          <v-toolbar-title>Querious</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
    <div style ='position: absolute; top: 64px; right: 0px; bottom: 0px; left: 0px;'>
        <iframe src="https://evemaps.dotlan.net/map/Querious/Q2-N6W,L-6BE1,GOP-GE#adm" style="left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
</iframe>
    </div>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
import Axios from "axios";
import { EventBus } from "../event-bus";
import ApiL from '../service/apil';
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
export default {
    data() {
        return {
            // loading2: null,
            // auth:null,
            dialog: false,
        notifications: false,
        sound: true,
        widgets: false,
        };
    },
   async mounted() {

   await this.test();
//   await this.updatetoken();


    },
    methods: {


        // async test(){
        //    var $data = await ApiL().get('/notifications');
        //     console.log($data);


        // }

    //   async checktoken() {
    //         this.auth=null;
    //         const res = await axios.get("/getAuthCheck").then(res => {
    //             if (res.status == 200) {
    //                 console.log(res);
    //                  console.log("hi");
    //             this.auth = res.data[0];
    //             }
    //         });
    //     },

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
