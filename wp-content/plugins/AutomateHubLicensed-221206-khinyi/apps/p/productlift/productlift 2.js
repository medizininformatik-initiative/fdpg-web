Vue.component('productlift', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { roleList: [], statusList:[], categoryList:[] },
        roleLoading: false,
        statusLoading: false,
        categoryLoading: false,
        fields: [
                // create user
              {type: 'textarea', value: 'name', title: 'Name', task: ['createuser'], required: false},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createuser'], required: false},
              {type: 'textarea', value: 'invitation_message', title: 'Invitation Message', task: ['createuser'], required: false},
              
              
              // create post
              {type: 'textarea', value: 'title', title: 'Title', task: ['createpost'], required: true},
              {type: 'textarea', value: 'description', title: 'Description', task: ['createpost'], required: true}
             
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


    selectedrole: function(event){
        this.fielddata.role = event.target.value;
    },

    selectedcategory: function(event){
        this.fielddata.category_id = event.target.value;
    },
     
    getfunctions: function(){
        this.getrole();
        this.getstatus();
    },

    getrole: function() {
        
        this.roleLoading = true;
        this.data.roleList = {
            0: {
                'id': 'admin',
                'name': 'admin'
            },
            1: {
                'id': 'member',
                'name': 'member'
            }
        };
        this.roleLoading = false;
    },

    getstatus: function() {
        
        this.statusLoading = true;
        activePlatformId=this.fielddata.activePlatformId;

        const body =  {
            '_nonce': awp.nonce,
            'platformid': activePlatformId,
            'action': 'awp_fetch_productlift_post_status',
        }

        jQuery.post( ajaxurl, body, (response) => {
            this.data.statusList = response.data
            this.statusLoading = false;
        });
    },

    getcategory: function(event) {
        this.fielddata.status_id = event.target.value;
        this.categoryLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        
        const body =  {
            '_nonce': awp.nonce,
            'platformid': activePlatformId,
            'action': 'awp_fetch_productlift_category',
        }


        jQuery.post( ajaxurl, body, (response) => {
            this.data.categoryList = response.data
            this.categoryLoading = false;
        });
    },
},

 template: '#productlift-action-template'
});