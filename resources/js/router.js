import Vue from 'vue'
import Router from 'vue-router'
import Timers from './views/Timers'
import Notifications from './views/Notifications'
import Campagins from './views/Campaigns'
import Campaign from './views/CampaignSystem'
import Vtest from './views/test.vue'
import Stest from './components/campaign/systemTable.vue'

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
      {
        path: '/stest',
        name: 'stest',
        component: Stest
      },

      {
        path: '/vtest',
        name: 'test',
        component: Vtest
      },


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
