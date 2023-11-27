Vue.component('samdock', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { oganizationList: []},
        oganizationLoading: false,
        fields: [
                // create contact
              {type: 'textarea', value: 'firstName', title: 'First Name', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'lastName', title: 'Last Name', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'phoneNumber', title: 'Phone Number', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'country', title: 'Country Code (e.g US, UK, NG)', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'city', title: 'City', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'street', title: 'Street Name', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'number', title: 'Street Number', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'postcode', title: 'Post Code', task: ['createcontact'], required: false},
            
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


    selectedoganization: function(event){
        var retval = event.target.value;
        var new_data = retval.split('+');
        this.fielddata.tenantid = new_data[0];
        this.fielddata.organizationid = new_data[1];
    },
    
     
    getorganizationdetail: function() {
        this.oganizationLoading = true;
        activePlatformId=this.fielddata.activePlatformId;

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_organization_list',
        }

        jQuery.post( ajaxurl, body, (response) => {
            this.data.oganizationList = response.data
            this.oganizationLoading = false;
        });
    }
   
},

 template: '#samdock-action-template'
});