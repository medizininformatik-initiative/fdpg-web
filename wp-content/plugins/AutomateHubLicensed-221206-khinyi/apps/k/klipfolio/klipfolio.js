Vue.component('klipfolio', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: { statuslist: [] },
            fields: [
                // create client
                { type: 'textarea', value: 'name', title: 'Name', task: ['createclient'], required: false },
                { type: 'textarea', value: 'description', title: 'Description', task: ['createclient'], required: false },
                // create Klips
                { type: 'textarea', value: 'kname', title: 'Name', task: ['createklip'], required: false },
                { type: 'textarea', value: 'kdescription', title: 'Description', task: ['createklip'], required: false },
                // create user
                { type: 'textarea', value: 'first_name', title: 'First Name', task: ['createuser'], required: false },
                { type: 'textarea', value: 'last_name', title: 'Last Name', task: ['createuser'], required: false },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createuser'], required: false },
                { type: 'textarea', value: 'password', title: 'Password', task: ['createuser'], required: false }

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


        selectedstatus: function(event) {
            this.fielddata.status = event.target.value;
        },

        getstatus: function() {
            this.data.statuslist = {
                0: { 'id': 'disabled', 'name': 'disabled' },
                1: { 'id': 'setup', 'name': 'setup' },
                2: { 'id': 'trial', 'name': 'trial' }
            };
        }
    },

    template: '#klipfolio-action-template'
});