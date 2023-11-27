Vue.component('sendypro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true, description: ''},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false, description: ''},
                {value: 'country', title: 'Country', task: ['subscribe'], required: false, description: ''},
                {value: 'ipaddress', title: 'IP Address', task: ['subscribe'], required: false, description: ''},
                {value: 'referrer', title: 'Referrer', task: ['subscribe'], required: false, description: ''},
                {value: 'custom_fields', title: 'Custom Fields', task: ['subscribe'], required: false, description: 'Use key:value format. Example Birthday:2000-12-12. For multiple custom fields use comma to separate. Example Birthday:2000-12-12,City:London,Profession:Teacher. Don\'t use any space.  You can use form fields as value.'}
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

        if (typeof this.fielddata.name == 'undefined') {
            this.fielddata.name = '';
        }
    },
    template: '#sendypro-action-template'
});