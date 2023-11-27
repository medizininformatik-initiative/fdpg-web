Vue.component('liveagent', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { companyList: []},
        fields: [
                // create customer

              {type: 'textarea', value: 'firstname', title: 'First Name', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'lastname', title: 'Last Name', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'city', title: 'City', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'phone', title: 'Phone', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'job_position', title: 'Job Description', task: ['createcustomer'], required: false},
              
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


    selectedCompany: function(event){
        this.fielddata.companyList = event.target.value;
        
    },
    
     
    getcompany: function() {
        this.companyLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_company_list',
        }

        jQuery.post( ajaxurl, body, (response) => {
            this.data.companyList = response.data
            this.companyLoading = false;
        });
    }
},

 template: '#liveagent-action-template'
});