Vue.component('eventbrite', {
    props: ["trigger", "action", "fielddata"],
    data: function () {

        return {
            data: {
                organizerslist: [],
                currencylist: []
            },
            listLoading: false,
            fields: [

                // create event
                {
                    type: 'textarea',
                    value: 'name',
                    title: 'Name',
                    task: ['createevent'],
                    required: true
                },
                {
                    type: 'textarea',
                    value: 'description',
                    title: 'Description',
                    task: ['createevent'],
                    required: true
                },
                {
                    type: 'textarea',
                    value: 'start',
                    title: 'Start Date',
                    task: ['createevent'],
                    required: true
                },
                {
                    type: 'textarea',
                    value: 'end',
                    title: 'End Date',
                    task: ['createevent'],
                    required: true
                },
                {
                    type: 'textarea',
                    value: 'capacity',
                    title: 'Capacity',
                    task: ['createevent'],
                    required: true
                },

                // create venue
                {
                    type: 'textarea',
                    value: 'vname',
                    title: 'Name',
                    task: ['createvenue'],
                    required: true
                },
                {
                    type: 'textarea',
                    value: 'vcapacity',
                    title: 'Capacity',
                    task: ['createvenue'],
                    required: true
                },
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

        getorganizationlist: function (event) {

            this.fielddata.accountId = event.target.value;
            activePlatformId = this.fielddata.activePlatformId;

            const body = {
                '_nonce': awp.nonce,
                'action': 'awp_fetch_organizers',
                'platformid': activePlatformId,
                accountId: this.fielddata.accountId
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.organizerslist = response.data;
                this.campfireLoading = false;
            });
        },

        getcurrencylist: function (event) {
            this.fielddata.organizationid = event.target.value;
            this.data.currencylist = {
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

        selectedcurrency(event) {
            this.fielddata.currency = event.target.value;
        }

    },

    mounted() {
        this.fielddata.organizationid = "";
        this.fielddata.name = "";
        this.fielddata.description = "";
        this.fielddata.start = ""
        this.fielddata.end = "";
        this.fielddata.capacity = "";
        this.fielddata.vname = ""
        this.fielddata.vcapacity = "";
        this.fielddata.vaddress = "";
    },

    template: '#eventbrite-action-template'
});