Vue.component('go4client', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: {},
            fields: [
                // create contact
                { type: 'textarea', value: 'name', title: 'Name', task: ['createcontact'], required: false },
                { type: 'textarea', value: 'phonenumber', title: 'Phone Number', task: ['createcontact'], required: false },
                { type: 'textarea', value: 'gender', title: 'Gender', task: ['createcontact'], required: false },


                // create voice campaign
                { type: 'textarea', value: 'cname', title: 'Name', task: ['createvoicecampaign'], required: true },
                { type: 'textarea', value: 'csender', title: 'Sender', task: ['createvoicecampaign'], required: true },
                { type: 'textarea', value: 'earliestTimeToCall', title: 'Earliest Time To Call', task: ['createvoicecampaign'], required: true },



            ]

        }
    },



    methods: {

        CheckinDatabase: function(item, name) {
            var not_match = true;
            var saved_item = '{{' + item + '}}';

            if (!(typeof fieldData == 'undefined')) {
                var fieldaa = this.fielddata;
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

    template: '#go4client-action-template'
});