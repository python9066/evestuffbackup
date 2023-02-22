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
        timers: [],
        timersRegions: [],
        user_name: null,
        user_id: null,
        webwayStartSystems: [],
        webwaySelectedStartSystem: {
            value: 30004759,
            text: "1DQ1-A",
        },
    }),

    getters: {},
    actions: {
        markOver(id) {
            var item = this.timers.find((item) => item.id === id);
            item.status = 1;
            Object.assign(item, timer);
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
    },
});
