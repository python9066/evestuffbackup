import Vue from 'vue'
import Vuex from 'vuex'
import ApiL from './service/apil'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
      timers: [],
      notifications: []

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

    }

  },
  getters: {

  }
})
