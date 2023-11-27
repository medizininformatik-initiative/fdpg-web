Vue.component('elasticemailpro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {value: 'customFields', title: 'Custom Fields', task: ['subscribe'], required: false, description: 'Use key:value format. Example company:Google. For multiple custom fields use comma to separate. Example company:Google,city:London,phone:3334445555. Don\'t use any space.  You can use form fields as value.'}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

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

        if (typeof this.fielddata.customFields == 'undefined') {
            this.fielddata.customFields = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_elasticemail_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#elasticemailpro-action-template'
});