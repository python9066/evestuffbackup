<template>
    <div class=" pr-4 h5">
        System TiDi:
        <span class="green--text font-weight-bold"
            >{{ CampaignSolaSystem[0]["tidi"] }}%</span
        >
        <v-menu :close-on-content-click="false" :value="timerShown">
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    v-if="checkHackUserEdit(item)"
                    v-bind="attrs"
                    v-on="on"
                    @click="(timerShown = true), (hackTime = null)"
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
                                (timerShown = false), addHacktime(item)
                            "
                            @keyup.esc="(timerShown = false), (hackTime = null)"
                        ></v-text-field>
                    </v-card-title>
                    <v-card-text>
                        <v-btn
                            icon
                            fixed
                            left
                            color="success"
                            @click="(timerShown = false), addHacktime(item)"
                            ><v-icon>fas fa-check</v-icon></v-btn
                        >

                        <v-btn
                            fixed
                            right
                            icon
                            color="warning"
                            @click="(timerShown = false), (hackTime = null)"
                            ><v-icon>fas fa-times</v-icon></v-btn
                        >
                    </v-card-text>
                </v-card>
            </template>
        </v-menu>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        CampaignSolaSystem: Array
    },
    data() {
        return {};
    },

    methods: {},

    computed: {}
};
</script>

<style></style>
