Vue.component('basecamp3', {
  props: ["trigger", "action", "fielddata"],
  
  data: function () {
      return {
        accountLoading: false,
        campfireLoading: false,
        data: { accountsList: [], campfiresList : [], projectsList : [], todoset : [], assigneelist: []},
        fields: [
              {type: 'textarea', value: 'name', title: 'Name', task: ['sendmsg'], required: false},
              {type: 'textarea', value: 'message', title: 'Message', task: ['sendmsg'], required: false},
              {type: 'textarea', value: 'Title', title: 'Title', task: ['addtodo'], required: false},
              {type: 'textarea', value: 'DueOn', title: 'Due Date', task: ['addtodo'], required: false},
              {type: 'textarea', value: 'Notes', title: 'Notes', task: ['addtodo'], required: true}
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

    onselect(event){
      this.fielddata.campfireUrl = event.target.value
    },
    
    selectedassignee(event){
      this.fielddata.AssignedTo = event.target.value
    },

    getAccountsList: function() {
        this.accountLoading = true;
        activePlatformId=this.fielddata.activePlatformId;

        const body =  {
          '_nonce': awp.nonce,
          'platformid': activePlatformId,
          'action': 'awp_fetch_accounts',
        }

        jQuery.post( ajaxurl, body, (response) => {
          this.data.accountsList = response.data
          this.accountLoading = false;
        });
    },

    getCampfiresList: function(event) {
      this.data.campfiresList = []
      this.campfireLoading = true;
      this.fielddata.accountId = event.target.value;
      activePlatformId=this.fielddata.activePlatformId;
      
      const body =  {
        '_nonce': awp.nonce,
        'action': 'awp_fetch_account_campfires',
        'platformid': activePlatformId,
        accountId: this.fielddata.accountId 
      }

      jQuery.post( ajaxurl, body, (response) => {
        this.data.campfiresList = response.data;
        console.log(this.data.campfiresList)
        this.campfireLoading = false;
      });
    },

    getProjects: function(event) {
      if(event.target.value.length < 1){
        this.loading = false;
        return;
      } 
      this.data.projectsList = []
      this.accountLoading = true;
      activePlatformId=this.fielddata.activePlatformId;
      this.fielddata.accountId = event.target.value;

      const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_projects',
        accountId: this.fielddata.accountId
      }

      jQuery.post( ajaxurl, body, (response) => {
        this.data.projectsList = response.data
        this.accountLoading = false;
      });
    },

    getTodoSet: function(event) {
      if(event.target.value.length < 1){
        this.loading = false;
        return;
      } 
      this.data.todoset = []
      this.accountLoading = true;
      activePlatformId=this.fielddata.activePlatformId;
      this.fielddata.projectid =  event.target.value;
      

      const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_todoset',
        'projectid': this.fielddata.projectid,
        accountId: this.fielddata.accountId
      }

      jQuery.post( ajaxurl, body, (response) => {
        this.data.todoset = response.data
        
        this.accountLoading = false;
      });
    },

    
    getAssigneeList: function(event) {
      
      this.data.assigneelist = []
      this.accountLoading = true;
      activePlatformId=this.fielddata.activePlatformId;
      this.fielddata.todoset = event.target.value;

      const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_peoples_list',
        accountId: this.fielddata.accountId,
        'projectid': this.fielddata.projectid
      }

      jQuery.post( ajaxurl, body, (response) => {
        this.data.assigneelist = response.data
        this.accountLoading = false;
      });
    },

    selectedCompletionSubscriber(event){
      this.fielddata.NotifyWhenDone = event.target.value
    }
  },

  mounted() {
    this.fielddata.message = "";
    this.fielddata.name = "";
    this.fielddata.NotifyWhenDone = "";
    this.fielddata.AssignedTo = ""
    this.fielddata.Title = "";
    this.fielddata.DueOn = "";
    this.fielddata.Notes = "";
  },
  
  template: '#basecamp3-action-template'
});
