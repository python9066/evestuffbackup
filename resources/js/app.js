/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";
import Pusher from "pusher-js";
window.Pusher = Pusher;
import { createApp } from "vue";

import router from "./router";
import store from "@/store.js";
import App from "./views/App.vue";
import { Quasar, LoadingBar } from "quasar";
import quasarLang from "quasar/lang/en-GB";
import quasarIconSet from "quasar/icon-set/svg-fontawesome-v6";
import VueGridLayout from "vue-grid-layout";

import "@quasar/extras/fontawesome-v6/fontawesome-v6.css";
import "quasar/src/css/index.sass";
const app = createApp(App);
app.use(router);
app.use(VueGridLayout);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
app.use(store);
app.provide("can", function (value) {
    if (window.Laravel.jsPermissions == 0) {
        return false;
    }
    let permissions = window.Laravel.jsPermissions.permissions;
    let _return = false;
    if (!Array.isArray(permissions)) {
        return false;
    }
    if (value.includes("|")) {
        value.split("|").forEach(function (item) {
            if (permissions.includes(item.trim())) {
                _return = true;
            }
        });
    } else if (value.includes("&")) {
        _return = true;
        value.split("&").forEach(function (item) {
            if (!permissions.includes(item.trim())) {
                _return = false;
            }
        });
    } else {
        _return = permissions.includes(value.trim());
    }
    return _return;
});

app.use(Quasar, {
    plugins: { LoadingBar }, // import Quasar plugins and add here
    lang: quasarLang,
    iconSet: quasarIconSet,
    /*
  config: {
    brand: {
      // primary: '#e46262',
      // ... or all other brand colors
    },
    notify: {...}, // default set of options for Notify Quasar plugin
    loading: {...}, // default set of options for Loading Quasar plugin
    loadingBar: { ... }, // settings for LoadingBar Quasar plugin
    // ..and many more (check Installation card on each Quasar component/directive/plugin)
  }
  */
});
app.config.productionTip = false;

app.mount("#app");

// app.component(
//     "CampaginWebWay",
//     require("./components/campaign/CampaginWebWay.vue").default
// );

// app.component(
//     "SoloCampaginWebWay",
//     require("./components/operations/SoloCampaginWebWay.vue").default
// );

// app.component(
//     "SystemTable",
//     require("./components/campaign/SystemTable.vue").default
// );
// app.component(
//     "UserTable",
//     require("./components/campaign/UserTable.vue").default
// );
// app.component(
//     "v-progress-circular",
//     require("easy-circular-progress/src/index.vue").default
// );

// app.component(
//     "SystemTableTimer",
//     require("./components/campaignAll/SystemTableTimer.vue").default
// );
// app.component(
//     "WatchUserTable",
//     require("./components/campaignAll/WatchUserTable.vue").default
// );

