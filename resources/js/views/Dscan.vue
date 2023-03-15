<template>
  <div class="q-ma-md">
    <div class="row q-gutter-lg">
      <div class="col-9" v-if="show">
        <q-tabs dense="" v-model="tab" class="text-teal">
          <q-tab v-if="store.dScan.locals" name="local" label="Local" />
          <q-tab name="total" label="Total" />
          <q-tab name="onGrid" label="On Grid" />
          <q-tab name="offGrid" label="Off Grid" />
        </q-tabs>
        <q-tab-panels :style="h" v-model="tab" animated>
          <q-tab-panel name="local"> <DscanLocal /> </q-tab-panel>
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
        <div :class="colClass" class="column">
          <div class="col-auto" v-if="scanLink">
            <q-card class="my-card">
              <div class="col text-h6">System: {{ systemName }}</div>
              <div class="col text-h6">Created By: {{ createdBy }}</div>
              <div class="col text-h6">Edited By: {{ updatedBy }}</div>
              <div class="col text-h6">
                Last Updated:
                <VueCountUp
                  class="q-pl-sm"
                  :emit-events="false"
                  :time="countUpTimeMil(age)"
                  :interval="1000"
                  v-slot="{ hours, minutes, seconds, days }"
                >
                  <span v-if="hours <= 1 && days == 0" class="text-red">
                    <q-chip
                      class=""
                      filter
                      pill
                      clickable
                      color="primary"
                      text-color="white"
                    >
                      <span v-if="hours == 1">
                        {{ hours }}:{{ minutes }}:{{ seconds }}
                      </span>
                      <span v-else> {{ minutes }}:{{ seconds }} </span>
                    </q-chip>
                  </span>
                  <span v-else class="text-red"
                    ><span>{{ days }}:{{ hours }}:{{ minutes }}:{{ seconds }}</span></span
                  >
                </VueCountUp>
              </div>
            </q-card>
          </div>
          <div class="col-auto text-h6">
            <q-card class="my-card myRoundTop">
              <q-card-section>
                <q-input
                  v-model="dScanText"
                  type="textarea"
                  label="Paste your Dscan or local here"
                />
              </q-card-section>
              <q-card-actions align="center">
                <q-btn
                  rounded
                  color="primary"
                  label="New"
                  :loading="loading"
                  @click="subScan()"
                />
                <q-btn
                  rounded
                  color="secondary"
                  label="Update"
                  :loading="loading"
                  @click="updateScan()"
                />
              </q-card-actions>
            </q-card>
          </div>
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

const DscanLocal = defineAsyncComponent(() =>
  import("@/components/dscan/DscanLocal.vue")
);

const VueCountUp = defineAsyncComponent(() => import("@/components/countup/index"));

let store = useMainStore();
let can = inject("can");

const $q = useQuasar();
let route = useRoute();
let router = useRouter();
let scanLink = $ref(null);
let dScanText = $ref(null);
let loading = $ref(false);
let tab = $ref("total");

onMounted(async () => {
  document.title = "Evestuff - Operations";
  scanLink = route.params.link ? route.params.link : null;

  Echo.private("dscanall").listen("DscanAllUpdate", (e) => {
    if (e.flag.flag == 1) {
    }
  });

  await checkDscan();
});

onBeforeUnmount(() => {
  Echo.leave("dscansolo." + scanLink);
  Echo.leave("dscanall");
});

let checkDscan = () => {
  if (scanLink) {
    store.getDscan(scanLink);
    Echo.private("dscansolo." + scanLink).listen("dScanSoloUpdate", (e) => {
      if (e.flag.flag == 1) {
        //update items
      }

      if (e.flag.flag == 2) {
        store.dScanLocalCorp = e.flag.message.corpsTotal;
        store.dScanLocalAlliance = e.flag.message.allianceTotal;
        store.updateLocalDscan(e.flag.message.soloLocal);
        console.log(e.flag.message.soloLocal);
      }
    });
  }
};

let show = $computed(() => {
  return store.dScan ? true : false;
});

let systemName = $computed(() => {
  return store.dScan.system ? store.dScan.system.system_name : "unknown";
});

let createdBy = $computed(() => {
  return store.dScan.madeby ? store.dScan.madeby.name : "unknown";
});

let updatedBy = $computed(() => {
  return store.dScan.updated_by ? store.dScan.updated_by.name : "unknown";
});

let age = $computed(() => {
  return store.dScan.updated_at ? store.dScan.updated_at : "unknown";
});

let subScan = async () => {
  loading = true;
  var data = {
    text: dScanText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    data: data,
    url: "/api/dscan",
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
    text: dScanText,
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

let countUpTimeMil = (time) => {
  if (!time) {
    return 0;
  }
  let dateString = time;
  let date = new Date(dateString);
  let offset = date.getTimezoneOffset() * 60000;
  let timestamp = Date.parse(dateString) - offset;
  return timestamp;
};

let colClass = $computed(() => {
  return scanLink ? "full-height justify-between" : "q-gutter-lg full-height justify-end";
});

let h = $computed(() => {
  let mins = 70;
  let window = store.size.height;

  let size = window - mins + "px";
  return "height:" + size;
});
</script>

<style lang="scss"></style>
