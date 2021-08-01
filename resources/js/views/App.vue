<template>
    <v-app id="teamfcat">
        <font-awesome-icon icon="user-secret" />

        <v-app-bar
            height="100px"
            app
            clipped-left
            elevate-on-scroll
            class="align-items-baseline"
        >
            <v-toolbar-title class="pl-15">
                <span class>EveStuff - {{ this.username }}</span>

                <v-avatar :size="avatarsize" tile class="">
                    <v-icon color="">fa fa-rocket fa-sm </v-icon>
                </v-avatar>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <div>
                <v-tabs
                    entered
                    centered
                    background-color="#272727"
                    icons-and-text
                    align-with-title
                >
                    <v-tabs-slider></v-tabs-slider>
                    <v-tab link to="/notifications">
                        Notifications
                    </v-tab>

                    <v-tab v-if="$can('super')" link to="/recon">
                        Recon
                    </v-tab>

                    <v-tab
                        v-if="$can('view_chill_timers')"
                        link
                        to="/chillstations"
                    >
                        Chilled Timers
                    </v-tab>

                    <v-tab v-if="$can('view_towers')" link to="/towers">
                        Towers
                    </v-tab>

                    <v-tab link to="/gsol" v-if="$can('view_gsol')">
                        GSOL
                    </v-tab>

                    <v-tab link to="/timers">
                        Windows
                    </v-tab>

                    <v-tab link to="/campaigns">
                        Campaigns
                    </v-tab>

                    <v-tab
                        v-if="$can('access_multi_campaigns')"
                        to="/mcampaigns"
                    >
                        Multi-Campaign
                    </v-tab>
                    <v-tab v-if="$can('edit_users')" link to="/pannel">
                        Users
                    </v-tab>

                    <v-tab v-if="$can('super')" link to="/feedback">
                        FeedBack
                    </v-tab>
                </v-tabs>
            </div>
            <v-spacer></v-spacer>
            <div
                class=" d-inline-flex align-content-center"
                v-if="$can('gunner')"
            >
                <v-switch
                    v-model="tooltipToggle"
                    color="primary"
                    hide-details
                    @change="changeTooltipToggle()"
                ></v-switch>
                <span class=" pt-1"> {{ tooltiponoff() }}</span>
            </div>
            <v-btn
                text
                class="mr-2"
                v-if="this.$vuetify.breakpoint.mdAndUp"
                @click="tidiCalc = !tidiCalc"
            >
                <v-icon class="mr-2 grey--text lighten-1">fa fa-clock</v-icon
                >Tidi
            </v-btn>
            <v-btn
                text
                class="mr-2"
                v-if="this.$vuetify.breakpoint.mdAndUp"
                @click="overlay = !overlay"
            >
                <v-icon class="mr-2 grey--text lighten-1">fa fa-comment</v-icon
                >Feedback
            </v-btn>
            <v-btn
                text
                class="mr-2"
                v-if="this.$vuetify.breakpoint.mdAndUp"
                @click="logout()"
            >
                <v-icon class="mr-2 grey--text lighten-1">fa fa-rocket</v-icon
                >Logout
            </v-btn>
        </v-app-bar>

        <!-- MAIN ROUTER-VIEW ------------------------------------->
        <v-main class="pb-10" v-if="ready == true">
            <v-overlay :value="tidiCalc">
                <TidiCalc @closeCalc="tidiCalc = false"> </TidiCalc>
            </v-overlay>
            <v-overlay :value="overlay">
                <v-row no-gutters>
                    <v-col cols="auto">
                        <v-card min-width="800">
                            <v-card-title> Give your feedback </v-card-title>
                            <v-card-subtitle
                                >All suggestions welcome</v-card-subtitle
                            >
                            <v-card-text>
                                <v-textarea
                                    v-model="feedBackText"
                                    label="Enter your feedback here"
                                    outlined
                                    shaped
                                ></v-textarea>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn
                                    color="success"
                                    @click="(overlay = false), submitFeedBack()"
                                >
                                    Submit
                                </v-btn>
                                <v-btn
                                    color="warning"
                                    @click="
                                        (overlay = false), (feedBackText = '')
                                    "
                                >
                                    Close
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-overlay>
            <!-- <transition name="fade" mode="out-in"> -->
            <v-fade-transition mode="out-in">
                <router-view :key="$route.path" />
            </v-fade-transition>
            <!-- </transition> -->
        </v-main>

        <!-- FOOTER SECTION ------------------------------------->
        <v-footer hidden app clipped class="px-10">
            <span>TeamFCAT &copy; 2020</span>
        </v-footer>
    </v-app>
</template>
<script>
// import { EventBus } from "../event-bus";
import ClickOutside from "vue-click-outside";
import { mapState } from "vuex";

export default {
    props: ["username", "token", "user_id"],
    mounted() {},
    data: () => ({
        ready: false,
        loading2: false,
        navdrawer: null,
        overlay: false,
        feedBackText: "",
        tidiCalc: false,
        tooltipToggle: false
    }),

    async beforeCreate() {},
    async created() {
        await this.$store.dispatch("setToken", this.token);
        await this.$store
            .dispatch("setUser_id", this.user_id)
            .then((this.ready = true));
        await this.$store.dispatch("setUser_name", this.username);
    },
    methods: {
        gotoCovid() {
            this.$router.push("/covid");
        },

        tooltiponoff() {
            if (this.tooltipToggle) {
                return "Tooltips: On";
            } else {
                return "Tooltips: Off";
            }
        },

        changeTooltipToggle() {
            this.$store.dispatch("updateTooltipToggle", this.tooltipToggle);
        },

        logout() {
            window.location.href = "/logout";
        },

        closeCalc() {
            tidiCalc == true;
        },

        async submitFeedBack() {
            let request = {
                user_id: this.$store.state.user_id,
                text: this.feedBackText
            };

            await axios({
                method: "post", //you can set what request you want to be
                url: "/api/feedback",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            this.feedBackText == null;
        }
    },
    computed: {
        avatarsize() {
            if (this.$vuetify.breakpoint.smAndDown) {
                return 32;
            } else {
                return 48;
            }
        }
    }
};
</script>
<style lang="scss" scoped>
.fade-enter {
    opacity: 0;
}

.fade-enter-active {
    transition: opacity 0.25s ease;
}

.fade-leave {
}

.fade-leave-active {
    transition: opacity 0.25s ease;
    opacity: 0;
}
</style>
