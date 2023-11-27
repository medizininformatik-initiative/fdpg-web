Vue.component('groundhogg', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                { value: 'email', title: 'Email', task: ['subscribe'], required: true },
                { value: 'firstName', title: 'First Name', task: ['subscribe'], required: false },
                { value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false },
                { value: 'primaryPhone', title: 'Primary Phone', task: ['subscribe'], required: false },
                { value: 'primaryPhoneExtension', title: 'Primary Phone Extension', task: ['subscribe'], required: false },
                { value: 'streetAddressOne', title: 'Street Address 1', task: ['subscribe'], required: false },
                { value: 'streetAddressTwo', title: 'Street Address 2', task: ['subscribe'], required: false },
                { value: 'city', title: 'City', task: ['subscribe'], required: false },
                { value: 'postalZip', title: 'Postal Zip', task: ['subscribe'], required: false },
                { value: 'country', title: 'Country', task: ['subscribe'], required: false },
                { value: 'tag', title: 'Tag', task: ['tag'], required: true }
            ]
        }
    },
    methods: {

        CheckinDatabase: function (item, name) {

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

    },
    mounted: function () {
        let that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }


    },
    template: '#groundhogg-action-template'
});
