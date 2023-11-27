Vue.component('sendinbluepro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true}
            ]

        }
    },
    methods: {
        getSendinblueListPro:function(){
            var that = this;

            this.listLoading = true;

            var attRequestData = {
                'action': 'awp_get_sendinbluepro_attributes',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, attRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push({ value: single.key, title: single.value, task: ['subscribe'], required: false } );
                        });
                    }
                }
            });

            var listRequestData = {
                'action': 'awp_get_sendinbluepro_list',
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    mounted: function() {
        
    },
    template: '#sendinbluepro-action-template'
});