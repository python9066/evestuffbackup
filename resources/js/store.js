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
        allianceticklist: [],
        ammoRequest:[],
        campaigns: [],
        campaignsRegion: [],
        campaignJoin: [],
        campaignSolaSystems:[],
        campaignslist: [],
        campaignusers: [],
        campaignsystems: [],
        campaignmembers: [],
        constellationlist:[],
        cores:[],
        delveLink: "",
        items:[],
        loggingAdmin:[],
        loggingcampaign: [],
        moonlist:[],
        multicampaigns: [],
        nodeJoin: [],
        notifications: [],
        periodbasisLink: "",
        queriousLink: "",
        rcfcs: [],
        rcsheetRegion: [],
        rcsheetItem:[],
        rcstations:[],
        recontasksystems:[],
        rolesList: [],
        startcampaigns: [],
        startcampaignJoin: [],
        startcampaignsystems:[],
        stations: [],
        stationFits: [],
        structurelist:[],
        systemlist: [],
        ticklist:[],
        timers: [],
        timersRegions: [],
        token: "",
        tooltipToggle: true,
        towers: [],
        towerlist:[],
        users:[],
        user_id: 0,
        user_name:"",
        userschars: [],
    },
    mutations: {
        SET_AMMO_REQUEST(state, ammorequest) {
            state.ammoRequest = ammorequest;
        },

        DELETE_AMMO_REQUEST(state, id) {
            let index = state.ammoRequest.findIndex(e => e.id == id)
            if(index >= 0){state.ammoRequest.splice(index, 1)}
        },

        ADD_AMMO_REQUEST(state, data) {
            state.ammoRequest.push(data)
        },

        UPDATE_AMMO_REQUEST(state, data) {
            const item = state.ammoRequest.find(item => item.id === data.id);
            const count = state.ammoRequest.filter(item => item.id === data.id).length
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.ammoRequest.push(data)
            }
        },

        SET_SYSTEMLIST(state, systemlist) {
            state.systemlist = systemlist;
        },

        SET_CONSTELLATION_LIST(state, constellationlist) {
            state.constellationlist = constellationlist;
        },

        UPDATE_TOOLTIP_TOGGLE(state, tooltipToggle) {
            if (tooltipToggle) {
                state.tooltipToggle = false
            } else {
                state.tooltipToggle = true
            }
        },

        SET_MOONLIST(state, moonlist) {
            state.moonlist = moonlist;
        },


        SET_STRUCTURELIST(state, structurelist) {
            state.structurelist = structurelist;
        },

        SET_TOWERLIST(state, towerlist) {
            state.towerlist = towerlist;
        },

        SET_TICKLIST(state, ticklist) {
            state.ticklist = ticklist;
        },

        SET_ALLIANCE_TICKLIST(state, allianceticklist) {
            state.allianceticklist = allianceticklist;
        },


        SET_TIMERS(state, timers) {
            state.timers = timers;
        },

        SET_TIMERS_REGIONS(state, timersRegions) {
            state.timersRegions = timersRegions;
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

        SET_ITEMS(state, items) {
          state.items = items
        },

        SET_CAMPAIGN_JOIN(state, campaignJoin) {
            state.campaignJoin = campaignJoin;
        },

        SET_START_CAMPAIGN_JOIN(state, startcampaignJoin) {
            state.startcampaignJoin = startcampaignJoin;
        },

        SET_TOWERS(state, towers) {
            state.towers = towers;
        },

        UPDATE_TOWERS(state, data) {
            const item = state.towers.find(c => c.id === data.id);
            Object.assign(item, data);
        },

        DELETE_TOWERS(state, id) {
            let index = state.towers.findIndex(e => e.id == id)
            if(index >= 0){state.towers.splice(index, 1)}
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

        SET_STATIONS_FIT(state, fit) {
            state.stationFits = fit;
        },

        UPDATE_STATION_NOTIFICATION(state, data) {
            const item = state.stations.find(item => item.id === data.id);
            const count = state.stations.filter(item => item.id === data.id).length
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.stations.push(data)
            }
        },

        UPDATE_RC_STATION(state, data) {
            const item = state.rcstations.find(item => item.id === data.id);
            const count = state.rcstations.filter(item => item.id === data.id).length
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.rcstations.push(data)
            }
        },

        UPDATE_RC_FC(state, data) {
            const item = state.rcfcs.find(item => item.id === data.id);
            const count = state.rcfcs.filter(item => item.id === data.id).length
            if (count > 0) {
                Object.assign(item, data);
            } else {
                state.rcfcs.push(data)
            }
        },

        SET_CORES(state, cores) {
            state.cores = cores;
        },

        UPDATE_CORES(state, data) {
            const item = state.cores.find(c => c.station_id === data.station_id);
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
            const item = state.userschars.find(item => item.id == data.id);
            if (item != null) {
                Object.assign(item, data);
            }
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

        SET_CAMPAIGNS_REGION(state, campaignsRegion) {
            state.campaignsRegion = campaignsRegion;
        },

        SET_RC_REGION(state, rcsheetRegion) {
            state.rcsheetRegion = rcsheetRegion;
        },

        SET_RC_TYPE(state, rcsheetItem) {
            state.rcsheetItem = rcsheetItem;
        },




        UPDATE_CAMPAIGNS(state, data) {
            const item = state.campaigns.find(c => c.id === data.id);
            Object.assign(item, data);
        },

        UPDATE_CAMPAIGN(state, data) {
            const item = state.campaigns.find(item => item.id == data.id);
            Object.assign(item, data);
        },

        SET_MULTI_CAMPAIGNS(state, multicampaigns) {
            state.multicampaigns = multicampaigns;
        },

        SET_START_CAMPAIGNS(state, startcampaigns) {
            state.startcampaigns = startcampaigns;
        },

        SET_CAMPAIGNSLIST(state, campaignslist) {
            state.campaignslist = campaignslist;
        },

        SET_LOGGING_CAMPAIGN(state, logs) {
            state.loggingcampaign = logs;
        },

        ADD_LOGGING_CAMPGIN(state, data) {
            state.loggingcampaign.push(data)
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

        SET_START_CAMPAIGN_SYSTEMS(state, systems) {
            state.startcampaignsystems = systems;
        },

        UPDATE_START_CAMPAIGN_SYSTEM(state, data) {
            const item = state.startcampaignsystems.find(item => item.id === data.id);
            Object.assign(item, data);
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
            if (item != null) {
                Object.assign(item, data);
            }
        },

        ADD_CAMPAIGN_USERS(state, data) {
            state.campaignusers.push(data);
        },


        DELETE_CAMPAIGN_USER(state, id) {
            let index = state.campaignusers.findIndex(user => user.id == id)
            if(index >= 0){state.campaignusers.splice(index, 1)}

        },

        ADD_STATION_NOTIFICATION(state, data) {
            const check = state.stations.find(station => station.id == data.id)
            if (check != null) {
                Object.assign(check, data);
            } else {
                state.stations.push(data)
            }
        },


        DELETE_STATION_NOTIFICATION(state, id) {
            let index = state.stations.findIndex(s => s.id == id)
            if(index >= 0){state.stations.splice(index, 1)}

        },


        SET_RECON_TASK_SYSTEMS(state, systems) {
            state.recontasksystems = systems;
        },

        SET_RC_STATIONS(state, stations) {
            state.rcstations = stations;
        },

        SET_RC_FCS(state, fcs) {
            state.rcfcs = fcs;
        },




        UPDATE_RECON_TASK_SYSTEMS(state, data) {
            const item = state.recontasksystems.find(c => c.id === data.id);
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

        async getTimerDataAllRegion({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/timersregions",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_TIMERS_REGIONS", res.data.timersregions);
        },





        async getSystemList({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/systemlist",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_SYSTEMLIST", res.data.systemlist);
        },

        async getAmmoRequest({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/ammorequestrecords",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_AMMO_REQUEST", res.data.ammorequest);
        },

        async getMoonList({ commit, state }, system_id) {
            let res = await axios({
                method: "get",
                url: "/api/moons/" + system_id,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_MOONLIST", res.data.moons);
        },



        async getStructureList({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/structurelist",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_STRUCTURELIST", res.data.structurelist);
        },

        async getTowerList({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/towerlist",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_TOWERLIST", res.data.towerlist);
        },





        async getTickList({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/ticklist",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_TICKLIST", res.data.ticklist);
        },

        async getAllianceTickList({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/allianceticklist",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_ALLIANCE_TICKLIST", res.data.allianceticklist);
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




        async getCampaignJoinDataByCampaign({ commit, state }, campid) {
            let res = await axios({
                method: "get",
                url: "/api/campaignjoinbyid/" + campid,
                data: this.picked,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_CAMPAIGN_JOIN", res.data.value);
        },

        async getCampaignJoinData({ commit, state }, campid) {
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

        async getStartCampaignJoinDataByCampaign({ commit, state }, campid) {
            let res = await axios({
                method: "get",
                url: "/api/startcampaignjoinbyid/" + campid,
                data: this.picked,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_START_CAMPAIGN_JOIN", res.data.value);
        },

        async getStartCampaignJoinData({ commit, state }, campid) {
            let res = await axios({
                method: "get",
                url: "/api/startcampaignjoin",
                data: this.picked,
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_START_CAMPAIGN_JOIN", res.data.value);
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

        async getCampaignsRegions({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/campaignsregion",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_CAMPAIGNS_REGION", res.data.campaignslistRegion);
        },

        async getRcRegions({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/rcregionlist",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_RC_REGION", res.data.rcsheetlistRegion);
        },

        async getRcItems({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/rcTypelist",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_RC_TYPE", res.data.rcsheetlistType);
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

        async getStartCampaigns({ commit, state }) {
            let res = await axios({
                method: "get",
                url: "/api/startcampaigns",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            commit("SET_START_CAMPAIGNS", res.data.campaigns);
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

        updateStationNotification({ commit }, data) {
            commit("UPDATE_STATION_NOTIFICATION", data);
        },

        updateRcStation({ commit }, data) {
            commit("UPDATE_RC_STATION", data);
        },

        updateRcFC({ commit }, data) {
            commit("UPDATE_RC_FC", data);
        },

        updateCores({ commit }, data) {
            commit("UPDATE_CORES", data);
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

        updateStartCampaignSystem({ commit }, data) {
            commit("UPDATE_START_CAMPAIGN_SYSTEM", data);
        },

        updateTooltipToggle({ commit }, data) {
            commit("UPDATE_TOOLTIP_TOGGLE", data);
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

        updateAmmoRequest({ commit }, data) {
            commit("UPDATE_AMMO_REQUEST", data);
        },

        updateNodeJoin({ commit }, data) {
            commit('UPDATE_NODE_JOIN', data)
        },

        updateReconTaskSystems({ commit }, data) {
            commit("UPDATE_RECON_TASK_SYSTEMS", data);
        },

        addNodeJoin({ commit }, data) {
            commit("ADD_NODE_JOIN", data)
        },

        addAmmoRequest({ commit }, data) {
            commit("ADD_AMMO_REQUEST", data)
        },

        addStationNotification({ commit }, data) {
            commit("ADD_STATION_NOTIFICATION", data)
        },

        addLoggingCampaign({ commit }, data) {
            commit("ADD_LOGGING_CAMPGIN",data)
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

        deleteStationNotification({ commit }, id) {
            commit("DELETE_STATION_NOTIFICATION", id)
        },

        deleteUsersChars({ commit }, id) {
            commit("DELETE_USER_CHAR",id)
        },

        deleteNodeJoin({ commit }, id) {
            commit("DELETE_NODE_JOIN",id)
        },

        deleteAmmoRequest({ commit }, id) {
            commit("DELETE_AMMO_REQUEST",id)
        },



        deleteTower({ commit }, id) {
            commit("DELETE_TOWERS",id)
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


        async getStartCampaignSystemsRecords({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/startcampaignsystemsrecords",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            if (res.data.length != 0) {
                commit("SET_START_CAMPAIGN_SYSTEMS", res.data.systems);
            }
        },

        async getReconTaskSystemsRecords({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/recontasksystems",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            if (res.data.length != 0) {
                commit("SET_RECON_TASK_SYSTEMS", res.data.systems);
            }
        },

        async getRcStationRecords({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/rcsheet",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            if (res.data.length != 0) {
                commit("SET_RC_STATIONS", res.data.stations);
            }
        },


        async getRcFcs({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/rcfc",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
            if (res.data.length != 0) {
                commit("SET_RC_FCS", res.data.fcs);
            }
        },






        async getConstellationList({ commit, state }) {
            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/constellations",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

                commit("SET_CONSTELLATION_LIST", res.data.constellationlist);

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


        async loadStationInfo({ commit, state }) {

            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/loadstationdata",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            if (res.data.length != 0) {
                commit("SET_CORES", res.data.cores);
                commit("SET_ITEMS", res.data.items);
                commit("SET_STATIONS_FIT", res.data.fit);
            }
        },

        async loadAmmoRequestInfo({ commit, state }) {

            let res = await axios({
                method: "get", //you can set what request you want to be
                url: "/api/loadammorequestdata",
                headers: {
                    Authorization: "Bearer " + state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            if (res.data.length != 0) {
                commit("SET_CORES", res.data.cores);
                commit("SET_ITEMS", res.data.items);
                commit("SET_STATIONS_FIT", res.data.fit);
                commit("SET_AMMO_REQUEST", res.data.ammorequest);
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

        getStartCampaignById: state => id => {
            return state.startcampaignJoin.filter(campaigns => campaigns.start_campaign_id == id);
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

        getStartJoinById: state => id => {
            return state.startcampaignJoin.filter(c => c.start_campaign_id == id);
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


        getStartCampaignsById: state => campid => {
            return state.startcampaigns.filter(c => c.id == campid)
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


        getStationItemsByStationID: state => id => {
            let pull = state.items.filter(item => item.station_id == id)
            let count = pull.length
            if (count != 0) {
                return pull
            } else {
                return []
            }
        },

        getCoreByStationID: state => id => {
            let pull = state.cores.filter(core => core.station_id == id)
            let count = pull.length
            if (count != 0) {
                return pull
            } else {
                return []
            }
        },

        getStationFitByStationID: state => id => {
            let pull = state.stationFits.filter(fit => fit.id == id)
            let count = pull.length
            if (count != 0) {
                return pull
            } else {
                return "NO"
            }
        }

    }
});
