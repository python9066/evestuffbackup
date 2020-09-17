import index from "@chenfengyuan/vue-countdown";
import Vue from "vue";
import Vuex from "vuex";
import ApiL from "./service/apil";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        timers: [],
        notifications: [],
        campaigns: [],
        delveLink: "",
        queriousLink: "",
        periodbasisLink: "",
        token: "",
        user_id: 0,
        campaignusers: [],
        campaignsystems: []
    },
    mutations: {
        SET_TIMERS(state, timers) {
            state.timers = timers;
        },

        SET_CAMPAIGNS(state, campaigns) {
            state.campaigns = campaigns;
        },

        MARK_TIMER_OVER(state, timer) {
            const item = state.timers.find(item => item.id === timer.id);
            Object.assign(item, timer);
        },

        UPDATE_NOTIFICATIONS(state, data) {
            const item = state.notifications.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_CAMPAIGN_SYSTEM(state, data) {
            const item = state.campaignsystems.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_CAMPAIGN(state, data) {
            const item = state.campaigns.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_CAMPAIGN_USERS(state, data) {
            const item = state.campaignusers.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        SET_TOKEN(state, token) {
            state.token = token;
        },
        SET_USER_ID(state, user_id) {
            state.user_id = user_id;
        },

        SET_NOTIFICATIONS(state, notifications) {
            state.notifications = notifications;
        },

        SET_DELVE_LINK(state, delveLink) {
            state.delveLink = delveLink;
        },

        SET_QUERIOUS_LINK(state, queriousLink) {
            state.queriousLink = queriousLink;
        },

        SET_PERIOD_BASIS_LINK(state, periodbasisLink) {
            state.periodbasisLink = periodbasisLink;
        },

        SET_CAMPAIGN_USERS(state, data) {
            state.campaignusers = data;
        },

        SET_CAMPAIGN_SYSTEMS(state, data) {
            state.campaignsystems = data;
        }
    },
    actions: {
        async getTimerDataAll({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/timers",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_TIMERS", res.data.timers);
        },

        async getCampaigns({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/campaigns",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // console.log(res.data.campaigns);
            commit("SET_CAMPAIGNS", res.data.campaigns);
        },

        markOver({ commit }, timer) {
            commit("MARK_TIMER_OVER", timer);
        },

        updateNotification({ commit }, data) {
            commit("UPDATE_NOTIFICATIONS", data);
        },

        updateCampaignSystem({ commit }, data) {
            commit("UPDATE_CAMPAIGN_SYSTEM", data);
        },

        updateCampaign({ commit }, data) {
            commit("UPDATE_CAMPAIGN", data);
        },

        updateCampaignUsers({ commit }, data) {
            commit("UPDATE_CAMPAIGN_USERS", data);
        },

        async getNotifications({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/notifications",
                // data: {id: varID},
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_NOTIFICATIONS", res.data.notifications);
        },

        async getdelveLink({ commit, state }) {
            // console.log('Bearer ' + state.token)
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/notifications/10000060",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // console.log(res.data)
            commit("SET_DELVE_LINK", res.data.link);
        },

        async getqueriousLink({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/notifications/10000050",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_QUERIOUS_LINK", res.data.link);
        },

        async getperiodbasisLink({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/notifications/10000063",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_PERIOD_BASIS_LINK", res.data.link);
        },

        setToken({ commit }, token) {
            commit("SET_TOKEN", token);
        },

        setUser_id({ commit }, user_id) {
            commit("SET_USER_ID", user_id);
        },

        async getCampaignUsersRecords({ commit, state }, id) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/campaignusersrecords/" + id,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            if (res.data.length != 0) {
                commit("SET_CAMPAIGN_USERS", res.data.users);
            }
        },

        async getCampaignSystemsRecords({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/campaignsystemsrecords",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            if (res.data.length != 0) {
                commit("SET_CAMPAIGN_SYSTEMS", res.data.systems);
            }
        }
    },
    getters: {
        getCampaignById: state => id => {
            return state.campaigns.find(campaigns => campaigns.id == id);
        },

        getCampaignUsersByCampaign: state => id => {
            return state.campaignusers.find(
                campaignusers => campaignusers.campaign_id === id
            );
        },

        getCampaignUsersByUserId: state => payload => {
            // console.log("poo",state.campaignusers.filter(campaignusers => campaignusers.site_id == id))
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == payload.id && campaignusers.campaign_id == payload.campaignID
            );
        },

        getCampaignUsersByUserIdEntosis: state => id => {
            // console.log("poo",state.campaignusers.filter(campaignusers => campaignusers.site_id == id))
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id && campaignusers.role_id == 1
            );
        },

        getActiveCampaigns: state => {
            return state.campaigns.find(campaigns => campaigns.status_id == 2);
        },

        getCampaignsCount: state => {
            return state.campaigns.length;
        },

        getCampaignUsersByUserIdEntosisCount: (state, getters) =>{
            if(getters.getCampaignUsersByUserIdEntosis == null){
                return 0
            }
            return getters.getCampaignUsersByUserIdEntosis.length
        },



        getCampaignUsersByUserIdCount: (state, getters) =>{
            return getters.getCampaignUsersByUserId.length
        }


    }
});
