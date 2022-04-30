/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
// window.Vue = require('vue');
import Vue from "vue";
import Vuetify from "vuetify";
import router from "./router";
import store from "./store";

import App from "./views/App";
import colors from "vuetify/lib/util/colors";
import "@fortawesome/fontawesome-free/css/all.css";
import VueCountdown from "@chenfengyuan/vue-countdown";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import moment from "moment";
import VueCountdownTimer from "vuejs-countdown-timer";
import VueCountupTimer from "./components/countup/index";
import CountdownTimer from "./components/countdown/index";
import VueMask from "v-mask";
import Permissions from "./mixins/Permissions.vue";
import titleMixin from "./mixins/titleMixin";
import Clipboard from "v-clipboard";

Vue.component("font-awesome-icon", FontAwesomeIcon);
library.add(fas); // Include needed icons
Vue.component(
    "messageComponent",
    require("./components/random/messageComponent.vue").default
);
Vue.component(
    "CampaginWebWay",
    require("./components/campaign/CampaginWebWay.vue").default
);

Vue.component(
    "SoloCampaginWebWay",
    require("./components/operations/SoloCampaginWebWay.vue").default
);
Vue.component(
    "errorMessage",
    require("./components/random/errorMessage.vue").default
); //component name should be in camel-case
Vue.component(
    "SystemTable",
    require("./components/campaign/SystemTable.vue").default
);
Vue.component(
    "UserTable",
    require("./components/campaign/UserTable.vue").default
);
Vue.component(
    "hackingToolMessage",
    require("./components/random/hackingToolMessage.vue").default
);
Vue.component(
    "v-progress-circular",
    require("easy-circular-progress/src/index.vue").default
);
Vue.component(
    "messageNotification",
    require("./components/random/messageNotification.vue").default
);
Vue.component(
    "SystemTableTimer",
    require("./components/campaignAll/SystemTableTimer.vue").default
);
Vue.component(
    "WatchUserTable",
    require("./components/campaignAll/WatchUserTable.vue").default
);
Vue.component(
    "messageStations",
    require("./components/random/messageStations.vue").default
);
Vue.component(
    "testingMessage",
    require("./components/random/testingMessage.vue").default
);
Vue.component(
    "NotificationTimer",
    require("./components/notification/NotificationTimer.vue").default
);
Vue.component(
    "CampaignMap",
    require("./components/campaign/CampaignMap.vue").default
);
Vue.component(
    "CampaignMapSystem",
    require("./components/campaign/CampaignMapSystem.vue").default
);
Vue.component(
    "SystemItemList",
    require("./components/multicampaigns/SystemItemList.vue").default
);
Vue.component(
    "MultiCampaignAdd",
    require("./components/multicampaigns/MultiCampaignAdd.vue").default
);
Vue.component(
    "MultiCampaignEdit",
    require("./components/multicampaigns/MultiCampaignEdit.vue").default
);
Vue.component(
    "TitleBar",
    require("./components/multicampaigns/TitleBar.vue").default
);
Vue.component(
    "MultiSystemTable",
    require("./components/multicampaigns/MultiSystemTable.vue").default
);
Vue.component(
    "UsersChars",
    require("./components/campaignAll/UsersChars.vue").default
);
Vue.component(
    "UsersCharsEdit",
    require("./components/campaignAll/UsersCharsEdit.vue").default
);
Vue.component(
    "ShowNotes",
    require("./components/campaignAll/ShowNotes.vue").default
);
Vue.component(
    "LastedChecked",
    require("./components/campaignAll/LastedChecked.vue").default
);
Vue.component(
    "SystemScout",
    require("./components/campaignAll/SystemScout.vue").default
);
Vue.component(
    "StationGunner",
    require("./components/station/StationGunner.vue").default
);
Vue.component(
    "SystemTidi",
    require("./components/campaign/SystemTidi.vue").default
);
Vue.component(
    "SystemTidiMulti",
    require("./components/multicampaigns/SystemTidiMulti.vue").default
);
Vue.component(
    "AdminHackUserTable",
    require("./components/campaignAll/admin/UserTable.vue").default
);
Vue.component(
    "SolaSystemLogging",
    require("./components/logging/campaign/SolaSystemLogging.vue").default
);
Vue.component(
    "CampaignLogging",
    require("./components/logging/campaign/CampaignLogging.vue").default
);
Vue.component(
    "AdminLogging",
    require("./components/logging/adminpanel/AdminLogging.vue").default
);
Vue.component(
    "NodeExtraChar",
    require("./components/campaign/NodeExtraChar.vue").default
);
Vue.component("TidiCalc", require("./components/random/TidiCalc.vue").default);
Vue.component(
    "JoinNodeTable",
    require("./components/campaignAll/JoinNodeTable.vue").default
);
Vue.component(
    "NodeExtraCharMulti",
    require("./components/multicampaigns/NodeExtraCharMulti.vue").default
);
Vue.component(
    "SystemMessage",
    require("./components/campaignALL/SystemMessage.vue").default
);
Vue.component(
    "SystemAttackMessage",
    require("./components/campaignALL/SystemAttackMessage.vue").default
);
Vue.component("Info", require("./components/station/Info.vue").default);
Vue.component(
    "StationTimer",
    require("./components/station/StationTimer.vue").default
);
Vue.component(
    "Structure",
    require("./components/station/Structure.vue").default
);
Vue.component(
    "AddStation",
    require("./components/station/AddStation.vue").default
);
Vue.component(
    "TowerOnlineTimer",
    require("./components/tower/TowerOnlineTimer.vue").default
);
Vue.component(
    "TowerRefTimer",
    require("./components/tower/TowerRefTimer.vue").default
);
Vue.component(
    "StationNewTimer",
    require("./components/station/StationNewTimer.vue").default
);
Vue.component("AddTower", require("./components/tower/AddTower.vue").default);
Vue.component(
    "StationAttack",
    require("./components/station/StationAttack.vue").default
);
Vue.component(
    "StationMessage",
    require("./components/station/StationMessage.vue").default
);
Vue.component(
    "TowerMessage",
    require("./components/tower/TowerMessage.vue").default
);
Vue.component(
    "StationRequestAmmo",
    require("./components/station/StationRequestAmmo.vue").default
);
Vue.component(
    "AmmoRequestTable",
    require("./components/gsol/AmmoRequestTable.vue").default
);
Vue.component(
    "AmmoStationInfo",
    require("./components/gsol/AmmoStationInfo.vue").default
);
Vue.component(
    "AmmoStocker",
    require("./components/gsol/AmmoStocker.vue").default
);
Vue.component(
    "AddReconTask",
    require("./components/recon/AddReconTask.vue").default
);
Vue.component(
    "ReconTaskTable",
    require("./components/recon/ReconTaskTable.vue").default
);
Vue.component(
    "LastedCheckedTimerRecon",
    require("./components/recon/LastedCheckedTimerRecon.vue").default
);
Vue.component("TaskInfo", require("./components/recon/TaskInfo.vue").default);
Vue.component(
    "DeleteReconTask",
    require("./components/recon/DeleteReconTask.vue").default
);
Vue.component(
    "MultiCampaigns",
    require("./components/multicampaigns/MultiCampaigns.vue").default
);
Vue.component(
    "StartCampaign",
    require("./components/startcampaign/StartCampaign.vue").default
);
Vue.component(
    "StartCampaignAdd",
    require("./components/startcampaign/StartCampaignAdd.vue").default
);
Vue.component(
    "StartSystemItemList",
    require("./components/startcampaign/StartSystemItemList.vue").default
);
Vue.component(
    "StartTitleBar",
    require("./components/startcampaign/StartTitleBar.vue").default
);
Vue.component(
    "StartSystemTable",
    require("./components/startcampaign/StartSystemTable.vue").default
);
Vue.component(
    "StartCampaignMessage",
    require("./components/random/StartCampaignMessage").default
);
Vue.component(
    "StartSystemTableTimer",
    require("./components/startcampaign/StartSystemTableTimer.vue").default
);
Vue.component(
    "DeleteButton",
    require("./components/multicampaigns/DeleteButton.vue").default
);
Vue.component(
    "RcFCButton",
    require("./components/rcsheet/RcFCButton.vue").default
);
Vue.component(
    "RcReconButton",
    require("./components/rcsheet/RcReconButton.vue").default
);
Vue.component(
    "RcGsolButton",
    require("./components/rcsheet/RcGsolButton.vue").default
);
Vue.component("RcFCAdd", require("./components/rcsheet/RcFCAdd.vue").default);
Vue.component(
    "AddCorpTicker",
    require("./components/rcsheet/AddCorpTicker.vue").default
);
Vue.component(
    "AddAllianceTicker",
    require("./components/rcsheet/AddAllianceTicker.vue").default
);
Vue.component(
    "RcStationMessage",
    require("./components/rcsheet/RcStationMessage.vue").default
);
Vue.component(
    "AdminLoggingSheet",
    require("./components/rcsheet/AdminLoggingSheet.vue").default
);
Vue.component(
    "RcMoveImage",
    require("./components/rcsheet/RcMoveImage.vue").default
);
Vue.component(
    "RcMoveMessage",
    require("./components/rcsheet/RcMoveMessage.vue").default
);
Vue.component("RcInfo", require("./components/rcsheet/RcInfo.vue").default);
Vue.component(
    "EditStation",
    require("./components/station/EditStation.vue").default
);
Vue.component(
    "RcMoveCopyButton",
    require("./components/rcsheet/RcMoveCopyButton.vue").default
);
Vue.component(
    "EditFleets",
    require("./components/keypannel/EditFleet.vue").default
);
Vue.component(
    "EditKeys",
    require("./components/keypannel/EditKeys.vue").default
);
Vue.component(
    "DoneButton",
    require("./components/rcsheet/DoneButton.vue").default
);
Vue.component(
    "AddTimerFromDone",
    require("./components/rcsheet/AddTimerFromDone.vue").default
);
Vue.component(
    "AddTimerFromDoneCoord",
    require("./components/coord/AddTimerFromDoneCoord.vue").default
);
Vue.component(
    "DoneButtonCoord",
    require("./components/coord/DoneButtonCoord.vue").default
);
Vue.component(
    "FCMessage",
    require("./components/keypannel/FCMessage.vue").default
);
Vue.component(
    "StationLogs",
    require("./components/station/StationLogs.vue").default
);
Vue.component(
    "AddMissingCorp",
    require("./components/station/addMissingCorp.vue").default
);

