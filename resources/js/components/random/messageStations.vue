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
                <strong
                    >THIS PAGE IS 100% WORK IN PROGRESS, ATM ITS USELESS</strong
                >
            </p>
        </v-alert>
    </div>
</template>

<script>
export default {
    name: "messageNotification"
};
</script>
