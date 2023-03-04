<template>
  <div class="q-ma-md">
    <div class="row">
      <div class="col">
        <CampaignTitleBar
          :operationID="operationID"
          :title="store.newOperationInfo.title"
          :activeCampaigns="activeCampaigns"
          :warmUpCampaigns="warmUpCampaigns"
        />
      </div>
    </div>
    <div class="row">
      <div class="col">Campaign Bar</div>
    </div>
    <div class="row">
      <div class="col">SYSTEMSS</div>
    </div>
  </div>
</template>

<script setup>
import { useRoute } from "vue-router";
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";

let route = useRoute();
let store = useMainStore();
let can = inject("can");

const CampaignTitleBar = defineAsyncComponent(() =>
  import("../components/newcampaign/CampaignTitleBar.vue")
);

onMounted(async () => {
  await store.getOperationInfo(route.params.id);
  await store.getCampaignsList(operationID);

  Echo.private("operations." + operationID).listen("OperationUpdate", (e) => {
    if (e.flag.flag == 1) {
    }

    // * Set ReadOnlyFlag
    if (e.flag.flag == 2) {
      store.newOperationInfo.read_only = e.flag.message;
    }

    // * refresh Op info
    if (e.flag.flag == 3) {
      store.newOperationInfo = e.flag.message;
    }

    // * solo system update
    if (e.flag.flag == 4) {
      store.updateNewCampaigns(e.flag.message);
    }

    // * 5 is to remove op char from  chartable
    if (e.flag.flag == 5) {
      store.removeCharfromOpList(e.flag.userid);
    }

    // * 6 update op char table
    if (e.flag.flag == 6) {
      store.updateOpChar(e.flag.message);
    }

    if (e.flag.flag == 7) {
      store.updateNewCampaignSystem(e.flag.message);
    }

    if (e.flag.flag == 8) {
      store.updateNewCampaigns(e.flag.message.campaign[0]);
    }

    if (e.flag.flag == 9) {
      store.state.newCampaignSystems = e.flag.message;
    }
  });
  Echo.private("operationsown." + store.user_id + "-" + operationID).listen(
    "OperationOwnUpdate",
    (e) => {
      if (e.flag.flag == 1) {
        store.newOperationMessageOverlay = parseInt(e.flag.type);
      }

      if (e.flag.flag == 2) {
      }
      // * 3 add/update char to char table
      if (e.flag.flag == 3) {
        store.updateNewOwnChar(e.flag.message);
      }
      if (e.flag.flag == 4) {
      }
      // * 5 is to remove op char from own list
      if (e.flag.flag == 5) {
        store.removeCharfromOwnList(e.flag.userid);
      }

      if (e.flag.flag == 6) {
      }

      if (e.flag.flag == 7) {
      }

      if (e.flag.flag == 8) {
      }
    }
  );

  if (can("view_campaign_members")) {
    Echo.private("operationsadmin." + operationID).listen("OperationAdminUpdate", (e) => {
      // update watching user list
      if (e.flag.flag == 1) {
        store.operationUserList = e.flag.message;
      }

      if (e.flag.flag == 2) {
      }
      if (e.flag.flag == 3) {
      }
      if (e.flag.flag == 4) {
      }
      if (e.flag.flag == 5) {
      }

      if (e.flag.flag == 6) {
      }

      if (e.flag.flag == 7) {
      }

      if (e.flag.flag == 8) {
      }
    });
  }
});

onBeforeUnmount(async () => {
  Echo.leave("operations." + operationID);
  Echo.leave("operationsown." + store.user_id + "-" + operationID);
  Echo.leave("operationsadmin." + operationID);
});

let activeCampaigns = $computed(() => {
  var check = store.newCampaigns.length;
  if (check > 0) {
    var campaigns = store.newCampaigns.filter((c) => {
      if (c.status_id == 2) {
        return true;
      } else {
        return false;
      }
    });

    return campaigns;
  } else {
    return [];
  }
});

let warmUpCampaigns = $computed(() => {
  var check = store.newCampaigns.length;
  if (check > 0) {
    var campaigns = store.newCampaigns.filter((c) => {
      if (c.status_id == 5) {
        return true;
      } else {
        return false;
      }
    });

    return campaigns;
  } else {
    return [];
  }
});

let operationID = $computed(() => {
  return store.newOperationInfo.id;
});
</script>

<style lang="scss"></style>
