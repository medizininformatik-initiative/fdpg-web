Vue.component('constantcontact', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
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

        this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_constantcontact_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#constantcontact-action-template'
});