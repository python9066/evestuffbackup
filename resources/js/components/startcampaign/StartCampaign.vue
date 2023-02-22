<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myCustomTables myRound bg-webBack"
      :rows="store.startcampaigns"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      no-data-label="No Starting Campaigns"
      row-key="id"
      dark
      dense
      :filter="search"
      ref="tableRef"
      rounded
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Initial Campaigns</span> <AddStartCampaign />
          </div>
        </div>
      </template>
      <template v-slot:body-cell-campaign="props">
        <q-td :props="props"> <NewSystemItemList :campaigns="props.row.campaign" /></q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td>
          <div class="row justify-end items-baseline">
            <div class="col-auto"><EditOperation :operation="props.row" /></div>
            <div class="col-auto">
              <NewCustomCampaignDeleteButton :item="props.row" />
            </div>
            <div class="col-auto">
              <q-btn
                color="primary"
                rounded
                size="sm"
                label="view"
                @click="clickCampaign(props.row)"
              />
            </div>
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";
import Echo from "laravel-echo";
let router = useRouter();
let store = useMainStore();
const AddStartCampaign = defineAsyncComponent(() => import("./AddStartCampaign.vue"));
onMounted(async () => {
  store.getConstellationList();
  store.getStartCampaigns();
  this.loadStartCampaignJoinData();

  Echo.private("startcampaigns").listen("StartcampaignUpdate", (e) => {
    this.$store.dispatch("getStartCampaigns");
    loadStartCampaignJoinData();
  });
});

onBeforeUnmount(async () => {
  Echo.leave("startcampaigns");
});

let loadStartCampaignJoinData = () => {
  store.getStartCampaignJoinData();
};

let columns = $ref([
  {
    name: "name",
    align: "left",
    required: false,
    label: "Name",
    classes: "text-no-wrap",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "system",
    required: true,
    align: "center",
    label: "Constellations - Target",
    field: (row) => row.system,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "actions",
    align: "right",
    classes: "text-no-wrap",
    label: "",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
]);

let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myCustomTables
  /* height or max-height is important */
  height: v-bind(h)

  .q-table__top
    padding-top: 0 !important
    padding-left: 0 !important
    padding-right: 0 !important



  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #202020

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
