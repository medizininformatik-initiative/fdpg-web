Vue.component('callrail', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            data: { accountlists: [], companylist: [], role: [] },
            fields: [
                // create user
                { type: 'textarea', value: 'first_name', title: 'First Name', task: ['createuser'], required: false },
                { type: 'textarea', value: 'last_name', title: 'Last Name', task: ['createuser'], required: false },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createuser'], required: false },

                // create outbund caller ID
                { type: 'textarea', value: 'name', title: 'Name', task: ['createoutboundcallerid'], required: false },
                { type: 'textarea', value: 'phone_number', title: 'Phone Number', task: ['createoutboundcallerid'], required: false },
                // create tag
                { type: 'textarea', value: 'tagname', title: 'Name', task: ['createtag'], required: false },
                // create company
                { type: 'textarea', value: 'companyname', title: 'Company Name', task: ['createcompany'], required: false }


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

        getcompanyaccountlist: function() {
            this.accountLoading = true;
            activePlatformId = this.fielddata.activePlatformId;

            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_get_account',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.accountlists = response.data
                this.accountLoading = false;
            });
        },

        get_companies: function(event) {
            this.fielddata.account_id = event.target.value;

            activePlatformId = this.fielddata.activePlatformId;



            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_companylist'
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.companylist = response.data
                this.accountLoading = false;
            });
        },

        getrole: function(event) {
            this.fielddata.company_id = event.target.value;

            this.data.role = {
                0: { 'id': 'admin', 'name': 'admin' },
                1: { 'id': 'reporting', 'name': 'reporting' },
                2: { 'id': 'manager', 'name': 'manager' }
            }
        },


        selectedrole: function(event) {
            this.fielddata.roleid = event.target.value;
        }


    },

    template: '#callrail-action-template'
});