<template>
    <div class=" pr-4 h4  d-flex align-items-baseline justify-end">
        <div>
            System TiDi:
            <span class="green--text font-weight-bold"
                >{{ CampaignSolaSystem[0]["tidi"] }}%</span
            >
            <v-menu :close-on-content-click="false" :value="tidiShow">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        class="pb-1"
                        v-bind="attrs"
                        v-on="on"
                        @click="(tidiShow = true), (tidiEdit = null)"
                        icon
                        color="warning"
                    >
                        <v-icon>fas fa-edit</v-icon>
                    </v-btn>
                </template>

                <template>
                    <v-card tile min-height="150px">
                        <v-card-title class=" pb-0">
                            <v-text-field
                                v-model="tidiEdit"
                                label="Tidi %"
                                autofocus
                                v-mask="'###'"
                                :placeholder="this.placeHolder"
                                @keyup.enter="tidiShow = false"
                                @keyup.esc="
                                    (tidiShow = false), (tidiEdit = null)
                                "
                            ></v-text-field>
                        </v-card-title>
                        <v-card-text>
                            <v-btn
                                icon
                                fixed
                                left
                                color="success"
                                @click="tidiShow = false"
                                ><v-icon>fas fa-check</v-icon></v-btn
                            >

                            <v-btn
                                fixed
                                right
                                icon
                                color="warning"
                                @click="(tidiShow = false), (tidiEdit = null)"
                                ><v-icon>fas fa-times</v-icon></v-btn
                            >
                        </v-card-text>
                    </v-card>
                </template>
            </v-menu>
        </div>
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
        return {
            tidiShow: false,
            tidiEdit: null,
            placeHolder: 0
        };
    },

    mounted() {
        this.placeHolder = this.CampaignSolaSystem[0]["tidi"];
        this.placeHolder = toString(this.placeHolder);
    },
    methods: {},

    computed: {}
};
</script>

<style></style>
