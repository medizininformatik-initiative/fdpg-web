Vue.component('smtp', {
  props: ["trigger", "action", "fielddata"],
  data() {
    return {
      error: "",
      fields: [
        { type: 'textarea', value: 'email', title: 'Email', task: ['send_email'], required: true },
        { type: 'textarea', value: 'subject', title: 'Subject', task: ['send_email'], required: true },
        { type: 'textarea', value: 'body', title: 'Body', task: ['send_email'], required: true },
      ]
    }
  },
  
  methods: {
    CheckinDatabase: function (item) {
      let not_match = true;
      const saved_item = '{{' + item + '}}';

      if (!(typeof fieldData == 'undefined')) {
        const fieldaa = this.fielddata;
        for (i in fieldaa) {
          if (fieldaa[i]) {
            if (i == 'answerQ1' || i == 'answerQ2' || i == 'answerQ3') {

              if (fieldaa[i].length == 2) {

                if (fieldaa[i][1] == item) {
                  not_match = false;
                }
              }
            } else {
              if (fieldaa[i] && (typeof fieldaa[i] === 'string')) {
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
  
  template: '#smtp-action-template'
});
