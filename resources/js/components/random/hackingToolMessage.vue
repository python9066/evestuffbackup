<template>
  <div>
    <v-alert
      outlined
      dismissible
      text
      color="light-blue darken-1"
      :icon = false
      :elevation = 24
    >
     <p class="text-center"> This page should now be working as intended.  Contact <strong> JohnMonty</strong> on Jabber you see anything broken.</p>
     <p class="text-center"> Take note. To assign yourself to a node, you <strong>HAVE TO</strong> press the "ADD CHAR" button and pick<strong> Hacker </strong>as your role.</p>
    </v-alert>
  </div>
</template>


<script>
export default {
  name: "hackingToolMessage",
};
</script>
