<template>
    <div>
        <v-dialog max-width="700px" z-index="0" v-model="showReconTaskDelete">
            <template v-slot:activator="{ on, attrs }">
                <v-btn color="red" dark v-bind="attrs" v-on="on"
                    ><v-icon left>
                        faSvg fa-trash-alt
                    </v-icon>
                    Delete
                </v-btn>
            </template>

            <v-card
                tile
                max-width="700px"
                min-height="200px"
                max-height="1000px"
                class=" d-flex flex-column"
            >
                <v-card-title class="justify-center">
                    <p>Delete Task</p>
                </v-card-title>
                <v-card-text>
                    Are you sure you want delete this task?
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn>
                    <v-btn class="white--text" color="red" @click="submit()">
                        Delete
                    </v-btn></v-card-actions
                >
            </v-card>
        </v-dialog>
    </div>
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
            showReconTaskDelete: false
        };
    },

    methods: {
        close() {
            this.showReconTaskDelete = false;
        },

        async submit() {
            await axios({
                method: "delete", //you can set what request you want to be
                url: "api/recontask" + this.item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            }).then((this.showReconTaskDelete = false));
        }
    },

    computed: {},

    beforeDestroy() {}
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
}
</style>
