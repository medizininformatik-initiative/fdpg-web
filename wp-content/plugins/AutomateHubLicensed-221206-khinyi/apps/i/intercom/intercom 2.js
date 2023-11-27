Vue.component('intercom', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        
        return {
            data: { rolelist: [] },
            listLoading: false,
            fields: [
                {type: 'textarea', value: 'name', title: 'Name', task: ['createcontact'], required: true},
                {type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: true},
                {type: 'textarea', value: 'phone', title: 'Phone', task: ['createcontact'], required: true},
                
                {type: 'textarea', value: 'companyname', title: 'Name', task: ['createcompany'], required: true},
                {type: 'textarea', value: 'plan', title: 'Plan', task: ['createcompany'], required: true},
  
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

        getrole:function(){
            
            this.data.rolelist = {
                0: {'id':'lead', 'name':'lead'},
                1: {'id':'user', 'name':'user'}
            };
            
        },

        selectedrole(event){
            this.fielddata.role = event.target.value;

        }

    },
    template: '#intercom-action-template'
});