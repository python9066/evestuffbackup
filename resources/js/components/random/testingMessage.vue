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
      Doing some testing on this page at the moment. So you may be seeing some
      old notifications.
    </v-alert>
  </div>
</template>

<script>
export default {
  name: "errorMessage",
};
</script>
