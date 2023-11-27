Vue.component('trustpilot', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'templateId', title: 'Template ID', task: ['create_invitation'], required: false},
                {value: 'referenceId', title: 'Reference ID', task: ['create_invitation'], required: false},
                {value: 'name', title: 'Name', task: ['create_invitation'], required: false},
                {value: 'locale', title: 'Locale', task: ['create_invitation'], required: false},
                {value: 'redirectUri', title: 'Redirect URI', task: ['create_invitation'], required: false},
                {value: 'locationId', title: 'Location ID', task: ['create_invitation'], required: false},
                {value: 'email', title: 'Email', task: ['create_invitation'], required: false},

            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.referenceId == 'undefined') {
            this.fielddata.referenceId = '';
        }

        if (typeof this.fielddata.name == 'undefined') {
            this.fielddata.name = '';
        }

        if (typeof this.fielddata.locale == 'undefined') {
            this.fielddata.locale = '';
        }

        if (typeof this.fielddata.redirectUri == 'undefined') {
            this.fielddata.redirectUri = '';
        }

        if (typeof this.fielddata.locationId == 'undefined') {
            this.fielddata.locationId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }
    },
    template: '#trustpilot-action-template'
});