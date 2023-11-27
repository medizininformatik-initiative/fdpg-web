Vue.component('easysendy', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'fname', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lname', title: 'Last Name', task: ['subscribe'], required: false},
                {value: 'title', title: 'Title', task: ['create_list'], required: true},
                {value: 'description', title: 'Description', task: ['create_list'], required: false}
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

        getEasySendyList:function(){
            let that = this;
            this.listLoading = true;
            let listRequestData = {
                'action': 'awp_get_easysendy_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    mounted: function() {
        let that = this;

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
    template: '#easysendy-action-template'
});
