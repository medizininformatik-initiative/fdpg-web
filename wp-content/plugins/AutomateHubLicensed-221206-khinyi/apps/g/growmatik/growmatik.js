Vue.component('growmatik', {
    props: ["trigger", "action", "fielddata"],
    
    data: function () {
        return { 
        data: { categoryList: [] },
        categoryLoading: false,
        fields: [
                // create contact
              {type: 'textarea', value: 'firstName', title: 'First Name', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'lastName', title: 'Last Name', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'address', title: 'Address', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'phoneNumber', title: 'Phone Number', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'country', title: 'Country', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'region', title: 'Region', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'city', title: 'City', task: ['createcontact'], required: false},
              {type: 'textarea', value: 'email', title: 'Email', task: ['createcontact'], required: false},

            //   create category
              {type: 'textarea', value: 'name', title: 'Category Name', task: ['createcategory'], required: false},

              // create product
              {type: 'textarea', value: 'productname', title: 'Product Name', task: ['createproduct'], required: false},
              {type: 'textarea', value: 'content', title: 'Content', task: ['createproduct'], required: false},
              {type: 'textarea', value: 'excerpt', title: 'Description', task: ['createproduct'], required: false},
              {type: 'textarea', value: 'price', title: 'Price', task: ['createproduct'], required: false},
              {type: 'textarea', value: 'sprice', title: 'Sales Price', task: ['createproduct'], required: false},
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


    selectedcategory: function(event){
        this.fielddata.categoryid = event.target.value;
        
    },
     
    getcategorylist: function() {
        this.categoryLoading = true;
        activePlatformId=this.fielddata.activePlatformId;
        

        const body =  {
        '_nonce': awp.nonce,
        'platformid': activePlatformId,
        'action': 'awp_fetch_product_category',
        }

        jQuery.post( ajaxurl, body, (response) => {
        this.data.categoryList = response.data
        this.categoryLoading = false;
        });
    }
},

 template: '#growmatik-action-template'
});