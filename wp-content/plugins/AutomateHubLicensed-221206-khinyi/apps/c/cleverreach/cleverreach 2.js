Vue.component('cleverreach', {
    props: ["trigger", "action", "fielddata"],
    data: function() {

        return {
            data: { backupstatus: [] },
            listLoading: false,
            fields: [
                // Create group
                { type: 'textarea', value: 'name', title: 'Name', task: ['creategroup'], required: true },
                { type: 'textarea', value: 'receiver_info', title: 'Receiver Info', task: ['creategroup'], required: true },
                // create a mailing
                { type: 'textarea', value: 'name', title: 'Name', task: ['createmailing'], required: true },
                { type: 'textarea', value: 'subject', title: 'Subject', task: ['createmailing'], required: true },
                { type: 'textarea', value: 'sender_name', title: 'Sender Name', task: ['createmailing'], required: true },
                { type: 'textarea', value: 'sender_email', title: 'Sender Email', task: ['createmailing'], required: true },
                { type: 'textarea', value: 'content', title: 'Content', task: ['createmailing'], required: true },

            ]
        }
    },
    methods: {

        CheckinDatabase: function(item, name) {

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

        getbackupdetails: function() {

            this.data.backupstatus = {
                0: { 'id': 'true', 'name': 'true' },
                1: { 'id': 'false', 'name': 'false' }
            };

        },

        selectedstatus(event) {
            this.fielddata.backupstatusid = event.target.value;

        }

    },
    template: '#cleverreach-action-template'
});