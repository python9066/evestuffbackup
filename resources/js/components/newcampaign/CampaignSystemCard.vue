<template>
  <div>
    <q-expansion-item
      class="shadow-1 overflow-hidden"
      style="border-radius: 30px"
      label="dance"
      expand-icon-toggle
      header-class=" q-py-none bg-webBack text-webway text-center"
      @show="10"
      @hide="10"
      dark
    >
      <template v-slot:header>
        <div class="row q-gutter-none full-width items-center">
          <div class="col-1">{{ props.item.system_name }}</div>
          <q-separator vertical class="" color="webChip" />
          <div class="col-3">
            <SystemNodeCount :item="item.new_nodes" />
          </div>
          <div class="col-6 flex justify-end full-height align-center">
            <q-separator vertical class="" color="webChip" />
            <OnTheWay class="q-px-md" :operationID="operationID" :item="item" />
            <q-separator vertical class="" color="webChip" />
            <ReadyToGo class="q-px-md" :operationID="operationID" :item="item" />
            <q-separator vertical class="" color="webChip" />
          </div>
        </div>
      </template>

      <q-card>
        <q-card-section> dance </q-card-section>
      </q-card>
    </q-expansion-item>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
const props = defineProps({
  item: Object,
  operationID: Number,
});

const SystemNodeCount = defineAsyncComponent(() => import("./SystemNodeCount.vue"));
const OnTheWay = defineAsyncComponent(() => import("./OnTheWay.vue"));
const ReadyToGo = defineAsyncComponent(() => import("./ReadyToGo.vue"));
</script>

<style lang="scss"></style>
