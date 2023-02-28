<template>
  <div>
    <q-btn :color="pillColor()" :label="buttontext(item)" rounded
      ><q-menu>
        <q-list style="min-width: 100px">
          <q-item
            v-if="props.item.status.id != 16"
            clickable
            v-close-popup
            @click="statusUpdate(16)"
          >
            <q-item-section class="text-green">Online</q-item-section>
          </q-item>
          <q-separator />
          <q-item clickable v-close-popup @click="destoryed()">
            <q-item-section class="text-red">Dead</q-item-section>
          </q-item>
        </q-list>
      </q-menu></q-btn
    >
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});

let destoryed = async () => {
  await axios({
    method: "delete",
    url: "/api/rcmovedonebad/" + props.item.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let statusUpdate = async (statusID) => {
  var request = null;
  request = {
    station_status_id: statusID,
  };
  await axios({
    method: "put",
    url: "/api/stationsheet/updatestationnotification/" + props.item.id,
    data: request,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let pillColor = () => {
  if (props.item.status.id == 4) {
    return "orange darken-1";
  }
  if (props.item.status.id == 18) {
    return "brown lighten-2";
  }
  if (props.item.status.id == 16) {
    return "green";
  }
  if (props.item.status.id == 7) {
    return "red";
  }
  return "webChip";
};

let buttontext = () => {
  var ret = props.item.status.name.replace("Upcoming - ", "");
  return ret;
};
</script>

<style lang="scss"></style>
