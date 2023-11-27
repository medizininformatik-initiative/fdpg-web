Vue.component('getgist', {
    props: ["trigger", "action", "fielddata"],
    data: function() {

        return {
            data: { campaignList: [] },
            fields: [
                // Create contact
                { type: 'textarea', value: 'name', title: 'Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'phone', title: 'Phone', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'city_name', title: 'City Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'region_name', title: 'Region Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'country_name', title: 'Country Name', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'country_code', title: 'Country Code', task: ['createcontact'], required: true },
                { type: 'textarea', value: 'address', title: 'Address', task: ['createcontact'], required: true },

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

    },
    template: '#getgist-action-template'
});