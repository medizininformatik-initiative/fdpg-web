Vue.component('sellsy', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        
        return {
            data: { typelist: [], statuslist: [], privacylist: [], labelList: [] },
            typeLoading: false,
            statusLoading: false,
            labelLoading: false,
            fields: [

                // create contact
                {type: 'textarea', value: 'civility', title: 'Civility', task: ['createcontact'], required: true},
                {type: 'textarea', value: 'first_name', title: 'First Name', task: ['createcontact'], required: true},
                {type: 'textarea', value: 'last_name', title: 'Last Name', task: ['createcontact'], required: true},
                {type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: true},
                {type: 'textarea', value: 'phone_number', title: 'Phone Number', task: ['createcontact'], required: true},
                {type: 'textarea', value: 'mobile_number', title: 'Mobile Number', task: ['createcontact'], required: true},
                {type: 'textarea', value: 'note', title: 'Note', task: ['createcontact'], required: true},
                
                // // create individual
                // {type: 'textarea', value: 'name', title: 'Company Name', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'semail', title: 'Email', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'website', title: 'Website', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'sphone_number', title: 'Phone Number', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'smobile_number', title: 'Mobile Number', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'fax_number', title: 'Fax Number', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'capital', title: 'Capital', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'reference', title: 'Reference', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'snote', title: 'Note', task: ['createindividual'], required: true},
                // {type: 'textarea', value: 'auxiliary_code', title: 'Auxiliary Code', task: ['createindividual'], required: true},

                // create task
                {type: 'textarea', value: 'title', title: 'Task Title', task: ['createtask'], required: true},
                {type: 'textarea', value: 'description', title: 'Task Description', task: ['createtask'], required: true},
                {type: 'textarea', value: 'due_date', title: 'Task Due Date', task: ['createtask'], required: true},
                
            ]
        }
    },
    methods: {

        CheckinDatabase:function(item,name){

            let not_match = true;

            let saved_item = '{{'+item+'}}';
            if(!(typeof fieldData == 'undefined')){
                 let fieldaa = this.fielddata;
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

        gettype:function(){
            this.getstatus();
            this.typeLoading = true;
            this.data.typelist = {
                0: {'id':'prospect', 'name':'prospect'},
                1: {'id':'client', 'name':'client'},
                2: {'id':'supplier', 'name':'supplier'}
            };
            this.typeLoading = false;
        },

        selectedtype(event){
            this.fielddata.type = event.target.value;
        },

        getstatus:function(){
            this.statusLoading = true;
            this.data.statuslist = {
                0: {'id':'todo', 'name':'todo'},
                1: {'id':'done', 'name':'done'}
            };
            this.statusLoading = false;
        },

        getlabel:function(event){
            this.fielddata.status = event.target.value;

            this.labelLoading = true;

            this.data.labelList = {
                0: {'id':'4717935', 'name':'Recall'},
                1: {'id':'4717936', 'name':'Lunch'},
                2: {'id':'4717937', 'name':'Meeting'},
                3: {'id':'4717938', 'name':'holidays'},
                4: {'id':'4717939', 'name':'Phone call'},
                5: {'id':'4717940', 'name':'Shift'},
                6: {'id':'4717941', 'name':'Follow-up by email/phone'},
                7: {'id':'4717942', 'name':'To Program'},
                8: {'id':'4717943', 'name':'None'}
            };
            this.labelLoading = false;
        },

        selectedlabel(event){
            this.fielddata.label = event.target.value;

        }

    },
    template: '#sellsy-action-template'
});