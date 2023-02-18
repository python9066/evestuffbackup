import { defineStore } from "pinia";
//

export const useMainStore = defineStore("main", {
    state: () => ({
        size: [],
        user_name: null,
        user_id: null,
        eveUserCount: 0,
        webwayStartSystems: [],
        newSoloOperations: [],
        newSoloOperationsRegionList: [],
        newSoloOperationsConstellationList: [],
    }),

    getters: {},
    actions: {
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
    },
});
