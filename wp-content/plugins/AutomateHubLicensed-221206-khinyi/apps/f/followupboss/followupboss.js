Vue.component('followupboss', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: { stagelist: [], personlist: [] },
            fields: [
                // create peoples
                { type: 'textarea', value: 'firstName', title: 'First Name', task: ['createpeople'], required: false },
                { type: 'textarea', value: 'lastName', title: 'Last Name', task: ['createpeople'], required: false },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createpeople'], required: false },
                { type: 'textarea', value: 'phone', title: 'Phone', task: ['createpeople'], required: false },
                { type: 'textarea', value: 'address', title: 'Address', task: ['createpeople'], required: false }

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


        selectedstage: function(event) {
            this.fielddata.stageid = event.target.value;
        },

        getstage: function() {
            this.accountLoading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_stage',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.stagelist = response.data
                this.accountLoading = false;
            });
        }
    },

    template: '#followupboss-action-template'
});