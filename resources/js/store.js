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
        campaignJoin: [],
        campaignSolaSystems:[],
        campaignslist: [],
        campaignusers: [],
        campaignsystems: [],
        campaignmembers:[],
        delveLink: "",
        loggingAdmin:[],
        loggingcampaign:[],
        multicampaigns: [],
        nodeJoin: [],
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
        userschars: [],
    },
    mutations: {
        SET_TIMERS(state, timers) {
            state.timers = timers;
        },

        MARK_TIMER_OVER(state, timer) {
            const item = state.timers.find(item => item.id === timer.id);
            Object.assign(item, timer);
        },

        SET_NODE_JOIN(state, nodeJoin) {
            state.nodeJoin = nodeJoin;
        },

        ADD_NODE_JOIN(state, data) {
            state.nodeJoin.push(data)
        },

        UPDATE_NODE_JOIN(state, data) {
            const item = state.nodeJoin.find(c => c.id === data.id);
            Object.assign(item, data);
        },

        DELETE_NODE_JOIN(state, id) {
            let index = state.nodeJoin.findIndex(e => e.id == id)
            if(index >= 0){state.nodeJoin.splice(index, 1)}
        },

        SET_CAMPAIGN_JOIN(state, campaignJoin) {
            state.campaignJoin = campaignJoin;
        },

        SET_TOWERS(state, towers) {
            state.towers = towers;
        },

        UPDATE_TOWERS(state, data) {
            const item = state.towers.find(c => c.id === data.id);
            Object.assign(item, data);
        },

        SET_CAMPAIGN_SOLA_SYSTEMS(state, campaignSolaSystems) {
            state.campaignSolaSystems = campaignSolaSystems;
        },

        UPDATE_CAMPAIGN_SOLA_SYSTEMS(state, data) {
            const item = state.campaignSolaSystems.find(c => c.id === data.id);
            Object.assign(item, data);
        },

        SET_STATIONS(state, stations) {
            state.stations = stations;
        },

        UPDATE_STATIONS(state, data) {
            const item = state.stations.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        SET_CAMPAIGN_MEMBERS(state, users) {
            state.campaignmembers = users;
        },

        SET_USERS(state, users) {
            state.users = users;
        },

        SET_USERS_CHARS(state, data) {
            state.userschars = data;
        },

        UPDATE_USERS_CHARS(state, data) {
            const item = state.userschars.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        DELETE_USER_CHAR(state, id) {
            let index = state.userschars.findIndex(user => user.id == id)
            if(index >= 0){state.userschars.splice(index, 1)}

        },

        ADD_USER_CHAR(state, data) {
            state.userschars.push(data);
        },

        SET_ROLES(state, roles) {
            state.rolesList = roles;
        },

        SET_CAMPAIGNS(state, campaigns) {
            state.campaigns = campaigns;
        },

        UPDATE_CAMPAIGNS(state, data) {
            const item = state.campaigns.find(c => c.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_CAMPAIGN(state, data) {
            const item = state.campaigns.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        SET_MULTI_CAMPAIGNS(state, multicampaigns) {
            state.multicampaigns = multicampaigns;
        },

        SET_CAMPAIGNSLIST(state, campaignslist) {
            state.campaignslist = campaignslist;
        },

        SET_LOGGING_CAMPAIGN(state, logs) {
            state.loggingcampaign = logs;
        },

        SET_LOGGING_ADMIN(state, logs) {
            state.loggingAdmin = logs;
        },

        SET_NOTIFICATIONS(state, notifications) {
            state.notifications = notifications;
        },

        UPDATE_NOTIFICATIONS(state, data) {
            const item = state.notifications.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        SET_CAMPAIGN_SYSTEMS(state, systems) {
            state.campaignsystems = systems;
        },

        UPDATE_CAMPAIGN_SYSTEM(state, data) {
            const item = state.campaignsystems.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        ADD_CAMPAIGN_SYSTEM(state, data) {
            state.campaignsystems.push(data)
        },

        UPDATE_CAMPAIGN_SYSTEM_BY_USER_ID(state, payload) {
            const item = state.campaignsystems.find(item => item.user_id == payload.user_id);
            if (item > 0) {
                Object.assign(item, payload.data);
            }
        },

        UPDATE_CAMPAIGN_SYSTEM_UPDATE(state, data) {
            const item = state.campaignsystems.find(item => item.campaign_id === data.id);
            Object.assign(item, data);
        },

        DELETE_CAMPAIGN_SYSTEM(state, id) {
            let index = state.campaignsystems.findIndex(e => e.id == id)
            if(index >= 0){state.campaignsystems.splice(index, 1)}
        },


        SET_CAMPAIGN_USERS(state, data) {
            state.campaignusers = data;
        },

        UPDATE_CAMPAIGN_USERS(state, data) {
            const item = state.campaignusers.find(item => item.id === data.id);
            Object.assign(item, data);
        },

        ADD_CAMPAIGN_USERS(state, data) {
            state.campaignusers.push(data);
        },

        DELETE_CAMPAIGN_USER(state, id) {
            let index = state.campaignusers.findIndex(user => user.id == id)
            if(index >= 0){state.campaignusers.splice(index, 1)}

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

        SET_DELVE_LINK(state, delveLink) {
            state.delveLink = delveLink;
        },

        SET_QUERIOUS_LINK(state, queriousLink) {
            state.queriousLink = queriousLink;
        },

        SET_PERIOD_BASIS_LINK(state, periodbasisLink) {
            state.periodbasisLink = periodbasisLink;
        },


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

        async getNodeJoinByCampaignId({ commit, state }, campaign_id) {
            let res = await axios({
                method: "get",
                url: "/api/nodejoin/" + campaign_id,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_NODE_JOIN", res.data.nodeJoin);
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
            commit("SET_CAMPAIGN_MEMBERS", res.data.users);
        },


        async getUsersChars({ commit, state }, user_id) {
            let res = await axios({
                method: "get",
                url: "/api/campaignusersrecordsbychar/" + user_id,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_USERS_CHARS", res.data.users);
        },

        async getCampaignSolaSystems({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/campaignsolasystems",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_CAMPAIGN_SOLA_SYSTEMS", res.data.data);
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
            commit("SET_CAMPAIGNSLIST", res.data.campaignslist);
        },

        async getLoggingCampaign({ commit, state }, campaign_id) {
            let res = await axios({
                method: "get",
                url: "/api/checkcampaign/" + campaign_id,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_LOGGING_CAMPAIGN", res.data.logs);
        },

        async getLoggingAdmin({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/checkadmin",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_LOGGING_ADMIN", res.data.logs);
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

        updateCampaignSystemByUserID({ commit }, payload) {
            commit("UPDATE_CAMPAIGN_SYSTEM_BY_USER_ID", payload);
        },

        updateCampaignSolaSystem({ commit }, data) {
            commit("UPDATE_CAMPAIGN_SOLA_SYSTEMS", data);
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

        updateUsersChars({ commit }, data) {
            commit("UPDATE_USERS_CHARS", data);
        },

        updateNodeJoin({ commit }, data) {
            console.log(data)
            commit('UPDATE_NODE_JOIN', data)
        },

        addNodeJoin({ commit }, data) {
            commit("ADD_NODE_JOIN", data)
        },

        addCampaignUserNew({ commit }, data) {
            commit("ADD_CAMPAIGN_USERS", data)
        },

        addCampaignSystem({ commit }, data) {
            commit("ADD_CAMPAIGN_SYSTEM",data)
        },

        deleteCampaignUser({ commit }, id) {
            commit("DELETE_CAMPAIGN_USER", id)
        },

        deleteUsersChars({ commit }, id) {
            commit("DELETE_USER_CHAR",id)
        },

        deleteNodeJoin({ commit }, id) {
            commit("DELETE_NODE_JOIN",id)
        },


        deleteCampaignSystem({ commit }, id) {
            commit("DELETE_CAMPAIGN_SYSTEM",id)
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
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/notifications/10000060",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
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
        },

        async loadCampaignSystemData({ commit, state }, payload) {
            let request = {
                user_id: payload.user_id,
                campaign_id: payload.campaign_id,
                type: payload.type
            }

            let res = await axios({
                method: "post", //you can set what request you want to be
                url: "/api/campaignsystemload",
                data: request,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            if (res.data.length != 0) {
                commit("SET_CAMPAIGN_SOLA_SYSTEMS", res.data.sola);
                commit("SET_NODE_JOIN", res.data.nodejoin);
                commit("SET_CAMPAIGN_USERS", res.data.users);
                commit("SET_CAMPAIGN_SYSTEMS", res.data.systems);
                commit("SET_USERS_CHARS", res.data.usersbyid);
                commit("SET_LOGGING_CAMPAIGN", res.data.logs);
            }
        },


    },
    getters: {

        getCampaignsCount: state => {
            return state.campaigns.length;
        },

        getMultiCampaignsCount: state => {
            return state.multicampaigns.length;
        },

        getMultiCampaignName: state => campid => {
            return state.multicampaigns.filter(m => m.id == campid)
        },



        getSystemReadyToGoCount: state => payload => {

            return state.campaignusers.filter(u => u.campaign_id == payload.campaign_id && u.system_id == payload.system_id && u.status_id ==  3).length
        },

        getCampaignSolaSystemFilter: state => payload => {

            return state.campaignSolaSystems.filter(u => u.campaign_id == payload.campaign_id && u.system_id == payload.system_id)
        },

        getSystemOnTheWayCount: state => payload => {
            return state.campaignusers.filter(u => u.campaign_id == payload.campaign_id && u.system_id == payload.system_id && u.status_id == 2 ).length
        },

        getCampaignById: state => id => {
            return state.campaigns.find(campaigns => campaigns.id == id);
        },

        getCampaignByLink: state => id => {
            return state.campaigns.find(campaigns => campaigns.link == id);
        },

        getsCampaignById: state => id => {
            return state.campaignJoin.filter(campaigns => campaigns.custom_campaign_id == id);
        },

        getsActiveCampaignById: state => payload => {
            return state.campaignJoin.filter(c => c.custom_campaign_id == payload.id && (c.status_id == 2 || c.status_id == 1) && c.constellation_id == payload.constellation_id && c.warmup == 1);
        },

        //changeback

        getsActiveCampaignByIdDrop: state => payload => {
            return state.campaignJoin.filter(c => c.custom_campaign_id == payload.id && c.status_id == 2 && c.constellation_id == payload.constellation_id && c.warmup == 1);
        },

        // getsActiveCampaignByIdDrop: st  ate => payload => {
        //     return state.campaignJoin.filter(c => c.custom_campaign_id == payload.id && c.constellation_id == payload.constellation_id);
        // },


        getCampaignJoinById: state => id => {
            return state.campaignJoin.filter(c => c.custom_campaign_id == id);
        },

        getCampaignMembersByCampaign: state => id => {
            return state.campaignmembers.find(m => m.campaign_id == id);
        },

        getCampaignUsersReadyToGoAll: state => id => {
            return state.campaignusers.filter(
                campaignusers => campaignusers.system_id == id && campaignusers.status_id == 3
            );

        },

        getCampaignUsersOnTheWayAll: state => id => {
            return state.campaignusers.filter(
                campaignusers => campaignusers.system_id == id && campaignusers.status_id == 2
            );

        },

        getCampaignUsersByUserId: state => id => {
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
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id && campaignusers.role_id == 1
            );
        },

        getCampaignUsersByUserIdEntosisFree: state => id => {
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id && campaignusers.role_id == 1 && campaignusers.node_id == null
            );
        },

        getCampaignUsersByUserIdEntosisFreeCount: state => id => {
            return state.campaignusers.filter(
                campaignusers => campaignusers.site_id == id && campaignusers.role_id == 1 && campaignusers.node_id == null
            ).length;
        },

        getCampaignUsersByUserIdEntosisCount: state => id => {
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
                sys => sys.custom_campaign_id == id && sys.warmup == 1
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
                sys => sys.custom_campaign_id == id && sys.warmup == 1 && sys.status_id != 1 && (sys.status_id == 2 || sys.status_id == 3 || sys.status_id == 4 || sys.status_id == 8)
            ).length
        },

        getRedHackingNodeCountByMultiCampaign: state => id => {
            return state.campaignsystems.filter(
                sys => sys.custom_campaign_id == id && sys.warmup == 1 && (sys.status_id == 7 || sys.status_id == 5)
            ).length
        },



        getTotalNodeCountBySystem: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.campaign_id == payload.campaign_id
            ).length
        },

        getTotalNodeCountBySystemByMultiCampaign: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.warmup == 1 && sys.custom_campaign_id == payload.campaign_id
            ).length
        },

        getHackingNodeCountBySystem: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.status_id != 1 && sys.campaign_id == payload.campaign_id && (sys.status_id == 2 || sys.status_id == 3 || sys.status_id == 4 || sys.status_id == 8)
            ).length
        },

        getHackingNodeCountBySystemByMultiCampaign: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.warmup == 1 && sys.custom_campaign_id == payload.campaign_id && (sys.status_id == 2 || sys.status_id == 4 || sys.status_id == 8 || sys.status_id == 3)
            ).length

        },

        getRedHackingNodeCountBySystem: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.campaign_id == payload.campaign_id && (sys.status_id == 7 || sys.status_id == 5)
            ).length
        },

        getRedHackingNodeCountBySystemByMultiCampaign: state => payload => {
            return state.campaignsystems.filter(
                sys => sys.system_id == payload.system_id && sys.warmup == 1 && sys.custom_campaign_id == payload.campaign_id && (sys.status_id == 5 || sys.status_id == 7)
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


        },

        getLoggingCampaignBySola: state => sola_id => {
            return state.loggingcampaign.filter(log => log.campaign_sola_system_id == sola_id)
        },

        getLoggingCampaignByCampaign: state => campid => {
            return state.loggingcampaign.filter(log => log.campaign_sola_system_id == null && log.campaign_id == campid)
        },

        getLoggingAdmin: state => campid => {
            return state.loggingcampaign.filter(log => log.campaign_sola_system_id == null && log.campaign_id == campid)
        },

        getNodeJoinByNode: state => sysid => {
            return state.nodeJoin.filter(node => node.campaign_system_id == sysid)
        },

        getNodeJoinByNodeCount: state => sysid => {
            return state.nodeJoin.filter(node => node.campaign_system_id == sysid).length
        },

        getSystemTableExpandable: state => payload => {
            let count = state.campaignsystems.filter(node => node.system_id == payload.system_id && node.campaign_id == payload.campid && node.node_join_count > 0)
            if (count != null) {
                return count
            } else {
                return []
            }
        },

        getUsersOnNodeByID: state => nodeid => {
            let pull = state.campaignusers.filter(user => user.campaign_system_id == nodeid)
            let count = pull.length
            if (count != 0) {
                return pull
            } else {
                return []
            }
        },

        getCharsOnNodeByID: state => nodeid => {
            let pull = state.userschars.filter(char => char.campaign_system_id == nodeid)
            let count = pull.length
            if (count != 0) {
                return pull
            } else {
                return []
            }
        },



        getSystemTableExpandableMulti: state => payload => {
            let count = state.campaignsystems.filter(node => node.system_id == payload.system_id && node.custom_campaign_id == payload.campid && node.node_join_count > 0)
            if (count != null) {
                return count
            } else {
                return []
            }
        },

    }
});
