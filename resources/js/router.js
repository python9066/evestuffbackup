import Vue from "vue";
import Router from "vue-router";
import Timers from "./views/Timers";
import Notifications from "./views/Notifications";
import Campagins from "./views/Campaigns";
import Campaign from "./views/CampaignSystem";
import MultiCampaign from "./views/MultiCampaignSystem";
import Vtest from "./views/test.vue";
import Stest from "./components/campaign/SystemTable.vue";
import AdminPanel from "./views/AdminPanel.vue";
import StationsRedirect from "./views/redirect/StationsRedirect.vue";
// import Structures from "./views/structure.vue"
import Towers from "./views/Towers.vue"
import store from "./store";
import FeedBack from "./views/FeedBack.vue";
import campaginSystemKick from "./views/redirect/campaginSystemKick.vue";
import MultiCampagins from "./views/CustomCampaigns.vue";
import campaignFinished from "./views/redirect/campaignOver.vue";
import chillstations from "./components/chillstation/ChillStructure.vue";
import Gsol from "./views/Gsol"
import Recon from "./views/Recon";
import StartCampaign from "./views/StartCampaignSystem"
import KillList from "./views/RCsheet.vue"
import RCMOVETIMER from "./views/RCMove.vue"

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        // {
        //
        // },
        {
            path: "/",
            name: "default",
            component: Notifications
        },
        {
            path: "/campaign/:id",
            name: "campaign",
            component: Campaign,
            props: route => {
                const id = route.params.id;
                return { id };
            }
            //   beforeEnter(to, from, next) {

            //     // console.log(Permissions.indexOf('access_campaigns' )!== -1)
            //     if(Permissions.indexOf('campaign ' )!== -1){
            //         next()
            //     }else{
            //        next("/redirect/campagin")
            //     }

            //   }
        },

        {
            path: "/mcampaign/:id/:name",
            name: "mcampaign",
            component: MultiCampaign,
            props: route => {
                const id = Number.parseInt(route.params.id, 10);
                if (Number.isNaN(id)) {
                    return 0;
                }
                return { id };
            }
            //   beforeEnter(to, from, next) {

            //     // console.log(Permissions.indexOf('access_campaigns' )!== -1)
            //     if(Permissions.indexOf('campaign ' )!== -1){
            //         next()
            //     }else{
            //        next("/redirect/campagin")
            //     }

            //   }
        },


        {
            path: "/scampaign/:id",
            name: "scampaign",
            component: StartCampaign,
            props: route => {
                const id = Number.parseInt(route.params.id, 10);
                if (Number.isNaN(id)) {
                    return 0;
                }
                return { id };
            }
            //   beforeEnter(to, from, next) {

            //     // console.log(Permissions.indexOf('access_campaigns' )!== -1)
            //     if(Permissions.indexOf('campaign ' )!== -1){
            //         next()
            //     }else{
            //        next("/redirect/campagin")
            //     }

            //   }
        },



        {
            path: "/timers",
            name: "timers",
            component: Timers
        },

        // {
        //     path: "/structures",
        //     name: "structures",
        //     component: Structures,
        //       beforeEnter(to, from, next) {
        //         if(Permissions.indexOf('gunner' )!== -1 || Permissions.indexOf('edit_notifications' )!== -1){
        //             next()
        //         }else{
        //            next("/redirect/structures")
        //         }

        //       }
        // },

        {
            path: "/campaignkick",
            name: "campaginSystemKick",
            component: campaginSystemKick,
        },

        {
            path: "/feedback",
            name: "feedback",
            component: FeedBack,
              beforeEnter(to, from, next) {
                if(Permissions.indexOf('super' )!== -1){
                    next()
                }else{
                   next("/notifications")
                }

              }
        },

        {
            path: "/addtimer",
            name: "timerstomove",
            component: RCMOVETIMER,
              beforeEnter(to, from, next) {
                if(Permissions.indexOf('view_move_timers' )!== -1){
                    next()
                }else{
                   next("/notifications")
                }

              }
        },

        {
            path: "/killlist",
            name: "killlist",
            component: KillList,
              beforeEnter(to, from, next) {
                if(Permissions.indexOf('view_killsheet' )!== -1){
                    next()
                }else{
                   next("/notifications")
                }

              }
        },

        {
            path: "/fornatshealth",
            name: "killlist",
            component: KillList,
              beforeEnter(to, from, next) {
                if(Permissions.indexOf('view_killsheet' )!== -1){
                    next()
                }else{
                   next("/notifications")
                }

              }
        },

        {
            path: "/gsol",
            name: "gsol",
            component: Gsol,
              beforeEnter(to, from, next) {
                if(Permissions.indexOf('view_gsol' )!== -1){
                    next()
                }else{
                   next("/notifications")
                }

              }
        },

        {
            path: "/towers",
            name: "towers",
            component: Towers,
              beforeEnter(to, from, next) {
                if(Permissions.indexOf('view_towers' )!== -1){
                    next()
                }else{
                   next("/redirect/stations")
                }

              }
        },




        {
            path: "/stest",
            name: "stest",
            component: Stest
        },

        {
            path: "/redirect/stations",
            name: "campagin_redirect",
            component: StationsRedirect
        },

        {
            path: "/campaignfinished",
            name: "campaignfinished",
            component: campaignFinished
        },

        {
            path: "/dance",
            name: "test",
            component: Vtest
        },

        {
            path: "/pannel",
            name: "pannel",
            component: AdminPanel,
            async beforeEnter(to, from, next) {
                if (Permissions.indexOf("edit_users") !== -1) {
                    await store.dispatch("getUsers");
                    await store.dispatch("getRoles");
                    next();
                } else {
                    next("/notifications");
                }
            }
        },

        {
            path: "/notifications",
            name: "notifications",
            component: Notifications
        },

        {
            path: "/campaigns",
            name: "campaigns",
            component: Campagins
        },

        {
            path: "/mcampaigns",
            name: "mcampaigns",
            component: MultiCampagins,
            beforeEnter(to, from, next) {
                if(Permissions.indexOf('access_multi_campaigns')!== -1){
                    next()
                }else{
                   next("/campaigns")
                }

              }
        },


        {
            path: "/chillstations",
            name: "chillstations",
            component: chillstations,
            beforeEnter(to, from, next) {
                if(Permissions.indexOf('view_chill_timers')!== -1){
                    next()
                }else{
                   next("/notifications")
                }

              }
        },

        {
            path: "/recon",
            name: "recon",
            component: Recon,
            beforeEnter(to, from, next) {
                if(Permissions.indexOf('view_recon')!== -1){
                    next()
                }else{
                   next("/notifications")
                }

              }
        }




    ]
    // scrollBehavior(to, from, savedPosition) {
    //   return { x: 0, y: 0 }
    // },
});
