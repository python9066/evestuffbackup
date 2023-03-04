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
        towers: [],
        timers: [],
        timersRegions: [],
        ticklist: [],
        towerTypes: [],
        towerConstellation: [],
        rolesList: [],
        moonList: [],
        towerChatWindowId: null,
        stationChatWindowId: null,
        user_name: null,
        user_id: null,
        users: [],
        loggingAdmin: [],
        missingCorpID: 0,
        missingCorpTick: "",
        webwayStartSystems: [],
        webwaySelectedStartSystem: {
            value: 30004759,
            text: "1DQ1-A",
        },

        operationInfo: [],
        operationInfoPage: [],
        operationInfoUsers: [],
        operationInfoMumble: [],
        operationInfoDoctrines: [],
        operationInfoRecon: [],
        allianceticklist: [],
        operationInfoReconFleetRoleList: [],
        operationInfoOperationList: [],
        operationInfoJamList: [],
        userList: [],

        operationInfoSetting: {
            showReconTable: true,
            showSystemTable: true,
            showCheckList: true,
            showFleets: true,
        },

        operationInfoSettingOpetions: [
            {
                label: "Check List",
                value: "showCheckList",
            },
            {
                label: "Fleet Table",
                value: "showFleets",
            },
            {
                label: "Recon List",
                value: "showReconTable",
            },
            {
                label: "System Table",
                value: "showSystemTable",
            },
        ],
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

        getTowerMessages: (state) => (id) => {
            let tower = state.towers.find((s) => s.id == id);
            let messages = tower.notes;
            if (messages) {
                return messages;
            }
            return [];
        },

        getTowerUnreadMessageCount: (state) => (id) => {
            let tower = state.towers.find((s) => s.id == id);
            let messages = tower.notes;
            let count = messages.filter(
                (m) =>
                    m.read_by &&
                    m.read_by.user_id &&
                    !m.read_by.user_id.includes(state.user_id)
            ).length;

            if (state.towerChatWindowId == id && count > 0) {
                axios({
                    method: "put",
                    url: "/api/towermessage/" + id + "/notes",
                    withCredentials: true,
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                });
            }
            return count;
        },

        // {"text":"Yuzier","value":30000007}
        getOperationInfoSystemList: (state) => {
            var ids = [];
            if (state.operationInfoPage.systems) {
                var systems = state.operationInfoPage.systems;

                systems.forEach((s) => {
                    if (s.pivot.new_operation_id == null) {
                        ids.push({ text: s.system_name, value: s.id });
                    }
                });
            }
            return ids;
        },

        getOperationInfoTableStatus: (state) => {
            let values_array = [];

            if (state.operationInfoPage.check_list) {
                values_array.push("check_list");
            }
            if (state.operationInfoPage.fleet_table) {
                values_array.push("fleet_table");
            }
            if (state.operationInfoPage.recon_table) {
                values_array.push("recon_table");
            }
            if (state.operationInfoPage.system_table) {
                values_array.push("system_table");
            }

            return values_array;
        },

        getOperationInfoTableSettings: (state) => {
            let values_array = [];

            if (state.operationInfoSetting.showReconTable) {
                values_array.push("showReconTable");
            }
            if (state.operationInfoSetting.showSystemTable) {
                values_array.push("showSystemTable");
            }
            if (state.operationInfoSetting.showCheckList) {
                values_array.push("showCheckList");
            }
            if (state.operationInfoSetting.showFleets) {
                values_array.push("showFleets");
            }

            return values_array;
        },

        getOperationSystemInfo: (state) => (systemID) => {
            if (state.operationInfoPage.systems) {
                var systems = state.operationInfoPage.systems;
                var data = systems.find((s) => s.id == systemID);
                return data;
            }
            return [];
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

        async getTowerData() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/towersrecords",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.towers = res.data.towers;
        },

        async getTowerFilter() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/towertypelist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.towerTypes = res.data.towerList;
            this.systemlist = res.data.systemList;
        },

        async getMoonList(id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/tower/moonlist/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.moonList = res.data.moonList;
        },

        updateTowers(data) {
            const item = this.towers.find((item) => item.id === data.id);
            const count = this.towers.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.towers.push(data);
            }
        },

        deleteTower(id) {
            const items = this.towers.find((t) => t.id != id);
            this.towers = items;
        },

        async getUsers() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allusersroles",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.users = res.data.usersroles;
        },

        async getRoles() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/roles",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.rolesList = res.data.roles;
        },

        async getLoggingAdmin() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/checkadmin",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.loggingAdmin = res.data.logs;
        },

        async getOperationSheetInfo() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfosheet",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfo = res.data.opinfo;
        },

        updateOperationPageInfo(data) {
            const item = this.operationInfo.find((item) => item.id === data.id);
            const count = this.operationInfo.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.operationInfo.push(data);
            }
        },

        removeOperationPageInfo(id) {
            let newList = this.operationInfo.filter((o) => o.id != id);
            this.operationInfo = newList;
        },

        async getOperationSheetInfoPage(id) {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfopage/" + id,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoPage = res.data.data;
        },

        async getOperationUsers() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfousers",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoUsers = res.data.users;
        },

        async getOperationInfoMumble() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfomumble",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoMumble = res.data.mumble;
        },

        async getOperationInfoDoctrines() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfodoctrines",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoDoctrines = res.data.doc;
        },

        async getOperationRecon() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinforecon",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoRecon = res.data.recon;
        },

        async getAllianceTickList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/allianceticklist",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.allianceticklist = res.data.allianceticklist;
        },

        async getOperationInfoReconRoles() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfofleetreconrole",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoReconFleetRoleList = res.data.roleList;
        },

        async getOperationSheetInfoOperationList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationlistinfoop",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoOperationList = res.data.operations;
        },

        async getOperationInfoJamList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/operationinfojammerstatus",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.operationInfoJamList = res.data.jam;
        },

        async getUserList() {
            let res = await axios({
                method: "get",
                withCredentials: true,
                url: "/api/users",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            this.userList = res.data.users;
        },

        updateOperationSheetInfoPage(data) {
            const item = this.operationInfoPage;
            Object.assign(item, data);
        },

        updateOperationSheetInfoPageFleet(data) {
            const count = this.operationInfoPage.fleets.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                const item = this.operationInfoPage.fleets.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoPage.fleets.push(data);
            }
        },

        updateOperationReconSolo(data) {
            const count = this.operationInfoPage.recons.filter(
                (item) => item.id === data.id
            ).length;

            if (count > 0) {
                const item = this.operationInfoPage.recons.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoPage.recons.push(data);
            }

            const countRecon = this.operationInfoRecon.filter(
                (item) => item.id === data.id
            ).length;

            if (countRecon > 0) {
                const item = this.operationInfoRecon.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoRecon.push(data);
            }
        },

        deleteOperationSheetInfoPageFleet(id) {
            let check = this.operationInfoPage.fleets.filter(
                (e) => e.id == id
            ).length;
            if (check > 0) {
                this.operationInfoPage.fleets =
                    this.operationInfoPage.fleets.filter((e) => e.id != id);
            }
        },

        updateOperationMessage(data) {
            this.operationInfoPage.messages = data;
            this.operationInfoMessageCount = this.operationInfoMessageCount + 1;
        },

        removeOperationReconSolo(data) {
            var info = this.operationInfoPage.recons.filter(
                (r) => r.id != data.id
            );
            this.operationInfoPage.recons = info;

            const count = this.operationInfoRecon.filter(
                (item) => item.id === data.id
            ).length;

            if (count > 0) {
                const item = this.operationInfoRecon.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoRecon.push(data);
            }
        },

        updateOperationSoloSystems(data) {
            const count = this.operationInfoPage.systems.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                const item = this.operationInfoPage.systems.find(
                    (f) => f.id === data.id
                );
                Object.assign(item, data);
            } else {
                this.operationInfoPage.systems.push(data);
            }
        },

        clearOperationInfoSolo() {
            this.operationInfoPage = [];
            this.operationInfoUsers = [];
            this.operationInfoMumble = [];
            this.operationInfoDoctrines = [];
            this.operationInfoRecon = [];
            this.operationInfoReconFleetRoleList = [];
            this.operationInfoOperationList = [];
            this.operationInfoJamList = [];
        },

        updateNewCampaignSystemInfo(data) {
            const item = this.operationInfoPage.systems.find(
                (item) => item.id === data.id
            );
            const count = this.operationInfoPage.systems.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.operationInfoPage.systems.push(data);
            }
        },

        updateOperationCampaignsSolo(data) {
            const item = this.operationInfoPage.campaigns.find(
                (item) => item.id === data.id
            );
            const count = this.operationInfoPage.campaigns.filter(
                (item) => item.id === data.id
            ).length;
            if (count > 0) {
                Object.assign(item, data);
            } else {
                this.operationInfoPage.campaigns.push(data);
            }
        },

        setOwnOptions(data) {
            for (const prop in this.operationInfoSetting) {
                // Check if the property name is in the incoming array
                if (data.includes(prop)) {
                    // Set the property value to true
                    this.operationInfoSetting[prop] = true;
                } else {
                    // Set the property value to false
                    this.operationInfoSetting[prop] = false;
                }
            }
        },
    },
});
