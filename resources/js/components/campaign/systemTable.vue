<template>
<v-col cols="6">
      <v-card
      tile
      >
    <v-card-title>
        INFO GOS HERE {{this.system_name}}
    </v-card-title>
    <v-card-text>
        <v-data-table
            :headers="headers"
            :items="filteredItems"
            :expanded.sync="expanded"
            item-key="id"
            :items-per-page="10"
            :search="search"
            :sort-desc="[true, false]"
            multi-sort
            class="elevation-1"
        >
            >

            <template slot="no-data">
                No nodes have showen up here..... yet!!!!
            </template>
            <template v-slot:item.count="{ item }">
                <VueCountUptimer
                    :start-time="item.timestamp + ' UTC'"
                    :end-text="'Window Closed'"
                    :interval="1000"
                    @timecheck="timecheck(item)"
                >
                    <template slot="countup" slot-scope="scope">
                        <span class="red--text pl-3"
                            >{{ scope.props.hours }}:{{
                                scope.props.minutes
                            }}:{{ scope.props.seconds }}</span
                        >
                    </template>
                </VueCountUptimer>
            </template>

            <template
                v-slot:item.status_name="{ item }"
                class="align-items-center"
            >
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <div class="align-items-center">
                            <v-btn
                                v-if="item.status_id == 1"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="success"
                            >
                                <v-icon left>fas fa-plus</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                            <v-btn
                                v-if="item.status_id == 2"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="error"
                            >
                                <v-icon left>fas fa-fire fa-sm</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                            <v-btn
                                v-if="item.status_id == 3"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="dark-orange"
                            >
                                <v-icon left>fas fa-toolbox</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                            <v-btn
                                v-if="item.status_id == 4"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="primary"
                            >
                                <v-icon left>fas fa-thumbs-up</v-icon>
                                {{ item.status_name }}
                            </v-btn>
                            <v-btn
                                v-if="item.status_id == 5"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="warning"
                            >
                                <v-icon left>fas fa-exclamation-circle</v-icon>
                                {{ item.status_name }}
                            </v-btn>

                            <v-btn
                                v-if="item.status_id == 6"
                                class="ma-2"
                                v-bind="attrs"
                                v-on="on"
                                tile
                                outlined
                                color="light-green darken-1"
                            >
                                <v-icon left>fas fa-search</v-icon>
                                {{ item.status_name }}
                            </v-btn>


                            <!-- EXTRA BUTTON -->
                            <v-fab-transition>
                                <v-btn
                                    icon
                                    @click="expanded = [item], expanded_id = item.id"
                                    v-if="
                                        item.status_id == 5 &&
                                            !expanded.includes(item)
                                    "
                                    color="success"
                                    ><v-icon>fas fa-plus</v-icon></v-btn
                                >
                                <v-btn
                                    icon
                                    @click="expanded = [], expanded_id = 0"
                                    v-if="
                                        item.status_id == 5 &&
                                            expanded.includes(item)
                                    "
                                    color="error"
                                    ><v-icon>fas fa-minus</v-icon></v-btn
                                >
                            </v-fab-transition>
                        </div>
                    </template>

                    <v-list>
                        <v-list-item
                            v-for="(list, index) in dropdown_edit"
                            :key="index"
                            @click="
                                (item.status_id = list.value),
                                    (item.status_name = list.title),
                                    click(item)
                            "
                        >
                            <v-list-item-title>{{
                                list.title
                            }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
            <template
                v-slot:expanded-item="{ headers, item }"
                class="align-center"
                height="100%"
            >
                <td :colspan="headers.length" align="center">
                    <div>
                        <v-col class="align-center">
                            <v-text-field
                                v-bind:value="item.text"
                                label="aDash Board Link - needs to be a link to a scan, making a new scan from where will not save"
                                outlined
                                shaped
                                @change="
                                    (payload = $event),
                                        updatetext(payload, item)
                                "
                            ></v-text-field>
                        </v-col>
                    </div>
                    <div
                        v-if="
                            item.text != null &&
                                item.text.includes('https://adashboard.info/intel/dscan/')
                        "
                    >
                        <v-card class="mx-auto" elevation="24">
                            <iframe
                            :name="'ifram'+ item.id"
                                :src="item.text"
                                style="left:0; bottom:0; right:0; width:100%; height:600px; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"
                            >
                            </iframe>
                        </v-card>
                    </div>
                    <div>
                        {{ item.text }}
                    </div>
                </td>
            </template>
        </v-data-table>
    </v-card-text>
      </v-card>
</v-col>
</template>

<script>
export default {
    props: {
   system_name: String,
   system_id: Number,
   campaign_id: Number,
},
    data() {
        return {
            headers: [
                { text: "System", value: "system_name", width: "10%" },
                { text: "NodeID", value: "node" },
                { text: "Pilot", value: "pilot_name" },
                { text: "Status", value: "status_name" },
                { text: "Finished", value: "count" },
                { text: "Notes", value: "notes" },

                // { text: "Vulernable End Time", value: "vulnerable_end_time" }
            ]
        }
    }

}
</script>

<style>

</style>
