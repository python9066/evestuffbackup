<template>
  <div>
    <q-btn color="primary" icon="check" label="OK"
      ><q-menu class="myRoundTop">
        <q-card class="myRoundTop">
          <q-card-section class="bg-primary text-h5 text-center">
            <h5 class="no-margin">{{ cardTitle }}</h5> </q-card-section
          ><q-card-section>
            <q-tab-panels v-model="tab" animated>
              <q-tab-panel name="planning">
                <div class="column q-gutter-none text-webway">
                  <OperationinfocheckListTickBox
                    title="Fourm OP Posted"
                    storeName="planing_op_posted"
                    tagName="PlanningPosted"
                  />
                  <OperationinfocheckListTickBox
                    title="OP Pre-Pinged"
                    storeName="planing_op_pre_ping"
                    tagName="PrePing"
                  />
                  <OperationinfocheckListTickBox
                    title="Recon Informed"
                    storeName="planing_op_recon_alerted"
                    tagName="ReconAlerted"
                  />
                  <OperationinfocheckListTickBox
                    title="Capaital FC's Found"
                    storeName="planing_op_capital_fc_found"
                    tagName="CapitalFCFound"
                  />
                  <OperationinfocheckListTickBox
                    title="FC's Found"
                    storeName="planing_op_fc_found"
                    tagName="FCFound"
                  />
                  <OperationinfocheckListTickBox
                    title="Doctrines decided"
                    storeName="planing_op_doctromes_decoded"
                    tagName="DoctrinesDecided"
                  />
                  <OperationinfocheckListTickBox
                    title="Allies Informed"
                    storeName="planing_op_allies_infored"
                    tagName="AlliesInformed"
                  />
                </div>
              </q-tab-panel>
              <q-tab-panel name="formup">
                <div class="column q-gutter-none text-webway">
                  <OperationinfocheckListTickBox
                    title="FC's ready and informed"
                    storeName="form_op_fc_ready"
                    tagName="FCReady"
                  />
                  <OperationinfocheckListTickBox
                    title="Capital FC's ready and informed"
                    storeName="form_op_capital_fc_ready"
                    tagName="CapitalFCReady"
                  />
                  <OperationinfocheckListTickBox
                    title="Recon Online"
                    storeName="form_op_recon_ready"
                    tagName="ReconReady"
                  />
                  <OperationinfocheckListTickBox
                    title="Scouts Onlined"
                    storeName="form_op_scouts_ready"
                    tagName="ScoutsReady"
                  />
                  <OperationinfocheckListTickBox
                    title="GSOL Online"
                    storeName="form_op_gsol_ready"
                    tagName="GSOLReady"
                  />
                  <OperationinfocheckListTickBox
                    title="Black Hand Online"
                    storeName="form_op_blackhand_ready"
                    tagName="BlackHandReady"
                  />
                  <OperationinfocheckListTickBox
                    title="GSFOE Online"
                    storeName="form_op_gsfoe_ready"
                    tagName="GSFOEReady"
                  />
                  <OperationinfocheckListTickBox
                    title="Allies Confirmed"
                    storeName="form_op_allies_ready"
                    tagName="AlliesReady"
                  />
                </div>
              </q-tab-panel>

              <q-tab-panel name="postop">
                <div class="column q-gutter-none text-webway">
                  <OperationinfocheckListTickBox
                    title="Post-Op Debrief done"
                    storeName="post_op_defrief_done"
                    tagName="PostOpDebriefDone"
                  />
                  <OperationinfocheckListTickBox
                    title="Scouts Stood Down"
                    storeName="post_op_scouts_done"
                    tagName="ScoutsDone"
                  />
                  <OperationinfocheckListTickBox
                    title="Recon Stood Down"
                    storeName="post_op_recon_done"
                    tagName="ReconDone"
                  />
                  <OperationinfocheckListTickBox
                    title="FC's debriefed and stood down"
                    storeName="post_op_fc_done"
                    tagName="FCDone"
                  />
                  <OperationinfocheckListTickBox
                    title="Coord debriefed"
                    storeName="post_op_coord_done"
                    tagName="CoordDone"
                  />
                </div>
              </q-tab-panel>
            </q-tab-panels>
          </q-card-section>
        </q-card> </q-menu
    ></q-btn>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";

const OperationinfocheckListTickBox = defineAsyncComponent(() =>
  import("./OperationInfoCheckListTickBox.vue")
);
const store = useMainStore();
let tab = $ref("postop");

let cardTitle = $computed(() => {
  if (tab == "planning") {
    return "Planning";
  } else if (tab == "formup") {
    return "Pre-OP Form Up";
  } else if (tab == "postop") {
    return "Post-OP Cool Down";
  }
});
</script>

<style lang="scss"></style>
