Vue.component('messagebird', {
  props: ["trigger", "action", "fielddata"],
  data() {
    return {
      error: "",
      fields: [
        { type: 'textarea', value: 'msisdn', title: 'Phone Number', task: ['create_contact'], required: true },
        { type: 'textarea', value: 'firstName', title: 'First Name', task: ['create_contact'], required: false },
        { type: 'textarea', value: 'lastName', title: 'Last Name', task: ['create_contact'], required: false },
        { type: 'textarea', value: 'custom1', title: 'Custom Field 1', task: ['create_contact'], required: false },
        { type: 'textarea', value: 'custom2', title: 'Custom Field 2', task: ['create_contact'], required: false },
        { type: 'textarea', value: 'custom3', title: 'Custom Field 3', task: ['create_contact'], required: false },
        { type: 'textarea', value: 'custom4', title: 'Custom Field 4', task: ['create_contact'], required: false },
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
  template: '#messagebird-action-template'
});
