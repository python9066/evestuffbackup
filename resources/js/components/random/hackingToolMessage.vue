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
      <p class="text-center">
        This page should now be working as intended. Use the feedback button if
        you find any problems. Contact
        <strong> JohnMonty</strong> on Jabber if its a big problem.
      </p>
      <p class="text-center">
        Take note. To assign yourself to a node, you
        <strong>HAVE TO</strong> press the "CHARACTERS" button and pick<strong>
          Hacker </strong
        >as your role.
      </p>
    </v-alert>
  </div>
</template>

<script>
export default {
  name: "hackingToolMessage",
};
</script>
