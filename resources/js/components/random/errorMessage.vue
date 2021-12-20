<template>
  <div>
    <v-alert
      outlined
      dismissible
      text
      color="light-blue darken-1"
      :icon="false"
      :elevation="24"
    >
      This page is new, but should be working fine at the moment. Use the
      feedback button if you find any problems. Contact
      <strong> JohnMonty</strong> on Jabber if its a big problem.
    </v-alert>
  </div>
</template>

<script>
export default {
  name: "errorMessage",
};
</script>
