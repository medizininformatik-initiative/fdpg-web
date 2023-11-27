Vue.component('teamwork', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { sourceList: [] },
        fields: [
            // create project
            {type: 'textarea', value: 'name', title: 'Name', task: ['createproject'], required: true},
            {type: 'textarea', value: 'description', title: 'Description', task: ['createproject'], required: true},
            {type: 'textarea', value: 'startdate', title: 'Start Date', task: ['createproject'], required: true},
            {type: 'textarea', value: 'enddate', title: 'End Date', task: ['createproject'], required: true},
            
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
    },

},

 template: '#teamwork-action-template'
});