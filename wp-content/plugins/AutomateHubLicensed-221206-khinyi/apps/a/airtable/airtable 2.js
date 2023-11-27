Vue.component('airtable', {
  props: ["trigger", "action", "fielddata"],
  
  data(){
      return {
        tableId: "",
        tableName: "",
        error: "",
        loading: null,
        fields: [],
      }
  },

  methods: {
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

    getFields(){

      activePlatformId=this.fielddata.activePlatformId;

      this.tablefields = {};

      this.error = "";

      this.fields = [];
  
      if (this.tableId.length < 10 || this.tableName.length < 1 || typeof activePlatformId == 'undefined') return this.error = "Please check the fields"
    
      let tableId = this.tableId.split("/")
      tableId = tableId.length === 1 ? tableId[0] : tableId[3]

      this.loading = true

      const body =  {
        '_nonce': awp.nonce,
        'platformid':activePlatformId,
        'action': 'awp_fetch_table_fields',
        tableId,
        tableName : this.tableName
      }

      jQuery.post( ajaxurl, body, (response) => {
        if (response.data === undefined && response.error === undefined) {
          this.error = "Try again.";
    
        } else if (response.error !== undefined || response.data.error !== undefined) {
          this.error = "Please confirm the table link and name are correct."
        } 
        else {
          if (response.data.records.length < 1 || isObjEmpty(response.data.records[0].fields)) {
            this.error = "Table must have atleast one row filled before you can fetch fields. Please add a row and try again."
          } else {
            const fields = Object.keys(response.data.records[0].fields)
            fields.forEach((field) => {
              this.tablefields[field] = ''
              this.fields.push({value: field, title: field, task: [this.action.task], required: false})
            })
          }
        }
        
        this.loading = false
        this.tableId = tableId
      });


    },    
  },

  updated:function () {
    makedropable(this.fielddata);
  },
  mounted: function() {
    //reset activeplatformid value when new component mounts
        if( typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
  },
  
  template: '#airtable-action-template'
});
