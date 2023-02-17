<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-webDark text-white" height-hint="98">
      <div class="row justify-between q-px-md">
        <div class="col-2">
          <div class="row">
            <div class="col text-h6">{{ store.user_name }}</div>
          </div>
          <div class="row">
            <div class="col text-subtitle2">
              Eve Player Count:
              <span class="text-positive">
                {{ store.eveUserCount }}
              </span>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <q-tabs align="center">
            <q-tab label="Sovereignty">
              <template v-slot:default>
                <q-menu>
                  <q-list style="min-width: 100px">
                    <q-item to="/page10" clickable v-close-popup>
                      <q-item-section>Operations</q-item-section>
                    </q-item>
                    <q-item to="/page1220" clickable v-close-popup>
                      <q-item-section>Windows</q-item-section>
                    </q-item>
                    <q-item to="/page10" clickable v-close-popup>
                      <q-item-section>Custom Campaign</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </template></q-tab
            >
            <q-tab label="Stations"
              ><template v-slot:default>
                <q-menu>
                  <q-list style="min-width: 100px">
                    <q-item to="/page10" clickable v-close-popup>
                      <q-item-section>Station List</q-item-section>
                    </q-item>
                    <q-item to="/page10" clickable v-close-popup>
                      <q-item-section>To Check</q-item-section>
                    </q-item>
                    <q-item to="/page10" clickable v-close-popup>
                      <q-item-section>Add Timer</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </template></q-tab
            >
            <q-tab to="/page3" label="Operations" />
            <q-tab to="/page3" label="Towers" />
            <q-tab to="/page3" label="Users" />
            <q-tab to="/page3" label="Feedback" />
          </q-tabs>
        </div>
        <div class="col-2">
          <div class="row full-height flex-center">
            <div class="col flex justify-content-end">
              <q-btn
                color="bg-webDark"
                flat
                icon="fa-solid fa-comment"
                label="Feedback"
              />
            </div>
            <div class="col-1 align-bottom q-ml-xs">
              <q-btn
                dense
                flat
                round
                icon="fa-solid fa-right-from-bracket"
                @click="logout()"
              />
            </div>
          </div>
        </div>
      </div>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useQuasar } from "quasar";
import { useMainStore } from "@/store/useMain.js";
import { useRoute } from "vue-router";

let store = useMainStore();
let can = inject("can");
let route = useRoute();
let $q = useQuasar();
$q.dark.set(true); // or false or "auto"

onMounted(async () => {
  await store.getLoginInfo();
  await store.geteveusercount();
  Echo.private("evestuff").listen("EveUserUpdate", (e) => {
    if (e.flag.message != null) {
      store.eveUserCount = e.flag.message;
    }
  });
});

onBeforeUnmount(async () => {
  await Echo.leave("evestuff");
});

let logout = () => {
  window.location.href = "/logout";
};
</script>
