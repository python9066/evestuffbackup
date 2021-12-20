<template>
  <div>
    <v-alert
      outlined
      dismissible
      text
      type="warning"
      :icon="false"
      :elevation="24"
    >
      <p>
        This page has been updated. You now
        <strong>need roles</strong> to edit the notifications. Contact a<strong>
          Recon or Coord Member</strong
        >
        for roles.
      </p>
      <p>
        Use the feedback button if you find any problems. Contact
        <strong> JohnMonty</strong> on Jabber if its a big problem.
      </p>
    </v-alert>
  </div>
</template>

<script>
export default {
  name: "messageNotification",
};
</script>
