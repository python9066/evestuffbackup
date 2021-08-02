<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showTaskInfo"
            @click:outside="close()"
        >
            <v-card
                tile
                max-width="700px"
                min-height="200px"
                max-height="700px"
                class=" d-flex flex-column"
            >
                <v-card-title
                    >Notes for Station {{ task.title }}.
                </v-card-title>
                <v-card-text>
                    <v-textarea
                        height="400px"
                        readonly
                        no-resize
                        v-model="task.info"
                        outlined
                        placeholder="No Notes"
                    ></v-textarea>
                    <v-divider></v-divider>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
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
        station: Object
    },
    data() {
        return {
            showNumber: false,
            showTaskInfo: false
        };
    },

    async created() {},

    methods: {
        close() {
            this.showTaskInfo = false;
        },

        open() {
            this.showNumber = false;
        }
    },

    computed: {},

    beforeDestroy() {}
};
</script>

<style></style>
