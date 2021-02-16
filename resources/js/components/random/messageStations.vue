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
                    AT. NEW NOTIFICATION WILL NOW SHOW THEMSELF, THEY WILL SHOW
                    AS FAST AS I GET THEM SENT TO ME FROM THE BOT.
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
