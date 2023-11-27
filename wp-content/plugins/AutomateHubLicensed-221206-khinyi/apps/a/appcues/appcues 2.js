Vue.component('appcues', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { sourceList: [] },
        fields: [
                // create segment
              {type: 'textarea', value: 'name', title: 'Name', task: ['createsegment'], required: false},
              {type: 'textarea', value: 'description', title: 'Description', task: ['createsegment'], required: false},
             
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

 template: '#appcues-action-template'
});