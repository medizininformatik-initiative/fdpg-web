Vue.component('hubspot', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'phone', title: 'Phone Number', task: ['add_contact'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['add_contact'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['add_contact'], required: false},
            ]

        }
    },
    methods: {

           CheckinDatabase:function(item,name){

            var not_match = true;

            var saved_item = '{{'+item+'}}';
            if(!(typeof fieldData == 'undefined')){
                 var fieldaa = this.fielddata;
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
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#hubspot-action-template'
});