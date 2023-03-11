<template>
  <div>
    <span @click="copyText(setText)">{{ setText }}</span>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useQuasar, copyToClipboard } from "quasar";
const $q = useQuasar();
var setText = $ref("Hello World");

onMounted(async () => {
  setTextStart();
});

let setTextStart = async () => {
  let res = await axios({
    method: "get",
    withCredentials: true,
    url: "/api/teststationitempull/1038739510399",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  setText = res.data;
};

let copyText = (name) => {
  copyToClipboard(name).then(() => {
    $q.notify({
      type: "info",
      message: name + " copied to your clipboard",
    });
  });
};
</script>

<style lang="scss"></style>
