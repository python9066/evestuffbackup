<template>
  <div>
    <q-tabs style="25px; max-width: 600px; width: 600px" outside-arrows class="text-teal">
      <q-chip v-for="(recon, index) in systemInfo.recons" :key="index">{{
        recon.name
      }}</q-chip>
    </q-tabs>
  </div>
</template>

<script setup>
import { defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

let store = useMainStore();
const props = defineProps({
  item: [Array, Object],
});

let systemInfo = $computed(() => {
  return store.getOperationSystemInfo(props.item.id);
});
</script>

<style lang="scss"></style>
