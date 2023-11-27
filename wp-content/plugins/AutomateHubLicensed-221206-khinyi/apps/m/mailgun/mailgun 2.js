Vue.component('mailgun', {
  props: ["trigger", "action", "fielddata"],
  
  data(){
      return {
        error: "",
        listLoading: null,
        mailingLists: [],
        fields: [
          {type: 'textarea', value: 'email', title: 'Email', task: ['subscribe_to_list'], required: true},
          {type: 'textarea', value: 'name', title: 'Name', task: ['subscribe_to_list'], required: true},
      ]
      }
  },

  methods: {
    onselect(event){
      this.fielddata.mailListAddress = event.target.value
    },
  
    
    CheckinDatabase:function(item){
        let not_match = true;
        const saved_item = '{{'+item+'}}';

        if(!(typeof fieldData == 'undefined')){
              const fieldaa = this.fielddata;
              for(i in fieldaa){
                if(fieldaa[i]){
                    if(i=='answerQ1' || i=='answerQ2' || i=='answerQ3'){

                        if(fieldaa[i].length==2){
                            
                            if(fieldaa[i][1]==item){
                                not_match = false;
                            }
                        }

                    } else {
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

    getMailLists(){
      activePlatformId=this.fielddata.activePlatformId;
      this.error = "";
      this.listLoading = true;

      const body =  {
        '_nonce': awp.nonce,
        'platformid':activePlatformId,
        'action': 'awp_fetch_mail_lists',
      }

      jQuery.post( ajaxurl, body, (response) => {
          this.mailingLists = response.data.items.map(item => {
          const address = item.address;
          const name = item.name || item.description || address;
          return { name, address };
        });

        this.listLoading = false;
      });
    },    
  },


  mounted() {
    // this.getMailLists()
  },
  
  template: '#mailgun-action-template'
});
