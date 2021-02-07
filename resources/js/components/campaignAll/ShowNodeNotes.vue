<template>
    <v-card
        tile
        min-width="700px"
        min-height="200px"
        class=" d-flex flex-column"
    >
        <v-card-title
            >Notes for node {{ nodeNoteItem.node }}. Campaign
            {{ nodeNoteItem.text }}
        </v-card-title>
        <v-card-text>
            <v-textarea
                v-model="nodeNoteItem.notes"
                auto-grow
                outlined
                disabled
            ></v-textarea
            ><v-divider></v-divider>
            <div>
                <v-textarea
                    v-model="editText"
                    auto-grow
                    filled
                    label="Enter New Nodes Here"
                ></v-textarea>
            </div>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions
            >

            <v-btn class="white--text" color="green" @click="updatetext()">
                Submit
            </v-btn></v-card-actions
        >


            <v-btn class="white--text" color="teal" @click="close()">
                Close
            </v-btn></v-card-actions
        >
    </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
import moment from "moment";
export default {
    props: {
        campaign_id: Number,
        nodeNoteItem: Object
    },
    data() {
        return {
            editText: null
        };
    },

    methods: {
        close() {
            this.$emit("closeMessage", "yo");
        },

        updatetext() {
            this.editText = this.editText + "\n";
            if (this.nodeNoteItem.notes == null) {
                var note =
                    moment.utc().format("HH:mm:ss") +
                    ": " +
                    this.$store.state.user_name +
                    ": " +
                    this.editText;
            } else {
                var note =
                    moment.utc().format("HH:mm:ss") +
                    ": " +
                    this.$store.state.user_name +
                    ": " +
                    this.editText +
                    this.nodeNoteItem.notes;
            }

            this.nodeNoteItem.notes = note;
            let request = {
                notes: note
            };
            this.$store.dispatch("updateCampaignSystem", this.nodeNoteItem);
            // axios({
            //     method: "put",
            //     url: "/api/campaignsystems/" + item.id + "/" + this.campaign_id,
            //     data: request,
            //     headers: {
            //         Authorization: "Bearer " + this.$store.state.token,
            //         Accept: "application/json",
            //         "Content-Type": "application/json"
            //     }
            // });
            this.editText = null;
        }
    },

    computed: {}
};
</script>

<style></style>
