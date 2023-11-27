Vue.component('baremetrics', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { sourceList: [], customerlist: [], planList: [] },
        fields: [
                // create customer
              {type: 'textarea', value: 'name', title: 'Name', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createcustomer'], required: false},
              {type: 'textarea', value: 'notes', title: 'Notes', task: ['createcustomer'], required: false},
              
              
              // create plan
              {type: 'textarea', value: 'planname', title: 'Plan Name', task: ['createplan'], required: true},
              {type: 'textarea', value: 'interval', title: 'Interval', task: ['createplan'], required: true},
              {type: 'textarea', value: 'currency', title: 'Currency', task: ['createplan'], required: true},
              {type: 'textarea', value: 'amount', title: 'Amount', task: ['createplan'], required: true},
              {type: 'textarea', value: 'interval_count', title: 'Interval Count', task: ['createplan'], required: true},

              
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

    selectedcustomer: function(event){
        this.fielddata.customeroid = event.target.value;
        
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

    getCustomer: function(event){
        this.fielddata.planid = event.target.value;

        activePlatformId=this.fielddata.activePlatformId;

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_customers',
        'sourceid': this.fielddata.sourceid
        }

        jQuery.post( ajaxurl, body, (response) => {
        this.data.customerlist = response.data
        this.accountLoading = false;
        });
    },

    getPlanList: function(event){
        this.fielddata.sourceid = event.target.value;

        activePlatformId=this.fielddata.activePlatformId;

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_planlist',
        'sourceid': this.fielddata.sourceid
        }

        jQuery.post( ajaxurl, body, (response) => {
        this.data.planList = response.data
        this.accountLoading = false;
        });
    }
},

 template: '#baremetrics-action-template'
});