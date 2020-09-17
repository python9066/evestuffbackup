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
     <p class="text-center"> This whole page is very work in progress so things may not work fully.  Contact <strong> JohnMonty</strong> on Jabber you see anything 100% broken to the point its blowing up.</p>
     <p class="text-center"> If you want play around feel free.  Take note. To assign yourself to a node, you <strong>HAVE TO</strong> press the "ADD CHAR" button and pick<strong> Hacker </strong>as your role.</p>
    </v-alert>
  </div>
</template>


<script>
export default {
  name: "hackingToolMessage",
};
</script>