// app.component(
//     "NotificationTimer",
//     require("./components/notification/NotificationTimer.vue").default
// );
// app.component(
//     "CampaignMap",
//     require("./components/campaign/CampaignMap.vue").default
// );
// app.component(
//     "CampaignMapSystem",
//     require("./components/campaign/CampaignMapSystem.vue").default
// );
// app.component(
//     "SystemItemList",
//     require("./components/multicampaigns/SystemItemList.vue").default
// );
// app.component(
//     "MultiCampaignAdd",
//     require("./components/multicampaigns/MultiCampaignAdd.vue").default
// );
// app.component(
//     "MultiCampaignEdit",
//     require("./components/multicampaigns/MultiCampaignEdit.vue").default
// );
// app.component(
//     "TitleBar",
//     require("./components/multicampaigns/TitleBar.vue").default
// );
// app.component(
//     "MultiSystemTable",
//     require("./components/multicampaigns/MultiSystemTable.vue").default
// );
// app.component(
//     "UsersChars",
//     require("./components/campaignAll/UsersChars.vue").default
// );
// app.component(
//     "UsersCharsEdit",
//     require("./components/campaignAll/UsersCharsEdit.vue").default
// );
// app.component(
//     "ShowNotes",
//     require("./components/campaignAll/ShowNotes.vue").default
// );
// app.component(
//     "LastedChecked",
//     require("./components/campaignAll/LastedChecked.vue").default
// );
// app.component(
//     "SystemScout",
//     require("./components/campaignAll/SystemScout.vue").default
// );
// app.component(
//     "StationGunner",
//     require("./components/station/StationGunner.vue").default
// );
// app.component(
//     "SystemTidi",
//     require("./components/campaign/SystemTidi.vue").default
// );
// app.component(
//     "SystemTidiMulti",
//     require("./components/multicampaigns/SystemTidiMulti.vue").default
// );
// app.component(
//     "AdminHackUserTable",
//     require("./components/campaignAll/admin/UserTable.vue").default
// );
// app.component(
//     "SolaSystemLogging",
//     require("./components/logging/campaign/SolaSystemLogging.vue").default
// );
// app.component(
//     "CampaignLogging",
//     require("./components/logging/campaign/CampaignLogging.vue").default
// );
// app.component(
//     "AdminLogging",
//     require("./components/logging/adminpanel/AdminLogging.vue").default
// );
// app.component(
//     "NodeExtraChar",
//     require("./components/campaign/NodeExtraChar.vue").default
// );
// app.component("TidiCalc", require("./components/random/TidiCalc.vue").default);
// app.component(
//     "JoinNodeTable",
//     require("./components/campaignAll/JoinNodeTable.vue").default
// );
// app.component(
//     "NodeExtraCharMulti",
//     require("./components/multicampaigns/NodeExtraCharMulti.vue").default
// );
// app.component(
//     "SystemMessage",
//     require("./components/campaignAll/SystemMessage.vue").default
// );
// app.component(
//     "SystemAttackMessage",
//     require("./components/campaignAll/SystemAttackMessage.vue").default
// );
// app.component("Info", require("./components/station/Info.vue").default);
// app.component(
//     "StationTimer",
//     require("./components/station/StationTimer.vue").default
// );
// app.component(
//     "Structure",
//     require("./components/station/Structure.vue").default
// );
// app.component(
//     "AddStation",
//     require("./components/station/AddStation.vue").default
// );
// app.component(
//     "TowerOnlineTimer",
//     require("./components/tower/TowerOnlineTimer.vue").default
// );
// app.component(
//     "TowerRefTimer",
//     require("./components/tower/TowerRefTimer.vue").default
// );
// app.component(
//     "StationNewTimer",
//     require("./components/station/StationNewTimer.vue").default
// );
// app.component("AddTower", require("./components/tower/AddTower.vue").default);
// app.component(
//     "StationAttack",
//     require("./components/station/StationAttack.vue").default
// );
// app.component(
//     "StationMessage",
//     require("./components/station/StationMessage.vue").default
// );
// app.component(
//     "TowerMessage",
//     require("./components/tower/TowerMessage.vue").default
// );
// app.component(
//     "StationRequestAmmo",
//     require("./components/station/StationRequestAmmo.vue").default
// );
// app.component(
//     "AmmoRequestTable",
//     require("./components/gsol/AmmoRequestTable.vue").default
// );
// app.component(
//     "AmmoStationInfo",
//     require("./components/gsol/AmmoStationInfo.vue").default
// );
// app.component(
//     "AmmoStocker",
//     require("./components/gsol/AmmoStocker.vue").default
// );
// app.component(
//     "AddReconTask",
//     require("./components/recon/AddReconTask.vue").default
// );
// app.component(
//     "ReconTaskTable",
//     require("./components/recon/ReconTaskTable.vue").default
// );
// app.component(
//     "LastedCheckedTimerRecon",
//     require("./components/recon/LastedCheckedTimerRecon.vue").default
// );
// app.component("TaskInfo", require("./components/recon/TaskInfo.vue").default);
// app.component(
//     "DeleteReconTask",
//     require("./components/recon/DeleteReconTask.vue").default
// );
// app.component(
//     "MultiCampaigns",
//     require("./components/multicampaigns/MultiCampaigns.vue").default
// );
// app.component(
//     "StartCampaign",
//     require("./components/startcampaign/StartCampaign.vue").default
// );
// app.component(
//     "StartCampaignAdd",
//     require("./components/startcampaign/StartCampaignAdd.vue").default
// );
// app.component(
//     "StartSystemItemList",
//     require("./components/startcampaign/StartSystemItemList.vue").default
// );
// app.component(
//     "StartTitleBar",
//     require("./components/startcampaign/StartTitleBar.vue").default
// );
// app.component(
//     "StartSystemTable",
//     require("./components/startcampaign/StartSystemTable.vue").default
// );
// app.component(
//     "StartSystemTableTimer",
//     require("./components/startcampaign/StartSystemTableTimer.vue").default
// );
// app.component(
//     "DeleteButton",
//     require("./components/multicampaigns/DeleteButton.vue").default
// );

