import Vue from "vue";
import Router from "vue-router";
import Timers from "./views/Timers";
import Notifications from "./views/Notifications";
import Campagins from "./views/Campaigns";
import Campaign from "./views/CampaignSystem";
import Vtest from "./views/test.vue";
import Stest from "./components/campaign/systemTable.vue";
import AdminPanel from "./views/AdminPanel.vue";
import StationsRedirect from "./views/redirect/StationsRedirect.vue";
import Stations from "./views/Stations.vue"
import Towers from "./views/Towers.vue"
import store from "./store";
import FeedBack from "./views/FeedBack.vue";

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
            name: "notifications",
            component: Notifications
        },
        {
            path: "/campaign/:id",
            name: "campaign",
            component: Campaign,
            props: route => {
                const id = Number.parseInt(route.params.id, 10);
                if (Number.isNaN(id)) {
                    return 0;
                }
                return { id };
            }
            //   beforeEnter(to, from, next) {

            //     console.log(Permissions.indexOf('access_campaigns' )!== -1)
            //     if(Permissions.indexOf('access_campaigns' )!== -1){
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

        {
            path: "/stations",
            name: "stations",
            component: Stations,
              beforeEnter(to, from, next) {
                if(Permissions.indexOf('gunner' )!== -1){
                    next()
                }else{
                   next("/redirect/stations")
                }

              }
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
        }
    ]
    // scrollBehavior(to, from, savedPosition) {
    //   return { x: 0, y: 0 }
    // },
});
