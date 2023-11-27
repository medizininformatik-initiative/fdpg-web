Vue.component('mailchimppro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
            ]

        }
    },
    methods: {
        getFields: function() {
            var that = this;
            this.listLoading = true;
            this.fields = [];

            var listData = {
                'action': 'awp_get_mailchimppro_mergefields',
                '_nonce': awp.nonce,
                'listId': this.fielddata.listId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {

                that.fields.push({ value: 'email', title: 'Email', task: ['subscribe'], required: true } );

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push({ value: single.key, title: single.value, task: ['subscribe'], required: false } );
                        });
                    }
                }

                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.tags == 'undefined') {
            this.fielddata.tags = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_mailchimp_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        if( this.fielddata.listId ) {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'awp_get_mailchimppro_mergefields',
                '_nonce': awp.nonce,
                'listId': this.fielddata.listId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {

                that.fields.push({ value: 'email', title: 'Email', task: ['subscribe'], required: true } );

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push({ value: single.key, title: single.value, task: ['subscribe'], required: false } );
                        });
                    }
                }

                that.listLoading = false;
            });
        }
    },
    template: '#mailchimppro-action-template'
});