// app.component(
//     "RcFCButton",
//     require("./components/rcsheet/RcFCButton.vue").default
// );
// app.component(
//     "RcReconButton",
//     require("./components/rcsheet/RcReconButton.vue").default
// );
// app.component(
//     "RcGsolButton",
//     require("./components/rcsheet/RcGsolButton.vue").default
// );
// app.component("RcFCAdd", require("./components/rcsheet/RcFCAdd.vue").default);
// app.component(
//     "AddCorpTicker",
//     require("./components/rcsheet/AddCorpTicker.vue").default
// );
// app.component(
//     "AddAllianceTicker",
//     require("./components/rcsheet/AddAllianceTicker.vue").default
// );
// app.component(
//     "RcStationMessage",
//     require("./components/rcsheet/RcStationMessage.vue").default
// );
// app.component(
//     "AdminLoggingSheet",
//     require("./components/rcsheet/AdminLoggingSheet.vue").default
// );
// app.component(
//     "RcMoveImage",
//     require("./components/rcsheet/RcMoveImage.vue").default
// );
// app.component(
//     "RcMoveMessage",
//     require("./components/rcsheet/RcMoveMessage.vue").default
// );
// app.component("RcInfo", require("./components/rcsheet/RcInfo.vue").default);
// app.component(
//     "EditStation",
//     require("./components/station/EditStation.vue").default
// );
// app.component(
//     "RcMoveCopyButton",
//     require("./components/rcsheet/RcMoveCopyButton.vue").default
// );
// app.component(
//     "EditFleets",
//     require("./components/keypannel/EditFleet.vue").default
// );
// app.component(
//     "EditKeys",
//     require("./components/keypannel/EditKeys.vue").default
// );
// app.component(
//     "DoneButton",
//     require("./components/rcsheet/DoneButton.vue").default
// );
// app.component(
//     "AddTimerFromDone",
//     require("./components/rcsheet/AddTimerFromDone.vue").default
// );
// app.component(
//     "AddTimerFromDoneCoord",
//     require("./components/coord/AddTimerFromDoneCoord.vue").default
// );
// app.component(
//     "DoneButtonCoord",
//     require("./components/coord/DoneButtonCoord.vue").default
// );
// app.component(
//     "FCMessage",
//     require("./components/keypannel/FCMessage.vue").default
// );
// app.component(
//     "StationLogs",
//     require("./components/station/StationLogs.vue").default
// );
// app.component(
//     "AddMissingCorp",
//     require("./components/station/addMissingCorp.vue").default
// );

// app.component(
//     "SoloOperationsTable",
//     require("./components/operations/SoloOperationsTable.vue").default
// );

// app.component(
//     "SoloCampaignMap",
//     require("./components/operations/SoloCampaignMap.vue").default
// );

// app.component(
//     "StationSheetInfo",
//     require("./components/stationSheet/StationSheetInfo.vue").default
// );
// app.component(
//     "StationSheetLogs",
//     require("./components/stationSheet/StationSheetLogs.vue").default
// );

// app.component(
//     "StatusButton",
//     require("./components/stationSheet/StatusButton.vue").default
// );

// app.component(
//     "AddTimerFromStationSheet",
//     require("./components/stationSheet/AddTimerFromStationSheet.vue").default
// );

// app.component(
//     "SettingPannel",
//     require("./components/stationSheet/SettingPannel.vue").default
// );
// app.component("RcTimer", require("./components/rcsheet/RcTimer.vue").default);

