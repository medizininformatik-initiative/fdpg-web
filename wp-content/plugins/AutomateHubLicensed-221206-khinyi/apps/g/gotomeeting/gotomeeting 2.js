Vue.component('gotomeeting', {
    props: ["trigger", "action", "fielddata"],
    data: function() {
        return {
            data: { meetingtypelist: [], passwordrequirementinfo: [], callInfolist: [] },
            listLoading: false,
            fields: [
                { type: 'textarea', value: 'subject', title: 'Subject', task: ['createmeeting'], required: true },
                { type: 'textarea', value: 'starttime', title: 'Start Date And Time', task: ['createmeeting'], required: true },
                { type: 'textarea', value: 'endtime', title: 'End Date And Time', task: ['createmeeting'], required: true }
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

        getmeetingtype: function() {

            this.data.meetingtypelist = {
                0: { 'id': 'immediate', 'name': 'Immediate' },
                1: { 'id': 'recurring', 'name': 'Recurring' },
                2: { 'id': 'scheduled', 'name': 'Scheduled' }
            };

        },

        selectedpassword: function(event) {
            this.fielddata.passwordrequirementstatus = event.target.value;

        },

        passwordinfo: function(event) {

            this.fielddata.meetingtype = event.target.value;

            this.data.passwordrequirementinfo = {
                0: { 'id': 'true', 'name': 'True' },
                1: { 'id': 'false', 'name': 'False' }
            };
        },



    },
    template: '#gotomeeting-action-template'
});
