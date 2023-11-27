Vue.component('wealthbox', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: { typelist: [] },
            fields: [
                // create customer
                { type: 'textarea', value: 'salutation', title: 'Salutation', task: ['createcontact'], required: false },
                { type: 'textarea', value: 'first_name', title: 'First Name', task: ['createcontact'], required: false },
                { type: 'textarea', value: 'last_name', title: 'Last Name', task: ['createcontact'], required: false },
                { type: 'textarea', value: 'birth_date', title: 'Birthday', task: ['createcontact'], required: false },
                { type: 'textarea', value: 'job_title', title: 'Job Title', task: ['createcontact'], required: false },
                // create task
                { type: 'textarea', value: 'name', title: 'Name', task: ['createtask'], required: false },
                { type: 'textarea', value: 'due_date', title: 'Due Date', task: ['createtask'], required: false },
                { type: 'textarea', value: 'description', title: 'Description', task: ['createtask'], required: false },

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


        selectedtypeid: function(event) {
            this.fielddata.typeid = event.target.value;
        },

        gettypelist: function() {
            this.typeLoading = true;
            activePlatformId = this.fielddata.activePlatformId;

            this.data.typelist = {
                0: { "id": "Person", "name": "Person" },
                1: { "id": "Household", "name": "Household" },
                2: { "id": "Organization", "name": "Organization" },
                3: { "id": "Trust", "name": "Trust" }
            }

            this.typeLoading = false;

        }
    },

    template: '#wealthbox-action-template'
});