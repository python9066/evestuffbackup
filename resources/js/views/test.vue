<template>
  <div>
    <q-input v-model="dscan" type="textarea" label="Label" />
    <q-btn color="primary" icon="check" label="OK" @click="setTextStart" />
    <span>{{ result }}</span>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useQuasar, copyToClipboard } from "quasar";
const $q = useQuasar();
var dscan = $ref(null);
var result = $ref(null);

onMounted(async () => {});

let setTextStart = async () => {
  let data = {
    dscan: dscan,
  };
  let res = await axios({
    method: "post",
    withCredentials: true,
    url: "/api/testdscan",
    data: data,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  result = res.data;
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
