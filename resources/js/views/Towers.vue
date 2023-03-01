<template>
  <div>
    <q-table
      title="Connections"
      class="myTablePoS myRound bg-webBack"
      :rows="filterEnd"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      no-data-label="All Hostile Stations our reffed!!!!!!"
      dark
      dense
      :filter="search"
      ref="tableRef"
      rounded
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-11 flex flex-center">
            <span class="text-h4">Stations</span>
          </div>
        </div>
        <div class="row full-width q-pt-md justify-between">
          <div class="col-12">
            <div class="row q-gutter-sm q-pl-md">
              <div class="col-1">
                <q-input
                  rounded
                  standout
                  dense
                  debounce="300"
                  v-model="search"
                  clearable
                  placeholder="Search"
                >
                  <template v-slot:append>
                    <q-icon name="fa-solid fa-magnifying-glass" />
                  </template>
                </q-input>
              </div>
              <div class="col-2">
                <q-select
                  rounded
                  dense
                  clearable
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="region_id"
                  :options="regionList"
                  ref="toRegionRef"
                  label="Region"
                  @clear="region_id = []"
                  @filter="filterFnRegionFinish"
                  @filter-abort="abortFilterFn"
                  map-options
                  use-input
                  use-chips
                  multiple
                  input-style=" max-width: 10px; min-width: 10px"
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
                      text-color="white"
                      class="q-ma-none"
                      color="webChip"
                    >
                      <span class="text-xs"> {{ scope.opt.text }} </span>
                    </q-chip>
                  </template></q-select
                >
              </div>
              <div class="col-4">
                <q-select
                  rounded
                  clearable
                  dense
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="constellation_id"
                  :options="constellationList"
                  ref="toConstellationRef"
                  label="Constellations"
                  @clear="constellation_id = []"
                  @filter="filterFnConstellationFinish"
                  @filter-abort="abortFilterFn"
                  map-options
                  use-input
                  use-chips
                  multiple
                  input-style=" max-width: 10px; min-width: 10px"
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
                      text-color="white"
                      class="q-ma-none"
                      color="webChip"
                    >
                      <span class="text-xs"> {{ scope.opt.text }} </span>
                    </q-chip>
                  </template></q-select
                >
              </div>
              <div class="col-4">
                <q-select
                  rounded
                  clearable
                  dense
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="type_id"
                  :options="typeList"
                  ref="typeRef"
                  label="Type"
                  @filter="filterFnTypeFinish"
                  @filter-abort="abortFilterFn"
                  map-options
                  use-input
                  use-chips
                  multiple
                  input-style=" max-width: 10px; min-width: 10px"
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
                      text-color="white"
                      class="q-ma-none"
                      color="webChip"
                    >
                      <span class="text-xs"> {{ scope.opt.text }} </span>
                    </q-chip>
                  </template></q-select
                >
              </div>
            </div>
            <div class="row justify-end q-pt-sm">
              <div class="col-auto">
                <q-btn-toggle
                  v-model="filterStandingSelected"
                  rounded
                  unelevated
                  clearable
                  color="webDark"
                  text-color="gray"
                  toggle-color="primary"
                  toggle-text-color="gray"
                  :options="[
                    { label: 'Hostile', value: 1 },
                    { label: 'Friendly', value: 2 },
                  ]"
                />
                <q-btn-toggle
                  v-model="filterStatusSelcted"
                  rounded
                  unelevated
                  clearable
                  color="webDark"
                  text-color="gray"
                  toggle-color="primary"
                  toggle-text-color="gray"
                  :options="[
                    { label: 'Scouting', value: 2 },
                    { label: 'Anchoring', value: 3 },
                    { label: 'Online', value: 4 },
                    { label: 'Reffed', value: 5 },
                    { label: 'Dead', value: 6 },
                  ]"
                />
              </div>
            </div>
          </div>
        </div>
      </template>

      <template v-slot:header-cell-webway="props">
        <q-th :props="props">
          <div class="row">
            <div class="col">
              <span class="myFont">Webway</span>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <q-btn
                v-if="webwayButton"
                size="sm"
                :label="store.webwaySelectedStartSystem.text"
              >
                <q-menu>
                  <q-card class="my-card">
                    <q-list bordered>
                      <q-item
                        v-close-popup
                        clickable
                        v-ripple
                        v-for="(list, index) in webwayDropdownList(
                          store.webwaySelectedStartSystem.value
                        )"
                        :key="index"
                        @click="updateWebwaySelectedStartSystem(list)"
                      >
                        <q-item-section>{{ list.text }}</q-item-section>
                      </q-item>
                    </q-list>
                  </q-card>
                </q-menu></q-btn
              >
              <span v-else>{{ store.webwaySelectedStartSystem.text }}</span>
            </div>
          </div>
        </q-th>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let search = $ref("");
let can = inject("can");
let filterStandingSelected = $ref();
let filterStatusSelcted = $ref();

onMounted(async () => {
  Echo.private("towers")
    .listen("TowerChanged", (e) => {
      store.updateTowers(e.flag.message);
    })
    .listen("TowerNew", (e) => {
      store.getTowerData();
    })
    .listen("TowerDelete", (e) => {
      store.deleteTower(e.flag.id);
    });

  await store.getTowerData();
  //   await store.getTowerFilter();
});

onBeforeUnmount(async () => {
  Echo.leave("towers");
});

let filterRegion = $computed(() => {
  if (region_id.length > 0) {
    const filteredTowers = store.towers.filter((t) => {
      return region_id.some((r) => {
        return r.value === t.moon.region_id;
      });
    });
    return filteredTowers;
  }
  return store.towers;
});

