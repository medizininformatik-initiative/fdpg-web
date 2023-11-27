Vue.component('gotowebinar', {
    props: ["trigger", "action", "fielddata"],
    data: function() {
        return {
            data: { Webinarlist: [], demandstatuslist: [], experiencetypelist: [], webinartypelist: [], passwordstatuslist: [] },
            listLoading: false,
            fields: [
                { type: 'textarea', value: 'subject', title: 'Subject', task: ['createwebinar'], required: true },
                { type: 'textarea', value: 'description', title: 'Description', task: ['createwebinar'], required: true },
                { type: 'textarea', value: 'starttime', title: 'Start Date And Time', task: ['createwebinar'], required: true },
                { type: 'textarea', value: 'endtime', title: 'End Date And Time', task: ['createwebinar'], required: true }
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
        getdemandstatus: function(event) {
            this.fielddata.webinartype = event.target.value;
            this.data.demandstatuslist = {
                0: { 'id': 'false', 'name': 'False' },
                1: { 'id': 'true', 'name': 'True' }
            };

        },
        getexperiencetype: function(event) {
            this.fielddata.isOndemand = event.target.value;
            this.data.experiencetypelist = {
                0: { 'id': 'CLASSIC', 'name': 'CLASSIC' },
                1: { 'id': 'BROADCAST', 'name': 'BROADCAST' },
                2: { 'id': 'SIMULIVE', 'name': 'SIMULIVE' }
            };

        },
        getwebinartype: function() {

            this.data.webinartypelist = {
                0: { 'id': 'single_session', 'name': 'single_session' }
            };

        },
        getpasswordprotectionstatus: function(event) {
            this.fielddata.experienceType = event.target.value;
            this.data.passwordstatuslist = {
                0: { 'id': 'true', 'name': 'True' },
                1: { 'id': 'false', 'name': 'False' }
            };

        },
        selectedpassword: function(event) {
            this.fielddata.passwordstatus = event.target.value;
        }

    },
    template: '#gotowebinar-action-template'
});