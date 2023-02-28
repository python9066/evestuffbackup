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
          <div class="col-1 flex justify-end">
            <SettingPannel v-if="can('view_coord_sheet')"></SettingPannel>
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

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="webway" :props="props">
            <SoloCampaginWebWay
              v-if="webwayJumps(props.row) && webwayLink(props.row)"
              :jumps="webwayJumps(props.row)"
              :web="webwayLink(props.row)"
            ></SoloCampaginWebWay>
          </q-td>
          <q-td key="region" :props="props">
            {{ props.row.system.region.region_name }}
          </q-td>
          <q-td key="constellation" :props="props">
            {{ props.row.system.constellation.constellation_name }}
          </q-td>
          <q-td key="system" :props="props">
            <q-btn
              color="none"
              flat
              rounded
              text-color="positive"
              icon="fa-solid fa-map"
              :href="link(props.row)"
              target="_blank"
            />
            <span
              @click="copySystem(props.row.system.system_name)"
              class="cursor-pointer"
            >
              {{ props.row.system.system_name }}</span
            ></q-td
          >
          <q-td key="corpTicker" :props="props">
            <q-avatar size="lg" class="q-pr-xl">
              <img :src="props.row.corp.url" />
            </q-avatar>
            <span :class="standingCheckCorp(props.row)">
              {{ props.row.corp.ticker }}</span
            >
          </q-td>
          <q-td key="allianceTicker" :props="props">
            <span v-if="props.row.corp.alliance_id">
              <q-avatar size="lg" class="q-pr-xl">
                <img :src="props.row.corp.alliance.url" />
              </q-avatar>
              <span :class="standingCheck(props.row)">
                {{ props.row.corp.alliance.ticker }}
              </span></span
            >
          </q-td>
          <q-td key="type" :props="props">
            <q-avatar size="lg" class="q-pr-xl">
              <img :src="itemUrl(props.row.item_id)" />
            </q-avatar>
            {{ props.row.item.item_name }}
          </q-td>
          <q-td key="name" :props="props">
            {{ props.row.name }}
          </q-td>
          <q-td key="status" :props="props">
            <StatusButton v-if="can('add_timer')" :item="props.row" />
          </q-td>
          <q-td key="actions" :props="props">
            <div class="row">
              <div class="col"><AddStation :from="2" :station="props.row" /></div>
              <div class="col">
                <RcStationMessage :station="props.row" :type="4"></RcStationMessage>
              </div>
              <div class="col">
                <StationInfoSheet :station="props.row" v-if="showInfo(props.row)" />
              </div>
              <div class="col" v-if="can('view_station_logs')">
                <q-btn
                  size="md"
                  flat
                  color="none"
                  text-color="primary"
                  round
                  padding="none"
                  @click="props.expand = !props.expand"
                  icon="fa-solid fa-clock-rotate-left"
                />
              </div>
            </div>
          </q-td>
        </q-tr>
        <q-tr v-show="props.expand" :props="props">
          <q-td colspan="100%">
            <div>
              <StationSheetLogs :station="props.row" />
            </div>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let search = $ref("");
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
