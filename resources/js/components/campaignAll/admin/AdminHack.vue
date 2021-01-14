<template>
    <div>
        <v-btn v-if="showAddIcon" icon color="green darken-3" @click="showAdd">
            <v-icon small>fas fa-plus</v-icon></v-btn
        >
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { mapState } from "vuex";
export default {
    props: {
        item: Object
    },
    data() {
        return {};
    },

    methods: {
        showAdd() {
            this.$emit("openAdd", this.item);
        }
    },

    computed: {
        showAddIcon() {
            if (
                this.item.user_id == null &&
                (this.item.status_id != 4 ||
                    this.item.status_id != 5 ||
                    this.item.status_id != 7 ||
                    this.item.status_id != 8)
            ) {
                return true;
            } else {
                return false;
            }
        }
    }
};
</script>

<style></style>
