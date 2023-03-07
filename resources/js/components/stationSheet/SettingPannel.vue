<template>
  <div>
    <q-btn color="white" icon="fa-solid fa-gears" round flat @click="open()" />
    <q-dialog v-model="showSetting" persistent>
      <q-card class="myRoundTop" style="width: 1000px; max-width: 80vw">
        <q-card-section class="bg-primary text-h5 text-center">
          <h4 class="no-margin">Setting for Station Page</h4>
        </q-card-section>
        <q-card-section>
          <div class="column q-gutter-lg">
            <div>
              <q-select
                rounded
                dense
                standout
                input-debounce="0"
                label-color="webway"
                option-value="value"
                option-label="text"
                v-model="store.stationListPullRegions"
                :options="pickedRegions"
                label="Region To Update"
                ref="regionDropDown"
                @filter="pickedRegionStart"
                @filter-abort="abortFilterFn"
                map-options
                use-input
                use-chips
                multiple
              >
                <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                  <q-item v-bind="itemProps">
                    <q-item-section>
                      <q-item-label v-html="opt.text" />
                    </q-item-section>
                    <q-item-section side>
                      <q-toggle
                        :model-value="selected"
                        @update:model-value="toggleOption(opt)"
                      />
                    </q-item-section>
                  </q-item>
                </template>

                <template v-slot:selected-item="scope">
                  <q-chip
                    removable
                    @remove="scope.removeAtIndex(scope.index)"
                    :tabindex="scope.tabindex"
                    color="webWay"
                    text-color="white"
                    class="q-ma-none"
                  >
                    <span class="text-xs"> {{ scope.opt.text }} </span>
                  </q-chip>
                </template></q-select
              >
            </div>
            <div>
              <q-select
                rounded
                dense
                standout
                input-debounce="0"
                label-color="webway"
                option-value="value"
                option-label="text"
                v-model="store.stationListFCRegions"
                :options="fcPickedRegions"
                label="FC Regions"
                ref="regionDropDown"
                @filter="fcPickedRegionStart"
                @filter-abort="abortFilterFn"
                map-options
                use-input
                use-chips
                multiple
              >
                <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                  <q-item v-bind="itemProps">
                    <q-item-section>
                      <q-item-label v-html="opt.text" />
                    </q-item-section>
                    <q-item-section side>
                      <q-toggle
                        :model-value="selected"
                        @update:model-value="toggleOption(opt)"
                      />
                    </q-item-section>
                  </q-item>
                </template>

                <template v-slot:selected-item="scope">
                  <q-chip
                    removable
                    @remove="scope.removeAtIndex(scope.index)"
                    :tabindex="scope.tabindex"
                    color="webWay"
                    text-color="white"
                    class="q-ma-none"
                  >
                    <span class="text-xs"> {{ scope.opt.text }} </span>
                  </q-chip>
                </template></q-select
              >
            </div>
            <div>
              <q-select
                rounded
                dense
                standout
                input-debounce="0"
                label-color="webway"
                option-value="value"
                option-label="text"
                v-model="store.webwayStartSystems"
                :options="webwaySystems"
                label="Staging Systems (1DQ is defaulted)"
                ref="regionDropDown"
                @filter="pickedWebwayStart"
                map-options
                use-input
                use-chips
                multiple
              >
                <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                  <q-item v-bind="itemProps">
                    <q-item-section>
                      <q-item-label v-html="opt.text" />
                    </q-item-section>
                    <q-item-section side>
                      <q-toggle
                        :model-value="selected"
                        @update:model-value="toggleOption(opt)"
                      />
                    </q-item-section>
                  </q-item>
                </template>

                <template v-slot:selected-item="scope">
                  <q-chip
                    removable
                    @remove="scope.removeAtIndex(scope.index)"
                    :tabindex="scope.tabindex"
                    color="webWay"
                    text-color="white"
                    class="q-ma-none"
                  >
                    <span class="text-xs"> {{ scope.opt.text }} </span>
                  </q-chip>
                </template></q-select
              >
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn rounded color="primary" label="Submit" @click="submit" v-close-popup />
          <q-btn rounded color="negative" label="Close" @click="closed" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import axios from "axios";
let store = useMainStore();
let showSetting = $ref(false);

let open = () => {
  showSetting = true;
  setPicked();
};

let closed = () => {
  store.stationListPullRegions = pullPicked;
  store.stationListFCRegions = fcPicked;
  store.webwayStartSystems = webwayPicked;
};

let setPicked = () => {
  pullPicked = store.stationListPullRegions;
  fcPicked = store.stationListFCRegions;
  webwayPicked = store.webwayStartSystems;
};

let submit = async () => {
  var request = {
    system_ids: store.webwayStartSystems,
  };

  await axios({
    method: "post",
    url: "api/updatewebwaystartsystems",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  var request = {
    pull: store.stationListPullRegions,
    fc: store.stationListFCRegions,
  };

  await axios({
    method: "post", //you can set what request you want to be
    url: "api/updatesetting",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let regionUpdateText = $ref();
let pullPicked = $ref([]);
let pickedRegions = $computed(() => {
  if (regionUpdateText) {
    return store.stationListRegionList.filter(
      (d) => d.text.toLowerCase().indexOf(regionUpdateText) > -1
    );
  }
  return store.stationListRegionList;
});

let pickedRegionStart = (val, update, abort) => {
  update(() => {
    regionUpdateText = val.toLowerCase();
  });
};

let fcRegionUpdateText = $ref();
let fcPicked = $ref([]);
let fcPickedRegions = $computed(() => {
  if (fcRegionUpdateText) {
    return store.stationListPullRegions.filter(
      (d) => d.text.toLowerCase().indexOf(fcRegionUpdateText) > -1
    );
  }
  return store.stationListPullRegions;
});

let fcPickedRegionStart = (val, update, abort) => {
  update(() => {
    fcRegionUpdateText = val.toLowerCase();
  });
};

let webwayUpdateText = $ref();
let webwayPicked = $ref([]);
let webwaySystems = $computed(() => {
  if (webwayUpdateText) {
    return store.systemlist.filter(
      (d) => d.text.toLowerCase().indexOf(webwayUpdateText) > -1
    );
  }
  return store.systemlist;
});

let pickedWebwayStart = (val, update, abort) => {
  update(() => {
    webwayUpdateText = val.toLowerCase();
  });
};

let abortFilterFn = () => {
  // console.log('delayed filter aborted')
};
</script>

<style lang="scss"></style>
