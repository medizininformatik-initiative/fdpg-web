Vue.component('mojohelpdesk', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { priorityList: [], statusList: [] },
        priorityLoading: false,
        statusLoading: false,
        fields: [
                // create ticket
              {type: 'textarea', value: 'title', title: 'Title', task: ['createticket'], required: false},
              {type: 'textarea', value: 'description', title: 'Description', task: ['createticket'], required: false},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createticket'], required: false},
             
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


    selectedstatus: function(event){
        this.fielddata.status_id = event.target.value;
    },

    getstatus: function(event) {
        if(event.target.value.length < 1){
            this.statusLoading = false;
            return;
        } 
        this.fielddata.priority_id = event.target.value;
        this.statusLoading = true;
        this.data.statusList = {
            0: {
                'id': '10',
                'name': 'New'
            },
            1: {
                'id': '20',
                'name': 'In Progress'
            },
            2: {
                'id': '30',
                'name': 'On Hold'
            },
            3: {
                'id': '40',
                'name': 'Information Requested'
            },
            4: {
                'id': '50',
                'name': 'Solved'
            },
            5: {
                'id': '60',
                'name': 'Closed'
            }
        };
        this.statusLoading = false;
    },
     
    getpriority: function() {
        this.priorityLoading = true;
        this.data.priorityList = {
            0: {
                'id': '10',
                'name': 'Emergency'
            },
            1: {
                'id': '20',
                'name': 'Urgent'
            },
            2: {
                'id': '30',
                'name': 'Normal'
            },
            3: {
                'id': '40',
                'name': 'Low'
            }
        };
        this.priorityLoading = false;
    }

},

 template: '#mojohelpdesk-action-template'
});