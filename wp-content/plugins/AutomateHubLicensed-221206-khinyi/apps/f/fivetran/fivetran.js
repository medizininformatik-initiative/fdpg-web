Vue.component('fivetran', {
    props: ["trigger", "action", "fielddata"],

    data: function() {
        return {
            roleLoading: false,
            data: { rolelist: [] },
            fields: [
                // ivite user
                { type: 'textarea', value: 'family_name', title: 'First Name', task: ['createuser'], required: false },
                { type: 'textarea', value: 'given_name', title: 'Last Name', task: ['createuser'], required: false },
                { type: 'textarea', value: 'email', title: 'Email', task: ['createuser'], required: false },
                { type: 'textarea', value: 'phone', title: 'Phone', task: ['createuser'], required: false },
                // create team
                { type: 'textarea', value: 'name', title: 'Team Name', task: ['createteam'], required: true },
                { type: 'textarea', value: 'description', title: 'Description', task: ['createteam'], required: true },
                // create group
                { type: 'textarea', value: 'group_name', title: 'Group Name', task: ['creategroup'], required: true }


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

        selectedrole: function(event) {
            this.fielddata.role_id = event.target.value;
        },

        getrole: function() {
            this.roleLoading = true;
            activePlatformId = this.fielddata.activePlatformId;


            const body = {
                '_nonce': awp.nonce,
                'platformid': activePlatformId,
                'action': 'awp_fetch_role',
            }

            jQuery.post(ajaxurl, body, (response) => {
                this.data.rolelist = response.data
                this.roleLoading = false;
            });
        }
    },

    template: '#fivetran-action-template'
});