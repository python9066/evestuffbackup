<template>
  <div>
    <v-alert
      outlined
      dismissible
      text
      type="success"
      :icon="false"
      :elevation="24"
    >
      Use the feedback button if you find any problems. Contact
      <strong> JohnMonty</strong> on Jabber if its a big problem.
    </v-alert>
  </div>
</template>

<script>
export default {
  name: "messageComponent",
};
</script>
