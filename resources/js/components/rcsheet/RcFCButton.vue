<template>
    <div class=" d-inline-flex align-items-md-center  pl-4">
        <div>
            <span class="d-inline-flex align-items-md-center pr-2">
                <span class="pl-2" v-show="!showRcFCButton()">
                    {{ station.fc_name }}
                </span>
            </span>
        </div>
        <div>
            <v-btn
                v-show="showRcFCButton()"
                :key="'gunnerbutton' + station.gunner_id"
                class=""
                color="blue"
                x-small
                outlined
                @click="fcAdd()"
            >
                <v-icon x-small dark left>
                    fas fa-plus
                </v-icon>
                FC</v-btn
            >
            <v-icon
                v-show="!showRcFCButton()"
                color="orange darken-3"
                small
                @click="fcRemove()"
            >
                fas fa-trash-alt
            </v-icon>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        station: Object
    },
    data() {
        return {};
    },

    mounted() {
        this.showName;
    },

    methods: {
        showRcFCButton() {
            if (this.station.fc_user_id) {
                return true;
            } else {
                return false;
            }
        }
    },

    computed: {}
};
</script>

<style></style>
