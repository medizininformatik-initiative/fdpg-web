Vue.component('trigger', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { statuslist: [], currencyList: [], projectStatusList: [], billableTypeList: [], companylist: [] },
        statusLoading: false,
        currencyLoading: false,
        projectlistLoading: false,
        billableLoading: false,
        fields: [
                // create company
                {type: 'textarea', value: 'name', title: 'Name', task: ['createcompany'], required: false},
                {type: 'textarea', value: 'address1', title: 'Address', task: ['createcompany'], required: false},
                {type: 'textarea', value: 'city', title: 'City', task: ['createcompany'], required: false},
                {type: 'textarea', value: 'postcode', title: 'Postcode', task: ['createcompany'], required: false},
                {type: 'textarea', value: 'state', title: 'State', task: ['createcompany'], required: false},
                {type: 'textarea', value: 'phone', title: 'Phone', task: ['createcompany'], required: false},
                {type: 'textarea', value: 'country', title: 'Country', task: ['createcompany'], required: false},
                
              
              
              // create project
              {type: 'textarea', value: 'projectname', title: 'Project Name', task: ['trigger_createproject'], required: true},
              {type: 'textarea', value: 'description', title: 'Project Description', task: ['trigger_createproject'], required: true},
              {type: 'textarea', value: 'due_date', title: 'Due Date', task: ['trigger_createproject'], required: true},
             

              
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


    selectedCurrency: function(event){
        this.fielddata.due_date = event.target.value;   
    },

    selectedCompany: function(event){
          
    },

    selectedStatus: function(event){
        this.fielddata.status = event.target.value;
        
    },

    selectedBillabletype: function(event){
        this.fielddata.billable_type = event.target.value;
    },
    
     
    getOthers: function() {
       
        this.getStatus();
        this.getCurrency();
        this.getProjectStatus();
    },

    getCurrency: function () {
        this.data.currencyList = {
            0: {
                'id': '$',
                'name': '$'
            },
            1: {
                'id': '£',
                'name': '£'
            },
            2: {
                'id': '¥',
                'name': '¥'
            },
            3: {
                'id': 'CHF',
                'name': 'CHF'
            },
            4: {
                'id': '€R',
                'name': '€R'
            },
            5: {
                'id': 'R$',
                'name': 'R$'
            }
        };
    },

    getStatus: function(){
        this.data.statuslist = {
            0: {
                'id': 'lead',
                'name': 'lead'
            },
            1: {
                'id': 'open',
                'name': 'open'
            },
            2: {
                'id': 'archive',
                'name': 'archive'
            }
        };
    },

    getProjectStatus: function(){
        
        this.data.projectStatusList = {
            0: {
                'id': 'opportunity',
                'name': 'Opportunity'
            },
            1: {
                'id': 'open',
                'name': 'Open'
            },
            2: {
                'id': 'on_hold',
                'name': 'On hold'
            },
            3: {
                'id': 'closed',
                'name': 'Closed'
            }
        };
    },

    getBillableType: function(event){
        if(event.target.value.length < 1){
            this.billableLoading = false;
            return;
          } 
          this.billableLoading = true;
        this.fielddata.company_id = event.target.value; 
        this.data.billableTypeList = {
            0: {
                'id': 'Hourly',
                'name': 'Hourly'
            },
            1: {
                'id': 'Fixed',
                'name': 'Fixed'
            }
        };
        this.billableLoading = false;
    },

    getcompanylist: function(event) {
        if(event.target.value.length < 1){
            this.projectlistLoading = false;
            return;
          } 
        this.fielddata.projectstatus = event.target.value;
        this.projectlistLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        
        const body =  {
            '_nonce': awp.nonce,
            'platformid': activePlatformId,
            'action': 'awp_trigger_fetch_companylist',
        }

        jQuery.post( ajaxurl, body, (response) => {
            this.data.companylist = response.data
            this.projectlistLoading = false;
        });
    },
},

 template: '#trigger-action-template'
});
