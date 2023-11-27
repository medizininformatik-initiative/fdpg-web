Vue.component('squarespace', {
    props: ["trigger", "action", "fielddata"],
    data: function() {

        return {
            data: { storePageIdList: [], taglist: [], basecurrencylist: [], salescurrencylist: [], shippingweightmeasuringunitlist: [] },
            pageIdsouceloading: false,
            fields: [
                { type: 'textarea', value: 'name', title: 'Name', task: ['createproduct'], required: true },
                { type: 'textarea', value: 'description', title: 'Description', task: ['createproduct'], required: true },
                { type: 'textarea', value: 'baseamount', title: 'Base Price', task: ['createproduct'], required: true },
                { type: 'textarea', value: 'saleamount', title: 'Sales Price', task: ['createproduct'], required: true },
                { type: 'textarea', value: 'shippingweight', title: 'Shipment Weight', task: ['createproduct'], required: true },
                { type: 'textarea', value: 'productlenght', title: 'Product lenght', task: ['createproduct'], required: true },
                { type: 'textarea', value: 'productwidth', title: 'Product Width', task: ['createproduct'], required: true },
                { type: 'textarea', value: 'productheight', title: 'Product Height', task: ['createproduct'], required: true },
                { type: 'textarea', value: 'quantity', title: 'Quantity', task: ['createproduct'], required: true }
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

        getbasecurrencylist: function(event) {
            this.fielddata.tags = event.target.value;
            this.data.basecurrencylist = {
                0: {
                    'id': 'USD',
                    'name': 'USD'
                },
                1: {
                    'id': 'GBP',
                    'name': 'GBP'
                },
                2: {
                    'id': 'JPY',
                    'name': 'JPY'
                },
                3: {
                    'id': 'CHF',
                    'name': 'CHF'
                },
                4: {
                    'id': 'EUR',
                    'name': 'EUR'
                }
            };
        },

        getsalescurrencylist: function(event) {
            this.fielddata.basecurrency = event.target.value;
            this.data.salescurrencylist = {
                0: {
                    'id': 'USD',
                    'name': 'USD'
                },
                1: {
                    'id': 'GBP',
                    'name': 'GBP'
                },
                2: {
                    'id': 'JPY',
                    'name': 'JPY'
                },
                3: {
                    'id': 'CHF',
                    'name': 'CHF'
                },
                4: {
                    'id': 'EUR',
                    'name': 'EUR'
                }
            };
        },

        gettaglist: function(event) {
            this.fielddata.storePageId = event.target.value;
            this.data.taglist = {
                0: {
                    'id': 'artisanal',
                    'name': 'artisanal'
                },
                1: {
                    'id': 'steak',
                    'name': 'steak'
                },
            };
        },

        shippingweightmeasuringunit: function(event) {
            this.fielddata.salescurrency = event.target.value;
            this.data.shippingweightmeasuringunitlist = {
                0: {
                    'id': 'KILOGRAM',
                    'name': 'Kilogram'
                },
                1: {
                    'id': 'POUND',
                    'name': 'Pound'
                },
            };
        },

        getstorePageId: function() {
            this.pageIdsouceloading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_storePageId',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.storePageIdList = response.data
                this.pageIdsouceloading = false;
            });
        },

        selectedshippingweightmeasuringunit: function(event) {
            this.fielddata.shippingweightmeasuringunit = event.target.value;
        }



    },
    template: '#squarespace-action-template'
});