Vue.component('companyhub', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: { companyList: [] },
            fields: [
                // create contact
                { type: 'textarea', value: 'FirstName', title: 'First Name', task: ['createcontact'], required: false },
                { type: 'textarea', value: 'LastName', title: 'Last Name', task: ['createcontact'], required: false },
                { type: 'textarea', value: 'Email', title: 'Email', task: ['createcontact'], required: false },

                // create company
                { type: 'textarea', value: 'Name', title: 'Company Name', task: ['createcompany'], required: true },
                { type: 'textarea', value: 'Phone', title: 'Phone', task: ['createcompany'], required: true },
                { type: 'textarea', value: 'Description', title: 'Description', task: ['createcompany'], required: true }

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


        selectedcompany: function(event) {
            this.fielddata.companyid = event.target.value;

        },

        getCompany: function() {
            this.accountLoading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_company',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.companyList = response.data
                this.accountLoading = false;
            });
        },

    },

    template: '#companyhub-action-template'
});