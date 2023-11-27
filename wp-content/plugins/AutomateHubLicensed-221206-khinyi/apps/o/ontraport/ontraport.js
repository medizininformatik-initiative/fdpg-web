Vue.component('ontraport', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            data: {
                groupList: []
            },
            fields: [{
                    value: 'email',
                    title: 'Email',
                    task: ['subscribe'],
                    required: true
                },
                {
                    value: 'name',
                    title: 'Name',
                    task: ['subscribe'],
                    required: false
                }
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

        getOntraportList: function (event) {
            this.data.groupList = []
            this.listLoading = true;
            this.fielddata.accountId = event.target.value;
            activePlatformId = this.fielddata.activePlatformId;

            let body = {
                '_nonce': awp.nonce,
                'action': 'awp_get_ontraport_list',
                'platformid': activePlatformId,
                accountId: this.fielddata.accountId
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.groupList = response.data;
                this.listLoading = false;
            });
        },

    },
    mounted: function () {

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
    template: '#ontraport-action-template'
});
