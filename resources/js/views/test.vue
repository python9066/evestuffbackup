<template>
  <div class="q-ma-md">
    <div class="row">
      <div class="col-9" v-if="show">
        <q-tabs dense="" v-model="tab" class="text-teal">
          <q-tab name="total" label="Total" />
          <q-tab name="onGrid" label="On Grid" />
          <q-tab name="offGrid" label="Off Grid" />
        </q-tabs>
        <q-tab-panels :style="h" v-model="tab" animated>
          <q-tab-panel name="total">
            <DscanTotal :type="1" />
          </q-tab-panel>
          <q-tab-panel name="onGrid">
            <DscanTotal :type="2" />
          </q-tab-panel>
          <q-tab-panel name="offGrid">
            <DscanTotal :type="3" />
          </q-tab-panel>
        </q-tab-panels>
      </div>
      <div class="col-2">
        <div class="column">
          <div class="col">{{ systemName }}</div>
          <div class="col">
            <q-card class="my-card">
              <q-card-section>
                <q-input v-model="dScanText" type="textarea" label="Dscan" />
              </q-card-section>
              <q-card-actions vertical align="center">
                <q-btn flat label="New" :loading="loading" @click="subScan()" />
                <q-btn flat label="Update" @click="updateScan()" />
              </q-card-actions>
            </q-card>
          </div>
          <div class="col">local</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useQuasar, copyToClipboard } from "quasar";
import { useRoute, useRouter } from "vue-router";
import { useMainStore } from "@/store/useMain.js";

const DscanTotal = defineAsyncComponent(() =>
  import("@/components/dscan/DscanTotal.vue")
);

let store = useMainStore();
let can = inject("can");

const $q = useQuasar();
let tab = $ref("total");
let route = useRoute();
let router = useRouter();
let scanLink = $ref(null);
let dScanText = $ref(null);
let loading = $ref(false);

onMounted(async () => {
  document.title = "Evestuff - Operations";
  scanLink = route.params.link ? route.params.link : null;

  await checkDscan();
});

let checkDscan = () => {
  if (scanLink) {
    store.getDscan(scanLink);
  }
};

let show = $computed(() => {
  return store.dScan ? true : false;
});

let systemName = $computed(() => {
  return store.dScan.system ? store.dScan.system.system_name : null;
});

let subScan = async () => {
  loading = true;
  var data = {
    dscan: dScanText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    data: data,
    url: "/api/testdscan",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    loading = false;
    router.push({ path: `/dscan/${res.data.link}` });
  });
};

let updateScan = async () => {
  loading = true;
  var data = {
    dscan: dScanText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    data: data,
    url: "/api/dscan/" + scanLink,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    loading = false;
    store.dScan = res.data.data.dscan;
    dScanText = null;
  });
};

let h = $computed(() => {
  let mins = 70;
  let window = store.size.height;

  let size = window - mins + "px";
  return "height:" + size;
});
</script>

<style lang="scss"></style>
