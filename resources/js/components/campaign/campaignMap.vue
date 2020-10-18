<template>
    <div>
        <v-dialog
            v-model="dialog1"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
        >
            <template v-slot:activator="{ on, attrs }">
                <template>
                    <v-btn
                        color="primary"
                        class="ma-2 white--text"
                        v-bind="attrs"
                        v-on="on"
                        @click="dialog1 = true"
                    >
                        Delve
                        <v-icon right dark>fas fa-map fa-xs</v-icon>
                    </v-btn>
                </template>
            </template>
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="dialog1 = false">
                        <v-icon>fas fa-times-circle</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{region_name}} - {{system_name}}</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <div
                    style="position: absolute; top: 64px; right: 0px; bottom: 0px; left: 0px;"
                >
                    <iframe
                        :src="link()"
                        style="left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"
                    >
                    </iframe>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        system_name: String,
        region_name: String
    },
    data() {
        return {
            showmap: ""
        };
    },

    methods: {

        link(){
            return "https://evemaps.dotlan.net/map/"+this.region_name+"/"+this.system_name+"#adm"
        }

    },

    computed: {

    }
};
</script>
