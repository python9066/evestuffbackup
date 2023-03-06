<template>
  <div class="q-pa-lg">
    <q-option-group v-model="group" :options="options" color="yellow" type="toggle" />
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
onMounted(async () => {
  joinChannel();
});

onBeforeUnmount(async () => {
  leaveChannel();
});

let joinChannel = () => {
  options.forEach((o) => {
    console.log("test." + o.value);
    Echo.private("test." + o.value);
  });
};

let leaveChannel = () => {
  options.forEach((o) => {
    console.log("test." + o.value);
    Echo.leave("test." + o.value);
  });
};

let group = $ref(["op1"]);
let options = $ref([
  {
    label: "Option 1",
    value: "op1",
  },
  {
    label: "Option 2",
    value: "op2",
  },
  {
    label: "Option 3",
    value: "op3",
  },
]);
</script>

<style lang="scss"></style>
