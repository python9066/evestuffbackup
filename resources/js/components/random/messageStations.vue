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
                    >THIS PAGE IS 100% WORK IN PROGRESS ITS ONLY GOOD TO LOOK
                    AT. FOR THE TIME BEING TO SEE NEW DATA YOU WILL NEED TO
                    REFRESH THE PAGE.
                </strong>
            </p>
        </v-alert>
    </div>
</template>

<script>
export default {
    name: "messageNotification"
};
</script>
