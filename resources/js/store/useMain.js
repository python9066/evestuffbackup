import { defineStore } from "pinia";
//

export const useMainStore = defineStore("main", {
    state: () => ({
        constellationlist: [],
        eveUserCount: 0,
        newSoloOperations: [],
        newSoloOperationsRegionList: [],
        newSoloOperationsConstellationList: [],
        newCampaignsList: [],
        newOperationList: [],
        size: [],
        startcampaigns: [],
        startcampaignJoin: [],
        stationListPullRegions: [],
        stationListFCRegions: [],
        stationListRegionList: [],
        systemlist: [],
        structurelist: [],
        stationList: [],
        stations: [],
        timers: [],
        timersRegions: [],
        ticklist: [],
        stationChatWindowId: null,
        user_name: null,
        user_id: null,
        missingCorpID: 0,
        missingCorpTick: "",
        webwayStartSystems: [],
        webwaySelectedStartSystem: {
            value: 30004759,
            text: "1DQ1-A",
        },
    }),

    getters: {
        getStartJoinById: (state) => (id) => {
            let data = state.startcampaignJoin.filter(
                (c) => c.start_campaign_id == id
            );
            return data;
        },

        getStationMessages: (state) => (id) => {
            let station = state.stationList.find((s) => s.id == id);
            let messages = station.notes;
            if (messages) {
                return messages;
            }
            return [];
        },

        getUnreadMessageCount: (state) => (id) => {
            let station = state.stationList.find((s) => s.id == id);
            let messages = station.notes;
            let count = messages.filter(
                (m) =>
                    m.read_by &&
                    m.read_by.user_id &&
                    !m.read_by.user_id.includes(state.user_id)
            ).length;

            if (state.stationChatWindowId == id && count > 0) {
                axios({
                    method: "put",
                    url: "/api/sheetmessage/" + id + "/notes",
                    withCredentials: true,
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                });
            }
            return count;
        },
    },
    actions: {
        async updateTickList(ticker) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/addmissingcorp/" + ticker,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.ticklist = res.data.ticklist;
            this.misingCorpID = res.data.corpID;
            this.missingCorpTicker = res.data.corpTicker;
        },

        async getStructureList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/structurelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.structurelist = res.data.structurelist;
        },

        async getSystemList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/systemlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.systemlist = res.data.systemlist;
        },

        async getTickList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/ticklist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.ticklist = res.data.ticklist;
        },

        markOver(id) {
            var item = this.timers.find((item) => item.id === id);
            item.status = 1;
        },

        async getLoginInfo() {
            let res = await axios({
                method: "get",
                url: "/api/user/info",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.user_name = res.data.data.username;
            this.user_id = res.data.data.user_id;
        },

        async geteveusercount() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/eveusercount",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.eveUserCount = res.data.count;
        },

        async getWebwayStartSystems() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getwebwaystartsystems",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.webwayStartSystems = res.data.systems;
        },

        async getSoloOperationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/solooperationlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.newSoloOperations = res.data.solooplist;
            this.newSoloOperationsRegionList = res.data.regionList;
            this.newSoloOperationsConstellationList =
                res.data.constellationList;
        },

        updateSoloOperationList(data) {
            const item = this.newSoloOperations.find(
                (item) => item.id === data.id
            );
            const count = this.newSoloOperations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.newSoloOperations.push(data);
            }
        },

        updateWebwaySelectedStartSystem(data) {
            this.webwaySelectedStartSystem = data;
        },

        async getTimerDataAll() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/timers",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.timers = res.data.timers;
        },

        async getTimerDataAllRegion() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/timersregions",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.timersRegions = res.data.timersregions;
        },

        async getConstellationList() {
            let res = await axios({
                method: "get",
                withCredentials: true, //you can set what request you want to be
                url: "/api/constellations",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.constellationlist = res.data.constellationlist;
        },

        addoperationlist(data) {
            const check = this.newOperationList.find(
                (station) => station.id == data.id
            );
            if (check != null) {
                Object.assign(check, data);
            } else {
                this.newOperationList.push(data);
            }
        },

        updateoperationlist(data) {
            const item = this.newOperationList.find(
                (item) => item.id === data.id
            );
            const count = this.newOperationList.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            }
        },

        deleteoperationfromlist(num) {
            const count = this.newOperationList.filter(
                (o) => o.id == num
            ).length;
            if (count > 0) {
                this.newOperationList = this.newOperationList.filter(
                    (o) => o.id != num
                );
            }
        },

        async getNewCampaignsList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/newcampaignslist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.newCampaignsList = res.data.campaignslist;
        },

        async getCustomOperationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationlist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.newOperationList = res.data.operations;
        },

        async getStartCampaigns() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/startcampaigns",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.startcampaigns = res.data.campaigns;
        },

        async getStartCampaignJoinData() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/startcampaignjoin",
                data: this.picked,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.startcampaignJoin = res.data.value;
        },

        async getStationRegionLists() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/getregionlists",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            this.stationListPullRegions = res.data.pull;
            this.stationListFCRegions = res.data.fcs;
            this.stationListRegionList = res.data.regionlist;
            this.systemlist = res.data.systemlist;
            this.webwayStartSystems = res.data.webwayStartSystems;
        },

        async getStationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/stationsheet",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.stationList = res.data.stations;
        },

        updateStationList(data) {
            const item = this.stationList.find((item) => item.id === data.id);
            const count = this.stationList.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.stationList.push(data);
            }
        },

        deleteStationSheetNotification(id) {
            let index = this.stationList.filter((s) => s.id != id);
            this.stationList = index;
        },

        updateStationNotification(data) {
            const item = this.stations.find((item) => item.id === data.id);
            const count = this.stations.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.stations.push(data);
            }
        },

        deleteStationNotification(id) {
            let newList = this.stations.filter((s) => s.id != id);
            this.stations = newList;
        },

        async getStationData() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/stationrecords",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.stations = res.data.stations;
        },

        async getStationDataByUserId() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/stationrecordsbyid",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.stations = res.data.stations;
        },
    },
});
