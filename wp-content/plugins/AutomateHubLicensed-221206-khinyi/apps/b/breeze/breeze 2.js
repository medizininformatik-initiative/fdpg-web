Vue.component('breeze', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { workspaceList: [], currencylist: [] },
        workspaceLoading: false,
        fields: [

                // create customer
              {type: 'textarea', value: 'name', title: 'Project Name', task: ['createproject'], required: false},
              {type: 'textarea', value: 'description', title: 'Project Description', task: ['createproject'], required: false},
              {type: 'textarea', value: 'budget_amount', title: 'Budget Amount', task: ['createproject'], required: false},
              {type: 'textarea', value: 'budget_hours', title: 'Budget Hours', task: ['createproject'], required: false},
              {type: 'textarea', value: 'hourly_rate', title: 'Hourly Rate', task: ['createproject'], required: false},
              
            //   create workspace
              {type: 'textarea', value: 'workspacename', title: 'Workspace Name', task: ['createworkspace'], required: false},
              
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
        this.getcurrencylist();
        
    },
    
     
    getworkspaces: function() {
        this.workspaceLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_workspaces',
        }

        jQuery.post( ajaxurl, body, (response) => {
        this.data.workspaceList = response.data
        this.workspaceLoading = false;
        });
    },

    getcurrencylist: function () {
        this.data.currencylist = {
            0: {
                'id': 'USD',
                'name': 'USD'
            },
            1: {
                'id': 'GBP',
                'name': 'GBP'
            },
            2: {
                'id': 'JPY',
                'name': 'JPY'
            },
            3: {
                'id': 'CHF',
                'name': 'CHF'
            },
            4: {
                'id': 'EUR',
                'name': 'EUR'
            }
        };
    },

    selectedcurrency(event) {
        this.fielddata.currency = event.target.value;
    }

},

 template: '#breeze-action-template'
});