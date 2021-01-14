<template>
    <div>
        <v-btn v-if="showAddIcon" icon color="green darken-3" @click="showAdd">
            <v-icon small>fas fa-plus</v-icon></v-btn
        >
        <v-btn v-if="showRemoveIcon" icon color="red darken-3">
            <v-icon small>fas fa-trash-alt</v-icon></v-btn
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
            if (this.item.user_id == null) {
                console.log("addTrue");
                return true;
            } else {
                console.log("addFalse");
                return false;
            }
        },

        showRemoveIcon() {
            if (this.item.user_id != null) {
                console.log("removeTrue");
                return true;
            } else {
                console.log("removeFalse");
                return false;
            }
        }
    }
};
</script>

<style></style>
