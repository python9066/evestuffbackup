<template>
    <v-card
        tile
        max-width="1500px"
        max-height="1000px"
        class=" d-flex flex-column"
    >
        <v-card-title
            class=" d-lg-inline-block justify-space-between align-center "
        >
            <div>
                Tidi Calculator
            </div>
            <div>
                <v-menu :close-on-content-click="false" :value="timerShown">
                    <template v-slot:activator="{ on, attrs }">
                        <v-chip
                            v-bind="attrs"
                            v-on="on"
                            pill
                            :outlined="pillOutlined(item)"
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
                                    v-model="hackTime"
                                    label="Hack Time mm:ss"
                                    v-mask="'##:##'"
                                    autofocus
                                    placeholder="mm:ss"
                                    @keyup.enter="
                                        (timerShown = false), addHacktime(item)
                                    "
                                    @keyup.esc="
                                        (timerShown = false), (hackTime = null)
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
                                        (timerShown = false), addHacktime(item)
                                    "
                                    ><v-icon>fas fa-check</v-icon></v-btn
                                >

                                <v-btn
                                    fixed
                                    right
                                    icon
                                    color="warning"
                                    @click="
                                        (timerShown = false), (hackTime = null)
                                    "
                                    ><v-icon>fas fa-times</v-icon></v-btn
                                >
                            </v-card-text>
                        </v-card>
                    </template>
                </v-menu>
                <CountDowntimer
                    :start-time="moment.utc(item.end).unix()"
                    :end-text="endText(item)"
                    :interval="1000"
                >
                    <template slot="countdown" slot-scope="scope">
                        <span :class="hackCountDownTextColor(item)"
                            ><span v-if="scope.props.hours > 0"
                                >{{ scope.props.hours }}:</span
                            >{{ scope.props.minutes }}:{{
                                scope.props.seconds
                            }}</span
                        >
                        <v-menu
                            :close-on-content-click="false"
                            :value="timerShown"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-if="checkHackUserEdit(item)"
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="
                                        (timerShown = true), (hackTime = null)
                                    "
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
                                            v-model="hackTime"
                                            label="Hack Time mm:ss"
                                            autofocus
                                            v-mask="'##:##'"
                                            placeholder="mm:ss"
                                            @keyup.enter="
                                                (timerShown = false),
                                                    addHacktime(item)
                                            "
                                            @keyup.esc="
                                                (timerShown = false),
                                                    (hackTime = null)
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
                                                    addHacktime(item)
                                            "
                                            ><v-icon
                                                >fas fa-check</v-icon
                                            ></v-btn
                                        >

                                        <v-btn
                                            fixed
                                            right
                                            icon
                                            color="warning"
                                            @click="
                                                (timerShown = false),
                                                    (hackTime = null)
                                            "
                                            ><v-icon
                                                >fas fa-times</v-icon
                                            ></v-btn
                                        >
                                    </v-card-text>
                                </v-card>
                            </template>
                        </v-menu>
                    </template>
                    <template slot="end-text" slot-scope="scope">
                        <span>{{ scope.props.endText }}</span>
                    </template>
                </CountDowntimer>
            </div>
        </v-card-title>
        <v-card-text> </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions
            ><v-btn class="white--text" color="teal" @click="close()">
                Close
            </v-btn></v-card-actions
        >
    </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {},
    data() {
        return {
            overlay: true,
            timerShown: false,
            hackTime: {
                mm: "",
                ss: ""
            }
        };
    },

    methods: {
        close() {
            this.$emit("closeCalc", "yo");
        }
    },

    computed: {}
};
</script>

<style></style>