let filterConstellation = $computed(() => {
  if (constellation_id.length > 0) {
    const filteredTowers = filterRegion.filter((t) => {
      return constellation_id.some((c) => {
        return c.value === t.moon.constellation_id;
      });
    });
    return filteredTowers;
  }
  return filterRegion;
});

let filterType = $computed(() => {
  if (type_id.length > 0) {
    const filteredTowers = filterConstellation.filter((t) => {
      return type_id.some((c) => {
        return c.value === t.item_id;
      });
    });
    return filteredTowers;
  }
  return filterConstellation;
});

let filterEnd = $computed(() => {
  return filterType;
});

let region_id = $ref([]);
let regionText = $ref();
let regionDropDownList = $computed(() => {
  var data = filterEnd.map((s) => ({
    id: s.moon.region.id,
    name: s.moon.region.region_name,
  }));
  const result = [];
  const map = new Map();
  for (const item of data) {
    if (!map.has(item.id)) {
      map.set(item.id, true);
      result.push({
        value: item.id,
        text: item.name,
      });
    }
  }
  result.sort((a, b) => a.text.localeCompare(b.text));
  return result;
});

let regionList = $computed(() => {
  if (regionText) {
    return regionDropDownList.filter(
      (d) => d.text.toLowerCase().indexOf(regionText) > -1
    );
  }
  return regionDropDownList;
});

let filterFnRegionFinish = (val, update, abort) => {
  update(() => {
    regionText = val.toLowerCase();
  });
};

let constellation_id = $ref([]);
let constellationText = $ref();

let conDropDownList = $computed(() => {
  var data = filterEnd.map((s) => ({
    id: s.moon.constellation.id,
    name: s.moon.constellation.constellation_name,
  }));
  const result = [];
  const map = new Map();
  for (const item of data) {
    if (!map.has(item.id)) {
      map.set(item.id, true);
      result.push({
        value: item.id,
        text: item.name,
      });
    }
  }
  result.sort((a, b) => a.text.localeCompare(b.text));
  return result;
});

let constellationList = $computed(() => {
  if (constellationText) {
    return conDropDownList.filter(
      (d) => d.text.toLowerCase().indexOf(constellationText) > -1
    );
  }
  return conDropDownList;
});

let filterFnConstellationFinish = (val, update, abort) => {
  update(() => {
    constellationText = val.toLowerCase();
  });
};

let type_id = $ref([]);
let typeText = $ref();

let typeDropDownList = $computed(() => {
  var data = filterEnd.map((s) => ({
    id: s.item.id,
    type: s.item.item_name,
  }));
  const result = [];
  const map = new Map();
  for (const item of data) {
    if (!map.has(item.id)) {
      map.set(item.id, true);
      result.push({
        value: item.id,
        text: item.type,
      });
    }
  }
  result.sort((a, b) => a.text.localeCompare(b.text));
  return result;
});

let typeList = $computed(() => {
  if (typeText) {
    return typeDropDownList.filter((d) => d.text.toLowerCase().indexOf(typeText) > -1);
  }
  return typeDropDownList;
});

let filterFnTypeFinish = (val, update, abort) => {
  update(() => {
    typeText = val.toLowerCase();
  });
};
let abortFilterFn = () => {
  // console.log('delayed filter aborted')
};

let columns = $ref([
  {
    name: "region",
    align: "center",
    required: false,
    label: "Region",
    classes: "text-no-wrap",
    field: (row) => row.moon.region.region_name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "constellation",
    required: true,
    align: "left",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.moon.constellation.constellation_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "system",
    required: true,
    align: "left",
    label: "System",
    classes: "text-no-wrap",
    field: (row) => row.moon.system.system_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "corpTicker",
    align: "left",
    classes: "text-no-wrap",
    label: "Corp",
    field: (row) => row.corp.ticker,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "allianceTicker",
    align: "left",
    required: true,
    label: "Alliance",
    // style: "width: 5%",
    classes: "text-no-wrap",
    field: (row) => {
      if (row.corp.alliance) {
        return row.corp.alliance.ticker;
      } else {
        return null;
      }
    },
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "moon",
    align: "left",
    classes: "text-no-wrap",
    label: "Moon",
    field: (row) => row.moon.name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "type",
    label: "Type",
    align: "left",
    classes: "text-no-wrap",
    field: (row) => row.item.item_name,
    format: (val) => `${val}`,
    sortable: true,
    filter: true,
  },
  {
    name: "time",
    label: "Time",
    classes: "text-no-wrap",
    align: "right",
    sortable: true,
    field: (row) => row.out_time,
    format: (val) => `${val}`,
  },
  {
    name: "time",
    label: "Time Left",
    classes: "text-no-wrap",
    align: "right",
    sortable: true,
    field: (row) => row.out_time,
    format: (val) => `${val}`,
  },

  {
    name: "status",
    label: "Status",
    classes: "text-no-wrap",
    align: "right",
    sortable: true,
    field: (row) => row.status.name,
    format: (val) => `${val}`,
  },

  {
    name: "editedBy",
    label: "Edited By",
    classes: "text-no-wrap",
    align: "right",
    sortable: true,
    field: (row) => row.user.name,
    format: (val) => `${val}`,
  },

  {
    name: "actions",
    label: "",
    align: "right",
    classes: "text-no-wrap",
    sortable: false,
    field: (row) => row.id,
    format: (val) => `${val}`,
  },
]);

let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTablePoS
  /* height or max-height is important */
  height: v-bind(h)

  .q-table__top
    padding-top: 0 !important
    padding-left: 0 !important
    padding-right: 0 !important



  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #202020

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
