<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myTableOperations myRound bg-webBack"
      :rows="filterEnd"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder bg-amber"
      row-key="id"
      dense
      dark
      ref="tableRef"
      rounded
      color="amber"
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Operations</span>
          </div>
        </div>
        <div class="row full-width q-pt-md">
          <div class="col-auto">
            search
            <!-- <q-select
              rounded
              dense
              standout
              label-color="webway"
              option-value="value"
              option-label="text"
              v-model="start_system_id"
              :options="systemlistStartEnd"
              label="From"
              input-debounce="0"
              clearable
              ref="fromSystemRef"
              @filter="filterFnSystemListStart"
              map-options
              use-input
              hide-selected
              fill-input
            /> -->
          </div>
          <div class="col-auto">
            <q-select
              rounded
              dense
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
          </div>
          <div class="col-auto">
            <q-select
              rounded
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
          </div>
          <div class="col-auto">
            <q-btn dense flat round :icon="text" @click="clickBell()" />
          </div>
          <div class="col-auto">
            <q-btn-toggle
              v-model="filterItemTypeSelect"
              rounded
              unelevated
              clearable
              color="webDark"
              text-color="gray"
              toggle-color="primary"
              toggle-text-color="gray"
              :options="[
                { label: 'Ihub', value: 32458 },
                { label: 'TCU', value: 32226 },
              ]"
            />
          </div>
          <div class="col-auto">
            <q-btn-toggle
              v-model="filterStandingSelectList"
              rounded
              unelevated
              clearable
              color="webDark"
              text-color="gray"
              toggle-color="primary"
              toggle-text-color="gray"
              :options="[
                { label: 'Goon', value: 3 },
                { label: 'Friendly', value: 2 },
                { label: 'Hostile', value: 1 },
              ]"
            />
          </div>
          <div class="col-auto">
            <q-btn-toggle
              v-model="filterStatusSelect"
              rounded
              unelevated
              color="webDark"
              text-color="gray"
              toggle-color="primary"
              toggle-text-color="gray"
              :options="[
                { label: 'Upcoming', value: 1 },
                { label: 'Active', value: 2 },
                { label: 'Finished', value: 3 },
              ]"
            />
          </div>
        </div>
      </template>

      <template v-slot:body-cell-alliance="props">
        <q-td :props="props">
          <div>
            <q-avatar size="35">
              <img :src="props.row.campaign[0].alliance.url" />
            </q-avatar>
            {{ props.value }}

            <span v-if="props.row.priority == 0">
              <span v-if="props.row.campaign[0].alliance.standing > 0" class="text-blue"
                >{{ props.row.campaign[0].alliance.name }}
              </span>
              <span
                v-else-if="props.row.campaign[0].alliance.standing < 0"
                class="text-red"
                >{{ props.row.campaign[0].alliance.name }}
              </span>
              <span v-else class="">{{ props.row.campaign[0].alliance.name }}</span></span
            >
            <span v-else>
              <q-chip
                v-if="props.row.campaign[0].alliance.standing > 0"
                color="primary"
                >{{ props.row.campaign[0].alliance.name }}</q-chip
              >
              <q-chip
                v-else-if="props.row.campaign[0].alliance.standing < 0"
                color="red"
                text-color="white"
                >{{ props.row.campaign[0].alliance.name }}</q-chip
              >
              <q-chip v-else>{{ props.row.campaign[0].alliance.name }}</q-chip></span
            >
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-progress="props">
        <q-td :props="props">
          <div
            v-if="
              props.row.campaign[0].status_id == 1 || props.row.campaign[0].status_id == 5
            "
          >
            {{ props.row.campaign[0].start_time }}
          </div>
          <SoloCampaignProgressLine
            v-else-if="props.row.campaign[0].status_id == 2"
            :attackScore="props.row.campaign[0].attackers_score"
            :defenderScore="props.row.campaign[0].defenders_score"
            :attackScoreOld="props.row.campaign[0].attackers_score_old"
            :defenderScoreOld="props.row.campaign[0].defenders_score_old"
          />
          <span
            v-else-if="
              props.row.campaign[0].status_id == 3 || props.row.campaign[0].status_id == 4
            "
          >
            <p
              v-if="props.row.campaign[0].attackers_score == 0"
              class="text-md-center text-green"
            >
              {{ props.row.campaign[0].alliance.name }}
              <span class="font-weight-bold"> WON </span> the
              {{ itemType(props.row.campaign[0].event_type) }} timer.
            </p>
            <p v-else class="text-md-center text-red">
              {{ props.row.campaign[0].alliance.name }}
              <span class="font-weight-bold"> LOST </span> the
              {{ itemType(props.row.campaign[0].event_type) }} timer.
            </p>
          </span>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let can = inject("can");
