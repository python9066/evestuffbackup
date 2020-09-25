<template>
    <v-col>
    </v-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        system_name: String,
        system_id: Number,
        campaign_id: String
    },
    data() {
        return {
        };
    },

    methods: {
    },

    computed: {
    }
};
</script>

<style>

.style-2 {
  background-color: rgb(30,30,30,1)
}
.style-1 {
  background-color: rgb(184, 22, 35)
}</style>
