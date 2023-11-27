Vue.component("postmark", {
  props: ["trigger", "action", "fielddata"],
  data() {
    return {
      fields: [
        {
          type: "textarea",
          value: "ReplyTo",
          title: "Reply To Email Address",
          task: ["send_email"],
          required: true,
        },
        {
          type: "textarea",
          value: "Cc",
          title: "Cc Email Address",
          task: ["send_email"],
          required: false,
        },
        {
          type: "textarea",
          value: "Bcc",
          title: "Bcc Email Address",
          task: ["send_email"],
          required: false,
        },
        {
          type: "textarea",
          value: "Subject",
          title: "Subject",
          task: ["send_email"],
          required: true,
        },
        {
          type: "textarea",
          value: "TextBody",
          title: "Body",
          task: ["send_email"],
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
  },
  template: "#postmark-action-template",
});
