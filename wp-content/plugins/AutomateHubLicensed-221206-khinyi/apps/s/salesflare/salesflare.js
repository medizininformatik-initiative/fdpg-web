Vue.component('salesflare', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            accountLoading: false,
            data: { accountList: [] },
            fields: [
                // create contact

                { type: 'textarea', value: 'firstname', title: 'First Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'middle', title: 'Middle Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'lastname', title: 'Last Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'city', title: 'City', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'country', title: 'Country', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'state_region', title: 'State Region', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'phone_number', title: 'Phone Number', task: ['createcontact'], required: true },

                // create account
                { type: 'textarea', value: 'name', title: 'Account Name', task: ['createaccount'], required: true },
                { type: 'textarea', value: 'size', title: 'Account Size', task: ['createaccount'], required: true },
                { type: 'textarea', value: 'website', title: 'Website', task: ['createaccount'], required: true },
                { type: 'textarea', value: 'description', title: 'Description', task: ['createaccount'], required: true },
                { type: 'textarea', value: 'city', title: 'City', task: ['createaccount'], required: true },
                { type: 'textarea', value: 'country', title: 'Country', task: ['createaccount'], required: true },
                { type: 'textarea', value: 'state_region', title: 'State Region', task: ['createaccount'], required: true },
                { type: 'textarea', value: 'phone_number', title: 'Phone Number', task: ['createaccount'], required: true },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createaccount'], required: true },

                // create internal note
                { type: 'textarea', value: 'body', title: 'Note Body', task: ['createnote'], required: true },
                { type: 'textarea', value: 'date', title: 'Date', task: ['createnote'], required: true },


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

        selectedowner: function(event) {
            this.fielddata.owner = event.target.value;

        },

        getaccounts: function() {
            this.accountLoading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_account',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.accountList = response.data;
                this.accountLoading = false;
            });
        }
    },

    template: '#salesflare-action-template'
});