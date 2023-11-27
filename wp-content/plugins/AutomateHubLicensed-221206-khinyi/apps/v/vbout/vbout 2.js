Vue.component('vbout', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        campaignLoading: false,
        listsLoading: false,
        data: { campaignList: [], contactList: []},
        fields: [
            
                // Add Campaign
              {type: 'textarea', value: 'name', title: 'Name', task: ['addcampaign'], required: false},
              {type: 'textarea', value: 'Subject', title: 'Subject', task: ['addcampaign'], required: false},
              {type: 'textarea', value: 'fromemail', title: 'From Email', task: ['addcampaign'], required: false},
              {type: 'textarea', value: 'from_name', title: 'From Name', task: ['addcampaign'], required: false},
              {type: 'textarea', value: 'reply_to', title: 'Reply To', task: ['addcampaign'], required: false},
              {type: 'textarea', value: 'body', title: 'Campaign Body', task: ['addcampaign'], required: false},

              // Add Contact
              {type: 'textarea', value: 'firstname', title: 'First Name', task: ['addcontact'], required: false},
              {type: 'textarea', value: 'lastname', title: 'Last Name', task: ['addcontact'], required: false},
              {type: 'textarea', value: 'cemail', title: 'Email', task: ['addcontact'], required: false},
              {type: 'textarea', value: 'state', title: 'State', task: ['addcontact'], required: false},
              {type: 'textarea', value: 'city', title: 'City', task: ['addcontact'], required: false},
              {type: 'textarea', value: 'address', title: 'Address', task: ['addcontact'], required: false},
              {type: 'textarea', value: 'phonenumber', title: 'Phone', task: ['addcontact'], required: false},
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


    selectedtype: function(event){
        this.fielddata.campaigntype = event.target.value;
        
    },

    selectedList: function(event){
        this.fielddata.listid = event.target.value;
    },

    getstatus: function() {
        this.statusLoading = true;
        this.data.statusList = {
            0: {
                'id': 'active',
                'name': 'Active'
            },
            1: {
                'id': 'disactive',
                'name': 'Disactive'
            }
            
        };
        this.statusLoading = false;
    },
     
    gettype: function() {
        this.campaignLoading = true;
        this.data.campaignList = {
            0: {
                'id': 'standard',
                'name': 'Standard'
            },
            1: {
                'id': 'automated',
                'name': 'Automated'
            }
            
        };
        this.getstatus();
        this.campaignLoading = false;
    },

    getContactList: function(event){
        if(event.target.value.length < 1){
            this.listsLoading = false;
            return;
        } 
        this.fielddata.statusid = event.target.value;
        this.listsLoading = true;

        activePlatformId=this.fielddata.activePlatformId;

        const body =  {
            '_nonce': awp.nonce,
            'platformid': activePlatformId,
            'action': 'awp_fetch_list',
        }

        jQuery.post( ajaxurl, body, (response) => {
            this.data.contactList = response.data
            this.listsLoading = false;
        });
    }

},

 template: '#vbout-action-template'
});