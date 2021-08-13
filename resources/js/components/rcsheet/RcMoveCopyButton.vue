<template>
    <div>
        <button v-clipboard="() => button">
            {{ item.out_time }}
        </button>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        item: Object,
        type: String
    },
    data() {
        return {};
    },

    methods: {},

    computed: {
        button() {
            if (this.type == "outtime") {
                var str = item.out_time.replace(/\s+/g, "");
                str = str.replace(/[:]/g, "");
                str = str.replace(/[-]/g, "");
                str = str.substring(2);
                return str;
            }
        }
    },

    beforeDestroy() {}
};
</script>

<style></style>
