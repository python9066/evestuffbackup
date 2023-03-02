<template>
  <div class="q-ma-md">
    <q-card class="myRoundTop myOperationInfoMainCard">
      <q-card-section class="bg-primary text-center q-py-xs">
        <div class="row">
          <div class="col-10">
            <h5 class="no-margin">Operation - {{ opInfo.name }}</h5>
          </div>
          <div class="col-2 flex justify-end"><OperationInfoSettingPannel /></div>
        </div>
      </q-card-section>
      <q-card-section>
        <div class="row justify-between">
          <div class="col-3"><OperationInfoReconCard /></div>
          <div class="col-9">
            <div class="row">
              <div class="col">
                <OperationInfoSystemTable />
              </div>
            </div>
            <div class="row">
              <!-- <div class="col">
                <q-table
                  title="Connections"
                  class="myTablePoS myRound bg-webBack"
                  :rows="filterEnd"
                  :columns="columns"
                  table-class=" text-webway"
                  table-header-class=" text-weight-bolder"
                  row-key="id"
                  no-data-label="All Hostile Stations our reffed!!!!!!"
                  dark
                  dense
                  :filter="search"
                  ref="tableRef"
                  rounded
                  hide-bottom
                  :pagination="pagination"
                >
                  <template v-slot:top="props">
                    <div
                      class="row full-width flex-center q-pt-xs myRoundTop bg-secondary"
                    >
                      <div class="col-11 flex flex-center">
                        <span class="text-h4">Fleets</span>
                      </div>
                    </div>
                  </template>
                </q-table>
              </div> -->
            </div>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useRoute } from "vue-router";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";
let router = useRouter();
let route = useRoute();
let store = useMainStore();
let can = inject("can");

const OperationInfoReconCard = defineAsyncComponent(() =>
  import("../components/operationinfo/OperationInfoReconCard.vue")
);

const OperationInfoSettingPannel = defineAsyncComponent(() =>
  import("../components/operationinfo/OperationInfoSettingPannel.vue")
);

const OperationInfoSystemTable = defineAsyncComponent(() =>
  import("../components/operationinfo/OpertationInfoSystemTable.vue")
);

onMounted(async () => {
  await store.getOperationSheetInfoPage(route.params.link);
  await store.getOperationUsers();
  await store.getOperationInfoMumble();
  await store.getOperationInfoDoctrines();
  await store.getOperationRecon();
  await store.getAllianceTickList();
  await store.getSystemList();
  await store.getOperationInfoReconRoles();
  await store.getOperationSheetInfoOperationList();
  await store.getOperationInfoJamList();
  await store.getUserList();

  Echo.private("operationinfooppageown." + store.user_id + "-" + opInfo.id);

  Echo.private("operationinfooppage." + opInfo.id).listen(
    "OperationInfoPageSoloUpdate",
    (e) => {
      if (e.flag.flag == 1) {
        store.updateOperationSheetInfoPage(e.flag.message);
      }

      if (e.flag.flag == 2) {
        store.updateOperationSheetInfoPageFleet(e.flag.message);
      }

      if (e.flag.flag == 3) {
        store.operationInfoUsers = e.flag.message;
      }

      if (e.flag.flag == 4) {
        store.operationInfoRecon = e.flag.message;
      }

      if (e.flag.flag == 5) {
        store.updateOperationReconSolo(e.flag.message);
      }

      if (e.flag.flag == 6) {
        store.deleteOperationSheetInfoPageFleet(e.flag.message);
      }

      if (e.flag.flag == 7) {
        store.updateOperationMessage(e.flag.message);
      }

      if (e.flag.flag == 8) {
        store.operationInfoPage.status = e.flag.message;
      }

      if (e.flag.flag == 9) {
        store.operationInfoPage.operation = e.flag.message;
      }

      if (e.flag.flag == 10) {
        store.operationInfoPage.operation = e.flag.message;
      }

      if (e.flag.flag == 11) {
        store.operationInfoPage.systems = e.flag.message;
      }

      if (e.flag.flag == 12) {
        store.getOperationInfoDoctrines();
      }

      if (e.flag.flag == 13) {
        store.removeOperationReconSolo(e.flag.message);
      }

      if (e.flag.flag == 14) {
        store.updateOperationSoloSystems(e.flag.message);
      }

      if (e.flag.flag == 15) {
        store.clearOperationInfoSolo();
        router.push({ path: `/operationinfoover` });
      }
      if (e.flag.flag == 16) {
        store.updateNewCampaignSystemInfo(e.flag.message);
      }

      if (e.flag.flag == 17) {
        store.updateOperationCampaignsSolo(e.flag.message);
      }

      if (e.flag.flag == 18) {
        store.operationInfoUserList = e.flag.message;
      }

      if (e.flag.flag == 19) {
        store.operationInfoPage.fleets = e.flag.message;
      }

      if (e.flag.flag == 20) {
        store.operationInfoPage.dankop = e.flag.message;
      }
    }
  );
});

onBeforeUnmount(async () => {
  Echo.leave("operationinfooppage." + opInfo.id);
  Echo.leave("operationinfooppageown." + store.user_id + "-" + opInfo.id);
});

let opInfo = $computed(() => {
  return store.operationInfoPage;
});

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});

let t = $computed(() => {
  let mins = 150;
  let window = store.size.height;
  let num = (window - mins) / 2;

  return num + "px";
});

let r = $computed(() => {
  let mins = 120;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myOperationInfoMainCard
  /* height or max-height is important */
  height: v-bind(h)

.myOperationInfoReconCard
  /* height or max-height is important */
  height: v-bind(r)
</style>

<style lang="sass">
.myTablePoS
  /* height or max-height is important */
  height: v-bind(t)

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
