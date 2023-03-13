<template>
  <div>
    <div class="row justify-around">
      <div class="col-4">
        <q-card class="my-card overflow-auto" :style="h">
          <q-card-section class="q-py-none text-center">
            All Ships - {{ totalShips }} <span> old</span>
          </q-card-section>
          <q-card-section>
            <q-list bordered dense>
              <q-item clickable v-for="(list, index) in tableShipData" :key="index">
                <div class="row full-width justify-between">
                  <div class="col-auto">
                    <q-avatar size="32px"> <img :src="listUrl(list.item_id)" /></q-avatar>
                  </div>
                  <div class="col-auto">{{ list.item_name }}</div>
                  <div class="col-auto">
                    {{ list.total }}
                    <span
                      v-if="oldAllShipNumber(list) != 0"
                      :class="textColor(oldAllShipNumber(list))"
                    >
                      ({{ oldAllShipNumber(list) }})</span
                    >
                  </div>
                </div>
              </q-item>
            </q-list>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-4">
        <q-card class="my-card overflow-auto" :style="h">
          <q-card-section class="q-py-none text-center">
            All Combat - {{ totalShips }}
          </q-card-section>
          <q-card-section>
            <q-list bordered dense>
              <q-item clickable v-for="(list, index) in tableShipGroupData" :key="index">
                <div class="row full-width justify-between">
                  <div class="col-auto">
                    <q-avatar size="32px">
                      <!-- <img :src="listurlGroup(list.group_id)" /> -->
                      <img
                        v-if="list.group_id == 29"
                        src="@/img/dscan/capsule_64.png"
                        alt=""
                      />

                      <img
                        v-if="
                          list.group_id == 27 ||
                          list.group_id == 419 ||
                          list.group_id == 540 ||
                          list.group_id == 1201
                        "
                        src="@/img/dscan/battlecruiser_64.png"
                        alt=""
                      />

                      <img
                        v-if="list.group_id == 898 || list.group_id == 900"
                        src="@/img/dscan/battleship_64.png"
                        alt=""
                      />

                      <img
                        v-if="
                          list.group_id == 485 ||
                          list.group_id == 547 ||
                          list.group_id == 883
                        "
                        src="@/img/dscan/capital_64.png"
                        alt=""
                      />

                      <img
                        v-if="
                          list.group_id == 26 ||
                          list.group_id == 358 ||
                          list.group_id == 832 ||
                          list.group_id == 833 ||
                          list.group_id == 894 ||
                          list.group_id == 906 ||
                          list.group_id == 963 ||
                          list.group_id == 1972
                        "
                        src="@/img/dscan/cruiser_64.png"
                        alt=""
                      />

                      <img
                        v-if="
                          list.group_id == 420 ||
                          list.group_id == 541 ||
                          list.group_id == 1305 ||
                          list.group_id == 1534
                        "
                        src="@/img/dscan/destroyer_64.png"
                        alt=""
                      />

                      <img
                        v-if="list.group_id == 1538"
                        src="@/img/dscan/forceAuxiliary_32.png"
                        alt=""
                      />

                      <img
                        v-if="
                          list.group_id == 25 ||
                          list.group_id == 324 ||
                          list.group_id == 830 ||
                          list.group_id == 831 ||
                          list.group_id == 834 ||
                          list.group_id == 893 ||
                          list.group_id == 1022 ||
                          list.group_id == 1283 ||
                          list.group_id == 1527 ||
                          list.group_id == 2001
                        "
                        src="@/img/dscan/frigate_64.png"
                        alt=""
                      />
                      <img
                        v-if="list.group_id == 513 || list.group_id == 902"
                        src="@/img/dscan/freighter_64.png"
                        alt=""
                      />

                      <img
                        v-if="
                          list.group_id == 28 ||
                          list.group_id == 380 ||
                          list.group_id == 1202
                        "
                        src="@/img/dscan/industrial_64.png"
                        alt=""
                      />

                      <img
                        v-if="list.group_id == 941"
                        src="@/img/dscan/industrialCommand_64.png"
                        alt=""
                      />

                      <img
                        v-if="list.group_id == 463 || list.group_id == 543"
                        src="@/img/dscan/miningBarge_64.png"
                        alt=""
                      />

                      <img
                        v-if="list.group_id == 237"
                        src="@/img/dscan/rookie_64.png"
                        alt=""
                      />

                      <img
                        v-if="list.group_id == 31"
                        src="@/img/dscan/shuttle_64.png"
                        alt=""
                      />

                      <img
                        v-if="list.group_id == 659"
                        src="@/img/dscan/superCarrier_64.png"
                        alt=""
                      />

                      <img
                        v-if="list.group_id == 30"
                        src="@/img/dscan/titan_64.png"
                        alt=""
                      />
                    </q-avatar>
                  </div>
                  <div class="col-auto">{{ list.group_name }}</div>
                  <div class="col-auto">{{ list.total }}</div>
                </div>
              </q-item>
            </q-list>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-3">
        <q-card class="my-card overflow-auto" :style="h">
          <q-card-section class="q-py-none text-center">
            All Structures - {{ totalStructure }}
          </q-card-section>
          <q-card-section>
            <q-list bordered dense>
              <q-item clickable v-for="(list, index) in stableStructureData" :key="index">
                <div class="row full-width justify-between">
                  <div class="col-auto">
                    <q-avatar size="32px"></q-avatar>
                  </div>
                  <div class="col-auto">{{ list.group_name }}</div>
                  <div class="col-auto">{{ list.total }}</div>
                </div>
              </q-item>
            </q-list>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();