Vue.component(
    "SoloOperationsTable",
    require("./components/operations/SoloOperationsTable.vue").default
);

Vue.component(
    "SoloCampaignMap",
    require("./components/operations/SoloCampaignMap.vue").default
);

Vue.component(
    "StationSheetInfo",
    require("./components/stationSheet/StationSheetInfo.vue").default
);
Vue.component(
    "StationSheetLogs",
    require("./components/stationSheet/StationSheetLogs.vue").default
);

Vue.component(
    "StatusButton",
    require("./components/stationSheet/StatusButton.vue").default
);

Vue.component(
    "AddTimerFromStationSheet",
    require("./components/stationSheet/AddTimerFromStationSheet.vue").default
);

Vue.component(
    "SettingPannel",
    require("./components/stationSheet/SettingPannel.vue").default
);
Vue.component("RcTimer", require("./components/rcsheet/RcTimer.vue").default);

Vue.component(
    "CampaignTitleBar",
    require("./components/newcampaign/CampaignTitleBar.vue").default
);

Vue.component(
    "CampaignSystemCard",
    require("./components/newcampaign/CampaignSystemCard.vue").default
);

Vue.component(
    "CampaignActiveBar",
    require("./components/newcampaign/CampaignActiveBar.vue").default
);

Vue.component(
    "AddOperationUser",
    require("./components/newcampaign/AddOperationUser.vue").default
);

Vue.prototype.moment = moment;
// import '@fortawesome/fontawesome-f      ree/css/all.css'
Vue.use(Clipboard);
Vue.use(CountdownTimer);
Vue.use(VueCountdownTimer);
Vue.use(VueCountupTimer);
// library.add(faUserSecret)
Vue.config.productionTip = false;
Vue.component(VueCountdown.name, VueCountdown);
Vue.use(VueMask);
Vue.mixin(Permissions);
Vue.mixin(titleMixin);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('box', require('./components/Box.vue').default);

Vue.use(Vuetify);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

export default new Vuetify({
    theme: {
        light: {
            a: colors.teal,
            warning: "#F57C00",
            secondary: "#65F5FC",

            "dark-orange": "#F57C00",
        },
        dark: true,
    },
    icons: {
        iconfont: "fa",
    },
});
export const EventBus = new Vue();
const app = new Vue({
    components: {
        App,
    },
    el: "#app",
    router,
    store,
    vuetify: new Vuetify(),
});
