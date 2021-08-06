<template>
    <div>
        <v-dialog persistent max-width="700px" z-index="0" v-model="showDelete">
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="purple accent-3"
                    dark
                    icon
                    v-bind="attrs"
                    v-on="on"
                    @click="open()"
                    ><v-icon left>
                        fas fa-trash
                    </v-icon>
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
                    <p>ARE YOU SURE EMILY!!!!!!!!!!!</p>
                </v-card-title>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn>
                    <v-btn
                        class="white--text"
                        color="green"
                        :disabled="!showSubmit"
                        @click="deleteCampaign(item)"
                    >
                        YES
                    </v-btn></v-card-actions
                >
            </v-card>

            <!-- <showReconTask
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showReconTask = false"
            >
            </showReconTask> -->
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
            systemValue: [],
            taskName: null,
            infoText: null,
            showReconTask: false
        };
    },

    methods: {
        close() {
            this.taskName = null;
            this.showReconTask = false;
            this.infoText = null;
            this.systemValue = [];
        },

        async submit() {
            var request = {
                title: this.taskName,
                info: this.infoText,
                made_by_user_id: this.$store.state.user_id,
                systems: this.systemValue
            };

            await axios({
                method: "post", //you can set what request you want to be
                url: "api/recontask",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            }).then(
                (this.taskName = null),
                (this.showReconTask = false),
                (this.systemValue = []),
                (this.infoText = null)
            );
        },

        async deleteCampaign(item) {
            await axios({
                method: "delete", //you can set what request you want to be
                url: "/api/multicampaigns/" + item.id,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            sleep(500);

            this.$store.dispatch("getMultiCampaigns");
        },

        async open() {
            await this.$store.dispatch("getSystemList");
        }
    },

    computed: {
        ...mapGetters([]),
        ...mapState(["systemlist"]),

        showSubmit() {
            if (
                this.taskName != null &&
                this.infoText != null &&
                this.systemValue != []
            ) {
                return true;
            } else {
                return false;
            }
        },

        systemList() {
            return this.systemlist;
        }
    },

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
