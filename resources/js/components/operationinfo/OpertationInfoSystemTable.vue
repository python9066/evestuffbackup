<template>
  <v-row no-gutters>
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :items="opInfo.systems"
            disable-sort
            dense
            hide-default-footer
            disable-pagination
            class="elevation-24 rounded-xl full-width"
          >
            <template slot="no-data">
              No Systems Picked for this Operation
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <OperationInfoSystemAddNotes :loaded="loaded" :item="item" />
            </template>

            <template v-slot:[`item.pivot.jammed_status`]="{ item }">
              {{ jamerText(item.pivot.jammed_status) }}
            </template>

            <!-- <template v-slot:[`header.actions`]="{ headers }">
              <AddNode
                :item="item"
                :operationID="operationID"
                :activeCampaigns="activeCampaigns"
              />
            </template> -->
          </v-data-table>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    loaded: Boolean,
    windowSize: Object,
  },
  data() {
    return {
      singleExpand: false,
      headers: [
        {
          text: "Region",
          value: "region.region_name",
          sortable: false,
        },
        {
          text: "Constellation",
          value: "constellation.constellation_name",
          sortable: true,
        },

        {
          text: "System",
          value: "system_name",
          sortable: true,
        },

        {
          text: "Recon",
          value: "TODOShip",
          sortable: true,
        },

        {
          text: "Jammed",
          value: "pivot.jammed_status",
          sortable: true,
        },
        {
          text: "Notes",
          value: "pivot.notes",
          sortable: true,
        },

        {
          text: "",
          value: "actions",
          sortable: true,
          align: "center",
        },
      ],
    };
  },

  methods: {
    addNotes(item) {
      console.log(item);
    },

    jamerText(num) {
      switch (num) {
        case 1:
          return "No";
        case 2:
          return "Yes";
      }
    },
  },

  computed: {
    ...mapGetters([]),
    ...mapState["operationInfoPage"],

    opInfo: {
      get() {
        return this.$store.state.operationInfoPage;
      },
      set(newValue) {
        return this.$store.dispatch("updateOperationSheetInfoPage", newValue);
      },
    },
  },
};
</script>

