Vue.component('helpwise', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { mailboxesList: []},
        mailboxloding: false,
        fields: [

                // create mailbox
              {type: 'textarea', value: 'email', title: 'Email', task: ['createmailbox'], required: false},
              {type: 'textarea', value: 'displayName', title: 'Display Name', task: ['createmailbox'], required: false},
             
              // create contact
              {type: 'textarea', value: 'firstname', title: 'First Name', task: ['createcontact'], required: true},
              {type: 'textarea', value: 'lastname', title: 'Last Name', task: ['createcontact'], required: true},
              {type: 'textarea', value: 'companyName', title: 'Company Name', task: ['createcontact'], required: true},
              {type: 'textarea', value: 'email_id', title: 'Email', task: ['createcontact'], required: true},
              {type: 'textarea', value: 'phone', title: 'Phone', task: ['createcontact'], required: true},
              {type: 'textarea', value: 'jobTitle', title: 'Job Title', task: ['createcontact'], required: true},
              
              // send email
              {type: 'textarea', value: 'eto', title: 'To', task: ['sendemail'], required: true},
              {type: 'textarea', value: 'subject', title: 'Subject', task: ['sendemail'], required: true},
              {type: 'textarea', value: 'emailbody', title: 'Message Body', task: ['sendemail'], required: true},
              
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


    selectedMailbox: function(event){
        this.fielddata.mailbox_id = event.target.value;
        
    },
     
    getmailid: function() {
        this.mailboxloding = true;
        activePlatformId=this.fielddata.activePlatformId;
        

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_mailboxes_list',
        }

        jQuery.post( ajaxurl, body, (response) => {
        this.data.mailboxesList = response.data
        this.mailboxloding = false;
        });
    },

},

 template: '#helpwise-action-template'
});