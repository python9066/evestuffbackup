import Vue from 'vue'
import Router from 'vue-router'
import Timers from './views/Timers'
import Notifications from './views/Notifications'
import Campagins from './views/Campaigns'
import Campaign from './views/CampaignSystem'
import Vtest from './views/test.vue'
import Stest from './components/campaign/systemTable.vue'
import AdminPanel from './views/AdminPanel.vue'
import Permissions from './mixins/Permissions.vue'
import Axios from "axios"

Vue.use(Router)
Vue.mixin(Permissions);

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
      component: Campaign,
      props: (route) => {
        const id = Number.parseInt(route.params.id, 10)
        if (Number.isNaN(id)) {
          return 0
        }
        return { id }
      }
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
        path: '/dance',
        name: 'test',
        component: Vtest
      },

      {
        path: '/pannel',
        name: 'test',
        component: AdminPanel,
        beforeEnter(to, from, next) {

            if(Permissions.indexOf('edit all users','edit scout users','edit hack users')!== -1){
                next()
            }else{
                next('/')
            }

          }
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
