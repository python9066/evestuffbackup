<template>
    <div>
        <button v-clipboard="() => button">
            {{ item.out_time }}
        </button>
        <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
            {{ snackText }}

            <template v-slot:action="{ attrs }">
                <v-btn v-bind="attrs" text @click="snack = false">
                    Copied
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        item: Object,
        type: String
    },
    data() {
        return {
            snack: false,
            snackColor: "",
            snackText: ""
        };
    },

    methods: {},

    computed: {
        button() {
            if (this.type == "outtime") {
                var str = this.item.out_time.replace(/\s+/g, "");
                str = str.replace(/[:]/g, "");
                str = str.replace(/[-]/g, "");
                str = str.substring(2);
                this.snackColor = "success";
                this.snackText = "Absolute Time Copied";
                return str;
            }
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
