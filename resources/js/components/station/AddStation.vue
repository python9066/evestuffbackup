<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showInfo"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="green"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="open()"
                    ><v-icon>
                        faSvg fa-plus
                    </v-icon>
                    Add Timer
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
                    Enter Structure Details
                </v-card-title>
                <v-card-text>
                    <div>
                        <v-text-field
                            outlined
                            label="Prepend"
                            prepend-icon="faSvg fa-home"
                        ></v-text-field>
                    </div>
                </v-card-text>
                <v-spacer></v-spacer
                ><v-card-actions>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn></v-card-actions
                >
            </v-card>

            <!-- <ShowInfo
                :nodeNotestation="nodeNotestation"
                v-if="$can('super')"
                @closeMessage="showInfo = false"
            >
            </ShowInfo> -->
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
            headers: [
                { text: "", value: "icon", width: "5%", align: "start" },
                {
                    text: "Items",
                    value: "item_name",
                    width: "95%",
                    align: "start"
                }
            ],
            showInfo: false,
            editText: null,
            editAdashLink: null,
            fitted: false,
            maxWidth: "500px",
            loading: false,
            systems: [],
            search: null,
            select: null
        };
    },

    methods: {
        close() {
            this.showInfo = false;
        },

        openAdash(url) {
            var win = window.open(url, "_blank");
            win.focus();
        },

        taskFlag() {
            if (this.stationInfo[0]["task_flag"] == 1) {
                return true;
            } else {
                return false;
            }
        },

        url(item) {
            return "https://images.evetech.net/types/" + item.item_id + "/icon";
        },

        async open() {
            await this.$store.dispatch("getSystemList");
        },

        openRecon(hash) {
            var url = "https://recon.gnf.lt/structures/" + hash + "/view";
            var win = window.open(url, "_blank");
            win.focus();
        },

        taskRequest() {
            var request = {
                system_name: this.station.system_name,
                system_id: this.station.system_id,
                station_id: this.station.id,
                structure_name: this.station.station_name,
                username: this.$store.state.user_name
            };
            axios({
                method: "post", //you can set what request you want to be
                url: "api/taskrequest",
                data: request,
                headers: {
                    Authorization: "Bearer " + this.$store.state.token,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                }
            });
        },

        showfit() {
            if (this.fitted == true) {
                return true;
            } else {
                return false;
            }
        },

        lastUpdated() {
            if (this.fit[0]["r_updated_at"] != null) {
                var ago = moment(this.fit[0]["r_updated_at"]).fromNow();
                return ago;
            } else {
                return "Never";
            }
        }
    },

    computed: {
        ...mapGetters([
            "getStationItemsByStationID",
            "getCoreByStationID",
            "getStationFitByStationID"
        ]),

        systemList() {
            return this.$store.state("systemlist");
        },

        items() {
            return this.getStationItemsByStationID(this.station.id);
        },

        fit() {
            var fit = this.getStationFitByStationID(this.station.id);
            if (fit[0]["r_fitted"] == "Fitted") {
                this.fitted = true;
            }

            return fit;
        },

        r_lastupdated() {
            return this.fit.r_updated_at;
        },

        stationInfo() {
            return this.getCoreByStationID(this.station.id);
        },
        core() {
            var core = this.getCoreByStationID(this.station.id);

            if (this.fit == "NO") {
                return "No Info";
            }

            if (core.cored == "Yes") {
                return "Yes";
            } else {
                return "No";
            }
        },

        textcolor() {
            if (this.core == "Yes") {
                return "green--text";
            } else {
                return "red--text";
            }
        },

        showLinkButton() {
            if (
                this.$can("request_recon_task") &&
                this.fit[0]["r_research"] != null
            ) {
                return true;
            } else {
                return false;
            }
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