const props = defineProps({
  type: Number,
});

let listUrl = (id) => {
  return "https://imageserver.eveonline.com/Type/" + id + "_64.png";
};

let tableShipData = $computed(() => {
  if (props.type == 1) {
    if (store.getDscanAllNewShips) {
      return store.getDscanAllNewShips;
    } else {
      return [];
    }
  }

  if (props.type == 2) {
    if (store.getDscanOnGridNewShips) {
      return store.getDscanOnGridNewShips;
    } else {
      return [];
    }
  }

  if (props.type == 3) {
    if (store.getDscanOffGridNewShips) {
      return store.getDscanOffGridNewShips;
    } else {
      return [];
    }
  }
});

let tableShipGroupData = $computed(() => {
  if (props.type == 1) {
    if (store.getDscanAllNewShipsGroups) {
      return store.getDscanAllNewShipsGroups;
    } else {
      return [];
    }
  }

  if (props.type == 2) {
    if (store.getDscanOnGridNewShipsGroups) {
      return store.getDscanOnGridNewShipsGroups;
    } else {
      return [];
    }
  }

  if (props.type == 3) {
    if (store.getDscanOffGridNewShipsGroups) {
      return store.getDscanOffGridNewShipsGroups;
    } else {
      return [];
    }
  }
});

let stableStructureData = $computed(() => {
  if (props.type == 1) {
    if (store.getDscanAllNewStructures) {
      return store.getDscanAllNewStructures;
    } else {
      return [];
    }
  }

  if (props.type == 2) {
    if (store.getDscanOnGridNewStructures) {
      return store.getDscanOnGridNewStructures;
    } else {
      return [];
    }
  }

  if (props.type == 3) {
    if (store.getDscanOffGridNewStructures) {
      return store.getDscanOffGridNewStructures;
    } else {
      return [];
    }
  }
});

let totalShips = $computed(() => {
  if (props.type == 1) {
    if (tableShipData) {
      let totalOfTotals = 0;
      for (let i = 0; i < tableShipData.length; i++) {
        totalOfTotals += tableShipData[i].total;
      }

      return totalOfTotals;
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (tableShipData) {
      let totalOfTotals = 0;
      for (let i = 0; i < tableShipData.length; i++) {
        totalOfTotals += tableShipData[i].on;
      }

      return totalOfTotals;
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (tableShipData) {
      let totalOfTotals = 0;
      for (let i = 0; i < tableShipData.length; i++) {
        totalOfTotals += tableShipData[i].off;
      }

      return totalOfTotals;
    } else {
      return 0;
    }
  }
});

let totalStructure = $computed(() => {
  if (props.type == 1) {
    if (stableStructureData) {
      let totalOfTotals = 0;
      for (let i = 0; i < stableStructureData.length; i++) {
        totalOfTotals += stableStructureData[i].total;
      }

      return totalOfTotals;
    } else {
      return 0;
    }
  }

  if (props.type == 2) {
    if (stableStructureData) {
      let totalOfTotals = 0;
      for (let i = 0; i < stableStructureData.length; i++) {
        totalOfTotals += stableStructureData[i].on;
      }

      return totalOfTotals;
    } else {
      return 0;
    }
  }

  if (props.type == 3) {
    if (stableStructureData) {
      let totalOfTotals = 0;
      for (let i = 0; i < stableStructureData.length; i++) {
        totalOfTotals += stableStructureData[i].off;
      }

      return totalOfTotals;
    } else {
      return 0;
    }
  }
});

let oldAllShipNumber = (list) => {
  var newNum = list.total;
  var old = Object.values(store.dScan.totals.totals.items.old).find(
    (item) => item.item_id == list.item_id
  );

  var oldNum = old ? old.total : newNum;

  var total = newNum - oldNum;

  return total;
};

let textColor = (count) => {
  if (count > 0) {
    return "text-green";
  } else if (count < 0) {
    return "text-red";
  } else {
    return "text-white";
  }
};

let h = $computed(() => {
  let mins = 110;
  let window = store.size.height;

  let size = window - mins + "px";
  return "max-height:" + size;
});
</script>

<style lang="scss"></style>
