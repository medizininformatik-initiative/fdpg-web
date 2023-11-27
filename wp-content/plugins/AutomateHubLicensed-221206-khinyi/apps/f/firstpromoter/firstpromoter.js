Vue.component('firstpromoter', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: { sourceList: [] },
            fields: [
                // create customer
                { type: 'textarea', value: 'first_name', title: 'First Name', task: ['createpromoter'], required: false },
                { type: 'textarea', value: 'last_name', title: 'Last name', task: ['createpromoter'], required: false },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createpromoter'], required: false }
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




    },

    template: '#firstpromoter-action-template'
});