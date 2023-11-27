Vue.component('drift', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false},
            ]
        }
    },
    methods: {

        CheckinDatabase:function(item,name){

            let not_match = true;

            let saved_item = '{{'+item+'}}';
            if(!(typeof fieldData == 'undefined')){
                 let fieldaa = this.fielddata;
                 for(i in fieldaa){

                    if(fieldaa[i]){

                        if(i=='answerQ1' || i=='answerQ2' || i=='answerQ3'){

                            if(fieldaa[i].length==2){
                                
                                if(fieldaa[i][1]==item){
                                    not_match = false;
                                }
                            }

                        }else{

                            if(fieldaa[i] &&  (typeof fieldaa[i]==='string')  ){

                                if(fieldaa[i].includes(saved_item)){
                                    not_match = false;
                                }
                            }
                        }

                    }
                 }
                
            }

            return not_match;
        },

        getDriftList:function(){
            this.listLoading = true;

            let listRequestData = {
                'action': 'awp_get_drift_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                this.fielddata.list = response.data;
                this.listLoading = false;
            });
        }

    },
    mounted: function() {

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

        if (typeof this.fielddata.phoneNumber == 'undefined') {
            this.fielddata.phoneNumber = '';
        }
      
        this.listLoading = true;

        let listRequestData = {
            'action': 'awp_get_drift_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            this.fielddata.list = response.data;
            this.listLoading = false;
        });
    },
    template: '#drift-action-template'
});
