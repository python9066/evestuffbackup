import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
      timers: []

  },
  mutations: {
    SET_TIMERS(state, timers){
        state.timers = timers;
    },

    MARK_TIMER_OVER(state, timer){
        const item = state.timers.find(item => item.id === timer.id);
        Object.assign(item,timer);

    }


  },
  actions: {
    async getTimerDataAll({commit}) {

        let res = await axios.get("/api/timers");
        commit('SET_TIMERS', res.data.timers)
    },

    markOver({commit}, timer) {
        commit('MARK_TIMER_OVER',timer)

    }

  },
  getters: {

  }
})
