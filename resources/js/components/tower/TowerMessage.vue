<template>
  <div>
    <v-dialog
      max-width="700px"
      z-index="0"
      v-model="showTowerNotes"
      @click:outside="close()"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-badge
          color="green"
          overlap
          :content="messageCount"
          :value="showNumber"
        >
          <v-icon color="blue" v-bind="attrs" v-on="on" @click="open()">
            <font-awesome-icon :icon="icon" size="xl" />
          </v-icon>
        </v-badge>
      </template>

      <v-card
        tile
        max-width="700px"
        min-height="200px"
        max-height="700px"
        class="d-flex flex-column"
      >
        <v-card-title
          >Notes for Tower the {{ tower.alliance_name }} {{ tower.item_name }}.
        </v-card-title>
        <v-card-text>
          <v-textarea
            height="400px"
            readonly
            no-resize
            v-model="tower.notes"
            outlined
            placeholder="No Notes"
          ></v-textarea>
          <v-divider></v-divider>
          <div>
            <v-text-field
              v-model="editText"
              auto-grow
              filled
              autofocus
              label="Enter New Notes Here"
            ></v-text-field>
          </div>
        </v-card-text>
        <v-spacer></v-spacer
        ><v-card-actions>
          <v-btn
            class="white--text"
            color="green"
            @click="updatetext()"
            :disabled="submitActive"
          >
            Submit
          </v-btn>

          <v-btn class="white--text" color="teal" @click="close()">
            Close
          </v-btn></v-card-actions
        >
      </v-card>

      <!-- <ShowStationNotes
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showTowerNotes = false"
            >
            </ShowStationNotes> -->
    </v-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    tower: Object,
  },
  data() {
    return {
      messageCount: 0,
      showNumber: false,
      showTowerNotes: false,
      editText: null,
    };
  },

  async created() {
    Echo.private("towermessage." + this.tower.id).listen(
      "TowerMessageUpdate",
      (e) => {
        this.showNumber = true;
        this.messageCount = this.messageCount + 1;
        this.$store.dispatch("updateTowers", e.flag.message);
      }
    );
  },

  methods: {
    close() {
      this.editText = null;
      this.showTowerNotes = false;
    },

    open() {
      (this.showNumber = false), (this.messageCount = 0);
    },

    updatetext() {
      this.editText = this.editText + "\n";
      if (this.tower.notes == null) {
        var note =
          moment.utc().format("YYYY-MM-DD HH:mm:ss") +
          " - " +
          this.$store.state.user_name +
          ": " +
          this.editText;
      } else {
        var note =
          moment.utc().format("YYYY-MM-DD HH:mm:ss") +
          " - " +
          this.$store.state.user_name +
          ": " +
          this.editText +
          this.tower.notes;
      }

      this.tower.notes = note;
      let request = {
        notes: note,
      };
      this.$store.dispatch("updateTowers", this.tower);
      axios({
        method: "put",
        url: "/api/towermessage/" + this.tower.id,
        withCredentials: true,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.editText = null;
    },
  },

  computed: {
    icon() {
      if (this.tower.notes == null) {
        return "fa-regular fa-message";
      } else {
        return "fa-solid fa-message";
      }
    },

    submitActive() {
      if (this.editText != null) {
        return false;
      } else {
        return true;
      }
    },
  },

  beforeDestroy() {
    Echo.leave("towermessage." + this.tower.id);
  },
};
</script>

<style></style>
