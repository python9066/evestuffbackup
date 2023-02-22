<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myTableStations myRound bg-webBack"
      :rows="filterEnd"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      dark
      dense
      :filter="search"
      ref="tableRef"
      rounded
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Stations</span>
          </div>
        </div>
        <div class="row full-width q-pt-md justify-between">
          <div class="col-auto">
            <div class="row q-gutter-sm q-pl-md">
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
                @filter="filterFnRegionFinish"
                @filter-abort="abortFilterFn"
                map-options
                use-input
                hide-selected
                fill-input
              />
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
                @filter="filterFnConstellationFinish"
                @filter-abort="abortFilterFn"
                map-options
                use-input
                hide-selected
                fill-input
              />
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
                hide-selected
                fill-input
              />
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
      <template v-slot:body-cell-webway="props">
        <q-td :props="props">
          <SoloCampaginWebWay
            v-if="webwayJumps(props.row) && webwayLink(props.row)"
            :jumps="webwayJumps(props.row)"
            :web="webwayLink(props.row)"
          ></SoloCampaginWebWay>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";
let store = useMainStore();
let can = inject("can");
let pOnly = $ref(0);
let router = useRouter();
let search = $ref("");

const SoloCampaginWebWay = defineAsyncComponent(() =>
  import("../components/operations/SoloCampaginWebWay.vue")
);

onMounted(async () => {
  await store.getStationRegionLists();
  await store.getStationList();

  Echo.private("stationsheet")
    .listen("StationSheetUpdate", (e) => {
      if (e.flag.message != null) {
        store.updateStationList(e.flag.message);
      }

      if (e.flag.flag == 1) {
        store.getStationRegionLists();
      }

      if (e.flag.flag == 2) {
        store.getStationList();
      }
      if (e.flag.flag == 3) {
      }
    })
    .listen("StationDeadStationSheet", (e) => {
      store.deleteStationSheetNotification(e.flag.id);
    })
    .listen("StationSheetUpdateWebway", (e) => {
      updateWebwaySystem(e.flag.id);
    });
});

onBeforeUnmount(async () => {
  Echo.leave("stationsheet");
});
document.title = "Evestuff - Stations";

