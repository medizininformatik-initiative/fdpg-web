Vue.component('surecart', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                { value: 'name', title: 'Name', task: ['createcustomer'], required: true },
                { value: 'email', title: 'Email', task: ['createcustomer'], required: true },
                { value: 'phone', title: 'Phone', task: ['createcustomer'], required: false },
                { value: 'country', title: 'Country', task: ['createcustomer'], required: false },
                { value: 'state', title: 'State', task: ['createcustomer'], required: false },
                { value: 'city', title: 'City', task: ['createcustomer'], required: false },
                { value: 'postal_code', title: 'Postal Code', task: ['createcustomer'], required: false },
                { value: 'line_1', title: 'Address', task: ['createcustomer'], required: false },
                
            ]
        }
    },
    methods: {

        CheckinDatabase: function (item, name) {
            let not_match = true;
            let saved_item = '{{' + item + '}}';
            if (!(typeof fieldData == 'undefined')) {
                let fieldaa = this.fielddata;
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
  
    template: '#surecart-action-template'
});
