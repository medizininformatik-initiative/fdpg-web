Vue.component('clockify', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { workspaceList: [], colorlist: [], projectList: [] },
        workspaceLoading: false,
        colorLoading: false,
        projectLoading: false,
        fields: [
                // create client
              {type: 'textarea', value: 'name', title: 'Client Name', task: ['createclient'], required: false},
              {type: 'textarea', value: 'note', title: 'Note', task: ['createclient'], required: false},
              {type: 'textarea', value: 'address', title: 'Address', task: ['createclient'], required: false},
              
              // create tag
              {type: 'textarea', value: 'tagname', title: 'Tag Name', task: ['createtag'], required: true},
              
              // create project
              {type: 'textarea', value: 'projectname', title: 'Project Name', task: ['createproject'], required: true},
              {type: 'textarea', value: 'projectnote', title: 'Note', task: ['createproject'], required: true},
              
              // create task
              {type: 'textarea', value: 'taskname', title: 'Task Name', task: ['createtask'], required: true}
            
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


    selectedworkspace: function(event){
        this.fielddata.workspaceid = event.target.value;
        
    },
    
     
    getproject: function(event) {
        if(event.target.value.length < 1){
            this.projectLoading = false;
            return;
        } 
        this.projectLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        this.fielddata.workspaceid = event.target.value;

        const body =  {
            '_nonce': awp.nonce,
            'platformid': activePlatformId,
            'action': 'awp_fetch_project',
            'workspaceid': this.fielddata.workspaceid
        }

        jQuery.post( ajaxurl, body, (response) => {
            this.data.projectList = response.data
            this.projectLoading = false;
        });
    },

    getworkspace: function() {
        this.workspaceLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        

        const body =  {
            '_nonce': awp.nonce,
            'platformid': activePlatformId,
            'action': 'awp_fetch_workspace',
        }

        jQuery.post( ajaxurl, body, (response) => {
            this.data.workspaceList = response.data
            this.workspaceLoading = false;
        });
    },

    getcolor: function(event){
        if(event.target.value.length < 1){
            this.colorLoading = false;
            return;
          } 
        this.colorLoading = true;
        this.fielddata.workspaceid = event.target.value;
        this.data.colorlist = {
            0: {
                'id': '#008000',
                'name': 'Green'
            },
            1: {
                'id': '#800080',
                'name': 'Purple'
            },
            2: {
                'id': '#FF0000',
                'name': 'Red'
            },
            3: {
                'id': '#000000',
                'name': 'Black'
            }
        };
        this.colorLoading = false;
    },

    selectedcolor: function(event){
        this.fielddata.color = event.target.value; 
    },

    selectedproject: function(event){
        this.fielddata.projectid = event.target.value;
    },


},

 template: '#clockify-action-template'
});