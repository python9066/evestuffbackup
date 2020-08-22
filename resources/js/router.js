import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home'
import Updates from './views/Updates'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    // {
    //   path: '/:sectionname',
    //   name: 'landingpage',
    //   component: LandingPage,
    //   props: true
    // },
    {
      path: '/',
      name: 'base',
      component: Home
    },
    {
      path: '/home',
      name: 'home',
      component: Home
    },
    {
        path: '/updates',
        name: 'updates',
        component: Updates
      }

  ],
  // scrollBehavior(to, from, savedPosition) {
  //   return { x: 0, y: 0 }
  // },
})
