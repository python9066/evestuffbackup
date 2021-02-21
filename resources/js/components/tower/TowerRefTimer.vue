<template>
    <v-col>
        <div
            v-if="item.out_time == null && item.tower_status_id == 5"
            class="animate__animated animate__bounceIn"
        >
            <v-menu :close-on-content-click="false" :value="timerShown">
                <template v-slot:activator="{ on, attrs }">
                    <v-chip
                        v-bind="attrs"
                        v-on="on"
                        pill
                        outlined
                        @click="timerShown = true"
                        small
                        color="warning"
                    >
                        Add Time
                    </v-chip>
                </template>

                <template>
                    <v-card tile min-height="150px">
                        <v-card-title class=" pb-0">
                            <v-text-field
                                v-model="anchoringTime"
                                label="Reapir Time mm:ss"
                                v-mask="'##:##'"
                                autofocus
                                placeholder="mm:ss"
                                @keyup.enter="
                                    (timerShown = false), addAnchoringTime(item)
                                "
                                @keyup.esc="
                                    (timerShown = false), (anchoringTime = null)
                                "
                            ></v-text-field>
                        </v-card-title>
                        <v-card-text>
                            <v-btn
                                icon
                                fixed
                                left
                                color="success"
                                @click="
                                    (timerShown = false), addAnchoringTime(item)
                                "
                                ><v-icon>fas fa-check</v-icon></v-btn
                            >

                            <v-btn
                                fixed
                                right
                                icon
                                color="warning"
                                @click="
                                    (timerShown = false), (anchoringTime = null)
                                "
                                ><v-icon>fas fa-times</v-icon></v-btn
                            >
                        </v-card-text>
                    </v-card>
                </template>
            </v-menu>
        </div>

        <CountDowntimer
            v-if="item.out_time != null && item.tower_status_id == 5"
            :start-time="moment.utc(item.out_time).unix()"
            end-text="Just Onlined"
            :interval="1000"
        >
            <template slot="countdown" slot-scope="scope">
                <span class="red--text pl-3"
                    >{{ scope.props.minutes }}:{{ scope.props.seconds }}</span
                >
                <v-menu :close-on-content-click="false" :value="timerShown">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-bind="attrs"
                            v-on="on"
                            @click="(timerShown = true), (anchoringTime = null)"
                            icon
                            color="warning"
                        >
                            <v-icon x-small>fas fa-edit</v-icon>
                        </v-btn>
                    </template>

                    <template>
                        <v-card tile min-height="150px">
                            <v-card-title class=" pb-0">
                                <v-text-field
                                    v-model="anchoringTime"
                                    label="Repair Time d hh:mm:ss"
                                    autofocus
                                    v-mask="'#d ##:##:##'"
                                    placeholder="d:hh:mm:ss"
                                    @keyup.enter="
                                        (repairShown = false),
                                            addAnchoringTime(item)
                                    "
                                    @keyup.esc="
                                        (timerShown = false),
                                            (anchoringTime = null)
                                    "
                                ></v-text-field>
                            </v-card-title>
                            <v-card-text>
                                <v-btn
                                    icon
                                    fixed
                                    left
                                    color="success"
                                    @click="
                                        (timerShown = false),
                                            addAnchoringTime(item)
                                    "
                                    ><v-icon>fas fa-check</v-icon></v-btn
                                >

                                <v-btn
                                    fixed
                                    right
                                    icon
                                    color="warning"
                                    @click="
                                        (timerShown = false),
                                            (anchoringTime = null)
                                    "
                                    ><v-icon>fas fa-times</v-icon></v-btn
                                >
                            </v-card-text>
                        </v-card>
                    </template>
                </v-menu>
            </template>
            <template slot="end-text" slot-scope="scope">
                <span style="color: green">{{ scope.props.endText }}</span>
            </template>
        </CountDowntimer>
    </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        item: Object
    },
    data() {
        return {
            timerShown: false,
            anchoringTime: {
                mm: "",
                ss: ""
            }
        };
    },

    methods: {
        async addAnchoringTime(item) {
            var d = parseInt(this.refTime.substr(0, 1));
            var h = parseInt(this.refTime.substr(3, 2));
            var m = parseInt(this.refTime.substr(6, 2));
            var s = parseInt(this.refTime.substr(9, 2));
            var ds = d * 24 * 60 * 60;
            var hs = h * 60 * 60;
            var ms = m * 60;
            var sec = ds + hs + ms + s;
            var finishTime = moment
                .utc()
                .add(sec, "seconds")
                .format("YYYY-MM-DD HH:mm:ss");
            item.out_time = finishTime;
            this.$store.dispatch("updateTowers", item);
            var request = {
                out_time: finishTime
            };

            await axios({
                method: "put", //you can set what request you want to be
                url: "api/towerrecords/" + item.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        test() {
            if (this.item.tower_status_id == 5) {
                return "animate__animated animate__bounceIn";
            } else {
                return "animate__animated animate__bounceOut";
            }
        }
    },

    computed: {}
};
</script>

<style>
.style-2 {
    background-color: rgb(30, 30, 30, 1);
}
.style-1 {
    background-color: rgb(184, 22, 35);
}
</style>
