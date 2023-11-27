Vue.component('onehash', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { sourceList: [], customerlist: [], planList: [] },
        fields: [

                // create customer
              {type: 'textarea', value: 'cfirst_name', title: 'First Name', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'clast_name', title: 'Last Name', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'customer_primary_contact', title: 'Customer Primary Contact Name', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'mobile_no', title: 'Mobile Number', task: ['createcustomer'], required: false},
              
                // create contact
              {type: 'textarea', value: 'ccfirst_name', title: 'First Name', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'ccemail', title: 'Last Name', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'ccmobile_no', title: 'Mobile Number', task: ['createcontact'], required: false},
              
              
              // create lead
              {type: 'textarea', value: 'first_name', title: 'First Name', task: ['createlead'], required: true},
              {type: 'textarea', value: 'last_name', title: 'Last Name', task: ['createlead'], required: true},
              {type: 'textarea', value: 'company_name', title: 'Company Name', task: ['createlead'], required: true},
              {type: 'textarea', value: 'email_id', title: 'Email', task: ['createlead'], required: true},
              {type: 'textarea', value: 'phone', title: 'Phone', task: ['createlead'], required: true},
              {type: 'textarea', value: 'country', title: 'Country', task: ['createlead'], required: true},

              
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


    selectedsource: function(event){
        this.fielddata.sourceid = event.target.value;
        
    },
     
    getsourcesid: function() {
        this.accountLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_source',
        }

        jQuery.post( ajaxurl, body, (response) => {
        this.data.sourceList = response.data
        this.accountLoading = false;
        });
    },

},

 template: '#onehash-action-template'
});