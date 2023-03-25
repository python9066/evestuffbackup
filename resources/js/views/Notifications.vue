<template>
  <div class="q-ma-md">ddd</div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";

let store = useMainStore();
let can = inject("can");
let loadingf = $ref(true);
let loadingt = $ref(true);
let loadingr = $ref(true);

onMounted(async () => {
  Echo.private("notes")
    .listen("NotificationChanged", (e) => {
      store.updateNotification(e.notifications);
    })

    .listen("NotificationNew", (e) => {
      loadTimers();
    });
  store.getNotifications().then(() => {
    loadingt = false;
    loadingf = false;
    loadingr = false;
  });
});

onBeforeUnmount(async () => {
  Echo.leave("notes");
});

let loadTimers = () => {
  loadingr = true;
  store.getNotifications().then(() => {
    loadingr = false;
  });
};
</script>

<style lang="scss"></style>
