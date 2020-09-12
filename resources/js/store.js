import Vue from 'vue'
import Vuex from 'vuex'
import ApiL from './service/apil'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        timers: [],
        notifications: [],
        campaigns:[],
        delveLink: "",
        queriousLink: "",
        periodbasisLink: "",
        token: ""



    },
    mutations: {
        SET_TIMERS(state, timers) {
            state.timers = timers;
        },

        SET_CAMPAIGNS(state, campaigns){
            state.campaigns = campaigns
        },


        MARK_TIMER_OVER(state, timer) {
            const item = state.timers.find(item => item.id === timer.id);
            Object.assign(item, timer);

        },

        UPDATE_NOTIFICATIONS(state, data){
            const item = state.notifications.find(item => item.id === data.id);
            Object.assign(item, data);

        },

        UPDATE_CAMPAIGN(state, data){
            const item = state.campaigns.find(item => item.id === data.id);
            Object.assign(item, data);

        },

        SET_TOKEN(state, token) {
            state.token = token;
        },

        SET_NOTIFICATIONS(state, notifications) {
            state.notifications = notifications;
        },

        SET_DELVE_LINK(state, delveLink) {
            state.delveLink = delveLink
        },

        SET_QUERIOUS_LINK(state, queriousLink) {
            state.queriousLink = queriousLink
        },

        SET_PERIOD_BASIS_LINK(state, periodbasisLink) {
            state.periodbasisLink = periodbasisLink
        },


    },
    actions: {
        async getTimerDataAll({ commit, state }) {

            let res = await axios({
                method: 'get',
                url: '/api/timers',
                headers: {
                    Authorization: 'Bearer ' + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }

            })
            commit('SET_TIMERS', res.data.timers)
        },

        async getCampaigns({ commit, state }) {

            let res = await axios({
                method: 'get',
                url: '/api/campaigns',
                headers: {
                    Authorization: 'Bearer ' + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }

            })
            // console.log(res.data.campaigns);
            commit('SET_CAMPAIGNS', res.data.campaigns)
        },

        markOver({ commit }, timer) {
            commit('MARK_TIMER_OVER', timer)

        },

        updateNotification({ commit }, data) {
            commit('UPDATE_NOTIFICATIONS', data)

        },

        updateCampaign({ commit }, data) {
            commit('UPDATE_CAMPAIGN', data)

        },

        async getNotifications({ commit, state }) {
            let res = await axios({
                method: 'get', //you can set what request you want to be
                url: '/api/notifications',
                // data: {id: varID},
                headers: {
                    Authorization: 'Bearer ' + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
            })
            commit('SET_NOTIFICATIONS', res.data.notifications)
        },



        async getdelveLink({ commit, state }) {

            // console.log('Bearer ' + state.token)
            let res = await axios({
                method: 'get', //you can set what request you want to be
                url: '/api/notifications/10000060',
                headers: {
                    Authorization: 'Bearer ' + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
            })
            // console.log(res.data)
            commit('SET_DELVE_LINK', res.data.link)
        },

        async getqueriousLink({ commit, state }) {


            let res = await axios({
                method: 'get', //you can set what request you want to be
                url: '/api/notifications/10000050',
                headers: {
                    Authorization: 'Bearer ' + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
            })
            commit('SET_QUERIOUS_LINK', res.data.link)
        },

        async getperiodbasisLink({ commit, state }) {


            let res = await axios({
                method: 'get', //you can set what request you want to be
                url: '/api/notifications/10000063',
                headers: {
                    Authorization: 'Bearer ' + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                }
            })
            commit('SET_PERIOD_BASIS_LINK', res.data.link)
        },

        setToken({ commit }, token) {
            commit('SET_TOKEN', token)

        },



    },
    getters: {

        getCampaignById: (state) => (id) => {
            return state.campaigns.find(campaigns => campaigns.id == id)
        },

        getActiveCampaigns: (state) =>{
            return state.campaigns.find(campaigns => campaigns.status_id == 2)
        },

        getCampaignsCount: (state) =>{
            return state.campaigns.length
        }

    }
})
