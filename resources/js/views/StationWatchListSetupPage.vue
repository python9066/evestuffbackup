<template>
  <div class="q-ma-md">
    <div class="row q-gutter-lg">
      <div class="col-8">Lists Table</div>
      <div class="col-3">
        <q-card class="myRoundTop">
          <q-card-section class="bg-primary text-center text-h3">
            Regions to Watch
          </q-card-section>
          <q-card-section class="text-center">
            Stations that are in the selcted regions will be update/added from the Recon
            Tool. Stations, systems, constellation and region which you want to add to
            watch lists need to have there regions selected here.
          </q-card-section>
          <q-card-section>
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
              @update:model-value="submit"
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
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let can = inject("can");

onMounted(async () => {
  await store.getStationWatchListNeededInfo();

  Echo.private("stationwatchlistsetuppage").listen(
    "StationWatchListSettingPageUpdate",
    (e) => {
      if (e.flag.flag == 1) {
        store.updateStationWatchListNeededInfo(e.flag.message);
      }
    }
  );
});

onBeforeUnmount(() => {
  Echo.leave("stationwatchlistsetuppage");
});

let regionUpdateText = $ref();
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

let abortFilterFn = () => {
  // console.log('delayed filter aborted')
};

let submit = async () => {
  var request = {
    pull: store.stationListPullRegions,
  };

  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/watchlist/getneededinfo",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
