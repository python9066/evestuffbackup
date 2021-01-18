/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
// window.Vue = require('vue');
import Vue from "vue";
import Vuetify from "vuetify";
import router from "./router";
import store from "./store";
import App from "./views/App";
import colors from "vuetify/lib/util/colors";
import '@fortawesome/fontawesome-free/css/all.css'
import VueCountdown from '@chenfengyuan/vue-countdown';
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import moment from 'moment'
import VueCountdownTimer from 'vuejs-countdown-timer'
import VueCountupTimer from './components/countup/index'
import CountdownTimer from './components/countdown/index'
import VueMask from 'v-mask'
import Permissions from './mixins/Permissions.vue'

Vue.component('font-awesome-icon', FontAwesomeIcon)
library.add(fas) // Include needed icons
Vue.component('messageComponent', require('./components/random/messageComponent.vue').default);
Vue.component('errorMessage', require('./components/random/errorMessage.vue').default); //component name should be in camel-case
Vue.component('systemTable', require('./components/campaign/systemTable.vue').default);
Vue.component('userTable', require('./components/campaign/userTable.vue').default);
Vue.component('hackingToolMessage', require('./components/random/hackingToolMessage.vue').default);
Vue.component('v-progress-circular', require('easy-circular-progress/src/index.vue').default);
Vue.component('messageNotification', require('./components/random/messageNotification.vue').default);
Vue.component('systemTableTimer', require('./components/campaignAll/systemTableTimer.vue').default);
Vue.component('watchUserTable', require('./components/campaign/watchUserTable.vue').default);
Vue.component('messageStations', require('./components/random/messageStations.vue').default);
Vue.component('testingMessage', require('./components/random/testingMessage.vue').default);
Vue.component('notificationTimer', require('./components/notification/notificationTimer.vue').default);
Vue.component('campaignMap', require('./components/campaign/campaignMap.vue').default);
Vue.component('SystemItemList', require('./components/multicampaigns/systemItemList.vue').default);
Vue.component('MultiCampaignAdd', require('./components/multicampaigns/MultiCampaignAdd.vue').default);
Vue.component('MultiCampaignEdit', require('./components/multicampaigns/MultiCampaignEdit.vue').default);
Vue.component('TitleBar', require('./components/multicampaigns/TitleBar.vue').default);
Vue.component('MultiSystemTable', require('./components/multicampaigns/MultiSystemTable.vue').default);
Vue.component('UsersChars', require('./components/campaignAll/UsersChars.vue').default);
Vue.component('UsersCharsEdit', require('./components/campaignAll/UsersCharsEdit.vue').default);
Vue.component('ShowNotes', require('./components/campaignAll/ShowNotes.vue').default);
Vue.component('LastedChecked', require('./components/campaignAll/LastedChecked.vue').default);
Vue.component('SystemScout', require('./components/campaignAll/SystemScout.vue').default);
Vue.component('SystemTidi', require('./components/campaign/SystemTidi.vue').default);
Vue.component('SystemTidiMulti', require('./components/multicampaigns/SystemTidiMulti.vue').default);
Vue.component('AdminHack', require('./components/campaignAll/admin/AdminHack.vue').default);
Vue.component('AdminHackUserTable', require('./components/campaignAll/admin/UserTable.vue').default);
Vue.component('SolaSystemLogging', require('./components/logging/campaign/SolaSystemLogging.vue').default);
Vue.component('CampaignLogging', require('./components/logging/campaign/CampaignLogging.vue').default);



Vue.prototype.moment = moment
// import '@fortawesome/fontawesome-f      ree/css/all.css'
Vue.use(CountdownTimer)
Vue.use(VueCountdownTimer)
Vue.use(VueCountupTimer)
// library.add(faUserSecret)
Vue.config.productionTip = false
Vue.component(VueCountdown.name, VueCountdown);
Vue.use(VueMask)
Vue.mixin(Permissions);

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
        // light: {
        //   a: colors.teal,
        //     warning: "#F57C00",
        //     secondary:"#65F5FC",
        //     "dark-orange": "#F57C00"
        // },
      dark: true,
    },
    icons: {
        iconfont: 'fa',
    },
  })

  const app = new Vue({
    components: {
      App,
    },
    el: "#app",
    router,
    store,
    vuetify: new Vuetify()
  });


