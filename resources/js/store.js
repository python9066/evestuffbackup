import index from "@chenfengyuan/vue-countdown";
import { pullAll } from "lodash";
import Vue from "vue";
import Vuex from "vuex";
import ApiL from "./service/apil";

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        campaigns: [],
        campaignJoin:[],
        campaignslist: [],
        campaignusers: [],
        campaignsystems: [],
        campaignmembers:[],
        delveLink: "",
        multicampaigns:[],
        notifications: [],
        periodbasisLink: "",
        queriousLink: "",
        rolesList:[],
        stations:[],
        timers: [],
        token: "",
        towers:[],
        users:[],
        user_id: 0,
        user_name:"",
    },
    mutations: {
        SET_TIMERS(state, timers) {
            state.timers = timers;
        },

        SET_CAMPAIGN_JOIN(state, campaignJoin) {
            state.campaignJoin = campaignJoin;
        },

        SET_TOWERS(state, towers) {
            state.towers = towers;
        },

        SET_STATIONS(state, stations) {
            state.stations = stations;
        },

        SET_CAMPAIGN_MEMBERS(state, users) {
            state.campaignmembers = users;
        },

        SET_USERS(state, users) {
            state.users = users;
        },

        SET_ROLES(state, roles) {
            state.rolesList = roles;
        },

        SET_CAMPAIGNS(state, campaigns) {
            state.campaigns = campaigns;
        },

        SET_MULTI_CAMPAIGNS(state, multicampaigns) {
            state.multicampaigns = multicampaigns;
        },

        SET_CAMPAIGNSLIST(state, campaignslist) {
            state.campaignslist = campaignslist;
        },

        UPDATE_CAMPAIGNS(state, data) {
            const item = state.campaigns.find(c => c.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_TOWERS(state, data) {
            const item = state.towers.find(c => c.id === data.id);
            Object.assign(item, data);
        },

        MARK_TIMER_OVER(state, timer) {
            const item = state.timers.find(item => item.id === timer.id);
            Object.assign(item, timer);
        },

        UPDATE_NOTIFICATIONS(state, data) {
            const item = state.notifications.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_STATIONS(state, data) {
            const item = state.stations.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_CAMPAIGN_SYSTEM(state, data) {
            const item = state.campaignsystems.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_CAMPAIGN_SYSTEM_UPDATE(state, data) {
            const item = state.campaignsystems.find(item => item.campaign_id === data.id);
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
        SET_USER_NAME(state, user_name) {
            state.user_name = user_name;
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
            // console.log(res.data.timers)
            commit("SET_TIMERS", res.data.timers);
        },

        async getTowerData({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/towersrecords",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // console.log(res.data.timers)
            commit("SET_TOWERS", res.data.towers);
        },



        async getStationData({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/stationrecords",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // console.log(res.data.timers)
            commit("SET_STATIONS", res.data.stations);
        },

        async getCampaignJoinData({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/campaignjoin",
                data: this.picked,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // console.log(res.data.timers)
            commit("SET_CAMPAIGN_JOIN", res.data.value);
        },


        async getCampaignMembers({ commit, state }, campaign_id) {
            let res = await axios({
                method: "get",
                url: "/api/campaignsystemusers/" + campaign_id,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // debugger
            // console.log(res.data.timers)
            commit("SET_CAMPAIGN_MEMBERS", res.data.users);
        },


        async getUsers({ commit, state }) {
            if(state.token == ""){
                 await sleep(500)
            }
            let res = await axios({
                method: "get",
                url: "/api/allusersroles",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // debugger
            commit("SET_USERS",res.data.usersroles)
            // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
        },

        async getRoles({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/roles",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_ROLES",res.data.roles)
            // commit("SET_USER_ROLES", userRoles.map(u => ({id: u.id, name: u.name})));
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

        async getMultiCampaigns({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/multicampaigns",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // console.log(res.data.campaigns);
            commit("SET_MULTI_CAMPAIGNS", res.data.campaigns);
        },




        async getCampaignsList({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/campaignslist",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            // console.log(res.data.campaigns);
            commit("SET_CAMPAIGNSLIST", res.data.campaignslist);
        },

        markOver({ commit }, timer) {
            commit("MARK_TIMER_OVER", timer);
        },

        updateNotification({ commit }, data) {
            commit("UPDATE_NOTIFICATIONS", data);
        },

        updateStations({ commit }, data) {
            commit("UPDATE_STATIONS", data);
        },

        updateCampaigns({ commit }, data) {
            commit("UPDATE_CAMPAIGNS", data);
        },

        updateTowers({ commit }, data) {
            commit("UPDATE_TOWERS", data);
        },

        updateCampaignSystem({ commit }, data) {
            commit("UPDATE_CAMPAIGN_SYSTEM", data);
        },

        updateCampaignSystemBar({ commit }, data) {
            commit("UPDATE_CAMPAIGN_SYSTEM_UPDATE", data);
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
        setUser_name({ commit }, user_name) {
            commit("SET_USER_NAME", user_name);
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

        getCampaignsCount: state => {
            return state.campaigns.length;
        },



        getSystemReadyToGoCount: state => payload => {
            // console.log(payload, " - ", state.campaignusers)
            // console.log(state.campaignusers.filter(u => u.id == payload.campaign_id && u.system_id == payload.system_id && u.status_id ==  3))

            return state.campaignusers.filter(u => u.campaign_id == payload.campaign_id && u.system_id == payload.system_id && u.status_id ==  3).length
        },

        getSystemOnTheWayCount: state => payload => {
            return state.campaignusers.filter(u => u.campaign_id == payload.campaign_id && u.system_id == payload.system_id && u.status_id == 2 ).length
        },

        getCampaignById: state => id => {
            return state.campaigns.find(campaigns => campaigns.id == id);
        },

        getsCampaignById: state => id => {
            return state.campaignJoin.filter(campaigns => campaigns.custom_campaign_id == id);
        },

        getsActiveCampaignById: state => payload => {
            return state.campaignJoin.filter(c => c.custom_campaign_id == payload.id && (c.status_id == 2 || c.status_id == 1) && c.constellation_id == payload.constellation_id && c.warmup == 1);
        },

        getsActiveCampaignByIdDrop: state => payload => {
            return state.campaignJoin.filter(c => c.custom_campaign_id == payload.id && c.status_id == 2 && c.constellation_id == payload.constellation_id && c.warmup == 1);
        },


        getCampaignJoinById: state => id => {
            return state.campaignJoin.filter(c => c.custom_campaign_id == id);
        },

        getCampaignMembersByCampaign: state => id => {
            return state.campaignmembers.find(m => m.campaign_id == id);
        },

        getCampaignUsersReadyToGoAll: state => id => {
            // console.log("poo",state.campaignusers.filter(campaignusers => campaignusers.site_id == id))
            return state.campaignusers.filter(
                campaignusers => campaignusers.system_id == id && campaignusers.status_id == 3
            );

        },

        getCampaignUsersOnTheWayAll: state => id => {
            // console.log("poo",state.campaignusers.filter(campaignusers => campaignusers.site_id == id))
            return state.campaignusers.filter(
                campaignusers => campaignusers.system_id == id && campaignusers.status_id == 2
            );

        },

        getCampaignUsersByUserId: state => id => {
            // console.log("poo",state.campaignusers.filter(campaignusers => campaignusers.site_id == id))
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id
            );

        },

        getCampaignUsersByUserIdCount: state => id =>{
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id
            ).length;
        },

        getCampaignUsersByUserIdEntosis: state => id => {
            // console.log("poo",state.campaignusers.filter(campaignusers => campaignusers.site_id == id))
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id && campaignusers.role_id == 1
            );
        },

        getCampaignUsersByUserIdEntosisFree: state => id => {
            // console.log("poo",state.campaignusers.filter(campaignusers => campaignusers.site_id == id))
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id && campaignusers.role_id == 1 && campaignusers.node_id == null
            );
        },
        getCampaignUsersByUserIdEntosisCount: state => id => {
            // console.log("poo",state.campaignusers.filter(campaignusers => campaignusers.site_id == id))
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id && campaignusers.role_id == 1
            ).length;
        },


        getActiveCampaigns: state => {
            return state.campaigns.find(campaigns => campaigns.status_id == 2);
        },

        getTotalNodeCountByCampaign: state => id => {
            return state.campaignsystems.filter(
                sys => sys.campaign_id == id
            ).length
        },

        getTotalNodeCountByMultiCampaign: state => id => {
            return state.campaignsystems.filter(
                sys => sys.custom_campaign_id == id
            ).length
        },

        getHackingNodeCountByCampaign: state => id => {
            return state.campaignsystems.filter(
                sys => sys.campaign_id == id && sys.status_id != 1 && (sys.status_id == 2 || sys.status_id == 3 || sys.status_id == 4 || sys.status_id == 8)
            ).length
        },

        getRedHackingNodeCountByCampaign: state => id => {
            return state.campaignsystems.filter(
                sys => sys.campaign_id == id && (sys.status_id == 7 || sys.status_id == 5)
            ).length
        },


        getHackingNodeCountByMultiCampaign: state => id => {
            return state.campaignsystems.filter(
                sys => sys.custom_campaign_id == id && sys.status_id != 1 && (sys.status_id == 2 || sys.status_id == 3 || sys.status_id == 4 || sys.status_id == 8)
            ).length
        },

        getRedHackingNodeCountByMultiCampaign: state => id => {
            return state.campaignsystems.filter(
                sys => sys.custom_campaign_id == id && (sys.status_id == 7 || sys.status_id == 5)
            ).length
        },



        getTotalNodeCountBySystem: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.campaign_id == payload.campaign_id
            ).length
        },

        getTotalNodeCountBySystemByMultiCampaign: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.custom_campaign_id == payload.campaign_id
            ).length
        },

        getHackingNodeCountBySystem: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.status_id != 1 && sys.campaign_id == payload.campaign_id && (sys.status_id == 2 || sys.status_id == 3 || sys.status_id == 4 || sys.status_id == 8)
            ).length
        },

        getHackingNodeCountBySystemByMultiCampaign: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.custom_campaign_id == payload.campaign_id && (sys.status_id == 2 || sys.status_id == 4 || sys.status_id == 8 )
            ).length

        },

        getRedHackingNodeCountBySystem: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.campaign_id == payload.campaign_id && (sys.status_id == 7 || sys.status_id == 5)
            ).length
        },

        getRedHackingNodeCountBySystemByMultiCampaign: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.custom_campaign_id == payload.campaign_id && (sys.status_id == 5 || sys.status_id == 7)
            ).length
        },

        getNodeValue:state => payload => {

            let total = state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id
            ).length

            let hack = state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.status_id != 1 && sys.campaign_id == payload.campaign_id && sys.status_id !=7 && sys.status_id !=6
            ).length

            let num = (hack/total)*100

            if(num == null){
                return 0.0
            }else{

                return num
            }


        }

    }
});
