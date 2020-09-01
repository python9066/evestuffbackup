import Vue from 'vue'
import Vuex from 'vuex'
import ApiL from './service/apil'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
      timers: [],
      notifications: [],
      delveLink:"",
      queriousLink:"",
      periodbasisLink:""



  },
  mutations: {
    SET_TIMERS(state, timers){
        state.timers = timers;
    },


    MARK_TIMER_OVER(state, timer){
        const item = state.timers.find(item => item.id === timer.id);
        Object.assign(item,timer);

    },

    SET_NOTIFICATIONS(state, notifications){
        state.notifications = notifications;
    },

    SET_DELVE_LINK(state, delveLink){
        state.delveLink = delveLink
    },

    SET_QUERIOUS_LINK(state, queriousLink){
        state.queriousLink = queriousLink
    },

    SET_PERIOD_BASIS_LINK(state, periodbasisLink){
        state.periodbasisLink = periodbasisLink
    },


  },
  actions: {
    async getTimerDataAll({commit}) {

        let res = await ApiL().get("/timers");
        commit('SET_TIMERS', res.data.timers)
    },

    async getNotifications({commit}) {

        let res = await ApiL().get("/notifications");
        commit('SET_NOTIFICATIONS', res.data.notifications)
    },

    markOver({commit}, timer) {
        commit('MARK_TIMER_OVER',timer)

    },

    async getdelveLink({commit}) {

        let res = await ApiL().get("notifications/10000060");
        commit('SET_DELVE_LINK', res.data.link)
    },

    async getqueriousLink({commit}) {

        let res = await ApiL().get("notifications/10000050");
        commit('SET_QUERIOUS_LINK', res.data.link)
    },

    async getperiodbasisLink({commit}) {

        let res = await ApiL().get("notifications/10000063");
        commit('SET_PERIOD_BASIS_LINK', res.data.link)
    }




  },
  getters: {

  }
})
