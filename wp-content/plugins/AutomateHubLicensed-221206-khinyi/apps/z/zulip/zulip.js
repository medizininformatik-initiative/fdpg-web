Vue.component('zulip', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: { streamlist: [], customerlist: [], planList: [] },
            fields: [
                // create customer
                { type: 'textarea', value: 'name', title: 'Name', task: ['createstream'], required: false },
                { type: 'textarea', value: 'description', title: 'Description', task: ['createstream'], required: false },
                // send message
                { type: 'textarea', value: 'topic', title: 'Topic', task: ['sendmessage'], required: true },
                { type: 'textarea', value: 'content', title: 'Content', task: ['sendmessage'], required: true },
                // Create User
                { type: 'textarea', value: 'full_name', title: 'Full Name', task: ['createuser'], required: true },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createuser'], required: true },
                { type: 'textarea', value: 'password', title: 'Password', task: ['createuser'], required: true }




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


        selectedstream: function(event) {
            this.fielddata.streamname = event.target.value;

        },
        selectedstreamid: function(event) {
            this.fielddata.streamid = event.target.value;

        },

        getstream: function() {
            this.streamLoading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_stream',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.streamlist = response.data
                this.streamLoading = false;
            });
        }
    },

    template: '#zulip-action-template'
});