let pOnly = $ref(0);

const SoloCampaignProgressLine = defineAsyncComponent(() =>
  import("../components/operations/SoloCampaignProgressLine.vue")
);

onMounted(async () => {
  await store.getWebwayStartSystems();
  await store.getSoloOperationList();

  Echo.private("solooperation").listen("SoloOperationUpdate", (e) => {
    if (e.flag.flag == 1) {
      this.$store.dispatch("updateSoloOperationList", e.flag.message);
    }
  });
});

onBeforeUnmount(async () => {});

document.title = "Evestuff - Operations";

let abortFilterFn = () => {
  // console.log('delayed filter aborted')
};

let region_id = $ref(null);
let regionText = $ref();
let filterItemTypeSelect = $ref(32458);
let filterStandingSelectList = $ref(null);
let filterStatusSelect = $ref(1);

let regionList = $computed(() => {
  if (regionText) {
    return store.newSoloOperationsRegionList.filter(
      (d) => d.text.toLowerCase().indexOf(regionText) > -1
    );
  }
  return store.newSoloOperationsRegionList;
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

let constellationList = $computed(() => {
  if (constellationText) {
    return store.newSoloOperationsConstellationList.filter(
      (d) => d.text.toLowerCase().indexOf(constellationText) > -1
    );
  }
  return store.newSoloOperationsConstellationList;
});

let filterFnConstellationFinish = (val, update, abort) => {
  update(() => {
    constellationText = val.toLowerCase();
    if (constellationList.length > 0 && val) {
      constellation_id = constellationList[0];
    }
  });
};

let text = $computed(() => {
  if (pOnly == 0) {
    return "fa-regular fa-bell";
  } else {
    return "fa-solid fa-bell";
  }
});

let clickBell = () => {
  if (pOnly == 0) {
    pOnly = 1;
  } else {
    pOnly = 0;
  }
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
    return filterStart.filter((d) => d.campaign[0].constellation.region_id == region_id);
  } else {
    return filterStart;
  }
});

let filterMind1 = $computed(() => {
  if (constellation_id) {
    return filterMind.filter((d) => d.campaign[0].constellation_id == constellation_id);
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
  if (filterStatusSelect == 1) {
    return filterMind3.filter(
      (d) =>
        d.campaign[0].status_id == 5 ||
        d.campaign[0].status_id == 1 ||
        d.campaign[0].status_id == 2
    );
  } else if (filterStatusSelect == 2) {
    return filterMind3.filter(
      (d) => d.campaign[0].status_id == 5 || d.campaign[0].status_id == 2
    );
  } else {
    return filterMind3.filter(
      (d) => d.campaign[0].status_id == 3 || d.campaign[0].status_id == 4
    );
  }
});

let itemType = (typeID) => {
  if (typeID == 32458) {
    return "Ihub";
  } else {
    return "TCU";
  }
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
    required: false,
    label: "Webway",
    classes: "text-no-wrap",
    field: (row) => row.id,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "region",
    required: true,
    label: "Region",
    classes: "text-no-wrap",
    style: "width: 7%",
    field: (row) => row.campaign[0].constellation.region.region_name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "constellation",
    required: true,
    label: "Constellation",
    classes: "text-no-wrap",
    field: (row) => row.campaign[0].constellation.constellation_name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "system",
    classes: "text-no-wrap",
    label: "System",
    field: (row) => row.campaign[0].system.system_name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "alliance",
    required: true,
    classes: "text-no-wrap",
    label: "Alliance",
    field: (row) => row.campaign[0].alliance.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "ticker",
    required: true,
    label: "Ticker",
    classes: "text-no-wrap",
    field: (row) => row.campaign[0].alliance.ticker,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "adm",
    label: "ADM",
    classes: "text-no-wrap",
    field: (row) => row.campaign[0].system.adm,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "structure",
    label: "Structure",
    classes: "text-no-wrap",
    field: (row) => row.campaign[0].event_type,
    format: (val) => {
      if (val == 32458) {
        return "IHUB";
      }
      return "TCU";
    },
  },
  {
    name: "progress",
    label: "Start/Progress",
    classes: "text-no-wrap",
    align: "center",
    style: "width: 25%",
    field: (row) => row.campaign[0].start_time,
    format: (val) => `${val}`,
  },
  {
    name: "age",
    label: "Countdown/Age",
    align: "right",
    classes: "text-no-wrap",
    field: (row) => row.campaign[0].system.adm,
    format: (val) => `${val}`,
  },

  {
    name: "actions",
    label: "",
    align: "right",
    classes: "text-no-wrap",
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
.myTableOperations
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

<style lang="sass" scoped>
.my-custom-toggle
  border: 1px solid #027be3
</style>
