Vue.component('salesmate', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { activitytype: [], companylist: []},
        fields: [
                // Add Activity
              {type: 'textarea', value: 'title', title: 'Title', task: ['createactivity'], required: false},
              {type: 'textarea', value: 'description', title: 'Description', task: ['createactivity'], required: false},
              
              // create company
              {type: 'textarea', value: 'name', title: 'Company Name', task: ['createcompany'], required: true},
              {type: 'textarea', value: 'phone', title: 'Phone', task: ['createcompany'], required: true},
              {type: 'textarea', value: 'description', title: 'description', task: ['createcompany'], required: false},
              
              
              {type: 'textarea', value: 'firstName', title: 'First Name', task: ['createcontact'], required: true},
              {type: 'textarea', value: 'lastName', title: 'Last Name', task: ['createcontact'], required: true},
              {type: 'textarea', value: 'mobile', title: 'Mobile Number', task: ['createcontact'], required: true},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: true},

              
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


    selectedactivity: function(event){
        this.fielddata.activitytypeselected = event.target.value;
    },
  
    
     
    getactivitytype: function() {
        this.accountLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_activitytype',
        }

        jQuery.post( ajaxurl, body, (response) => {
        this.data.activitytype = response.data
        this.accountLoading = false;
        });
    }

   
},

mounted() {
    this.fielddata.activitytypeselected = "";
},

 template: '#salesmate-action-template'
});