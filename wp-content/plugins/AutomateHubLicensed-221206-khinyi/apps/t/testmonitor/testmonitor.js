Vue.component('testmonitor', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { sourceList: [] },
        fields: [
                // create project
              {type: 'textarea', value: 'name', title: 'Name', task: ['createproject'], required: false},
              {type: 'textarea', value: 'description', title: 'Description', task: ['createproject'], required: false},
              {type: 'textarea', value: 'starts_at', title: 'Start Date', task: ['createproject'], required: false},
              {type: 'textarea', value: 'ends_at', title: 'End Date', task: ['createproject'], required: false},
              
                //   create team
              {type: 'textarea', value: 'tname', title: 'Name', task: ['createteam'], required: false},
              {type: 'textarea', value: 'tdescription', title: 'Description', task: ['createteam'], required: false},
              
                //   create environment
              {type: 'textarea', value: 'envname', title: 'Name', task: ['createenvironment'], required: false},
              {type: 'textarea', value: 'envdescription', title: 'Description', task: ['createenvironment'], required: false},

                //   create application
              {type: 'textarea', value: 'appname', title: 'Name', task: ['createapplication'], required: false},
              {type: 'textarea', value: 'appdescription', title: 'Description', task: ['createapplication'], required: false},
              

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
    }

},

 template: '#testmonitor-action-template'
});