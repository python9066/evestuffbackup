import Vue from 'vue'
import Router from 'vue-router'
import Timers from './views/Timers'
import Notifications from './views/Notifications'
import Campagins from './views/Campaigns'
import Campaign from './views/CampaignSheet'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    // {
    //
    // },
    {
      path: '/',
      name: 'notifications',
        component: Notifications
    },
    {
      path: '/campaign/:id',
      name: 'campaign',
      component: Campaign
    },
    {
        path: '/timers',
        name: 'timers',
        component: Timers
      },
    //   {
    //     path: '/',
    //     name: 'buttonupdate',
    //     component: Buttonupdate
    //   },


      {
        path: '/notifications',
        name: 'notifications',
        component: Notifications
      },

      {
        path: '/campaigns',
        name: 'campaigns',
        component: Campagins
      },

  ],
  // scrollBehavior(to, from, savedPosition) {
  //   return { x: 0, y: 0 }
  // },
})
