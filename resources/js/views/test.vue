<template>
    <div class="q-ma-md">
        <div class="row">
            <div class="col-9">
                <q-tabs v-model="tab" class="text-teal">
                    <q-tab name="mails" icon="mail" label="Mails" />
                    <q-tab name="alarms" icon="alarm" label="Alarms" />
                    <q-tab name="movies" icon="movie" label="Movies" />
                </q-tabs>
                <q-tab-panels v-model="tab" animated>
                    <q-tab-panel name="mails">
                        <div class="text-h6">Mails</div>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </q-tab-panel>
                    <q-tab-panel name="alarms">
                        <div class="text-h6">Alarms</div>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </q-tab-panel>
                    <q-tab-panel name="movies">
                        <div class="text-h6">Movies</div>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </q-tab-panel>
                </q-tab-panels>
            </div>
            <div class="col-2">
                <div class="column">
                    <div class="col">dscan</div>
                    <div class="col">local</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useQuasar, copyToClipboard } from "quasar";
const $q = useQuasar();
var dscan = $ref(null);
var result = $ref(null);
let tab = $ref('mails')

onMounted(async () => { });

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
