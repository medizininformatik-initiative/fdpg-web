Vue.component('livestorm', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { eventtypelist: [], sessionlist: [], chatstatuslist: [], recordstatuslist: [] },
        
        chartLoading: false,
        eventLoading: false,
        sessionLoading: false,
        recordingLoading: false,

        fields: [
                // create event
              {type: 'textarea', value: 'title', title: 'Title', task: ['createevent'], required: false},
              {type: 'textarea', value: 'description', title: 'Description', task: ['createevent'], required: false}
              
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


    selectedrecordingstatus: function(event){
        this.fielddata.recordingstatus = event.target.value;
        
    },
    
     
    getlivestormeventtype: function() {
        this.eventLoading = true;
        activePlatformId=this.fielddata.activePlatformId;

        this.data.eventtypelist = {
            0: {
                'id': 'draft',
                'name': 'Draft'
            },
            1: {
                'id': 'published',
                'name': 'Published'
            }
        };

        this.eventLoading = false;
    },

    getchartstatus: function(event) {
        if(event.target.value.length < 1){
            this.chartLoading = false;
            return;
        }
        this.chartLoading = true;
        this.fielddata.eventtype = event.target.value;

       
        activePlatformId=this.fielddata.activePlatformId;
        
        this.data.chatstatuslist = {
            0: {
                'id': 'true',
                'name': 'True'
            },
            1: {
                'id': 'false',
                'name': 'False'
            }
        };

        this.chartLoading = false;
    },

    getrecordingfeature: function(event) {
        if(event.target.value.length < 1){
            this.recordingLoading = false;
            return;
        }
        this.recordingLoading = true;
        this.fielddata.chartstatus = event.target.value;

       
        activePlatformId=this.fielddata.activePlatformId;
        
        this.data.recordstatuslist = {
            0: {
                'id': 'true',
                'name': 'True'
            },
            1: {
                'id': 'false',
                'name': 'False'
            }
        };

        this.recordingLoading = false;
    }
    
},

 template: '#livestorm-action-template'
});