// app.component(
//     "CampaignTitleBar",
//     require("./components/newcampaign/CampaignTitleBar.vue").default
// );

// app.component(
//     "CampaignSystemCard",
//     require("./components/newcampaign/CampaignSystemCard.vue").default
// );

// app.component(
//     "CampaignActiveBar",
//     require("./components/newcampaign/CampaignActiveBar.vue").default
// );

// app.component(
//     "AddOperationUser",
//     require("./components/newcampaign/AddOperationUser.vue").default
// );

// app.component(
//     "OperationUserTable",
//     require("./components/newcampaign/OperationUserTable.vue").default
// );

// app.component(
//     "CampaignSystemCardContent",
//     require("./components/newcampaign/CampaignSystemCardContent.vue").default
// );

// app.component(
//     "NewSystemTable",
//     require("./components/newcampaign/NewSystemTable.vue").default
// );

// app.component(
//     "AddPilot",
//     require("./components/newcampaign/AddPilot.vue").default
// );

// app.component(
//     "NewSystemTableSimpleText",
//     require("./components/newcampaign/NewSystemTableSimpleText.vue").default
// );

// app.component(
//     "NewSystemTableStatusButton",
//     require("./components/newcampaign/NewSystemTableStatusButton.vue").default
// );

// app.component(
//     "NewSystemTableTimer",
//     require("./components/newcampaign/NewSystemTableTimer.vue").default
// );

// app.component(
//     "NewNodeExtraChar",
//     require("./components/newcampaign/NewNodeExtraChar.vue").default
// );

// app.component(
//     "NewJoinNodeTable",
//     require("./components/newcampaign/NewJoinNodeTable.vue").default
// );

// app.component(
//     "AddNode",
//     require("./components/newcampaign/AddNode.vue").default
// );

// app.component(
//     "OnTheWay",
//     require("./components/newcampaign/OnTheWay.vue").default
// );

// app.component(
//     "ReadyToGo",
//     require("./components/newcampaign/ReadyToGo.vue").default
// );

// app.component(
//     "SystemNodeCount",
//     require("./components/newcampaign/SystemNodeCount.vue").default
// );

// app.component(
//     "CampaignTitleBarContent",
//     require("./components/newcampaign/CampaignTitleBarContent.vue").default
// );

// app.component(
//     "SystemCheckButton",
//     require("./components/newcampaign/SystemCheckButton.vue").default
// );

// app.component(
//     "TidiButton",
//     require("./components/newcampaign/TidiButton.vue").default
// );

// app.component(
//     "AddMultiCampaign",
//     require("./components/newcampaign/AddMultiCampaign.vue").default
// );

// app.component(
//     "NewMultiCampaigns",
//     require("./components/newcampaign/NewMultiCampaigns.vue").default
// );

// app.component(
//     "CampaginPriorityButton",
//     require("./components/campaign/CampaginPriorityButton.vue").default
// );

// app.component(
//     "SoloCampaignPriorityButton",
//     require("./components/operations/SoloCampaignPriorityButton.vue").default
// );

// app.component(
//     "NewSystemItemList",
//     require("./components/newcampaign/NewSystemItemList.vue").default
// );

// app.component(
//     "EditOperation",
//     require("./components/newcampaign/EditOperation.vue").default
// );

// app.component(
//     "NewCustomCampaignDeleteButton",
//     require("./components/newcampaign/NewCustomCampaignDeleteButton.vue")
//         .default
// );

// app.component(
//     "NewCampaginPriorityButton",
//     require("./components/newcampaign/NewCampaginPriorityButton.vue").default
// );

// app.component(
//     "AddPilotAdmin",
//     require("./components/newcampaign/AddPilotAdmin.vue").default
// );

// app.component(
//     "OperationUserListTable",
//     require("./components/newcampaign/OperationUserListTable.vue").default
// );

// app.component(
//     "OperationLogTable",
//     require("./components/newcampaign/OperationLogTable.vue").default
// );

// app.component(
//     "NewCampaignLogText",
//     require("./components/newcampaign/NewCampaignLogText.vue").default
// );
// app.component(
//     "AddOperationUserButton",
//     require("./components/newcampaign/AddOperationUserButton.vue").default
// );

