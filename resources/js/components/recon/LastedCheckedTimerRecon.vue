<template>
    <div class=" d-inline-flex align-items-md-center ">
        <div>
            <!-- <span :v-if="item.user_id != null" class=" d-inline-flex mb-4"> -->
            <span class=" d-inline-flex mb-4 pt-4">
                <VueCountUptimer
                    :start-time="moment.utc(item.last_update).unix()"
                    :end-text="'Window Closed'"
                    :interval="1000"
                    ><template slot="countup" slot-scope="scope"
                        ><div
                            v-if="item.user_name != null"
                            class="green--text pl-2 pr-2"
                        >
                            {{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                                scope.props.seconds
                            }}
                        </div>
                        <div v-else>
                            N/A
                        </div>
                    </template>
                </VueCountUptimer>
            </span>
        </div>
        <v-icon color="blue" @click="checkClick()">
            {{ icon }}
        </v-icon>
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
            test1: ""
        };
    },

    methods: {
        async checkClick() {
            var timeStamp = moment.utc().format("YYYY-MM-DD HH:mm:ss");

            var data = {
                id: this.item.id,
                user_id: this.$store.state.user_id,
                user_name: this.$store.state.user_name,
                last_edit: timeStamp
            };

            this.$store.dispatch("updateReconTaskSystems", data);

            var request = null;
            request = {
                user_id: this.$store.state.user_id,
                last_edit: timeStamp
            };

            await axios({
                //adds user name of last checked
                method: "put",
                url: "/api/recontasksystemtimeupdate/" + this.item.id,
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });

            //------logging start------//
        }
    },

    computed: {
        icon() {
            return "fas fa-check-circle";
        }
    }
};
</script>

<style></style>
