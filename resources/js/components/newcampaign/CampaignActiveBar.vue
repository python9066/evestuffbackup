<template>
  <div>
    <q-expansion-item
      class="shadow-1 overflow-hidden"
      style="border-radius: 30px"
      label="dance"
      expand-icon-toggle
      hide-expand-icon
      :model-value="showPannel"
      header-class=" q-py-none bg-webBack text-webway text-center"
      @show="10"
      @hide="10"
      dark
    >
      <template v-slot:header>
        <div class="row q-gutter-none full-width items-center">
          <div class="col-auto">
            <div class="row full-width">
              <div class="col-auto">
                <q-btn
                  class="myOutLineButtonMid"
                  color="primary"
                  :outline="charTableOutlined"
                  @click="btnShowCharTable"
                  label="Char Table"
                  rounded
                />
              </div>
              <div class="col-auto">
                <AddOperationUser :operationID="props.operationID" />
              </div>
              <div class="col-auto"><OperationCal :operationID="operationID" /></div>
            </div>
          </div>
          <div v-if="can('access_multi_campaigns')" class="col-auto">
            <q-btn
              v-if="can('access_campaigns')"
              flat
              rounded
              padding="none"
              color="primary"
              icon="fa-solid fa-bullhorn"
              @click="sendAddCharMessage"
            />
            <q-btn
              v-if="can('access_campaigns')"
              flat
              rounded
              padding="none"
              color="negative"
              icon="fa-solid fa-bullhorn"
              @click="sendAddCharMessagePlus"
            />
          </div>
          <div class="col-auto" v-if="can('view_campaign_members')">
            <q-btn
              class="myOutLineButtonMid"
              color="primary"
              :outline="userTableOutlined"
              @click="btnShowUserTable"
              label="User List"
              rounded
            />
          </div>
          <div class="col-auto" v-if="can('view_campaign_members')">
            <q-btn
              class="myOutLineButtonMid"
              color="primary"
              :outline="logTableOutlined"
              @click="btnShowLogTable"
              label="Logs"
              rounded
            />
          </div>
          <div class="col-auto">
            <q-toggle
              v-model="store.newOperationInfo.read_only"
              :true-value="1"
              :false-value="0"
              color="green"
              >dwadwa</q-toggle
            >
          </div>
          <div class="col-auto">Toggle Systems</div>
        </div>
      </template>

      <q-card>
        <q-card-section>
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
        </q-card-section>
      </q-card>
    </q-expansion-item>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

const store = useMainStore();

const AddOperationUser = defineAsyncComponent(() => import("./AddOperationUser.vue"));
const OperationCal = defineAsyncComponent(() => import("./OperationCal.vue"));
let can = inject("can");
const props = defineProps({
  operationID: Number,
});
let showPannel = $ref(false);
let tab = $ref("mails");

let btnShowCharTable = () => {
  if (showPannel && tab == "charTable") {
    showPannel = false;
    tab = null;
    return;
  }

  if (showPannel && tab != "charTable") {
    tab = "charTable";
    return;
  }
  if (!showPannel) {
    showPannel = true;
    tab = "charTable";
    return;
  }
};

let charTableOutlined = $computed(() => {
  if (showPannel && tab == "charTable") {
    return false;
  } else {
    return true;
  }
});

let btnShowUserTable = () => {
  if (showPannel && tab == "userTable") {
    showPannel = false;
    tab = null;
    return;
  }

  if (showPannel && tab != "userTable") {
    tab = "userTable";
    return;
  }
  if (!showPannel) {
    showPannel = true;
    tab = "userTable";
    return;
  }
};

let userTableOutlined = $computed(() => {
  if (showPannel && tab == "userTable") {
    return false;
  } else {
    return true;
  }
});

let btnShowLogTable = () => {
  if (showPannel && tab == "logTable") {
    showPannel = false;
    tab = null;
    return;
  }

  if (showPannel && tab != "logTable") {
    tab = "logTable";
    return;
  }
  if (!showPannel) {
    showPannel = true;
    tab = "logTable";
    return;
  }
};

let logTableOutlined = $computed(() => {
  if (showPannel && tab == "logTable") {
    return false;
  } else {
    return true;
  }
});

let sendAddCharMessage = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/sendadduseroverlay/" + props.operationID + "/" + 1,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let sendAddCharMessagePlus = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    url: "/api/sendadduseroverlay/" + props.operationID + "/" + 2,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};
</script>

<style lang="scss"></style>