// app.component(
//     "OperationCal",
//     require("./components/newcampaign/OperationCal.vue").default
// );

// app.component(
//     "NewUserEdit",
//     require("./components/newcampaign/NewUserEdit.vue").default
// );

// app.component(
//     "AddOperationInfo",
//     require("./components/operationinfo/AddOperationInfo.vue").default
// );

// app.component(
//     "OperationInfoTable",
//     require("./components/operationinfo/OperationInfoTable.vue").default
// );

// app.component(
//     "OperationInfoPlanningCard",
//     require("./components/operationinfo/OperationInfoPlanningCard.vue").default
// );
// app.component(
//     "OperationPreOpFormUpCard",
//     require("./components/operationinfo/OperationPreOpFormUpCard.vue").default
// );
// app.component(
//     "OperationInfoPostOpCard",
//     require("./components/operationinfo/OperationInfoPostOpCard.vue").default
// );

// app.component(
//     "OperationInfoMessageCard",
//     require("./components/operationinfo/OperationInfoMessageCard.vue").default
// );

// app.component(
//     "OperationInfoFleetCard",
//     require("./components/operationinfo/OperationInfoFleetCard.vue").default
// );

// app.component(
//     "OperationInfoFleetSoloCard",
//     require("./components/operationinfo/OperationInfoFleetSoloCard.vue").default
// );

// app.component(
//     "OperationInfoReconCard",
//     require("./components/operationinfo/OperationInfoReconCard.vue").default
// );

// app.component(
//     "AddOperationReconButton",
//     require("./components/operationinfo/AddOperationReconButton.vue").default
// );

// app.component(
//     "OperationInfoSettingPannel",
//     require("./components/operationinfo/OperationInfoSettingPannel.vue").default
// );

// app.component(
//     "OpertationInfoSystemTable",
//     require("./components/operationinfo/OpertationInfoSystemTable.vue").default
// );

// app.component(
//     "OperationInfoSystemAddNotes",
//     require("./components/operationinfo/OperationInfoSystemAddNotes.vue")
//         .default
// );

// app.component(
//     "AddOperationFleetReconButton",
//     require("./components/operationinfo/AddOperationFleetReconButton.vue")
//         .default
// );

// app.component(
//     "OperationInfoFleetReconCard",
//     require("./components/operationinfo/OperationInfoFleetReconCard.vue")
//         .default
// );

// app.component(
//     "AddOperationFleetReconEditButton",
//     require("./components/operationinfo/AddOperationFleetReconEditButton.vue")
//         .default
// );

// app.component(
//     "OperationInfoSystemJammerSetting",
//     require("./components/operationinfo/OperationInfoSystemJammerSetting.vue")
//         .default
// );

// app.component(
//     "OperationInfoSystemAddRecon",
//     require("./components/operationinfo/OperationInfoSystemAddRecon.vue")
//         .default
// );

// app.component(
//     "OperationInfoSystemReconChips",
//     require("./components/operationinfo/OperationInfoSystemReconChips.vue")
//         .default
// );

// app.component(
//     "OperationInfoReconCardNames",
//     require("./components/operationinfo/OperationInfoReconCardNames.vue")
//         .default
// );

// app.component(
//     "AddOperationStartTime",
//     require("./components/operationinfo/AddOperationStartTime.vue").default
// );

// app.component(
//     "OperationInfoShowSetting",
//     require("./components/operationinfo/OperationInfoShowSetting.vue").default
// );

// app.component(
//     "OperationInfoShowHacking",
//     require("./components/operationinfo/OperationInfoShowHacking.vue").default
// );
// app.component(
//     "OperationInfoShowHackingContent",
//     require("./components/operationinfo/OperationInfoShowHackingContent.vue")
//         .default
// );

// app.component(
//     "AddOperationDankFleetButton",
//     require("./components/operationinfo/AddOperationDankFleetButton.vue")
//         .default
// );
// import '@fortawesome/fontawesome-f      ree/css/all.css'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => app.component(key.split('/').pop().split('.')[0], files(key).default))

// app.component('box', require('./components/Box.vue').default);