let updateWebwaySystem = (id) => {
  axios({
    method: "put",
    url: "/api/stationsheetupdatewebway/" + id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let abortFilterFn = () => {
  // console.log('delayed filter aborted')
};

let region_id = $ref(null);
let regionText = $ref();
let regionDropDownList = $computed(() => {
  var data = store.stationList.map((s) => ({
    id: s.system.region.id,
    name: s.system.region.region_name,
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
    if (regionList.length > 0 && val) {
      region_id = regionList[0];
    }
  });
};

let constellation_id = $ref(null);
let constellationText = $ref();

let conDropDownList = $computed(() => {
  var data = store.stationList.map((s) => ({
    id: s.system.constellation.id,
    name: s.system.constellation.constellation_name,
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
    if (constellationList.length > 0 && val) {
      constellation_id = constellationList[0];
    }
  });
};

let type_id = $ref(null);
let typeText = $ref();

let typeDropDownList = $computed(() => {
  var data = store.stationList.map((s) => ({
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
    if (typeList.length > 0 && val) {
      type_id = typeList[0];
    }
  });
};

let filterStart = $computed(() => {
  if (filterItemTypeSelect) {
    return store.newSoloOperations.filter(
      (d) => d.campaign[0].event_type == filterItemTypeSelect
    );
  } else {
    return store.newSoloOperations;
  }
});

let filterMind = $computed(() => {
  if (region_id) {
    return filterStart.filter(
      (d) => d.campaign[0].constellation.region_id == region_id.value
    );
  } else {
    return filterStart;
  }
});

let filterMind1 = $computed(() => {
  if (constellation_id) {
    return filterMind.filter(
      (d) => d.campaign[0].constellation_id == constellation_id.value
    );
  } else {
    return filterMind;
  }
});

let filterMind2 = $computed(() => {
  if (filterStandingSelectList) {
    if (filterStandingSelectList == 3) {
      return filterMind1.filter((d) => d.campaign[0].alliance.color == 3);
    } else if (filterStandingSelectList == 2) {
      return filterMind1.filter((d) => d.campaign[0].alliance.standing > 0);
    } else {
      return filterMind1.filter((d) => d.campaign[0].alliance.standing <= 0);
    }
  } else {
    return filterMind1;
  }
});

let filterMind3 = $computed(() => {
  if (pOnly == 1) {
    return filterMind2.filter((d) => d.priority == 1);
  } else {
    return filterMind2;
  }
});

let filterEnd = $computed(() => {
  //   if (filterStatusSelect == 1) {
  //     return filterMind3.filter(
  //       (d) =>
  //         d.campaign[0].status_id == 5 ||
  //         d.campaign[0].status_id == 1 ||
  //         d.campaign[0].status_id == 2
  //     );
  //   } else if (filterStatusSelect == 2) {
  //     return filterMind3.filter(
  //       (d) => d.campaign[0].status_id == 5 || d.campaign[0].status_id == 2
  //     );
  //   } else {
  //     return filterMind3.filter(
  //       (d) => d.campaign[0].status_id == 3 || d.campaign[0].status_id == 4
  //     );
  //   }
  return store.stationList;
});

let webwayJumps = (item) => {
  if (item.system.webway) {
    var base = item.system.webway;
    var filter = base.filter(
      (f) => f.start_system_id == store.webwaySelectedStartSystem.value
    );
    if (filter.length > 0) {
      var jumps = filter[0].jumps;
      return jumps;
    } else {
      return null;
    }
  } else {
    return null;
  }
};

let updateWebwaySelectedStartSystem = (item) => {
  var data = {
    value: item.value,
    text: item.text,
  };

  menu = false;

  store.updateWebwaySelectedStartSystem(data);
};

let webwayLink = (item) => {
  if (item.system.webway) {
    var base = item.system.webway;
    var filter = base.filter(
      (f) => f.start_system_id == store.webwaySelectedStartSystem.value
    );
    if (filter.length > 0) {
      var link = filter[0].webway ?? null;
      return link;
    } else {
      return null;
    }
  } else {
    return null;
  }
};

let webwayButton = $computed(() => {
  if (store.webwaySelectedStartSystem.length > 0) {
    return true;
  }
  return false;
});

let webwayButtonList = $computed(() => {
  var list = store.webwayStartSystems;
  var data = {
    value: 30004759,
    text: "1DQ1-A",
  };
  list.push(data);
  list.sort(function (a, b) {
    return a.value - b.value || a.text.localeCompare(b.text);
  });

  return list;
});

let webwayDropdownList = (value) => {
  var list = webwayButtonList.filter((f) => f.value != value);
  return list;
};

let pagination = $ref({
  sortBy: "progress",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let columns = $ref([
  {
    name: "webway",
    align: "center",
    required: false,
    label: "Webway",
    classes: "text-no-wrap",
    field: (row) => {
      if (row.system.webway[0]) {
        return row.system.webway[0].jumps;
      } else {
        return null;
      }
    },
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "region",
    required: true,
    align: "left",
    label: "Region",
    classes: "text-no-wrap",
    style: "width: 7%",
    field: (row) => row.system.region.region_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "constellation",
    required: true,
    align: "left",
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.system.constellation.constellation_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "system",
    align: "left",
    classes: "text-no-wrap",
    label: "System",
    field: (row) => row.system.system_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "corpTicker",
    align: "left",
    required: true,
    classes: "text-no-wrap",
    label: "Corp",
    field: (row) => row.corp.ticker,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "allianceTicker",
    align: "left",
    required: true,
    label: "Alliance",
    style: "width: 5%",
    classes: "text-no-wrap",
    field: (row) => {
      if (row.corp.alliance) {
        return row.corp.alliance.ticker;
      } else {
        return null;
      }
    },
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "type",
    label: "Type",
    classes: "text-no-wrap",
    field: (row) => row.item.item_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "name",
    label: "Name",
    classes: "text-no-wrap",
    field: (row) => row.item.item_name,
    format: (val) => `${val}`,
    sortable: false,
    filter: true,
  },
  {
    name: "status",
    label: "Status",
    classes: "text-no-wrap",
    align: "right",
    style: "width: 25%",
    sortable: false,
    field: (row) => row.status.name,
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
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableStations
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
