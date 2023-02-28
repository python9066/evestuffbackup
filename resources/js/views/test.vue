<template>
  <div class="q-pa-md">
    <AddStation />
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
const AddStation = defineAsyncComponent(() =>
  import("../components/station/AddStation.vue")
);
</script>
