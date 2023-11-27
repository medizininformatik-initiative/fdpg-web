Vue.component("monday", {
  props: ["trigger", "action", "fielddata"],

  data() {
    return {
      error: "",
      loading: null,
      selectedBoardId: null,
      mondayFormData: { boards: [], groups: [] },
      fields: [
        {
          type: "textarea",
          value: "name",
          title: "Item Name",
          task: ["create_board_item"],
          required: true,
        },
      ],
    };
  },

  methods: {
    CheckinDatabase: function (item) {
      let not_match = true;
      const saved_item = "{{" + item + "}}";

      if (!(typeof fieldData == "undefined")) {
        const fieldaa = this.fielddata;
        for (i in fieldaa) {
          if (fieldaa[i]) {
            if (i == "answerQ1" || i == "answerQ2" || i == "answerQ3") {
              if (fieldaa[i].length == 2) {
                if (fieldaa[i][1] == item) {
                  not_match = false;
                }
              }
            } else {
              if (fieldaa[i] && typeof fieldaa[i] === "string") {
                if (fieldaa[i].includes(saved_item)) {
                  not_match = false;
                }
              }
            }
          }
        }
      }

      return not_match;
    },

    getBoards(event) {
      activePlatformId = this.fielddata.activePlatformId;
      this.error = "";
      this.loading = true;
      this.mondayFormData = { boards: [], groups: [] };

      if (event.target.value.length < 1) {
        this.loading = false;
        return;
      }

      const body = {
        _nonce: awp.nonce,
        platformid: activePlatformId,
        action: "awp_fetch_boards",
      };

      jQuery.post(ajaxurl, body, (response) => {
        if (response.data == undefined) {
          this.loading = false;
          this.error = "Try again";
          return;
        }

        if ("errors" in response.data) {
          this.error = response.data.errors[0];
        } else {
          this.mondayFormData.boards = response.data.data.boards;
        }

        this.loading = false;
      });
    },

    getGroups(event) {
      activePlatformId = this.fielddata.activePlatformId;
      this.error = "";
      (this.selectedBoardId = null), (this.loading = true);
      this.mondayFormData.groups = [];
      const boardId = event.target.value;

      if (boardId.length < 1) {
        this.loading = false;
        return;
      }

      const body = {
        _nonce: awp.nonce,
        platformid: activePlatformId,
        action: "awp_fetch_groups",
        boardId,
      };

      jQuery.post(ajaxurl, body, (response) => {
        if (response.data == undefined) {
          this.loading = false;
          this.error = "Try again";
          return;
        }

        if ("errors" in response.data) {
          this.error = response.data.errors[0];
        } else {
          this.selectedBoardId = response.data.boardId;
          this.mondayFormData.groups = response.data.data.boards[0].groups;
        }

        this.loading = false;
      });
    },

    onGroupSelect(event) {
      if (event.target.value.length < 1) return;
      this.fielddata.groupId = event.target.value;
      this.fielddata.boardId = this.selectedBoardId;
    },
  },

  template: "#monday-action-template",
});
