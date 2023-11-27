Vue.component('chargebee', {
    props: ["trigger", "action", "fielddata"],
    data: function() {

        return {
            data: { plantypelist: [], itemfamilylist: [] },
            listLoading: false,
            itemfamilyloading: false,
            fields: [
                { type: 'textarea', value: 'first_name', title: 'First Name', task: ['createcustomer'], required: true },
                { type: 'textarea', value: 'last_name', title: 'Last Name', task: ['createcustomer'], required: true },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createcustomer'], required: true },
                { type: 'textarea', value: 'line', title: 'Billing Address(line)', task: ['createcustomer'], required: true },
                { type: 'textarea', value: 'city', title: 'Billing Address City', task: ['createcustomer'], required: true },
                { type: 'textarea', value: 'state', title: 'Billing Address State', task: ['createcustomer'], required: true },
                { type: 'textarea', value: 'zip', title: 'Billing Address Zip', task: ['createcustomer'], required: true },
                { type: 'textarea', value: 'country', title: 'Billing Address Country', task: ['createcustomer'], required: true },
                // Create Item
                { type: 'textarea', value: 'itemname', title: 'Item Name', task: ['createitem'], required: true },
                // Create Item family
                { type: 'textarea', value: 'itemfamilyname', title: 'Family Name', task: ['createitemfamily'], required: true },
                { type: 'textarea', value: 'description', title: 'Description', task: ['createitemfamily'], required: true },
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

        getplantype: function() {
            this.listLoading = true;
            this.data.plantypelist = {
                0: { 'id': 'plan', 'name': 'Plan' },
                1: { 'id': 'addon', 'name': 'Addon' }
            };
            this.listLoading = false;
        },

        getItemfamilyList: function(event) {
            this.itemfamilyloading = true;
            activePlatformId = this.fielddata.activePlatformId;
            this.fielddata.plan_id = event.target.value;

            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_itemfamily_name',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.itemfamilylist = response.data
                this.itemfamilyloading = false;
            });
        },

        selectedfamily(event) {
            this.fielddata.family_id = event.target.value;

        }

    },
    template: '#chargebee-action-template'
});