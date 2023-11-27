Vue.component('todoist', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { projectlist: []},
        fields: [
                // create project
              {type: 'textarea', value: 'name', title: 'Name', task: ['createproject'], required: false},
              
              // create task
              {type: 'textarea', value: 'content', title: 'Task Content', task: ['createtask'], required: true},
              {type: 'textarea', value: 'description', title: 'Description', task: ['createtask'], required: true},
              
              // create session
              {type: 'textarea', value: 'name', title: 'Session Name', task: ['createsession'], required: true}
              

              
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

    selectedproject: function(event){
        this.fielddata.projectid = event.target.value;
        
    },


    getprojectlist: function() {
        this.accountLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_projectlist',
        }

        jQuery.post( ajaxurl, body, (response) => {
        this.data.projectlist = response.data
        this.accountLoading = false;
        });
    },

},

 template: '#todoist-action-template'
});