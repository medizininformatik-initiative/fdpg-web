Vue.component('esputnik', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: { groupList: [] },
            fields: [
                // create contact
                { type: 'textarea', value: 'firstName', title: 'First Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'lastName', title: 'Last Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'sms', title: 'SMS Number', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'address', title: 'Address', task: ['createcontact'], required: true },

                // create mail
                { type: 'textarea', value: 'subject', title: 'Mail Subject', task: ['sendemail'], required: true },
                { type: 'textarea', value: 'htmlText', title: 'Email Content', task: ['sendemail'], required: true },
                { type: 'textarea', value: 'receiver', title: 'Receiver\'s Email', task: ['sendemail'], required: true },

                // create sms
                { type: 'textarea', value: 'text', title: 'Message Content', task: ['sendsms'], required: true },
                { type: 'textarea', value: 'phoneNumbers', title: 'Receiver Number', task: ['sendsms'], required: true }


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


        selectedgroup: function(event) {
            this.fielddata.groupid = event.target.value;

        },

        getGroupList: function() {
            this.accountLoading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_group',
            }

            jQuery.post(ajaxurl, body, (response) => {

                this.data.groupList = response.data;
                this.accountLoading = false;

            });


        }
    },

    template: '#esputnik-action-template'
});