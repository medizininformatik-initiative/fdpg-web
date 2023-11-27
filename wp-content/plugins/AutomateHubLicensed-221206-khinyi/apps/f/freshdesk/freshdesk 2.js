Vue.component('freshdesk', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { companyList: []},
        fields: [
            
            // create contact

              {type: 'textarea', value: 'name', title: 'Name', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'phone', title: 'Phone', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'address', title: 'Address', task: ['createcontact'], required: false},
              
          ]

      }
  },



methods: {

    CheckinDatabase:function(item, name){
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

 template: '#freshdesk-action-template'
});