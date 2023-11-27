
function makedropable(fielddata=''){
        if(!(fielddata) && !(typeof fieldData == 'undefined')){
            fielddata = fieldData;
        }
        var dragged_obj = '';
        Array.prototype.remove = function() {
                var what, a = arguments, L = a.length, ax;
                while (L && this.length) {
                    what = a[--L];
                    while ((ax = this.indexOf(what)) !== -1) {
                        this.splice(ax, 1);
                    }
                }
                return this;
            };
        jQuery( ".form_fields_name" ).draggable({
            revert : function(event, ui) {
                jQuery(this).data("uiDraggable").originalPosition = {
                    top : 0,
                    left : 0
                };
                return !event;
            },
            //connectToSortable: ".sortable",
            // revert :"invalid",
            helper: "clone",
             start: function(event, ui){
                dragged_obj = jQuery(this);
             },
             drag:function(event, ui){
                dragged_obj = jQuery(this);
                      ui.helper.removeClass("end-draggable");
             },
             stop: function( event, ui ){
                var marginTop = 10;
      			ui.helper.addClass("end-draggable");
             }
        });

        jQuery( ".form_fields_db_name" ).draggable({
            revert : function(event, ui) {
                if(!(typeof jQuery(this).data("uiDraggable") =='undefined')){
                    jQuery(this).data("uiDraggable").originalPosition = {
                        top : 0,
                        left : 0
                    };
                }
                return !event;
            },
            //revert :"invalid",
            helper: "clone",
            start: function(){
                dragged_obj = jQuery(this);
            },
             drag:function(){
                dragged_obj = jQuery(this);
             },
        });

        jQuery( ".sperse_reverse_draggable" ).droppable({
           tolerance: 'touch',
           greedy: true,
          drop: function( event, ui ) {
            if(dragged_obj){
                if(typeof int_id != 'undefined'){
                    var maaped_value = dragged_obj.attr('data-name');
                    var fieldname = dragged_obj.closest('.sperse_dropped').find("input").attr('data-field');
                    if(!(fieldname)){
                        fieldname = dragged_obj.attr('data-field');
                    }
                    var field_data = awpNewIntegration.fieldData;
                    var filteredfordisplaystored = field_data[fieldname+'dis'];
                    var mapped_pr_value = '{{'+maaped_value;
                    maaped_value = '{{'+maaped_value+'}}';
                    var already_value = dragged_obj.closest('.sperse_dropped').find("input").val();
                    if(!(already_value)){
                        already_value = field_data[fieldname];
                    }
                    if(already_value){
                         maaped_value = already_value.replace(maaped_value,'');
                    }
                    if(filteredfordisplaystored){
                        filteredfordisplaystored.remove(mapped_pr_value);
                    }
                    dragged_obj.closest('.sperse_dropped').find("input").val(maaped_value);
                    jQuery("input[data-field='"+fieldname+"']").val(maaped_value);
                    field_data[fieldname] = maaped_value;
                    field_data[fieldname+'dis'] = filteredfordisplaystored;
                    awpNewIntegration.fieldData = field_data;
                    if(!(maaped_value)){
                        dragged_obj.parent().find( ".fx-placeholder-label" ).show();
                        jQuery("input[data-field='"+fieldname+"']").parent().find( ".fx-placeholder-label" ).show();
                        jQuery("input[data-field='"+fieldname+"']").parent().removeClass('sperse_dropped'); 
                        dragged_obj.parent().removeClass('sperse_dropped'); 
                    }
            }else{
                    var maaped_value = dragged_obj.attr('data-name');
                    var fieldname = dragged_obj.closest('.sperse_dropped').find("input").attr('data-field');
                    if(!(fieldname)){
                        fieldname = dragged_obj.attr('data-field');
                    }
                    var field_data = awpNewIntegration.fieldData;
                    var filteredfordisplaystored = field_data[fieldname+'dis'];
                    var mapped_pr_value = '{{'+maaped_value;
                    maaped_value = '{{'+maaped_value+'}}';
                    var already_value = dragged_obj.closest('.sperse_dropped').find("input").val();
                    if(!(already_value)){
                        already_value = field_data[fieldname];
                    }
                    if(already_value){
                         maaped_value = already_value.replace(maaped_value,'');
                    }
                    if(filteredfordisplaystored){
                        filteredfordisplaystored.remove(mapped_pr_value);
                    }
                    dragged_obj.closest('.sperse_dropped').find("input").val(maaped_value);
                    jQuery("input[data-field='"+fieldname+"']").val(maaped_value);
                    field_data[fieldname] = maaped_value;
                    field_data[fieldname+'dis'] = filteredfordisplaystored;
                    awpNewIntegration.fieldData = field_data;
                    if(!(maaped_value)){
                        dragged_obj.parent().find( ".fx-placeholder-label" ).show();
                        jQuery("input[data-field='"+fieldname+"']").parent().find( ".fx-placeholder-label" ).show();
                        jQuery("input[data-field='"+fieldname+"']").parent().removeClass('sperse_dropped'); 
                        dragged_obj.parent().removeClass('sperse_dropped'); 
                    }
            }
            dragged_obj = '';
            }
          },
         out: function(event, ui) {
            if(dragged_obj){
            }
         }
        });

        jQuery( ".form_field_dropable" ).droppable({
            tolerance: 'touch',
            greedy: true,
            drop: function( event, ui ) {
            if(dragged_obj){
                
                // Edit integration
                if(typeof int_id != 'undefined'){
                    
                    var maaped_value = dragged_obj.attr('data-name');
                    var bac_mapped_value = maaped_value;
                    var mapped_pr_value = '{{'+maaped_value;
                    maaped_value = '{{'+maaped_value+'}}';
                    var field_data = awpNewIntegration.fieldData;
                    var action =awpNewIntegration.action;
                    var  dropedfieldname = jQuery(this).find('input').attr('data-field');
                    var fromfieldName = dragged_obj.attr('data-field');
                 
                    if(fromfieldName){
                      
                        if(!(fromfieldName==dropedfieldname) ){
                         
                           var previous_form_field =  field_data[fromfieldName];
                           var previous_form_field_ar =  field_data[fromfieldName+'dis'];
                            if(previous_form_field && !(dropedfieldname=='answerQ1' || dropedfieldname=='answerQ2' || dropedfieldname=='answerQ3') && !(fromfieldName=='answerQ1' || fromfieldName=='answerQ2' || fromfieldName=='answerQ3') ){
                               
                                 previous_form_field = previous_form_field.replace(maaped_value,'');
                            }else{
                               
                                previous_form_field = [];
                            }
                            if(previous_form_field_ar && !(dropedfieldname=='answerQ1' || dropedfieldname=='answerQ2' || dropedfieldname=='answerQ3') && !(fromfieldName=='answerQ1' || fromfieldName=='answerQ2' || fromfieldName=='answerQ3')  ){
                            
                                previous_form_field_ar.remove(mapped_pr_value);
                            }else{
                            
                                previous_form_field_ar = [];
                            }
                          
                            field_data[fromfieldName] = previous_form_field;
                            field_data[fromfieldName+'dis'] =  previous_form_field_ar;
                            jQuery("input[data-field='"+fromfieldName+"']").val(previous_form_field);
                            if((previous_form_field.length==0)){
                           
                                jQuery("input[data-field='"+fromfieldName+"']").closest('.form_field_dropable').removeClass('sperse_dropped');
                                jQuery("input[data-field='"+fromfieldName+"']").closest('.fx-placeholder-label').show();
                            }
                    
                        }
                 
                    }
               
                    var fordisplaynames = field_data[dropedfieldname];
                    var fordisplaystored = [];
                    var already_value = jQuery(this).find('input').val();
                    if(already_value){
               
                        var  calready_value =  already_value.split(" ").join("");
                         maaped_value = maaped_value.split(" ").join("");
                        if(calready_value.indexOf(maaped_value) !== -1){
                        
                            maaped_value = already_value;
                        }else{
                        
                             maaped_value = already_value+maaped_value;
                        }
                     
                    }
                    if(dropedfieldname=='answerQ1' || dropedfieldname=='answerQ2' || dropedfieldname=='answerQ3' ){
                        var question = awpNewIntegration.trigger.formFields[bac_mapped_value];
                        field_data[dropedfieldname] = [question,bac_mapped_value];
                        jQuery(this).find('input').val(question+','+'{{'+bac_mapped_value+'}}');
                        var back_mapped_value = bac_mapped_value;
                        fordisplaystored = back_mapped_value.split('}}');
                        var filteredfordisplaystored = fordisplaystored.filter(function (el) {
                          return el != "";
                        });
                        dragged_obj.attr('data-field',fieldname);
                        field_data[dropedfieldname+'dis'] = filteredfordisplaystored;
                    }else{
                
                        var back_mapped_value = maaped_value.trim();
                        fordisplaystored = back_mapped_value.split('}}');
                        var filteredfordisplaystored = fordisplaystored.filter(function (el) {
                          return el != "";
                        });
                        dragged_obj.attr('data-field',dropedfieldname);
                        jQuery(this).find('input').val(maaped_value);
                        field_data[dropedfieldname+'dis'] = filteredfordisplaystored;
                        field_data[dropedfieldname] = maaped_value;
                        jQuery(this).find('input').val(maaped_value);
                    
                    }
             
                    awpNewIntegration.fieldData = field_data;
                    jQuery(this).find(".fx-placeholder-label" ).hide();
                    jQuery(this).addClass('sperse_dropped');
                    if(dragged_obj.hasClass('form_fields_db_name')){
                        //dragged_obj.remove();
                    }
               
                }else{
               
                        var clone_obj = ui.draggable.clone();
                        jQuery( clone_obj ).find('.field-actions').removeClass('hide');
                        var maaped_value = clone_obj.attr('data-name');
                        var count = jQuery(this).find('div').length;
                        var that = jQuery(this).find('.sortable');
                        jQuery( clone_obj ).attr('id', 'draggeddd'+count).addClass('fx-controls-in-zone').removeClass('ui-draggable ui-draggable-handle');
                        jQuery( clone_obj ).removeAttr('style');
                        clone_obj.draggable({
                            revert : function(event, ui) {
                                jQuery(this).data("uiDraggable").originalPosition = {
                                    top : 0,
                                    left : 0
                                };
                                return !event;
                            },
                            helper: "clone",
                            start: function(event, ui){
                                dragged_obj = jQuery(this);
                            },
                            drag:function(event, ui){
                                dragged_obj = jQuery(this);
                                ui.helper.removeClass("end-draggable");
                            },
                            stop: function( event, ui ){
                                ui.helper.addClass("end-draggable");
                            }
                        });
                        clone_obj.appendTo(that);
                        var bac_mapped_value = maaped_value;
                        var mapped_pr_value = '{{'+maaped_value;
                        maaped_value = '{{'+maaped_value+'}}';
                        var field_data = awpNewIntegration.fieldData;
                        //console.log(field_data);
                        var action =awpNewIntegration.action;
                        var fieldname = jQuery(this).find('input').attr('data-field');
                        var fromfieldName = jQuery( clone_obj ).attr('data-field');
                        if(fromfieldName){
                            if(!(fromfieldName==fieldname)){
                            var previous_form_field =  field_data[fromfieldName];
                            var previous_form_field_ar =  field_data[fromfieldName+'dis'];
                            if(previous_form_field && !(fieldname=='answerQ1' || fieldname=='answerQ2' || fieldname=='answerQ3') ){
                                    previous_form_field = previous_form_field.replace(maaped_value,'');
                                }else{
                                    previous_form_field = [];
                                }
                                if(previous_form_field_ar && !(fieldname=='answerQ1' || fieldname=='answerQ2' || fieldname=='answerQ3')  ){
                                    previous_form_field_ar.remove(mapped_pr_value);
                                }else{
                                        previous_form_field_ar = [];
                                }
                                field_data[fromfieldName] = previous_form_field;
                                field_data[fromfieldName+'dis'] =  previous_form_field_ar;
                                jQuery("input[data-field='"+fromfieldName+"']").val(previous_form_field);
                                if(previous_form_field.length==0){
                                    jQuery("input[data-field='"+fromfieldName+"']").closest('.form_field_dropable').removeClass('sperse_dropped');
                                    jQuery("input[data-field='"+fromfieldName+"']").closest('.fx-placeholder-label').show();
                                }
                            }
                        }
                        var fordisplaynames = field_data[fieldname];
                        var fordisplaystored = [];
                        var already_value = jQuery(this).find('input').val();
                        if(already_value){
                            if(already_value.indexOf(maaped_value) !== -1){
                                maaped_value = already_value;
                            }else{
                                maaped_value = already_value+maaped_value;
                            }
                        }
                        if(fieldname=='answerQ1' || fieldname=='answerQ2' || fieldname=='answerQ3' ){
                            var question = awpNewIntegration.trigger.formFields[bac_mapped_value];
                            field_data[fieldname] = [question,bac_mapped_value];
                            jQuery(this).find('input').val(question+','+'{{'+bac_mapped_value+'}}');
                            var back_mapped_value = bac_mapped_value;
                            fordisplaystored = back_mapped_value.split('}}');
                            var filteredfordisplaystored = fordisplaystored.filter(function (el) {
                            return el != "";
                            });
                            jQuery( clone_obj ).attr('data-field',fieldname);
                            field_data[fieldname+'dis'] = filteredfordisplaystored;
                        }else{
                            var back_mapped_value = maaped_value;
                            fordisplaystored = back_mapped_value.split('}}');
                            var filteredfordisplaystored = fordisplaystored.filter(function (el) {
                            return el != "";
                            });
                            jQuery( clone_obj ).attr('data-field',fieldname);
                            jQuery(this).find('input').val(maaped_value);
                            field_data[fieldname+'dis'] = filteredfordisplaystored;
                            field_data[fieldname] = maaped_value;
                            jQuery(this).find('input').val(maaped_value);
                        }
                        awpNewIntegration.fieldData = field_data;
                        jQuery(this).find(".fx-placeholder-label" ).hide();
                        jQuery(this).addClass('sperse_dropped');
                        jQuery( clone_obj ).find('.field-actions').removeClass('hide');
                        if(jQuery( clone_obj ).hasClass('form_fields_db_name')){
                            //dragged_obj.remove();
                        }
                        ui.draggable.hide();
                }
                dragged_obj = '';
            }
          },
         out: function(event, ui) {
            if(dragged_obj){
            }
         }
        });
}


Vue.component('editable-field', {
    props: ["trigger", "action", "fielddata", "field",],
    template: '#editable-field-template',
    data: function() {
        return{
            selected: ''
        }
    },
    methods: {
        say:function(event){
            
            var $this = jQuery('#'+event.target.id);
            var forfieldname = $this.attr('data-name');
            var tomappedfieldname = $this.attr('data-field');
            var field_data = awpNewIntegration.fieldData;
            var already_value = field_data[tomappedfieldname];
            var filteredfordisplaystored = field_data[tomappedfieldname+'dis'];
            var mapped_pr_value = '{{'+forfieldname;
            var maaped_value = '{{'+forfieldname+'}}';
            maaped_value=maaped_value.replace('----',' ');
            mapped_pr_value=mapped_pr_value.replace('----',' ');
            already_value=already_value.replace('----',' ');
            var temp =filteredfordisplaystored.map(function(item,index){
              return item.replace('----',' ');

            });
            filteredfordisplaystored=temp;
            
            // console.log('1111');
            // console.log($this);
            // console.log('2222');
            // console.log(forfieldname);
            // console.log('3333');
            // console.log(tomappedfieldname);
            // console.log('4444');
            // console.log(field_data);
            // console.log('5555');
            // console.log(already_value);
            // console.log('6666');
            // console.log(filteredfordisplaystored);
            // console.log('7777');
            // console.log(mapped_pr_value);
            // console.log('8888');
            // console.log(maaped_value);
            // console.log('9999');
            // console.log(awpNewIntegration.fieldData);
            
            if(tomappedfieldname=='answerQ1' || tomappedfieldname=='answerQ2' || tomappedfieldname=='answerQ3' ){
            	maaped_value='';
            	filteredfordisplaystored=[];
            }else{
	            if(already_value){
	                maaped_value = already_value.replace(maaped_value,'');
	            }
	            if(filteredfordisplaystored){
	                filteredfordisplaystored.remove(mapped_pr_value);
	            }
            }
            field_data[tomappedfieldname] = maaped_value;
            field_data[tomappedfieldname+'dis'] = filteredfordisplaystored;
            awpNewIntegration.fieldData = field_data;
            maaped_value = maaped_value.trim();
            if((maaped_value.length==0)){
                jQuery("input[data-field='"+tomappedfieldname+"']").closest('.form_field_dropable').removeClass('sperse_dropped'); 
            }
        },
        updateFieldValue: function(e) {
            if(this.selected || this.selected == 0) {
                if (this.fielddata[this.field.value] || "0" == this.fielddata[this.field.value]) {
                    this.fielddata[this.field.value] += ' {{' + this.selected + '}}';
                } else {
                    this.fielddata[this.field.value] = '{{' + this.selected + '}}';
                }
                if(this.action.actionProviderId=='sperse' && (this.action.task=='createLead' || this.action.task=="createUser") ){
                    var question = this.trigger.formFields[this.selected];
                    if(this.field.value=='answerQ1'){
                        this.fielddata[this.field.value]=[question,'{{' + this.selected + '}}'];
                    }
                    if(this.field.value=='answerQ2'){
                        this.fielddata[this.field.value]=[question,'{{' + this.selected + '}}'];
                    }
                    if(this.field.value=='answerQ3'){
                        this.fielddata[this.field.value]=[question,'{{' + this.selected + '}}'];
                    }
                }
            }
        },
        inArray: function(needle, haystack) {
             if(haystack){           
            var length = haystack.length;
            for(var i = 0; i < length; i++) {
                if(haystack[i] == needle) return true;
            }}
            return false;
        },
        parseFieldValue:function(item_value){
            item_value = item_value.replace(/[{}]/g, "");
            item_value = item_value.replace(' ','----');
            return item_value;
        },
        parsedFormFieldValue:function(field_value){
            var nfield_value = field_value.replace(/[{}]/g, "");
            nfield_value = nfield_value.replace('----',' ');
            nfield_value =  nfield_value.trim();
            var field_name = this.trigger.formFields[nfield_value];
            var static_or_copy_found='';


            if(typeof field_name == "undefined" && nfield_value.search('copied_') > -1){
                
                
                var crop_index=nfield_value.indexOf('_',7);
                nfield_value=nfield_value.substring(crop_index+1);
                static_or_copy_found='true';
                //first check if we could find it in field_name
                if(typeof this.trigger.formFields[nfield_value] !== 'undefined' || this.trigger.formFields[nfield_value] === null){
                    //yes it's in formfields so we will return this value directly
                    nfield_value=this.trigger.formFields[nfield_value];
                }
                
               
            }

            //special handling for static fields as they are not in the forms
            if(typeof field_name == "undefined" && nfield_value.search('static_') > -1){

                var nfield_value= nfield_value.replace(/_/g,' ');
                nfield_value=nfield_value.replace('static ','');
                static_or_copy_found='true';
                //first check if we could find it in field_name
                if(typeof this.trigger.formFields[nfield_value] !== 'undefined' || this.trigger.formFields[nfield_value] === null){
                    //yes it's in formfields so we will return this value directly
                    nfield_value=this.trigger.formFields[nfield_value];
                }
             
            }
            
            

            if(static_or_copy_found != ''){
                return nfield_value;
            }
            else{
                return field_name;
            }
        },

    },
    updated:function () {
        makedropable(this.fielddata);
    },
    mounted: function() {
        
        setTimeout(function () {
 
          makedropable();
          triggerStaticFieldsWork();
      
        }, 1200);
    },

});

Vue.component('everwebinar', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            webinarLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['register_webinar'], required: true, description: 'Required'},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['register_webinar'], required: true, description: 'Required'},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['register_webinar'], required: false},
                {type: 'text', value: 'ipAddress', title: 'IP Address', task: ['register_webinar'], required: false},
                {type: 'text', value: 'phoneCountryCode', title: 'Phone Country Code', task: ['register_webinar'], required: false},
                {type: 'text', value: 'phone', title: 'Phone Number', task: ['register_webinar'], required: false},
                {type: 'text', value: 'timezone', title: 'Timezone', task: ['register_webinar'], required: false},
                {type: 'text', value: 'date', title: 'Date', task: ['register_webinar'], required: false}
            ]
        }
    },
    methods: {
        getSchedule: function() {
            var that = this;
            this.webinarLoading = true;
            var scheduleData = {
                'action': 'awp_get_everwebinar_schedules',
                '_nonce': awp.nonce,
                'webinarId': this.fielddata.webinarId,
                'task': this.action.task,
                'platformid':this.fielddata.activePlatformId,
            };
            jQuery.post( ajaxurl, scheduleData, function( response ) {
                var schedules = response.data;
                that.fielddata.schedules = schedules;
                that.webinarLoading = false;
            });
        },
          CheckinDatabase:function(item,name){
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
        getEverwebinarList:function(){
            var that = this;
            this.webinarLoading = true;
            var webinarRequestData = {
                'action': 'awp_get_everwebinar_webinars',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, webinarRequestData, function( response ) {
                that.fielddata.webinars = response.data;
                that.webinarLoading = false;
            });
        }
    },
    created: function() {
    },
    mounted: function() {
        var that = this;
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#everwebinar-action-template'
});

Vue.component('freshworks', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact', 'add_lead'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact', 'add_lead'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact', 'add_lead'], required: false}
            ]
        }
    },
    methods: {
         CheckinDatabase:function(item,name){
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
    },
    created: function() {
    },
    mounted: function() {
        var that = this;
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#freshworks-action-template'
});


Vue.component('kartra', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'middleName', title: 'Middle Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName2', title: 'Last Name 2', task: ['subscribe'], required: false},
                {type: 'text', value: 'phoneCountryCode', title: 'Phone Country Code', task: ['subscribe'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'ip', title: 'IP', task: ['subscribe'], required: false},
                {type: 'text', value: 'address', title: 'Address 1', task: ['subscribe'], required: false},
                {type: 'text', value: 'zip', title: 'ZIP', task: ['subscribe'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['subscribe'], required: false},
                {type: 'text', value: 'state', title: 'State', task: ['subscribe'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['subscribe'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['subscribe'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {type: 'text', value: 'facebook', title: 'Facebook', task: ['subscribe'], required: false},
                {type: 'text', value: 'twitter', title: 'Twitter', task: ['subscribe'], required: false},
                {type: 'text', value: 'linkedin', title: 'LinkedIn', task: ['subscribe'], required: false}
            ]
        }
    },
    methods: {
        CheckinDatabase:function(item,name){
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
        getKartraList:function(){
            var that = this;
            this.listLoading = true;

            var listRequestData = {
                'action': 'awp_get_kartra_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {
    },
    mounted: function() {
        var that = this;
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#kartra-action-template'
});

Vue.component('lemlist', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
     methods: {

                CheckinDatabase:function(item,name){

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
        getLemlistList:function(){
            var that = this;
            this.listLoading = true;

            var listRequestData = {
                'action': 'awp_get_lemlist_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#lemlist-action-template'
});

Vue.component('hubspot', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'phone', title: 'Phone Number', task: ['add_contact'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['add_contact'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['add_contact'], required: false},
            ]

        }
    },
    methods: {

           CheckinDatabase:function(item,name){

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
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#hubspot-action-template'
});

Vue.component('pushover', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return { 
            listLoading: false,
            fields: [
                {type: 'text', value: 'title', title: 'Title', task: ['push'], required: false},
                {type: 'text', value: 'message', title: 'Message', task: ['push'], required: false},
                {type: 'text', value: 'device', title: 'Device', task: ['push'], required: false}
            ]

        }
    },
    methods: {

           CheckinDatabase:function(item,name){

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
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        
    },
    template: '#pushover-action-template'
});

Vue.component('twilio', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'to', title: 'To', task: ['subscribe'], required: true},
                {type: 'textarea', value: 'body', title: 'Body', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

        CheckinDatabase:function(item,name){

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
        getTwilioList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_twilio_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#twilio-action-template'
});

Vue.component('webinarjam', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            webinarLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['register_webinar'], required: true, description: 'Required'},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['register_webinar'], required: true, description: 'Required'},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['register_webinar'], required: false},
                {type: 'text', value: 'ipAddress', title: 'IP Address', task: ['register_webinar'], required: false},
                {type: 'text', value: 'phoneCountryCode', title: 'Phone Country Code', task: ['register_webinar'], required: false},
                {type: 'text', value: 'phone', title: 'Phone Number', task: ['register_webinar'], required: false},
                {type: 'text', value: 'timezone', title: 'Timezone', task: ['register_webinar'], required: false},
                {type: 'text', value: 'date', title: 'Date', task: ['register_webinar'], required: false}
            ]

        }
    },
    methods: {
        getSchedule: function() {
            var that = this;
            this.webinarLoading = true;

            var scheduleData = {
                'action': 'awp_get_webinarjam_schedules',
                '_nonce': awp.nonce,
                'webinarId': this.fielddata.webinarId,
                'platformid':this.fielddata.activePlatformId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, scheduleData, function( response ) {
                var schedules = response.data;
                that.fielddata.schedules = schedules;
                that.webinarLoading = false;
                console.log(typeof(schedules) );
                if(!schedules.length){
                    document.getElementsByClassName('notice-msg-schedule')[0].innerHTML='No Schedule Found!<br/> Please schedule an event for a future date and try again';
                    document.getElementsByClassName('notice-msg-schedule')[0].classList.add("failed-notice");
                    document.getElementsByClassName('notice-msg-schedule')[0].classList.add("show-notice");
                    
                }
            });
        },
                CheckinDatabase:function(item,name){

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
        getWebinarjamList:function(){
            this.webinarLoading = true;
            var that = this;
            var webinarRequestData = {
                'action': 'awp_get_webinarjam_webinars',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, webinarRequestData, function( response ) {
                that.fielddata.webinars = response.data;
                that.webinarLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#webinarjam-action-template'
});


Vue.component('lifterlms', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                
                {type: 'text', value: 'email', title: 'Email', task: ['add_student'], required: true},
                {type: 'text', value: 'first_name', title: 'First Name', task: ['add_student'], required: false},
                {type: 'text', value: 'last_name', title: 'Last Name', task: ['add_student'], required: false},
                {type: 'text', value: 'name', title: 'Name', task: ['add_student'], required: false},
                {type: 'text', value: 'nickname', title: 'Nickname', task: ['add_student'], required: false},
                {type: 'text', value: 'password', title: 'Password', task: ['add_student'], required: false},
                {type: 'text', value: 'description', title: 'Description', task: ['add_student'], required: false},
                {type: 'text', value: 'username', title: 'Username', task: ['add_student'], required: false},
                {type: 'text', value: 'billing_address_1', title: 'Address 1', task: ['add_student'], required: false},
                {type: 'text', value: 'billing_address_2', title: 'Address 2', task: ['add_student'], required: false},
                {type: 'text', value: 'billing_city', title: 'City', task: ['add_student'], required: false},
                {type: 'text', value: 'billing_country', title: 'Country', task: ['add_student'], required: false},
                {type: 'text', value: 'billing_postcode', title: 'Postal Code', task: ['add_student'], required: false},
                {type: 'text', value: 'billing_state', title: 'Billing State', task: ['add_student'], required: false},                
                {type: 'text', value: 'registered_date', title: 'Registeration Date', task: ['add_student'], required: false},
                
                
                
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        // var pipelineRequestData = {
        //     'action': 'awp_get_agilecrm_pipelines',
        //     '_nonce': awp.nonce
        // };

        // jQuery.post( ajaxurl, pipelineRequestData, function( response ) {

        //     if( response.success ) {
        //         if( response.data ) {
        //             response.data.map(function(single) {
        //                 that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_contact'], required: false, description: single.description } );
        //             });
        //         }
        //     }
        // });
    },
    template: '#lifterlms-action-template'
});





Vue.component('agilecrm', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email [Contact]', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name [Contact]', task: ['add_contact'], required: true},
                {type: 'text', value: 'lastName', title: 'Last Name [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'title', title: 'Title [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'company', title: 'Company [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'phone', title: 'Phone [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'address', title: 'Address [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'city', title: 'City [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'state', title: 'State [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'zip', title: 'Zip [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'country', title: 'Country [Contact]', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

        getAgileCRMPipelines:function(){
            var pipelineRequestData = {
                'action': 'awp_get_agilecrm_pipelines',
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, pipelineRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_contact'], required: false, description: single.description } );
                        });
                    }
                }
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;


        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        
        
    },
    template: '#agilecrm-action-template'
});


Vue.component('mailchimp', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {value: 'FNAME', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'LNAME', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

        CheckinDatabase:function(item,name){

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
        getMailchimpList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_mailchimp_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#mailchimp-action-template'
});
Vue.component('jetwebinar', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['createLead'], required: true},
                {value: 'firstName', title: 'First Name', task: ['createLead'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['createLead'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){


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
        getJetwebinarList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_jetwebinar_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#jetwebinar-action-template'
});

Vue.component('sendfox', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
        getSendfoxList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_sendfox_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#sendfox-action-template'
});

Vue.component('woodpecker', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
        getWoodpeckerListPro:function(){
            this.listLoading = true;
            var that = this;
            var accountRequestData = {
                'action': 'awp_get_woodpreckerpro_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, accountRequestData, function( response ) {

               // console.log(response);
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#woodpecker-action-template'
});

Vue.component('aweber', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
        getLists: function() {
            var that = this;
            this.listLoading = true;
            var listData = {
                'action': 'awp_get_aweber_lists',
                '_nonce': awp.nonce,
                'platformid':this.fielddata.activePlatformId,
                'accountId': this.fielddata.accountId,
                'task': this.action.task
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.lists = lists;
                that.listLoading = false;
            });
        },
        CheckinDatabase:function(item,name){

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

        getAweberAccounts:function(){
            this.accountLoading = true;
            var that = this;
            var accountRequestData = {
                'action': 'awp_get_aweber_accounts',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, accountRequestData, function( response ) {
                that.fielddata.accounts = response.data;
                that.accountLoading = false;
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#aweber-action-template'
});

Vue.component('activecampaign', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            platformLoading:false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {value: 'phoneNumber', title: 'Phone', task: ['subscribe'], required: false},
                
                // Create Message
                {value: 'fromname', title: 'Message From Name', task: ['createmessage'], required: false},
                {value: 'fromemail', title: 'Message From Email', task: ['createmessage'], required: false},
                {value: 'reply2', title: 'Reply to Email', task: ['createmessage'], required: false},
                {value: 'subject', title: 'Subject', task: ['createmessage'], required: false},
                {value: 'preheader_text', title: 'Pre Header Text', task: ['createmessage'], required: false},
                {value: 'text', title: 'Message Content', task: ['createmessage'], required: false},

                // Create Tag
                {value: 'tagname', title: 'Tag Name', task: ['createtag'], required: false},
                {value: 'tagType', title: 'Tag Type', task: ['createtag'], required: false},
                {value: 'description', title: 'Tag Description', task: ['createtag'], required: false},

                // Create Group
                {value: 'groupname', title: 'Group Name', task: ['creategroup'], required: false},

            ]
        }
    },
    methods: {
        getActiveCampaignList:function(){
            this.getActiveCampaignGroupList();
                var that=this;
                this.listLoading = true;
                var listRequestData = {
                    'action': 'awp_get_activecampaign_list',
                    'platformid':this.fielddata.activePlatformId,
                    '_nonce': awp.nonce
                };

                jQuery.post( ajaxurl, listRequestData, function( response ) {            
                    that.fielddata.list = response.data;
                    that.listLoading = false;
                });
        },
        CheckinDatabase:function(item,name){

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
    },
    created: function() {
    },
    mounted: function() {
        var that = this;
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        


       
    },
    template: '#activecampaign-action-template'
});


Vue.component('smartsheet', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: []
        }
    },
    methods: {
        getFields: function() {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'awp_get_smartsheet_fields',
                '_nonce': awp.nonce,
                'listId': this.fielddata.listId,
                'platformid':this.fielddata.activePlatformId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        for(var key in response.data) {
                            that.fields.push({type: 'text', value: key, title: response.data[key], task: ['add_row'], required: false});
                        }
                    }
                }

                that.listLoading = false;
            });
        },
          CheckinDatabase:function(item,name){

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
        getSmartsheetList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_smartsheet_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });

            if(this.fielddata.listId ) {
                var that = this;
                this.listLoading = true;

                var listData = {
                    'action': 'awp_get_smartsheet_fields',
                    '_nonce': awp.nonce,
                    'platformid':this.fielddata.activePlatformId,
                    'listId': this.fielddata.listId,
                    'task': this.action.task
                };

                jQuery.post( ajaxurl, listData, function( response ) {
                    if(response.success) {
                        if(response.data) {
                            for(var key in response.data) {
                                that.fields.push({type: 'text', value: key, title: response.data[key], task: ['add_row'], required: false});
                            }
                        }
                    }

                    that.listLoading = false;
                });
            }
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
   updated:function () {
        makedropable();
        
    },
    template: '#smartsheet-action-template'
});


Vue.component('highlevel', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            stageListLoading: false,
            ownerListLoading: false,
            fields: [
                {type: 'text', value: 'opportunityTitle', title: 'Opportunity Title', task: ['add_contact_with_opportunity'], required: true},
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact','add_contact_with_opportunity'], required: true},
                {value: 'firstName', title: 'First Name', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {type: 'text', value: 'name', title: 'Name', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {value: 'leadValue', title: 'Lead Value', task: ['add_contact_with_opportunity'], required: false},
                {value: 'source', title: 'Source', task: ['add_contact_with_opportunity'], required: false},
                {value: 'phoneNumber',type: 'text', title: 'Phone', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {value: 'address1', type: 'text',title: 'Address 1', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {type: 'text', value: 'state', title: 'State', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {type: 'text', value: 'postCode', title: 'Zipcode', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['add_contact','add_contact_with_opportunity'], required: false},
                {value: 'companyName'  , title: 'Company Name'         , task: ['add_contact','add_contact_with_opportunity'], required: false},                
                {value: 'tags', title: 'Tags', task: ['add_contact','add_contact_with_opportunity'], required: false},

                
            ],

        }
    },
    methods: {

          CheckinDatabase:function(item,name){

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


        getPipelines:function(){
            this.listLoading = true;
            var that=this;
            var listRequestData = {
                'action': 'awp_get_pipeline_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.pipelineList = response.data;
                that.listLoading = false;
            });
            this.getCustomFields();
        },
        getStages:function(){
            this.stageListLoading = true;
            var that=this;
            var listRequestData = {
                'action': 'awp_get_stages_list',
                'platformid':this.fielddata.activePlatformId,
                'pipelineId':this.fielddata.pipelineId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.stageList = response.data;
                that.stageListLoading = false;

                //for edit page
                if(that.fielddata.stageIdPicked !=''){
                    that.fielddata.stageId=that.fielddata.stageIdPicked;
                }
            
            });
        },
        getOwnerList:function(){
            this.ownerListLoading = true;
            var that=this;
            var listRequestData = {
                'action': 'awp_get_owners_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.stageList = response.data;
                that.ownerListLoading = false;
            });
        },
        mapCustomFieldsToArray:function(fields){
            var fieldsArray=[];
            var tempfield={};
            for (var i = 0; i < fields.length; i++) {
                if(fields[i].dataType == 'TEXT' || fields[i].dataType == 'LARGE_TEXT' || fields[i].dataType == 'PHONE' ){
                    tempfield={};
                    tempfield['value']='cus_'+fields[i].id;
                    tempfield['type']='text';
                    tempfield['title']=fields[i].name;
                    tempfield['task']=['add_contact','add_contact_with_opportunity'];
                    tempfield['required']=false;
                    fieldsArray.push(tempfield);
                  
                }
                else if(fields[i].dataType == 'FILE_UPLOAD'){
                    tempfield={};
                    tempfield['value']='cusfile_'+fields[i].id;
                    tempfield['type']='text';
                    tempfield['title']=fields[i].name;
                    tempfield['task']=['add_contact','add_contact_with_opportunity'];
                    tempfield['required']=false;
                    fieldsArray.push(tempfield);
                }
                else{
                     //console.log(fields[i]);
                    //console.log(fields[i].dataType);
                }
            }

            return fieldsArray;

        },

        getCustomFields:function(){
            var customfields=[];
            var that=this;
            var listRequestData = {
                'action': 'awp_get_custom_fields',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };
         
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                
                if(response.success){
                    var fields=response.data.customFields;
                
                    customfields=that.mapCustomFieldsToArray(fields);
                    
                    for (var i = 0; i < customfields.length; i++) {
                        
                        that.fields.push(customfields[i]);
                    }
                    
                }
          
            });

            return customfields;
        },

        

        
       
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        this.fielddata.statusList={'open':'Open','won':'Won','lost':'Lost','abandoned':'Abandoned'};
        this.fielddata.stageIdPicked='';

        if(this.fielddata.activePlatformId != ''){
            //this.getCustomFields();
            this.getPipelines();

        }
        if(this.fielddata.pipelineId != ''){

            this.fielddata.stageIdPicked=this.fielddata.stageId;
            this.getStages();
        }
        /*this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_pabbly_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });*/

   
    },
    template: '#highlevel-action-template'
});




Vue.component('pabbly', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'mobile', title: 'Mobile', task: ['subscribe'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['subscribe'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['subscribe'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {type: 'text', value: 'facebook', title: 'Facebook', task: ['subscribe'], required: false},
                {type: 'text', value: 'age', title: 'Age', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

          CheckinDatabase:function(item,name){

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
        getPabblyList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_pabbly_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#pabbly-action-template'
});


Vue.component('sperse', {
    props: ["trigger", "action", "fielddata"],
    components: {
    "vue-select": VueSelect.VueSelect
    },
    watch: {
        'action.task':{
            handler: function(val, oldVal) {
                //this.foo(); // call it in the context of your component object
            },
            deep: true
        }
        
    },
    data: function () {
        return {
            listLoading: false,
            lisstLoading: false,
            StagesLoading:false,
            tagLoading:false,
            usesLoading: false,
            sperseLoading:false,
            roleLoading:false,
            entryUrl:false,
            referrerUrl:false,
            userAgent:false,
            redirectUrl:false,
            sections:[],
            mappedProducts:[],
            connectAccount: false,
            mappedProductLoading:false,
            // sperseTimeDuration: [{id:'Piece', value:'Lifetime Access'},{id:'Year', value:'Year'},{id:'Month', value:'Month'}],


            fields: [
                {value: 'affiliateCode', title: 'Affiliate Code'       , task: ['createLead','createUser'], required: false },
                {value: 'systemType',    title: 'System Type'       , ztask: ['loginUser'], required: true },
                {value: 'email'        , title: 'Email'                , task: ['createLead','createUser','loginUser'], required: true },
                {value: 'fullName'     , title: 'Full Name'            , task: ['createLead','createUser'], required: false},                
                {value: 'firstName'    , title: 'First Name'           , task: ['createLead','createUser'], required: false},
                {value: 'lastName'     , title: 'Last Name'            , task: ['createLead','createUser'], required: false},
                {value: 'companyName'  , title: 'Company Name'         , task: ['createLead','createUser'], required: false},                
                {value: 'jobTitle'     , title: 'Job Title'            , task: ['createLead','createUser'], required: false},
                {value: 'industry'     , title: 'Industry'             , task: ['createLead','createUser'], required: false},                                
                {value: 'phoneNumber'  , title: 'Phone'                , task: ['createLead','createUser'], required: false},
                {value: 'bankCode'     , title: 'BANKCODE'             , task: ['createLead','createUser'], required: false},
                {value: 'note'         , title: 'Comments'             , task: ['createLead','createUser'], required: false},                
                {value: 'streetAddress', title: 'Street Address'       , task: ['createLead','createUser'], required: false},                
                {value: 'city'         , title: 'City or Town'         , task: ['createLead','createUser'], required: false},                
                {value: 'stateName'    , title: 'State Name'           , task: ['createLead','createUser'], required: false},
                {value: 'stateId'      , title: '2-Letter State Code'  , task: ['createLead','createUser'], required: false},                
                {value: 'countryId'    , title: '2-Letter Country Code', task: ['createLead','createUser'], required: false}, 
                {value: 'zipCode'      , title: 'Zip or Postal Code'   , task: ['createLead','createUser'], required: false},                               
                {value: 'webURL'       , title: 'Your Website URL'     , task: ['createLead','createUser'], required: false},                               
                {value: 'answerQ1'     , title: 'Question1 Answer'     , task: ['createLead','createUser'], required: false},                               
                {value: 'answerQ2'     , title: 'Question2 Answer'     , task: ['createLead','createUser'], required: false},                               
                {value: 'answerQ3'     , title: 'Question3 Answer'     , task: ['createLead','createUser'], required: false},  
                {value: 'password'     , title: 'Password'             , task: ['loginUser' ], required: true }
            ]
        }
    },
    methods: {
        say:function(event){

            var $this = jQuery('#'+event.target.id);
            var forfieldname = $this.attr('data-name');
            var tomappedfieldname = jQuery("li[data-name='"+forfieldname+"']").attr('data-field');
            var field_data = awpNewIntegration.fieldData;
            var already_value = field_data[tomappedfieldname];
            var filteredfordisplaystored = field_data[tomappedfieldname+'dis'];
            var mapped_pr_value = '{{'+forfieldname;
            var maaped_value = '{{'+forfieldname+'}}';
            
           if(tomappedfieldname=='answerQ1' || tomappedfieldname=='answerQ2' || tomappedfieldname=='answerQ3' ){

           		maaped_value='';
           		filteredfordisplaystored=[];
           }else{

	            if(already_value){
	                maaped_value = already_value.replace(maaped_value,'');
	            }
	            if(filteredfordisplaystored){
	                filteredfordisplaystored.remove(mapped_pr_value);
	            }

           }

            field_data[tomappedfieldname] = maaped_value;
            field_data[tomappedfieldname+'dis'] = filteredfordisplaystored;
           awpNewIntegration.fieldData = field_data;
            jQuery("li[data-name='"+forfieldname+"']").css({
                'left':'unset',
                'top' :'unset'
            }); 
            jQuery("li[data-name='"+forfieldname+"']").find('.field-actions').addClass('hide');
            if(!(maaped_value)){
                jQuery("input[data-field='"+tomappedfieldname+"']").closest('.form_field_dropable').removeClass('sperse_dropped'); 
            }
        },
        mountSelect2Box:function(selectvar){
            //used dict to project front-end names of select elements with vue variables 
            var dict={
                "fielddata.list":"list_id",
                "fielddata.stages":"stages_id",
                "fielddata.ausers":"user_id",
                "fielddata.lists":"tags_listing",
                "fielddata.tags":"tag_id",
            };

            // if(dict[selectvar]){
            //     jQuery('select[name="'+dict[selectvar]+'"]').select2();

            //     jQuery('select[name="'+dict[selectvar]+'"]').on('select2:select', function (e) {
               
            //         var data = e.params.data;
                    
            //         jQuery(this).val(data.id);
            //     });
                

            // }
            
            
        },
        removeProductMapping: function(wpProductId,sperseProductCode){
            var that = this;
             var sperse_data = {
                action : 'awp_sperse_mapping_remove',
                '_nonce': awp.nonce,
                
                'id':this.fielddata.sperse_accountId,
                'task': this.action.task,
                wpProductId:wpProductId,
                sperseProductCode:sperseProductCode
            };
            
            jQuery.post( ajaxurl, sperse_data, function( response ) {
                
                response = JSON.parse(response);
                
                alert(response.message);
            });
            
               that.mappedProductLoading = true;
                    var tagRequestData = {
                'action': 'awp_get_mapped_products',
                '_nonce': awp.nonce,
                'id':this.fielddata.sperse_accountId,
            };
           // var that = this;
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                    
                    that.mappedProductLoading = false;
                    that.fielddata.mappedProducts = response.data;
                    // that.sections = response.data;
                     that.sections = (response.data) ? response.data : [];
               // that.productLoading = false;
            });
            
            
        },
        saveProductMapping: function(colIndex,rowIndex) {
                    // console.log(rowIndex);
                    // console.log(colIndex);
                    var that = this;
                    // this.mappedProductLoading = true;
                    var wpProductId = jQuery('#wpProductId'+rowIndex).attr('wpProductId'+rowIndex);
                    var wpProductName = jQuery('#wpProductId'+rowIndex+' .vs__selected').text().trim();
                    var sperseProductCode = jQuery('#sperseProductId'+rowIndex+colIndex).attr('sperseProductId'+rowIndex+colIndex);
                    var sperseProductName = jQuery('#sperseProductId'+rowIndex+colIndex+' .vs__selected').text().trim();
                    // var sperseTimeDurationId = jQuery('#sperseTimeDurationId'+rowIndex+colIndex).attr('sperseTimeDurationId'+rowIndex+colIndex);
                    // var sperseTimeDurationValue = jQuery('#sperseTimeDurationId'+rowIndex+colIndex+' .vs__selected').text().trim();
                    
                    if(!wpProductId){
                        alert("Please Select Worpress Product");
                        return;
                    }
                    if(!sperseProductCode){
                        alert("Please Select Sperse Product");
                        return;
                    }
                    // if(!sperseTimeDurationId){
                    //     alert("Please Select Sperse Product Time Duration");
                    //     return;
                    // }
                    if(wpProductId && sperseProductCode){
                        var sperse_data = {
                            action : 'awp_save_sperse_mapping',
                            '_nonce': awp.nonce,
                            // 'accountId': this.fielddata.accountId,
                            'id':this.fielddata.sperse_accountId,
                            'task': this.action.task,
                            wpProductId:wpProductId,
                            wpProductName:wpProductName,
                            sperseProductCode:sperseProductCode,
                            sperseProductName:sperseProductName,
                            // sperseTimeDurationId:sperseTimeDurationId,
                            // sperseTimeDurationValue:sperseTimeDurationValue
                        };
                        
                        jQuery.post( ajaxurl, sperse_data, function( response ) {
                            response = JSON.parse(response);
                            // that.mappedProductLoading = false;
                            alert(response.message);
                             that.sections.splice(rowIndex, 1);
                            // that.sections[rowIndex].additionals.splice(rowIndex,1);
                            // document.getElementById('dropdown_sperse_account').dispatchEvent(new Event('change'));
                        });
                    }
                    
                    that.mappedProductLoading = true;
                    var tagRequestData = {
                'action': 'awp_get_mapped_products',
                '_nonce': awp.nonce,
                'id':this.fielddata.sperse_accountId,
            };
           // var that = this;
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                   
                    that.mappedProductLoading = false;
                    that.fielddata.mappedProducts = response.data;
                    // that.sections = response.data;
                     that.sections = (response.data) ? response.data : [];
               
            });
                    
                },

        removeSperseElement: function(rowIndex,index) {
            this.sections[rowIndex].sperseProducts.splice(index,1);
        },  
        removeElement: function(index) {
            // this.rows.splice(index, 1);
            this.sections.splice(index, 1);
        },
        addRow:function(event){
            
             this.sections.push({
                wpProduct: '',
                sperseProducts: []
                // additionals: []
            })
            
        },
        addNewItem:function(id){
            
            this.sections[id].sperseProducts.push({
                sperseProduct: ''
            });
            
            
            
        },
        saveNewItem: function(id){
            
            var productsValidation = false;
            if(this.sections[id].wpProduct == '' || this.sections[id].wpProduct == null){
                
                jQuery('#wpProductRow'+[id]).after("<span style='color:red'>This field is required.</span>");
                productsValidation = true;
                
            }else{
                
                jQuery('#wpProductRow'+[id]).next().remove();
                
                if(this.sections[id].sperseProducts.length === 0){
                    jQuery('.mapAndSaveBtns').after("<span style='color:red'>This field is required.</span>");
                    productsValidation = true;
                }else{
                     
                     jQuery('.mapAndSaveBtns').next().remove();
                    
                    jQuery.each(this.sections[id].sperseProducts, function(index, item) {
                        
                        if(item.sperseProduct == '' || item.sperseProduct == null){
                            jQuery('#sperseProductCol'+[id]+index).after("<span style='color:red'>This field is required.</span>");
                            productsValidation = true;
                        }else{
                             productsValidation = false;
                             jQuery('#sperseProductCol'+[id]+index).next().remove();
                        }  
                    });
                    
                    // if(this.sections[id].sperseProducts[0].sperseProduct != ''){
                        
                    // }else{
                    //      jQuery('#sperseProductCol'+[id]+'0').after("<span style='color:red'>This field is required.</span>");
                    // }    
                }
                
            }
            
            Array.prototype.unique = function() {
                var a = this.concat();
                for(var i=0; i<a.length; ++i) {
                    for(var j=i+1; j<a.length; ++j) {
                        if(a[i] === a[j])
                            a.splice(j--, 1);
                    }
                }
            
                return a;
            };
            if(!productsValidation){ 
              
              var sperse_data = {
                            action : 'awp_save_sperse_mapping',
                            '_nonce': awp.nonce,
                            // 'accountId': this.fielddata.accountId,
                            'id':this.fielddata.sperse_accountId,
                            'task': this.action.task,
                            'productMappingObj': JSON.stringify(this.sections.concat(this.fielddata.mappedProducts).unique().filter(n => n))
                            // sperseTimeDurationId:sperseTimeDurationId,
                            // sperseTimeDurationValue:sperseTimeDurationValue
                        };
                        
                        jQuery.post( ajaxurl, sperse_data, function( response ) {
                            response = JSON.parse(response);
                            // that.mappedProductLoading = false;
                            alert(response.message);
                            //  this.sections = ;
                            // that.sections[rowIndex].additionals.splice(rowIndex,1);
                            // document.getElementById('dropdown_sperse_account').dispatchEvent(new Event('change'));
                        });
              
            }
             
        },
        parseFieldValue:function(item_value){
            item_value = item_value.replace(/[{}]/g, "");
            return item_value;
        },
        CheckinDatabase:function(item,name){


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
        getLists: function() {
            var that = this;
            this.listLoading = true;
            var listData = {
                'action': 'awp_get_sperse_lists',
                '_nonce': awp.nonce,
                'accountId': this.fielddata.accountId,
                'task': this.action.task
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                // var lists = response.data;
                let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.lists = data;
                // that.fielddata.lists = lists;
                that.listLoading = false;
            });
        },
        getWpProducts: function() {
            var that = this;
            // this.listLoading = true;
            var listData = {
                'action': 'awp_get_sperse_wpproducts',
                '_nonce': awp.nonce,
                // 'accountId': this.fielddata.accountId,
                'task': this.action.task
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.wpproducts = lists;
                // that.listLoading = false;
            });  
        },
        setPMId:function(event,id){
            jQuery(('#'+id)).attr(id, event);
        },
        increaseCount:function(event){
            
            var that = this;
            that.fielddata.list = [];
            that.connectAccount = false;
            
            var sperse_account_id = event; 
	        this.listLoading = true;
	        var listRequestData = {
	            'action': 'awp_get_sperse_list',
	            '_nonce': awp.nonce,
	            'id':sperse_account_id
	        };
	        jQuery.post( ajaxurl, listRequestData, function( response ) {
	            
	           let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.list = data;
	            that.listLoading = false;
	            that.connectAccount = true;
                that.mountSelect2Box('fielddata.list');
	        });
	        this.StagesLoading = true;
	        var stagesRequestData = {
	            'action': 'awp_get_sperse_stages',
	            '_nonce': awp.nonce,
	            'id':sperse_account_id,
	            'groupId':(that.fielddata.listId) ? that.fielddata.listId : 'C'
	        };
	        jQuery.post( ajaxurl, stagesRequestData, function( response ) {
	           // that.fielddata.stages = response.data;
	           let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                //console.log(data);
                that.fielddata.stages = data;
	            that.StagesLoading = false;
                that.mountSelect2Box('fielddata.stages');
	        });
	        
	        this.tagLoading = true;
	        var tagRequestData = {
	            'action': 'awp_get_sperse_tags',
	            '_nonce': awp.nonce,
	            'id':sperse_account_id
	        };
	        jQuery.post( ajaxurl, tagRequestData, function( response ) {
	           // that.fielddata.tags = response.data;
	            let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.tags = data;
	            that.tagLoading = false;
                that.mountSelect2Box('fielddata.tags');
	        });

	        this.lisstLoading = true;
	        var listsRequestData = {
	            'action': 'awp_get_sperse_lists',
	            '_nonce': awp.nonce,
	            'id':sperse_account_id
	        };
	        jQuery.post( ajaxurl, listsRequestData, function( response ) {
	           // that.fielddata.lists = response.data;
	           // that.lisstLoading = false;
	           let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.lists = data;
                that.lisstLoading = false;
                that.mountSelect2Box('fielddata.lists');
	        });

	        this.usesLoading = true;
	        var UsersRequestData = {
	            'action': 'awp_get_sperse_ausers',
	            '_nonce': awp.nonce,
	            'id': sperse_account_id
	        };
	        jQuery.post( ajaxurl, UsersRequestData, function( response ) {
	           // that.fielddata.ausers = response.data;
	           let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.ausers = data;
	            that.usesLoading = false;
                that.mountSelect2Box('fielddata.ausers');
	        });

            this.productLoading = true;
            var tagRequestData = {
                'action': 'awp_get_sperse_products',
                '_nonce': awp.nonce,
                'id':sperse_account_id
            };
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                console.log(response);
                if(response.data.result != undefined){
                    that.fielddata.sperseProducts = response.data.result;
                    that.productLoading = false;
                    that.mountSelect2Box('fielddata.sperseProducts');
                }
            });

            this.mappedProductLoading = true;
            var tagRequestData = {
                'action': 'awp_get_mapped_products',
                '_nonce': awp.nonce,
                'id':sperse_account_id
            };
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                that.fielddata.mappedProducts = response.data;
                that.sections = (response.data) ? response.data : [];
                that.mappedProductLoading = false;
               // that.productLoading = false;
            });


                this.roleLoading = true;
                var rolesRequestData = {
                    'action': 'awp_get_sperse_roles',
                    '_nonce': awp.nonce,
                };
                if(!(typeof integration_id === "undefined")){
                    rolesRequestData['id']=integration_id;
                }

                jQuery.post( ajaxurl, rolesRequestData, function( response ) {
                    
                    let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.roles = data;
                    that.roleLoading = false;
                });

        },
        changeContactGroup:function(event){
             var that = this;
            // var sperse_contact_group = event.target.value;
            if(jQuery('.selectedStagesClassW button').attr('title') == 'Clear Selected') { jQuery('.selectedStagesClassW button').click(); }
            var sperse_contact_group = event; 
            this.StagesLoading = true;

            that.fielddata.stages =[];
              var stagesRequestData = {
                'action': 'awp_get_sperse_stages',
                '_nonce': awp.nonce,
                'id':this.fielddata.sperse_accountId,
                'groupId':sperse_contact_group
            };   
            jQuery.post( ajaxurl, stagesRequestData, function( response ) { 
                // that.fielddata.stages = response.data;
                let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                 //console.log(data);
                that.fielddata.stages = data;
                that.StagesLoading = false;
                that.mountSelect2Box('fielddata.stages');
            });
            this.tagLoading = true;
            var tagRequestData = {
                'action': 'awp_get_sperse_tags',
                '_nonce': awp.nonce,
                'id':this.fielddata.sperse_accountId,
                'groupId':sperse_contact_group
            };
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                // that.fielddata.tags = response.data;
                let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.tags = data;
                that.tagLoading = false;
                that.mountSelect2Box('fielddata.tags');
            });

            this.lisstLoading = true;
            var listsRequestData = {
                'action': 'awp_get_sperse_lists',
                '_nonce': awp.nonce,
                'id':this.fielddata.sperse_accountId,
                'groupId':sperse_contact_group

            };
            jQuery.post( ajaxurl, listsRequestData, function( response ) {
                // that.fielddata.lists = response.data;
                let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.lists = data;
                that.lisstLoading = false;
                that.mountSelect2Box('fielddata.lists');
            });

            this.usesLoading = true;
            var UsersRequestData = {
                'action': 'awp_get_sperse_ausers',
                '_nonce': awp.nonce,
                'id': this.fielddata.sperse_accountId,
                'groupId':sperse_contact_group

            };
            jQuery.post( ajaxurl, UsersRequestData, function( response ) {
                // that.fielddata.ausers = response.data;
                let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.ausers = data;
                that.usesLoading = false;
                that.mountSelect2Box('fielddata.ausers');
            });
            
        },
        triggerAssignmentData:function(){
            var that = this;
            var sperse_account_id=this.fielddata.activePlatformId;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_sperse_list',
                '_nonce': awp.nonce,
                'id':sperse_account_id
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                
               let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.list = data;
                that.listLoading = false;
                that.connectAccount = true;
                that.mountSelect2Box('fielddata.list');
            });
            this.StagesLoading = true;
            var stagesRequestData = {
                'action': 'awp_get_sperse_stages',
                '_nonce': awp.nonce,
                'id':sperse_account_id,
                'groupId':(that.fielddata.listId) ? that.fielddata.listId : 'C'
            };
            jQuery.post( ajaxurl, stagesRequestData, function( response ) {
               // that.fielddata.stages = response.data;
               let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                //console.log(data);
                that.fielddata.stages = data;
                that.StagesLoading = false;
                that.mountSelect2Box('fielddata.stages');
            });
            
            this.tagLoading = true;
            var tagRequestData = {
                'action': 'awp_get_sperse_tags',
                '_nonce': awp.nonce,
                'id':sperse_account_id
            };
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
               // that.fielddata.tags = response.data;
                let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.tags = data;
                that.tagLoading = false;
                that.mountSelect2Box('fielddata.tags');
            });

            this.lisstLoading = true;
            var listsRequestData = {
                'action': 'awp_get_sperse_lists',
                '_nonce': awp.nonce,
                'id':sperse_account_id
            };
            jQuery.post( ajaxurl, listsRequestData, function( response ) {
               // that.fielddata.lists = response.data;
               // that.lisstLoading = false;
               let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.lists = data;
                that.lisstLoading = false;
                that.mountSelect2Box('fielddata.lists');
            });

            this.usesLoading = true;
            var UsersRequestData = {
                'action': 'awp_get_sperse_ausers',
                '_nonce': awp.nonce,
                'id': sperse_account_id
            };
            jQuery.post( ajaxurl, UsersRequestData, function( response ) {
               // that.fielddata.ausers = response.data;
               let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.ausers = data;
                that.usesLoading = false;
                that.mountSelect2Box('fielddata.ausers');
            });

            this.productLoading = true;
            var tagRequestData = {
                'action': 'awp_get_sperse_products',
                '_nonce': awp.nonce,
                'id':sperse_account_id
            };
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                console.log(response);
                if(response.data.result != undefined){
                    that.fielddata.sperseProducts = response.data.result;
                    that.productLoading = false;
                    that.mountSelect2Box('fielddata.sperseProducts');
                }
            });

            this.mappedProductLoading = true;
            var tagRequestData = {
                'action': 'awp_get_mapped_products',
                '_nonce': awp.nonce,
                'id':sperse_account_id
            };
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                that.fielddata.mappedProducts = response.data;
                that.sections = (response.data) ? response.data : [];
                that.mappedProductLoading = false;
               // that.productLoading = false;
            });


                this.roleLoading = true;
                var rolesRequestData = {
                    'action': 'awp_get_sperse_roles',
                    '_nonce': awp.nonce,
                };
                if(!(typeof integration_id === "undefined")){
                    rolesRequestData['id']=integration_id;
                }

                jQuery.post( ajaxurl, rolesRequestData, function( response ) {
                    
                    let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.roles = data;
                    that.roleLoading = false;
                });
        },
    },
    beforeMount: function() {
        this.getWpProducts();
    },
    updated:function () {
        makedropable();
      
    },
    created: function() {
        //console.log('hit');
    },

    mounted: function() {
        var that = this;
        if (typeof this.fielddata.systemType    == 'undefined') { this.fielddata.systemType    = ''; }
        if (typeof this.fielddata.affiliate_code    == 'undefined') { this.fielddata.affiliate_code    = ''; }  

        if (typeof this.fielddata.entryUrl    == 'undefined') { this.fielddata.entryUrl    = awp.entryUrl; }
        if (typeof this.fielddata.referrerUrl    == 'undefined') { this.fielddata.referrerUrl    = awp.referrerUrl; }   
        if (typeof this.fielddata.userAgent    == 'undefined') { this.fielddata.userAgent    = awp.userAgent; }
        if (typeof this.fielddata.redirectUrl    == 'undefined') { this.fielddata.redirectUrl    = awp.redirectUrl; }
        
        if (typeof this.fielddata.email         == 'undefined') { this.fielddata.email         = ''; }
        if (typeof this.fielddata.fullName      == 'undefined') { this.fielddata.fullName      = ''; }
        if (typeof this.fielddata.firstName     == 'undefined') { this.fielddata.firstName     = ''; }
        if (typeof this.fielddata.lastName      == 'undefined') { this.fielddata.lastName      = ''; }
        if (typeof this.fielddata.companyName   == 'undefined') { this.fielddata.companyName   = ''; }
        if (typeof this.fielddata.jobTitle      == 'undefined') { this.fielddata.jobTitle      = ''; }
        if (typeof this.fielddata.industry      == 'undefined') { this.fielddata.industry      = ''; }
        if (typeof this.fielddata.phoneNumber   == 'undefined') { this.fielddata.phoneNumber   = ''; }
        if (typeof this.fielddata.bankCode      == 'undefined') { this.fielddata.bankCode      = ''; }
        if (typeof this.fielddata.password      == 'undefined') { this.fielddata.password      = ''; }
        if (typeof this.fielddata.note          == 'undefined') { this.fielddata.note          = ''; }
        if (typeof this.fielddata.streetAddress == 'undefined') { this.fielddata.streetAddress = ''; }
        if (typeof this.fielddata.city          == 'undefined') { this.fielddata.city          = ''; }
        if (typeof this.fielddata.stateName     == 'undefined') { this.fielddata.stateName     = ''; }
        if (typeof this.fielddata.stateId       == 'undefined') { this.fielddata.stateId       = ''; }
        if (typeof this.fielddata.countryId     == 'undefined') { this.fielddata.countryId     = ''; }
        if (typeof this.fielddata.zipCode       == 'undefined') { this.fielddata.zipCode       = ''; }
        if (typeof this.fielddata.webURL        == 'undefined') { this.fielddata.webURL        = ''; }        
        if (typeof this.fielddata.answerQ1      == 'undefined') { this.fielddata.answerQ1      = ''; }        
        if (typeof this.fielddata.answerQ2      == 'undefined') { this.fielddata.answerQ2      = ''; }        
        if (typeof this.fielddata.answerQ3      == 'undefined') { this.fielddata.answerQ3      = ''; }       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        this.sperseLoading = true;
        var SperseRequestData = {
            'action': 'awp_get_sperse_account',
            '_nonce': awp.nonce,
        };

        if(typeof editable_field != 'undefined'){
            jQuery.post( ajaxurl, SperseRequestData, function( response ) {
                that.fielddata.sperseAccounts = response.data;
                that.sperseLoading = false;
            });    
        }
        

        this.listLoading = true;
        var listRequestData = {
            'action': 'awp_get_sperse_list',
            '_nonce': awp.nonce,
        };

        if(!(typeof integration_id === "undefined") ){
        	listRequestData['id']=integration_id;
        }

        if(typeof editable_field != 'undefined'){
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                // that.fielddata.list = response.data;
                let data = [];
                let lists = response.data;
                for (var key in lists) {
                    if (lists.hasOwnProperty(key)) {
                        data.push({id: key, name: lists[key]});
                    }
                }
                that.fielddata.list = data;
                that.listLoading = false;
            });

        }
        


        this.StagesLoading = true;
        var stagesRequestData = {
            'action': 'awp_get_sperse_stages',
            '_nonce': awp.nonce,
            'groupId':(that.fielddata.listId) ? that.fielddata.listId : 'C'
        };

        if(!(typeof integration_id === "undefined")){
        	stagesRequestData['id']=integration_id;
        }

        if(typeof editable_field != 'undefined'){
            jQuery.post( ajaxurl, stagesRequestData, function( response ) {
                // that.fielddata.stages = response.data;
                let data = [];
                    let lists = response.data;
                    for (var key in lists) {
                        if (lists.hasOwnProperty(key)) {
                            data.push({id: key, name: lists[key]});
                        }
                    }
                     //console.log(data);
                    that.fielddata.stages = data;
                that.StagesLoading = false;
                that.mountSelect2Box('fielddata.stages');
            });
        }
        
        
        this.tagLoading = true;
        var tagRequestData = {
            'action': 'awp_get_sperse_tags',
            '_nonce': awp.nonce,
        };

        if(!(typeof integration_id === "undefined")){
        	tagRequestData['id']=integration_id;
        }

        if(typeof editable_field != 'undefined'){
            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                // that.fielddata.tags = response.data;
                let data = [];
                    let lists = response.data;
                    for (var key in lists) {
                        if (lists.hasOwnProperty(key)) {
                            data.push({id: key, name: lists[key]});
                        }
                    }
                    that.fielddata.tags = data;
                that.tagLoading = false;
                that.mountSelect2Box('fielddata.tags');
            });

        }
        

        this.lisstLoading = true;
        var listsRequestData = {
            'action': 'awp_get_sperse_lists',
            '_nonce': awp.nonce,
        };
        if(!(typeof integration_id === "undefined")){
        	listsRequestData['id']=integration_id;
        }

        if(typeof editable_field != 'undefined'){

            jQuery.post( ajaxurl, listsRequestData, function( response ) {
                // that.fielddata.lists = response.data;
                let data = [];
                    let lists = response.data;
                    for (var key in lists) {
                        if (lists.hasOwnProperty(key)) {
                            data.push({id: key, name: lists[key]});
                        }
                    }
                    that.fielddata.lists = data;
                that.lisstLoading = false;
                that.mountSelect2Box('fielddata.lists');
            });

        }
        
   

        this.roleLoading = true;
        var rolesRequestData = {
            'action': 'awp_get_sperse_roles',
            '_nonce': awp.nonce,
        };
        if(!(typeof integration_id === "undefined")){
            rolesRequestData['id']=integration_id;
        }
        

        if(typeof editable_field != 'undefined'){
            jQuery.post( ajaxurl, rolesRequestData, function( response ) {
          
                let data = [];
                    let lists = response.data;
                    for (var key in lists) {
                        if (lists.hasOwnProperty(key)) {
                            data.push({id: key, name: lists[key]});
                        }
                    }
                that.fielddata.roles = data;
                that.roleLoading = false;
            });

        }
        


        this.usesLoading = true;
        var UsersRequestData = {
            'action': 'awp_get_sperse_ausers',
            '_nonce': awp.nonce,
        };
        if(!(typeof integration_id === "undefined")){
        	UsersRequestData['id']=integration_id;
        }

        if(typeof editable_field != 'undefined'){

            jQuery.post( ajaxurl, UsersRequestData, function( response ) {
                // that.fielddata.ausers = response.data;
                let data = [];
                    let lists = response.data;
                    for (var key in lists) {
                        if (lists.hasOwnProperty(key)) {
                            data.push({id: key, name: lists[key]});
                        }
                    }
                    that.fielddata.ausers = data;
                that.usesLoading = false;
                that.mountSelect2Box('fielddata.ausers');
            });

        }
        

        // this.productLoading = true;
        // var tagRequestData = {
        //     'action': 'awp_get_sperse_products',
        //     '_nonce': awp.nonce,
        //   // 'id':1
        // };
        // if(!(typeof integration_id === "undefined")){
        // tagRequestData['id']=integration_id;
        // }
        // jQuery.post( ajaxurl, tagRequestData, function( response ) {
        //     console.log(response);
        //         console.log(response.data.result);
        //     that.fielddata.sperseProducts = response.data.result;
        //     that.productLoading = false;
        // });

        this.mappedProductLoading = true;
            var tagRequestData = {
                'action': 'awp_get_mapped_products',
                '_nonce': awp.nonce
            };
            if(!(typeof integration_id === "undefined")){
            tagRequestData['id']=integration_id;
        }

        if(typeof editable_field != 'undefined'){

            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                that.fielddata.mappedProducts = response.data;
                 that.sections = (response.data) ? response.data : [];
                that.mappedProductLoading = false;
               // that.productLoading = false;
            });

        }
        


        //added by faizan
        this.fielddata.inviteOptions=[{'id': 'false', 'name': 'false'},{'id': 'true', 'name': 'true'}];
        //end faizan

    },
    template: '#sperse-action-template'
});


Vue.component('influencersoft', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            lisstLoading: false,
            StagesLoading:false,
            tagLoading:false,
            usesLoading: false,
            influencersoftLoading:false,
            influencersoftListLoading:false,
            fields: [
                {value: 'email'        , title: 'Email'                , task: ['createLead','UpdateSubscriberData','unsubscribe'], required: true },
                {value: 'fullName'     , title: 'Full Name'            , task: ['createLead','UpdateSubscriberData'], required: false},                
                {value: 'phoneNumber'  , title: 'Phone'                , task: ['createLead','UpdateSubscriberData'], required: false},
                {value: 'city'         , title: 'City or Town'         , task: ['createLead','UpdateSubscriberData'], required: false},                
            ]
        }
    },
    methods: {
        say:function(event){

        },
        getLists: function() {
            var that = this;
            this.influencersoftListLoading = true;
            var listData = {
                'action': 'awp_get_influencersoft_list',
                '_nonce': awp.nonce,
                'accountId': this.fielddata.accountId,
                'task': this.action.task
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.influencersoftList = response.data;
                that.influencersoftListLoading = false;
            });
        },
        increaseCount:function(event){
            var that = this;
            var sperse_account_id = event.target.value; 
            this.influencersoftListLoading = true;
            var listRequestData = {
                'action': 'awp_get_influencersoft_list',
                '_nonce': awp.nonce,
                'id':sperse_account_id
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                //console.log(response);
                //console.log(that);

                that.fielddata.influencersoftList = response.data;
                that.fielddata.lists = response.data;
                that.influencersoftListLoading = false;
            });
        },
        CheckinDatabase:function(item,name){

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


    },
    updated:function () {
        //makedropable();
    },
    created: function() {
        //console.log('hit');
    },
    mounted: function() {
        var that = this;
        
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        this.influencersoftLoading = true;
        var influencersoftRequestData = {
            'action': 'awp_get_influencersoft_account',
            '_nonce': awp.nonce,
        };

        jQuery.post( ajaxurl, influencersoftRequestData, function( response ) {
            that.fielddata.influencersoftAccounts = response.data;
            that.influencersoftLoading = false;
        });


    },
    template: '#influencersoft-action-template'
});

Vue.component('elasticemail', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]
        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

        getElasticEmailList:function(){
            var that = this;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_elasticemail_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    created: function() {
    },
    mounted: function() {
        var that = this;
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        var that = this;
        this.listLoading = true;
        var listRequestData = {
            'action': 'awp_get_elasticemail_list',
            '_nonce': awp.nonce
        };
        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#elasticemail-action-template'
});

Vue.component('mailerlite', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false}
            ]
        }
    },
    methods: {
                CheckinDatabase:function(item,name){

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
        getMailerliteList:function(){
            var that = this;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_mailerlite_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    created: function() {
    },
    mounted: function() {
        var that = this;
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#mailerlite-action-template'
});

Vue.component('emailoctopus', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]
        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
        getEmailoctopusList:function(){
            var that = this;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_emailoctopus_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    created: function() {
    },
    mounted: function() {
        var that = this;
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#emailoctopus-action-template'
});

Vue.component('jumplead', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#jumplead-action-template'
});

Vue.component('klaviyo', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['unsubscribe'], required: true},
                {value: 'phoneNumber', title: 'Phone Number', task: ['unsubscribe'], required: false},
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {value: 'title', title: 'Title', task: ['subscribe'], required: false},
                {value: 'organization', title: 'Organization', task: ['subscribe'], required: false},
                {value: 'phoneNumber', title: 'Phone Number', task: ['subscribe'], required: false},
                {value: 'address1', title: 'Address 1', task: ['subscribe'], required: false},
                {value: 'address2', title: 'Address 2', task: ['subscribe'], required: false},
                {value: 'region', title: 'Region', task: ['subscribe'], required: false},
                {value: 'zip', title: 'ZIP', task: ['subscribe'], required: false},
                {value: 'country', title: 'Country', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
        getKlaviyoList:function(){
            var that = this;
            this.listLoading = true;
            
            var listRequestData = {
                'action': 'awp_get_klaviyo_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

     
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
                that.$forceUpdate();
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#klaviyo-action-template'
});

Vue.component('moosend', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
                CheckinDatabase:function(item,name){

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
        getMoosendList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_moosend_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        
    },
    template: '#moosend-action-template'
});

Vue.component('sendy', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#sendy-action-template'
});

Vue.component('convertkit', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

        getConvertKitLists:function(){
            var that = this;
            this.listLoading = true;
            this.tagLoading  = true;
            var listRequestData = {
            'action': 'awp_get_convertkit_list',
            'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });


            var tagRequestData = {
                'action': 'awp_get_convertkitpro_tags',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, tagRequestData, function( response ) {
                that.fielddata.tagList = response.data;
                that.tagLoading = false;
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);



        

        
    },
    template: '#convertkit-action-template'
});

Vue.component('getresponse', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            tagLoading:false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

        CheckinDatabase:function(item,name){

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
        getGetresponseList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_getresponse_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;                
                that.listLoading = false;
            });
        },
        getGetresponseTags:function(){
            this.tagLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_getresponse_tags',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.tagList = response.data;                
                that.tagLoading = false;
            });
        },


    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#getresponse-action-template'
});

Vue.component('mailjet', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

        CheckinDatabase:function(item,name){

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
        getMailjetList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_mailjet_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#mailjet-action-template'
});

Vue.component('mailify', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'phone', title: 'Phone', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
        getMailifyList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_mailify_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
              //  console.log(response);
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#mailify-action-template'
});

Vue.component('directiq', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

        getDirectiqList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_directiq_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#directiq-action-template'
});

Vue.component('revue', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#revue-action-template'
});

Vue.component('liondesk', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {value: 'lasName', title: 'Last Name', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#liondesk-action-template'
});

Vue.component('curated', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true}
            ]

        }
    },
    methods: {
        CheckinDatabase:function(item,name){

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

    },
    mounted: function() {
        var that = this;

        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#curated-action-template'
});

Vue.component('sendinblue', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
        methods: {
                CheckinDatabase:function(item,name){

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
        getSendinblueList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_sendinblue_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },

    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#sendinblue-action-template'
});

Vue.component('zapier', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {}
    },
    mounted: function() {

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
       
    },
    template: '#zapier-action-template'
});

Vue.component('webhookout', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {}
    },
    mounted: function() {

        if (typeof this.fielddata.webhookoutUrl == 'undefined') {
            this.fielddata.webhookoutUrl = '';
        }
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        
    },
    template: '#webhookout-action-template'
});

Vue.component('webhook-row', {
    props: ["trigger", "action", "fielddata"],
    template: '#webhook-row-template'
});

Vue.component('varisend', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            messageLoading: false,
            accountSelectType:false,
            selectedFieldValue:false,
            staticAccounts:[],
            staticAccountLoading:false,
            fields: [
                {value: 'email',    title: 'Email'   , task: ['add_contact'], required: true},
                {value: 'name',     title: 'Name'    , task: ['add_contact'], required: false},
                {value: 'phone',    title: 'Phone'   , task: ['add_contact'], required: false},
                {value: 'bankcode', title: 'BANKCODE', task: ['add_contact'], required: false},                
            ],
        }
    },
    methods: {

        changeAccountSelectType:function(){

            var that = this;
            this.staticAccountLoading = true;
            var listData = {
                'action': 'awp_get_varisend_accounts',
                '_nonce': awp.nonce,
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.staticAccounts = lists;
                that.staticAccountLoading = false;
            });
        },
        getList:function(){
            var that = this;
            this.messageLoading = true;
            var listData = {
                'action': 'awp_get_messages_list2',
                '_nonce': awp.nonce,
                'messageTemplateId': this.fielddata.messageTemplateId,
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.messageTemplates = lists;
                that.messageLoading = false;
            });

        },
        CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;


       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.messageLoading = true;

        var listRequestData = {
            'action': 'awp_get_messages_list2',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.messageTemplates = response.data;
            that.messageLoading = false;
        });
    },
    template: '#varisend-action-template'
});


Vue.component('codebreakersms', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            messageLoading: false,
            accountSelectType:false,
            selectedFieldValue:false,
            staticAccounts:[],
            staticAccountLoading:false,
            fields: [
                {value: 'email',    title: 'Email'   , task: ['add_contact'], required: true},
                {value: 'name',     title: 'Name'    , task: ['add_contact'], required: false},
                {value: 'phone',    title: 'Phone'   , task: ['add_contact'], required: false},
                {value: 'bankcode', title: 'BANKCODE', task: ['add_contact'], required: false},                
            ],
        }
    },
    methods: {

        changeAccountSelectType:function(){

            var that = this;
            this.staticAccountLoading = true;
            var listData = {
                'action': 'awp_get_codebreaker_accounts',
                '_nonce': awp.nonce,
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.staticAccounts = lists;
                that.staticAccountLoading = false;
            });
        },
        getList:function(){
            var that = this;
            this.messageLoading = true;
            var listData = {
                'action': 'awp_get_messages_list',
                '_nonce': awp.nonce,
                'messageTemplateId': this.fielddata.messageTemplateId,
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.messageTemplates = lists;
                that.messageLoading = false;
            });

        },
        CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;


        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.messageLoading = true;

        var listRequestData = {
            'action': 'awp_get_messages_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.messageTemplates = response.data;
            that.messageLoading = false;
        });
    },
    template: '#codebreakersms-action-template'
});


Vue.component('drip', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['create_subscriber'], required: true},
                {value: 'firstName', title: 'First Name', task: ['create_subscriber'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['create_subscriber'], required: false}
            ]

        }
    },
    methods: {
        getDripAccount:function(){
            this.accountLoading = true;
            var that = this;
            var accountRequestData = {
                'action': 'awp_get_drip_accounts',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, accountRequestData, function( response ) {
                that.fielddata.accounts = response.data;
                that.accountLoading = false;
            });
        },
        getList: function() {
            var that = this;
            this.accountLoading = true;

            var listData = {
                'action': 'awp_get_drip_list',
                '_nonce': awp.nonce,
                'accountId': this.fielddata.accountId,
                'task': this.action.task,
                'platformid':this.fielddata.activePlatformId
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var list = response.data;
                that.fielddata.list = list;
                that.accountLoading = false;
            });
        },
                CheckinDatabase:function(item,name){

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




    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);


    },
    template: '#drip-action-template'
});

Vue.component('autopilot', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'twitter', title: 'Twitter', task: ['subscribe'], required: false},
                {type: 'text', value: 'salutation', title: 'Salutation', task: ['subscribe'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['subscribe'], required: false},
                {type: 'text', value: 'numberOfEmployees', title: 'Number Of Employees', task: ['subscribe'], required: false},
                {type: 'text', value: 'title', title: 'Title', task: ['subscribe'], required: false},
                {type: 'text', value: 'industry', title: 'Industry', task: ['subscribe'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'mobilePhone', title: 'MobilePhone', task: ['subscribe'], required: false},
                {type: 'text', value: 'fax', title: 'Fax', task: ['subscribe'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingStreet', title: 'MailingStreet', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingCity', title: 'MailingCity', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingState', title: 'MailingState', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingPostalCode', title: 'MailingPostalCode', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingCountry', title: 'MailingCountry', task: ['subscribe'], required: false},
                {type: 'text', value: 'leadSource', title: 'LeadSource', task: ['subscribe'], required: false},
                {type: 'text', value: 'linkedIn', title: 'LinkedIn', task: ['subscribe'], required: false}

            ]

        }
    },
    methods: {

           CheckinDatabase:function(item,name){

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

        getAutopilotList:function(){
            this.listLoading = true;
            var that=this;
            var listRequestData = {
                'action': 'awp_get_autopilot_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }


    },
    created: function() {

    },
    mounted: function() {
        var that = this;

      
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        


    },
    template: '#autopilot-action-template'
});


Vue.component('benchmark', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'middleName', title: 'Middle Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
        CheckinDatabase:function(item,name){

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
        getBenchmarkList:function(){
            var that = this;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_benchmark_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;


        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#benchmark-action-template'
});

Vue.component('capsulecrm', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                
                {type: 'text', value: 'email', title: 'Email', task: ['add_person','add_organisation'], required: true},
                {type: 'text', value: 'name', title: 'Organisation Name', task: ['add_organisation'], required: false},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_person'], required: true},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_person'], required: false},
                {type: 'text', value: 'title', title: 'Title', task: ['add_person'], required: false},
                {type: 'text', value: 'phoneNumbers', title: 'Phone', task: ['add_person','add_organisation'], required: true},
                {type: 'text', value: 'addresses', title: 'Address', task: ['add_person','add_organisation'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['add_person','add_organisation'], required: false},
                {type: 'text', value: 'country', title: 'Country ', task: ['add_person','add_organisation'], required: false},
                {type: 'text', value: 'state', title: 'State ', task: ['add_person','add_organisation'], required: false},
                {type: 'text', value: 'zip', title: 'Postal Code ', task: ['add_person','add_organisation'], required: false},
                {type: 'text', value: 'websites', title: 'Website ', task: ['add_person','add_organisation'], required: false},
                {type: 'text', value: 'jobTitle', title: 'Job Title ', task: ['add_person'], required: false},
                {type: 'text', value: 'about', title: 'About ', task: ['add_person'], required: false},
                {type: 'text', value: 'pictureURL', title: 'Picture Url', task: ['add_person'], required: false}
                
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
    },
    created: function() {

    },
    mounted: function() {
        var that = this;



        

      
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        // var pipelineRequestData = {
        //     'action': 'awp_get_agilecrm_pipelines',
        //     '_nonce': awp.nonce
        // };

        // jQuery.post( ajaxurl, pipelineRequestData, function( response ) {

        //     if( response.success ) {
        //         if( response.data ) {
        //             response.data.map(function(single) {
        //                 that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_contact'], required: false, description: single.description } );
        //             });
        //         }
        //     }
        // });

    },
    updated:function(){

    },
    template: '#capsulecrm-action-template'
});




Vue.component('asana', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            workspaceLoading: false,
            projectLoading: false,
            sectionLoading: false,
            userLoading: false,
            fields: [
                {type: 'text', value: 'name', title: 'Name', task: ['create_task'], required: true},
                {type: 'textarea', value: 'notes', title: 'Notes', task: ['create_task'], required: false},
                {type: 'text', value: 'dueOn', title: 'Due On', task: ['create_task'], required: false, description: 'Use YYYY-MM-DD format'},
            ]

        }
    },
    methods: {
        CheckinDatabase:function(item,name){

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
        getProjects: function() {
            var that = this;
            this.projectLoading = true;
            this.userLoading = true;

            var projectData = {
                'action': 'awp_get_asana_projects',
                '_nonce': awp.nonce,
                'platformid':this.fielddata.activePlatformId,
                'workspaceId': this.fielddata.workspaceId
            };

            jQuery.post( ajaxurl, projectData, function( response ) {
                var projects = response.data;
                that.fielddata.projects = projects;
                that.projectLoading = false;
            });

            var userData = {
                'action': 'awp_get_asana_users',
                '_nonce': awp.nonce,
                'platformid':this.fielddata.activePlatformId,
                'workspaceId': this.fielddata.workspaceId
            };

            jQuery.post( ajaxurl, userData, function( response ) {
                var users = response.data;
                that.fielddata.users = users;
                that.userLoading = false;
            });
        },
        getSections: function() {
            var that = this;
            this.sectionLoading = true;

            var sectionData = {
                'action': 'awp_get_asana_sections',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce,
                'projectId': this.fielddata.projectId
            };

            jQuery.post( ajaxurl, sectionData, function( response ) {
                var sections = response.data;
                that.fielddata.sections = sections;
                that.sectionLoading = false;
            });
        },
        getAsanaWorkspaces:function(){
            this.workspaceLoading = true;
            var that=this;

            var workspaceRequestData = {
                'action': 'awp_get_asana_workspaces',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, workspaceRequestData, function( response ) {
                
                that.fielddata.workspaces = response.data;
                that.workspaceLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        


        if( this.fielddata.workspaceId ) {
            this.getProjects();
        }

        if( this.fielddata.workspaceId && this.fielddata.projectId ) {
            this.getSections();
        }
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#asana-action-template'
});



Vue.component('slack', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'textarea', value: 'message', title: 'Message', task: ['sendmsg'], required: false}
            ]

        }
    },
    methods: {
        CheckinDatabase:function(item,name){

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
    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#slack-action-template'
});





Vue.component('sendpulse', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
        CheckinDatabase:function(item,name){

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
        getSendpulseList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_sendpulse_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        },
        getSendpulseAdditionalVariables:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_sendpulse_additionalvariables',
                'bookid':this.fielddata.listId,
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                if(response.success){
                    if(response.data.length){
                        var tempfields=[
                            {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                            {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false}

                        ];
                        for (var i = 0 ; i < response.data.length; i++) {
                            that.fielddata[response.data[i].name.toLowerCase()]='';
                            tempfields.push({type: 'text', value:response.data[i].name.toLowerCase(), title: response.data[i].name, task: ['subscribe'], required: false});
                        }
                        that.$set(that, 'fields', tempfields);
                    }
                    
                }
                that.listLoading = false;
                
            });
        }

        
    },
    created: function() {

    },
    updated:function(){
        
    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        if(this.fielddata.listId){
            this.getSendpulseAdditionalVariables();
        }
        if (typeof this.fielddata.phone == 'undefined') {
            this.fielddata.phone = '';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#sendpulse-action-template'
});

Vue.component('trello', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            boardLoading: false,
            listLoading: false,
            fields: [
                {type: 'text', value: 'name', title: 'Name', task: ['add_card'], required: true},
                {type: 'textarea', value: 'description', title: 'Description', task: ['add_card'], required: false},
                {type: 'text', value: 'pos', title: 'Position', task: ['add_card'], required: false, description: 'The position of the new card. top, bottom, or a positive float'}
            ]

        }
    },
    methods: {
        CheckinDatabase:function(item,name){

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
        getLists: function() {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'awp_get_trello_lists',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce,
                'boardId': this.fielddata.boardId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.lists = lists;
                that.listLoading = false;
            });
        },
        getTrelloList:function(){
            this.boardLoading = true;
            var that = this;
            var boardRequestData = {
                'action': 'awp_get_trello_boards',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, boardRequestData, function( response ) {
                that.fielddata.boards = response.data;
                that.boardLoading = false;
            });

            if( this.fielddata.boardId ) {
                var that = this;
                this.listLoading = true;

                var listData = {
                    'action': 'awp_get_trello_lists',
                    'platformid':this.fielddata.activePlatformId,
                    '_nonce': awp.nonce,
                    'boardId': this.fielddata.boardId,
                    'task': this.action.task
                };

                jQuery.post( ajaxurl, listData, function( response ) {
                    var lists = response.data;
                    that.fielddata.lists = lists;
                    that.listLoading = false;
                });
            }
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

      
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#trello-action-template'
});

Vue.component('pipedrive', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            ownerLoading: false,
            worksheetLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {value: 'name', title: 'Name', task: ['add_contact'], required: false},
                {value: 'note_content', title: 'Content [Note]', task: ['add_contact'], required: false, description: ''},
                {value: 'act_subject', title: 'Subject [Activity]', task: ['add_contact'], required: false, description: ''},
                {value: 'act_type', title: 'Type [Activity]', task: ['add_contact'], required: false, description: 'Example: call, meeting, task, deadline, email, lunch'},
                {value: 'act_due_date', title: 'Due Date [Activity]', task: ['add_contact'], required: false, description: 'Format: YYYY-MM-DD'},
                {value: 'act_due_time', title: 'Due Time [Activity]', task: ['add_contact'], required: false, description: 'Format: HH:MM'},
                {value: 'act_duration', title: 'Duration [Activity]', task: ['add_contact'], required: false, description: 'Format: HH:MM'},
                {value: 'act_note', title: 'Note [Activity]', task: ['add_contact'], required: false, description: ''},
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
        getPipeDriveData:function(){
            this.ownerLoading = true;
            var that = this;
            var ownerRequestData = {
                'action': 'awp_get_pipedrive_owner_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, ownerRequestData, function( response ) {

                that.fielddata.ownerList = response.data;
                that.ownerLoading = false;
            });

            var orgRequestData = {
                'action': 'awp_get_pipedrive_org_fields',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, orgRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                        });
                    }
                }
            });

            var personRequestData = {
                'action': 'awp_get_pipedrive_person_fields',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, personRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                        });
                    }
                }
            });

            var dealRequestData = {
                'action': 'awp_get_pipedrive_deal_fields',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, dealRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                        });
                    }
                }
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        var that = this;


       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#pipedrive-action-template'
});

Vue.component('omnisend', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {value: 'phone', title: 'Phone', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;


        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#omnisend-action-template'
});

Vue.component('close', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'orgName', title: 'Organization Name', task: ['add_lead'], required: true},
                {value: 'url', title: 'URL', task: ['add_lead'], required: false},
                {value: 'description', title: 'Description', task: ['add_lead'], required: false},
                {value: 'contactName', title: 'Contact Name', task: ['add_lead'], required: false},
                {value: 'title', title: 'Title', task: ['add_lead'], required: false},
                {value: 'email', title: 'Email', task: ['add_lead'], required: false},
                {value: 'phone', title: 'Phone', task: ['add_lead'], required: false},
                {value: 'address1', title: 'Address 1', task: ['add_lead'], required: false},
                {value: 'address2', title: 'Address 2', task: ['add_lead'], required: false},
                {value: 'city', title: 'City', task: ['add_lead'], required: false},
                {value: 'zip', title: 'Zip', task: ['add_lead'], required: false},
                {value: 'state', title: 'State', task: ['add_lead'], required: false},
                {value: 'country', title: 'Country', task: ['add_lead'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;


       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#close-action-template'
});


// Vue.component('salesforce', {
//     props: ["trigger", "action", "fielddata"],
//     data: function () {
//         return {
//             listLoading: false,
//             fields: [
//                 {value: 'email', title: 'Email', task: ['subscribe'], required: true},
//                 {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
//                 {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
//                 {value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
//                 {value: 'company', title: 'Company', task: ['subscribe'], required: false},
//                 {value: 'jobTitle', title: 'Job Title', task: ['subscribe'], required: false},
//                 {value: 'industry', title: 'Industry', task: ['subscribe'], required: false},
//                 {value: 'webURL', title: 'Website', task: ['subscribe'], required: false},
                

//             ]

//         }
//     },
//     methods: {

//                 CheckinDatabase:function(item,name){

//             var not_match = true;

//             var saved_item = '{{'+item+'}}';
//             if(!(typeof fieldData == 'undefined')){
//                  var fieldaa = this.fielddata;
//                  for(i in fieldaa){

//                     if(fieldaa[i]){

//                         if(i=='answerQ1' || i=='answerQ2' || i=='answerQ3'){

//                             if(fieldaa[i].length==2){
                                
//                                 if(fieldaa[i][1]==item){
//                                     not_match = false;
//                                 }
//                             }

//                         }else{

//                             if(fieldaa[i] &&  (typeof fieldaa[i]==='string')  ){

//                                 if(fieldaa[i].includes(saved_item)){
//                                     not_match = false;
//                                 }
//                             }
//                         }

//                     }
//                  }
                
//             }

//             return not_match;
//         },

//     },
//     created: function() {

//     },
//     mounted: function() {
//         var that = this;


//         if (typeof this.fielddata.email == 'undefined') {
//             this.fielddata.email = '';
//         }

//         if (typeof this.fielddata.firstName == 'undefined') {
//             this.fielddata.firstName = '';
//         }

//         if (typeof this.fielddata.lastName == 'undefined') {
//             this.fielddata.lastName = '';
//         }
//     },
//     template: '#salesforce-action-template'
// });



// Vue.component('salesforce', {
//     props: ["trigger", "action", "fielddata"],
//     data: function () {
//         return {
//             accountLoading: false,
//             listLoading: false,
//             fields: [
//                  {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
//                 {value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
//                 {value: 'company', title: 'Company', task: ['subscribe'], required: false},
//                 {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
//                 {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
//                 {value: 'jobTitle'     , title: 'Job Title'            , task: ['subscribe'], required: false},
//                 {value: 'industry'     , title: 'Industry'             , task: ['subscribe'], required: false},                                
//                 {value: 'webURL'       , title: 'Your Website URL'     , task: ['subscribe'], required: false},       
//             ]

//         }
//     },
//     methods: {
//         getLists: function() {
//             /*var that = this;
//             this.listLoading = true;
//             var listData = {
//                 'action': 'awp_get_aweber_lists',
//                 'formData': jQuery('#new-integration').serialize(),
//                 'triggerData': this.trigger,
//                 'actionData': this.action,
//                 'fieldData': this.fieldData,
//                 'task': this.action.task,
//                 'accountId': this.fielddata.accountId,

//             };
//             jQuery.post( 'post.php', listData, function( response ) {
//                 console.log(response);
//                 var lists = JSON.parse(response);
//                 that.fielddata.lists = lists;
//                 that.listLoading = false;
//             });*/
//         },
//         CheckinDatabase:function(item,name){

//             var not_match = true;

//             var saved_item = '{{'+item+'}}';
//             if(!(typeof fieldData == 'undefined')){
//                  var fieldaa = this.fielddata;
//                  for(i in fieldaa){

//                     if(fieldaa[i]){

//                         if(i=='answerQ1' || i=='answerQ2' || i=='answerQ3'){

//                             if(fieldaa[i].length==2){
                                
//                                 if(fieldaa[i][1]==item){
//                                     not_match = false;
//                                 }
//                             }

//                         }else{

//                             if(fieldaa[i] &&  (typeof fieldaa[i]==='string')  ){

//                                 if(fieldaa[i].includes(saved_item)){
//                                     not_match = false;
//                                 }
//                             }
//                         }

//                     }
//                  }
                
//             }

//             return not_match;
//         },

//     },
//     created: function() {

//     },
//     mounted: function() {
//         var that = this;

        
//         //reset activeplatformid value when new component mounts
//         if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
//             this.fielddata.activePlatformId='';
//         }
//         this.fielddata=setComponentFieldData(this.fielddata,this.fields);
//         // this.accountLoading = true;

//         // var accountRequestData = {
//         //     'action': 'awp_get_salesforce_accounts',
//         //     'formData': jQuery('#new-integration').serialize(),
//         //     'triggerData': this.trigger,
//         //     'actionData': this.action,
//         //     'fieldData': this.fieldData,
//         //     'task': this.action.task
//         // };


//         // jQuery.post( 'post.php', accountRequestData, function( response ) {
//         //     console.log(response);
//         //     that.fielddata.accounts = JSON.parse(response);
//         //     that.accountLoading = false;
//         // });
//     },
//     template: '#salesforce-action-template'
// });


Vue.component('salesforce', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            listLoading: false,
            salesforceaccountLoading:false,
            fields: [
                {value: 'email', title: 'Email', task: ['create_contact', 'unsubscribe'], required: true},
                {value: 'phone', title: 'Phone', task: ['create_contact'], required: false},
                {value: 'firstName', title: 'First Name', task: ['create_contact'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['create_contact'], required: false},
            ]

        }
    },
    methods: {
        getSalesforceaccounts: function() {
            var that = this;
            this.salesforceaccountLoading = true;
            var listData = {
                'action': 'awp_get_salesforce_accounts',
                'formData': jQuery('#new-integration').serialize(),
                'triggerData': this.trigger,
                'actionData': this.action,
                'fieldData': this.fieldData,
                'task': this.action.task,
                'accountId': this.fielddata.activePlatformId,
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                console.log(response);
                console.log(response.data);
                //var lists = JSON.parse(response.data);
                that.fielddata.Salesforceaccounts = response.data;
                that.salesforceaccountLoading = false;
            });
        },
        getLists: function() {
            var that = this;
            this.listLoading = true;
            var listData = {
                'action': 'awp_get_salesforce_lists',
                'formData': jQuery('#new-integration').serialize(),
                'triggerData': this.trigger,
                'actionData': this.action,
                'fieldData': this.fieldData,
                'task': this.action.task,
                'accountId': this.fielddata.salesforceaccountId,
                'account_Id': this.fielddata.activePlatformId,

            };
            jQuery.post( ajaxurl, listData, function( response ) {
                console.log(response);
                var lists = JSON.parse(response);
                that.fielddata.lists = lists;
                that.listLoading = false;
            });
        },
        CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        // this.accountLoading = true;

        // var accountRequestData = {
        //     'action': 'awp_get_salesforce_accounts',
        //     'formData': jQuery('#new-integration').serialize(),
        //     'triggerData': this.trigger,
        //     'actionData': this.action,
        //     'fieldData': this.fieldData,
        //     'task': this.action.task
        // };


        // jQuery.post( 'post.php', accountRequestData, function( response ) {
        //     console.log(response);
        //     that.fielddata.accounts = JSON.parse(response);
        //     that.accountLoading = false;
        // });
    },
    template: '#salesforce-action-template'
});


Vue.component('insightly', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;


        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#insightly-action-template'
});

Vue.component('copper', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {value: 'name', title: 'Name', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {
        CheckinDatabase:function(item,name){
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


    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#copper-action-template'
});

Vue.component('freshsales', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['add_contact', 'add_lead'], required: true},
                {value: 'firstName', title: 'First Name', task: ['add_contact', 'add_lead'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['add_contact', 'add_lead'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;


       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#freshsales-action-template'
});




Vue.component('campaignmonitor', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['create_subscriber'], required: true},
                {value: 'name', title: 'Name', task: ['create_subscriber'], required: false}
            ]
        }
    },
    methods: {
        getList: function() {
            var that = this;
            this.accountLoading = true;

            var listData = {
                'action': 'awp_get_campaignmonitor_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce,
                'accountId': this.fielddata.accountId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var list = response.data;
                that.fielddata.list = list;
                that.accountLoading = false;
            });
        },
        CheckinDatabase:function(item,name){

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

        getCampaignMonitorAccounts:function(){
            var that = this;
            this.accountLoading = true;
            var accountRequestData = {
                'action': 'awp_get_campaignmonitor_accounts',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, accountRequestData, function( response ) {
                that.fielddata.accounts = response.data;
                that.accountLoading = false;
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#campaignmonitor-action-template'
});

Vue.component('moonmail', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

        CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

       /* this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_moonmail_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });*/
    },
    template: '#moonmail-action-template'
});

Vue.component('clinchpad', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {value: 'name', title: 'Name', task: ['add_contact'], required: false},
                {value: 'designation', title: 'Designation', task: ['add_contact'], required: false},
                {value: 'phone', title: 'Phone', task: ['add_contact'], required: false},
                {value: 'address', title: 'Address', task: ['add_contact'], required: false},
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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




    },
    created: function() {

    },
    mounted: function() {
        var that = this;


        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#clinchpad-action-template'
});

    Vue.component('cl-main', {
    props: ["trigger", "action", "fielddata"],
    template: '#cl-main-template',
    data: function() {
        return{}
    },
    methods: {
        clAddCondition: function(event) {
            var conditionL = awpNewIntegration.action.cl.conditions.length;
            awpNewIntegration.action.cl.conditions.push({id: conditionL+1, field: "", operator: "equal_to", value: ""});
        }
    }
});

Vue.component('conditional-logic', {
    props: ["trigger", "action", "fielddata", "condition"],
    template: '#conditional-logic-template',
    data: function() {
        return{}
    },
    methods: {
        clRemoveCondition: function(condition) {
            const conditionIndex = awpNewIntegration.action.cl.conditions.indexOf(condition);
            awpNewIntegration.action.cl.conditions.splice(conditionIndex, 1);
        }
    }
});
Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
var awpNewIntegration;
if(document.getElementById("awp-new-integration")){
    awpNewIntegration = new Vue({
        el: '#awp-new-integration',
        components: {
        "vue-select": VueSelect.VueSelect,

        },
        data: {
            trigger: {
                integrationTitle: '',
                formProviderId: '',
                forms: [],
                formId: '',
                formName: '',
                formFields: [],
                backupformFields: [],
                totalForms:'',
            },
            formValidated: 0,
            actionValidated: 0,
            
            action: {
                actionProviderId: '',
                task: '',
                cl: {
                    active: "no",
                    match: "any",
                    conditions: []
                },
                tasks: [],
                paltformConnected: 'loading',
                accountList:{},
                platformList:[],
                formProviderList:[],
                integrationSettings:{
                    delaytype:'',
                    delayval:'',
                },
                    
            },
            formLoading: false,
            fieldLoading: false,
            actionLoading: false,
            functionLoading: false,
            fieldData: {},
            

        },
        methods: {
            isNumber: function(evt) {
              evt = (evt) ? evt : window.event;
              var charCode = (evt.which) ? evt.which : evt.keyCode;
              if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
              } else {
                return true;
              }
            },
            getAllFormProviders: function(){
                var platformAccountsRequestDat2a = {
                    'action': 'awp_get_allFormProviders',
                    '_nonce': awp.nonce,
                };
                
              jQuery.post( ajaxurl, platformAccountsRequestDat2a, function( response ) {

                    
                  awpNewIntegration.action.formProviderList = response.data;
           
                    
                });
            },
            getAllPlatforms: function(){
                var platformAccountsRequestDat1a = {
                    'action': 'awp_get_actionProviders',
                    '_nonce': awp.nonce,
                };
                jQuery.post( ajaxurl, platformAccountsRequestDat1a, function( response ) {
                    data = response.data;
                    data = data.sort(function(a, b) {
                        var textA = a.id.toUpperCase();
                        var textB = b.id.toUpperCase();
                        return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
                    });
                    awpNewIntegration.action.platformList = data;
                });
            },
            saveIntegration: function(event) {
                event.preventDefault();
                //handling for edit integration page
                if(typeof editable_field != 'undefined'){

                    var submissionData = {
                            'action': 'awp_save_integration',
                            'nonce': awp.nonce,
                            'formData': jQuery('#new-integration').serialize(),
                            'triggerData': this.trigger,
                            'actionData': this.action,
                            'fieldData': JSON.stringify(this.fieldData)
                        }
                        //console.log(jQuery('#new-integration').serialize());
                        //console.log(this.trigger);
                        //console.log(this.action);
                        //console.log( JSON.stringify(this.fieldData));
                        params = new URLSearchParams(window.location.search);
                        if(window.isSibling){
                            submissionData['willHaveSibling']=true;
                        }
                        if(params.has('parent_id')){
                            submissionData['parentID']=params.get('parent_id');
                        }
                      
                        jQuery.post( ajaxurl, submissionData, function( response ) {
                            if(typeof response != 'object'){
                                response= JSON.parse(response);
                            }
                            window.isSibling=false;
                            // for ultimate version start
                                //window.location.href = response.redirectUrl;
                            // for ultimate version end

                            // for limited version start (disable ultimate version condition first)
                                if(response.success){
                                    window.location.href = response.redirectUrl;
                                }
                                else{

                                    alert(response.msg);
                                    window.location.href = awp.licenseUrl;
                                }
                            // for limited version end
                        });


                        return;




                }
                message="Do you want to create another step";
                Swal.fire({
                    allowOutsideClick: false,
                      title: 'Add more steps to this Spot',
                      text: message,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Go to next step',
                      cancelButtonText:'Save this Spot only',
                      customClass: 'swal-wide-integration',
                    }).then((result) => {

                      if (result.isConfirmed) {
                        window.isSibling=true;
                      }




                        var submissionData = {
                            'action': 'awp_save_integration',
                            'nonce': awp.nonce,
                            'formData': jQuery('#new-integration').serialize(),
                            'triggerData': this.trigger,
                            'actionData': this.action,
                            'fieldData': JSON.stringify(this.fieldData)
                        }
                        //console.log(jQuery('#new-integration').serialize());
                        //console.log(this.trigger);
                        //console.log(this.action);
                        //console.log( JSON.stringify(this.fieldData));
                        params = new URLSearchParams(window.location.search);
                        if(window.isSibling){
                            submissionData['willHaveSibling']=true;
                        }
                        if(params.has('parent_id')){
                            submissionData['parentID']=params.get('parent_id');
                        }
                      
                        jQuery.post( ajaxurl, submissionData, function( response ) {
                            if(typeof response != 'object'){
                                response= JSON.parse(response);
                            }
                            window.isSibling=false;
                            // for ultimate version start
                                //window.location.href = response.redirectUrl;
                            // for ultimate version end

                            // for limited version start (disable ultimate version condition first)
                                if(response.success){
                                    window.location.href = response.redirectUrl;
                                }
                                else{

                                    alert(response.msg);
                                    window.location.href = awp.licenseUrl;
                                }
                            // for limited version end
                        });






                }); //end of swal fire then





                
                

                
            },
            changeFormProvider: function(event) {
                this.formValidated  = 1;
                awpNewIntegration.formLoading = true;
                this.trigger.formId = '';
                
                if(this.trigger.formProviderId == '') {
                    awpNewIntegration.trigger.forms = [];
                    awpNewIntegration.formValidated = 0;
                    awpNewIntegration.formLoading = false;
                }
                var formProviderData = {
                    'action': 'awp_get_forms',
                    'nonce': awp.nonce,
                    'formProviderId': this.trigger.formProviderId
                };

                jQuery.post( ajaxurl, formProviderData, function( response ) {
                    var forms = response.data;
                    if(Object.keys(forms).length>0){
                        var sortable = [];
                        for (var form in forms) {
                            sortable.push({t:forms[form],o:form});
                        }
                        sortable.sort(function (o1, o2) { return o1.t.toUpperCase() > o2.t.toUpperCase() ? 1 : o1.t.toUpperCase() < o2.t.toUpperCase() ? -1 : 0; });  
                        var sortable2 = sortable;
                        var new_check_array = [];
                        var new_check_array2 = {};
                        for(var i=0;i<sortable2.length;i++){
                            new_check_array2[' '+sortable2[i].o] = sortable2[i].t;
                        }
                        awpNewIntegration.trigger.forms         = new_check_array2;
                        awpNewIntegration.trigger.totalForms = Object.keys(forms).length;
                    }else{
                        awpNewIntegration.trigger.forms         = forms;
                        awpNewIntegration.trigger.totalForms =forms.length;
                    }
                    let data=[];
                    let formsn = forms;
                    for (var key in formsn) {
                        if (formsn.hasOwnProperty(key)) {
                            
                            data.push({id: key, name: formsn[key]});
                        }
                    }
                    awpNewIntegration.trigger.forms           = data;
                    awpNewIntegration.formValidated = 0;
                    awpNewIntegration.formLoading = false;
                    if(Object.keys(forms).length==1){
                        awpNewIntegration.trigger.formId = Object.keys(forms)[0];
                        awpNewIntegration.changedForm(jQuery("#form_default"));
                    }
                    jQuery("#ActionTaskList").trigger('change');

                    
                });
            },
            changeFormProvider1: function(event,defaultForm) {
                this.formValidated  = 1;
                awpNewIntegration.formLoading = true;
                this.trigger.formId = '';
                
                if(this.trigger.formProviderId == '') {
                    awpNewIntegration.trigger.forms = [];
                    awpNewIntegration.formValidated = 0;
                    awpNewIntegration.formLoading = false;
                }
                var formProviderData = {
                    'action': 'awp_get_forms',
                    'nonce': awp.nonce,
                    'formProviderId': this.trigger.formProviderId
                };

                jQuery.post( ajaxurl, formProviderData, function( response ) {
                    var forms = response.data;
                    if(Object.keys(forms).length>0){
                        var sortable = [];
                        for (var form in forms) {
                            sortable.push({t:forms[form],o:form});
                        }
                        sortable.sort(function (o1, o2) { return o1.t.toUpperCase() > o2.t.toUpperCase() ? 1 : o1.t.toUpperCase() < o2.t.toUpperCase() ? -1 : 0; });  
                        var sortable2 = sortable;
                        var new_check_array = [];
                        var new_check_array2 = {};
                        for(var i=0;i<sortable2.length;i++){
                            new_check_array2[' '+sortable2[i].o] = sortable2[i].t;
                        }
                        awpNewIntegration.trigger.forms         = new_check_array2;
                        awpNewIntegration.trigger.totalForms = Object.keys(forms).length;
                    }else{
                        awpNewIntegration.trigger.forms         = forms;
                        awpNewIntegration.trigger.totalForms =forms.length;
                    }
                    let data=[];
                    let formsn = forms;
                    for (var key in formsn) {
                        if (formsn.hasOwnProperty(key)) {
                            
                            data.push({id: key, name: formsn[key]});
                        }
                    }
                    awpNewIntegration.trigger.forms           = data;
                    awpNewIntegration.formValidated = 0;
                    awpNewIntegration.formLoading = false;
                    if(Object.keys(forms).length==1){
                        awpNewIntegration.trigger.formId = Object.keys(forms)[0];
                        awpNewIntegration.changedForm(jQuery("#form_default"));
                    }
                    jQuery("#ActionTaskList").trigger('change');

                    if(typeof defaultForm !== 'undefined'  && defaultForm !=''){
                        awpNewIntegration.trigger.formId=defaultForm;
                  
                        awpNewIntegration.formValidated=2;
                    }
                    else{
                        awpNewIntegration.formValidated=0;
                    }
                });
            },
            enable_search_form_fields:function(){
                //console.log(jQuery('.sperse_reverse_draggable').length>0 && !(jQuery('.form_fields .filter-input-wrap').length>0));
                if(jQuery('.sperse_reverse_draggable').length>0 && !(jQuery('.form_fields .filter-input-wrap').length>0)) {
                    jQuery('.form_fields ul').before("<div class='filter-input-wrap'><input class='filter-input' placeholder='Type here to search by field name in your source form'></input></div>");
                    
                    function filter(filter, query) {
                        query = jQuery.trim(query);
                        jQuery(filter).each(function () {
                        (jQuery(this).text().search(new RegExp(query, "i")) < 0) ? jQuery(this).hide().removeClass('name') :
                        jQuery(this).show().addClass('name');
                            });
                        }
                        
                    jQuery('.filter-input').on('input', function (event) {
                        if (event.keyCode == 27 || jQuery(this).val() == '') {
                        jQuery(this).val('');
                        jQuery('.form_fields li').removeClass('name').show().addClass('name');
                        }
                        else {
                        filter('.form_fields li', jQuery(this).val());
                        }
                    });
                }

            },

            changeActiontask:function(event){
               // console.log(event);
               // jQuery(document).resize();
               // makedropable();

            },
             cleanObj:function(obj) {
              for (var propName in obj) {
            
                if (obj[propName] ==="" || obj[propName] === null || obj[propName] === undefined) {
                  delete obj[propName];
                }
              }
              return obj
            },
            changedForm: function(event) {

                awpNewIntegration.fieldLoading = true;
                var formData = {
                    'action': 'awp_get_form_fields',
                    'formProviderId': this.trigger.formProviderId,
                    'nonce': awp.nonce,
                    'formId': this.trigger.formId
                };
                jQuery.post( ajaxurl, formData, function( response ) {

                    var fields             = response.data;
                    var cleanobj =  awpNewIntegration.cleanObj(fields);
                    awpNewIntegration.trigger.formFields = cleanobj;
                    awpNewIntegration.trigger.backupformFields = cleanobj;
                    awpNewIntegration.fieldLoading = false;
                    awpNewIntegration.checkDroppedFields();

                    
                    
                });

            },
            checkDroppedFields:function(){

                if(Object.keys(this.fieldData).length>0 ){
                    var field_data = this.fieldData;
                    var formfields = awpNewIntegration.trigger.backupformFields;
                    for(i in field_data){

                            if(!(field_data[i]=="")){
                                

                                if(typeof field_data[i] != 'undefined'){

                                                                    if(!(typeof field_data[i] === 'object')){

                                    var fielddata_i = field_data[i];
                                    if(Array.isArray(fielddata_i)){
                                        fielddata_i = fielddata_i[0];
                                    }
                                    var res= fielddata_i.match(/{{/g);
                                    if(res){
                                        jQuery('.sperse_inner').find("li[data-field='"+i+"']").remove();
                                        jQuery('.form_field_dropable').removeClass('sperse_dropped');
                                        field_data[i]='';
                                    }
                                }
                                }
                            }
                    }

                awpNewIntegration.fieldData = field_data;

                }

                if(awpNewIntegration.action.task){
                    setTimeout(function(){ 
                        makedropable();
                       awpNewIntegration.enable_search_form_fields();

                    }, 550);

                        
                    }



            },
            changeActionProvider: function(event) {
                //might need later
                // validKeys = ['googleaccountID','activePlatformId'];
                // filteredEntries = Object.entries(this.fieldData).filter(([key]) => validKeys.includes(key))
                // this.$set(this, 'fieldData', Object.fromEntries(filteredEntries));
                
              
                this.actionValidated  = 1;
                awpNewIntegration.actionLoading = true;
                this.action.task = '';
                if(this.actionProviderId == '') {
                    awpNewIntegration.action.tasks = '';
                    awpNewIntegration.actionValidated = 0;
                    awpNewIntegration.actionLoading = false;
                }

                var actionProviderData = {
                    'action': 'awp_get_tasks',
                    'nonce': awp.nonce,
                    'actionProviderId': this.action.actionProviderId
                };

                jQuery.post( ajaxurl, actionProviderData, function( response ) {

                        let dataN = [];
                        let tasks = response.data;
                        for (var key in tasks) {
                            if (tasks.hasOwnProperty(key)) {
                                dataN.push({id: key, name: tasks[key]});
                            }
                        }
                        awpNewIntegration.action.tasks = dataN;
                        awpNewIntegration.actionValidated = 0;
                        awpNewIntegration.actionLoading = false;
                        if(Object.keys(response.data).length>0){
                            awpNewIntegration.action.task = Object.keys(response.data)[0];
                            awpNewIntegration.changedForm(jQuery("#form_default"));
                        }
                });




                awpNewIntegration.action.paltformConnected='loading';
                awpNewIntegration.action.accountList={};

                
                // temp variable is used because googlesheets and googlecalendar shares same table so currently both the data are saved under googlesheets name
                var tempActionProviderId;
                if(this.action.actionProviderId == 'googlecalendar' || this.action.actionProviderId == 'googledrive'){
                    tempActionProviderId ='googlesheets';
                }
                else{
                    tempActionProviderId =this.action.actionProviderId;
                }
                var platformAccountsRequestData = {
                    'action': 'awp_get_platform_accounts',
                    'platform':tempActionProviderId,
                    '_nonce': awp.nonce,
                };
                var that=this;
                jQuery.post( ajaxurl, platformAccountsRequestData, function( response ) {

                    if(response.data.isConnected){
                        let data = [];
                        let accounts = response.data.accounts;
                        for (var key in accounts) {
                            if (accounts.hasOwnProperty(key)) {
                                data.push({accountId: key, accountName: accounts[key]});
                            }
                        }
                        //console.log(that);
                        if(that.action.actionProviderId == 'sperse'){
                            awpNewIntegration.action.accountList = data;
                        }
                        else{
                            awpNewIntegration.action.accountList=response.data.accounts;
                        }
                        awpNewIntegration.action.accountList=response.data.accounts;
                        awpNewIntegration.action.paltformConnected = true;
                    }
                    else{
                        awpNewIntegration.action.paltformConnected = false;
                    }
                    
                });





                
                
                // var platformConnectedData = {
                //     'action': 'awp_get_platform_status',
                //     'nonce': awp.nonce,
                //     'actionProviderId': this.action.actionProviderId
                // };


                // jQuery.post( ajaxurl, platformConnectedData, function( response ) {
                //     if(response.success){
                //         awpNewIntegration.action.paltformConnected = response.success;
                //     }
                //     else{
                //         alert("Error requesting server please referesh your page");
                //     }
                    
                // });


            }
        },
        mounted: function() {
            if (typeof integrationTitle != 'undefined') {
                this.trigger.integrationTitle = integrationTitle;
            }        if (typeof triggerData != 'undefined') {
                this.trigger = triggerData;
            }
            if (typeof actionData != 'undefined') {
                this.action = actionData;
            }
            if (typeof fieldData != 'undefined') {
                this.fieldData = fieldData;
            }


        this.getAllPlatforms();
        this.getAllFormProviders();

        },
        watch: {
            'trigger.formId': function(val) {
                let formName = (this.trigger.forms.find(o => o.id === val)) ? this.trigger.forms.find(o => o.id === val).name : '';
                
                awpNewIntegration.trigger.formName = formName;
            },
        beforeMount: function() {
            this.getAllPlatforms();
            this.getAllFormProviders();
        },    
            
            
        }
        
        
    });  
}


if(jQuery('#awp-new-message').length>0){
var awpNewMessage = new Vue({
    el: '#awp-new-message',
    data: {
        trigger: {
            messageTitle: '',
            formProviderId: '',
            forms: '',
            formId: '',
            formName: '',
            formFields: [],
            backupformFields: [],
            isformFields:false,
            totalForms:'',
            subjectName:''
        },
        formValidated: 0,
        actionValidated: 0,
        action: {
            actionProviderId: '',
            task: '',
            tasks: []
        },
        formLoading: false,
        fieldLoading: false,
        actionLoading: false,
        functionLoading: false,
        fieldData: {}
    },
    methods: {
CheckinDatabase:function(item,name){
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
         changedForm: function(event) {
            awpNewIntegration.fieldLoading = true;
            var formData = {
                'action': 'awp_get_form_fields',
                'formProviderId': this.trigger.formProviderId,
                'nonce': awp.nonce,
                'formId': this.trigger.formId
            };
            jQuery.post( ajaxurl, formData, function( response ) {
                var fields             = response.data;
                awpNewMessage.trigger.formFields = fields;
                awpNewMessage.trigger.backupformFields = fields;
                awpNewMessage.trigger.isformFields = true;
                awpNewMessage.fieldLoading = false;
            });



        },
        changeFormProvider: function(event) {
            this.formValidated  = 1;
            awpNewMessage.formLoading = true;
            this.trigger.formId = '';
            if(this.trigger.formProviderId == '') {
                awpNewMessage.trigger.forms = [];
                awpNewMessage.formValidated = 0;
                awpNewMessage.formLoading = false;
            }

            var formProviderData = {
                'action': 'awp_get_forms',
                'nonce': awp.nonce,
                'formProviderId': this.trigger.formProviderId
            };

            jQuery.post( ajaxurl, formProviderData, function( response ) {

                var forms = response.data;
                if(Object.keys(forms).length>0){
                    var sortable = [];
                    for (var form in forms) {
                        sortable.push({t:forms[form],o:form});
                    }
                    sortable.sort(function (o1, o2) { return o1.t.toUpperCase() > o2.t.toUpperCase() ? 1 : o1.t.toUpperCase() < o2.t.toUpperCase() ? -1 : 0; });  
                    var sortable2 = sortable;
                    var new_check_array = [];
                    var new_check_array2 = {};
                    for(var i=0;i<sortable2.length;i++){
                        new_check_array2[' '+sortable2[i].o] = sortable2[i].t;
                    }
                    awpNewMessage.trigger.forms         = new_check_array2;
                    awpNewMessage.trigger.totalForms = Object.keys(forms).length;
                }else{
                    awpNewMessage.trigger.forms         = forms;
                    awpNewMessage.trigger.totalForms =forms.length;
                }

                let data=[];
                let formsd = forms;
                    for (var key in formsd) {
                        if (formsd.hasOwnProperty(key)) {
                            data.push({id: key, name: formsd[key]});
                        }
                    }

                awpNewMessage.trigger.forms         = formsd;
                awpNewMessage.formValidated = 0;
                awpNewMessage.formLoading = false;
                jQuery("#ActionTaskList").trigger('change');
            });

        },
        saveMessage: function(event) {
            var submissionData = {
                'action': 'awp_save_message',
                'nonce': awp.nonce,
                'formData': jQuery('#new-message').serialize(),
                'triggerData': this.trigger,
                'actionData': this.action,
                'fieldData': this.fieldData
            }
            
            jQuery.post( ajaxurl, submissionData, function( response ) {
                
                window.location.href = awp.message_template_url;
            });

        },
    },
    mounted: function() {
        if (typeof messageTitle != 'undefined') {
            this.trigger.messageTitle = messageTitle;
        }

        if (typeof triggerData != 'undefined') {
            this.trigger = triggerData;
        }


        if (typeof actionData != 'undefined') {
            this.action = actionData;
        }


        if (typeof fieldData != 'undefined') {
            this.fieldData = fieldData;
        }

    },
});
}

Vue.component('moosendpro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false},
                {value: 'customFields', title: 'Custom Fields', task: ['subscribe'], required: false, description: 'Use key=value format, example: Age=25. For multiple fields use comma, example: Age=25,Country=USA (without space)'}
            ]

        }
    },
    methods: {
        getMoosendList:function(){
            this.listLoading = true;
            var that = this;
            var listRequestData = {
                'action': 'awp_get_moosend_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#moosendpro-action-template'
});

Vue.component('sendypro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true, description: ''},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false, description: ''},
                {value: 'country', title: 'Country', task: ['subscribe'], required: false, description: ''},
                {value: 'ipaddress', title: 'IP Address', task: ['subscribe'], required: false, description: ''},
                {value: 'referrer', title: 'Referrer', task: ['subscribe'], required: false, description: ''},
                {value: 'custom_fields', title: 'Custom Fields', task: ['subscribe'], required: false, description: 'Use key:value format. Example Birthday:2000-12-12. For multiple custom fields use comma to separate. Example Birthday:2000-12-12,City:London,Profession:Teacher. Don\'t use any space.  You can use form fields as value.'}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
    },
    template: '#sendypro-action-template'
});

Vue.component('pipedrivepro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            worksheetLoading: false,
            fields: [
                {value: 'note_content', title: 'Content [Note]', task: ['add_ocdna'], required: false, description: ''},
                {value: 'act_subject', title: 'Subject [Activity]', task: ['add_ocdna'], required: false, description: ''},
                {value: 'act_type', title: 'Type [Activity]', task: ['add_ocdna'], required: false, description: 'Example: call, meeting, task, deadline, email, lunch'},
                {value: 'act_due_date', title: 'Due Date [Activity]', task: ['add_ocdna'], required: false, description: 'Format: YYYY-MM-DD'},
                {value: 'act_due_time', title: 'Due Time [Activity]', task: ['add_ocdna'], required: false, description: 'Format: HH:MM'},
                {value: 'act_duration', title: 'Duration [Activity]', task: ['add_ocdna'], required: false, description: 'Format: HH:MM'},
                {value: 'act_note', title: 'Note [Activity]', task: ['add_ocdna'], required: false, description: ''},
            ]

        }
    },
    methods: {


                CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'awp_get_pipedrivepro_owner_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {

            that.fielddata.ownerList = response.data;
            that.ownerLoading = false;
        });

        var orgRequestData = {
            'action': 'awp_get_pipedrivepro_org_fields',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, orgRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });

        var personRequestData = {
            'action': 'awp_get_pipedrivepro_person_fields',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, personRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });

        var dealRequestData = {
            'action': 'awp_get_pipedrivepro_deal_fields',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, dealRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });

    },
    watch: {},
    template: '#pipedrivepro-action-template'
});

Vue.component('activecampaignpro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {value: 'phoneNumber', title: 'Phone', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_activecampaign_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        var customFieldData = {
            'action': 'awp_get_activecampaignpro_custom_fields',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, customFieldData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { value: single.key, title: single.value, task: ['subscribe'], required: false, description: single.description } );
                    });
                }
            }
        });
    },
    template: '#activecampaignpro-action-template'
});

Vue.component('mailerlitepro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: []

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_mailerlite_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        var customFieldData = {
            'action': 'awp_get_mailerlitepro_custom_fields',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, customFieldData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { value: single.key, title: single.value, task: ['subscribe'], required: false, description: single.description } );
                    });
                }
            }
        });
    },
    template: '#mailerlitepro-action-template'
});

Vue.component('woodpeckerpro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {value: 'company', title: 'Company', task: ['subscribe'], required: false},
                {value: 'industry', title: 'Industry', task: ['subscribe'], required: false},
                {value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {value: 'tags', title: 'Tags', task: ['subscribe'], required: false},
                {value: 'title', title: 'Title', task: ['subscribe'], required: false},
                {value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
                {value: 'city', title: 'City', task: ['subscribe'], required: false},
                {value: 'state', title: 'State', task: ['subscribe'], required: false},
                {value: 'country', title: 'Country', task: ['subscribe'], required: false},
                {value: 'status', title: 'Status', task: ['subscribe'], required: false, description: 'ACTIVE | BLACKLIST | REPLIED | INVALID | BOUNCED'},
                {value: 'snippet1', title: 'Snippet1', task: ['subscribe'], required: false},
                {value: 'snippet2', title: 'Snippet2', task: ['subscribe'], required: false},
                {value: 'snippet3', title: 'Snippet3', task: ['subscribe'], required: false},
                {value: 'snippet4', title: 'Snippet4', task: ['subscribe'], required: false},
                {value: 'snippet5', title: 'Snippet5', task: ['subscribe'], required: false},
                {value: 'snippet6', title: 'Snippet6', task: ['subscribe'], required: false},
                {value: 'snippet7', title: 'Snippet7', task: ['subscribe'], required: false},
                {value: 'snippet8', title: 'Snippet8', task: ['subscribe'], required: false},
                {value: 'snippet9', title: 'Snippet9', task: ['subscribe'], required: false},
                {value: 'snippet10', title: 'Snippet10', task: ['subscribe'], required: false},
                {value: 'snippet11', title: 'Snippet11', task: ['subscribe'], required: false},
                {value: 'snippet12', title: 'Snippet12', task: ['subscribe'], required: false},
                {value: 'snippet13', title: 'Snippet13', task: ['subscribe'], required: false},
                {value: 'snippet14', title: 'Snippet14', task: ['subscribe'], required: false},
                {value: 'snippet15', title: 'Snippet15', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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
        getWoodpeckerListPro:function(){
            this.listLoading = true;
            var that = this;
            var accountRequestData = {
                'action': 'awp_get_woodpreckerpro_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, accountRequestData, function( response ) {

               // console.log(response);
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        
    },
    template: '#woodpeckerpro-action-template'
});

Vue.component('aweberpro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {value: 'name', title: 'Name', task: ['subscribe'], required: false},
                {value: 'ipAddress', title: 'IP Address', task: ['subscribe'], required: false},
                {value: 'adTracking', title: 'Ad Tracking', task: ['subscribe'], required: false},
                {value: 'miscNotes', title: 'Additional Notes', task: ['subscribe'], required: false},
                {value: 'tags', title: 'Tags', task: ['subscribe'], required: false, description: 'For multiple values use comma without space. Ex: tag1,tag2,tag3'},
                {value: 'customFields', title: 'Custom Fields', task: ['subscribe'], required: false, description: 'Use key=value format, example: Age=25. For multiple fields use comma, example: Age=25,Country=USA (without space)'}
            ]

        }
    },
    methods: {
        getLists: function() {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'awp_get_aweber_lists',
                '_nonce': awp.nonce,
                'accountId': this.fielddata.accountId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.lists = lists;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

       
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.accountLoading = true;

        var accountRequestData = {
            'action': 'awp_get_aweber_accounts',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, accountRequestData, function( response ) {
            that.fielddata.accounts = response.data;
            that.accountLoading = false;
        });

    },
    template: '#aweberpro-action-template'
});

Vue.component('campaignmonitorpro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['create_subscriber'], required: true},
                {value: 'name', title: 'Name', task: ['create_subscriber'], required: false},
                {value: 'customFields', title: 'Custom Fields', task: ['create_subscriber'], required: false, description: 'Use key=value format, example: Age=25. For multiple fields use comma, example: Age=25,Country=USA (without space)'}
            ]
        }
    },
    methods: {
        getList: function() {
            var that = this;
            this.accountLoading = true;

            var listData = {
                'action': 'awp_get_campaignmonitor_list',
                '_nonce': awp.nonce,
                'accountId': this.fielddata.accountId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var list = response.data;
                that.fielddata.list = list;
                that.accountLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.accountLoading = true;

        var accountRequestData = {
            'action': 'awp_get_campaignmonitor_accounts',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, accountRequestData, function( response ) {
            that.fielddata.accounts = response.data;
            that.accountLoading = false;
        });
    },
    template: '#campaignmonitorpro-action-template'
});



Vue.component('keap', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'given_name', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'middle_name', title: 'Middle Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'family_name', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'job_title', title: 'Job Title', task: ['add_contact'], required: false},
                {type: 'text', value: 'spouse_name', title: 'Spouse Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'preferred_name', title: 'Preffered Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'phone_numbers', title: 'Phone Number', task: ['add_contact'], required: false},
                {type: 'text', value: 'time_zone', title: 'Time Zone', task: ['add_contact'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['add_contact'], required: false},
                {type: 'text', value: 'line1', title: 'Line 1', task: ['add_contact'], required: false},
                {type: 'text', value: 'line2', title: 'Line 2', task: ['add_contact'], required: false},
                {type: 'text', value: 'locality', title: 'Locality', task: ['add_contact'], required: false},
                {type: 'text', value: 'region', title: 'Region', task: ['add_contact'], required: false},
                {type: 'text', value: 'zip_code', title: 'Zip Code', task: ['add_contact'], required: false,description:'Mainly used in the United States, this is typically numeric. ex. 85001, 90002 Note: this is to be used instead of postal_code, not in addition to.'},
                {type: 'text', value: 'postal_code', title: 'Postal Code', task: ['add_contact'], required: false,description:"Field used to store postal codes containing a combination of letters and numbers ex. 'EC1A', 'S1 2HE', '75000'"},
                {type: 'text', value: 'opt_in_reason', title: 'Opt-in Reason', task: ['add_contact'], required: false},
                
                                
            ]
        }
    },
    methods: {

         CheckinDatabase:function(item,name){

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
        getKeapList:function(){
            var that = this;
            this.listLoading = true;

            var listRequestData = {
                'action': 'awp_get_keap_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

      
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

    },
    template: '#keap-action-template'
});




Vue.component('zoho', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
            ]
        }
    },
    methods: {

         CheckinDatabase:function(item,name){

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
        getSpreadSheets: function() {

            if(!this.fielddata.googleaccountID) {
                return;
            }

            var that = this;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_spreadsheet_list',
                'accountid':this.fielddata.googleaccountID,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.spreadsheetList = response.data;
                that.listLoading = false;
            });
            
        },
        getZohoList:function(){
            var that = this;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_zoho_list',
                'platformid':this.fielddata.activePlatformId,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

     

        

    },
    template: '#zoho-action-template'
});



Vue.component('constantcontact', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {

                CheckinDatabase:function(item,name){

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

    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_constantcontact_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#constantcontact-action-template'
});

Vue.component('convertkitpro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'customFields', title: 'Custom Fields', task: ['subscribe'], required: false, description: 'Use key=value format, example: Age=25. For multiple fields use comma, example: Age=25,Country=USA (without space)'}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);

        this.listLoading = true;
        this.tagLoading  = true;

        var listRequestData = {
            'action': 'awp_get_convertkit_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        var tagRequestData = {
            'action': 'awp_get_convertkitpro_tags',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, tagRequestData, function( response ) {
            that.fielddata.tagList = response.data;
            that.tagLoading = false;
        });
    },
    template: '#convertkitpro-action-template'
});

Vue.component('googlecalendar', {
    props: ["trigger", "action", "fielddata"],
    data: function () { 
            return { 
                    listLoading: false,
                    fields:[
                            {value: 'title', title: 'Event Title', task: ['addEvent'], required: true},
                            {value: 'description', title: 'Event Description', task: ['addEvent'], required: false},
                            {value: 'start', title: 'Event Start Time', task: ['addEvent'], required: false},
                            {value: 'end', title: 'Event End Time', task: ['addEvent'], required: false},
                            {value: 'timezone', title: 'Event Timezone', task: ['addEvent'], required: false},
                            {value: 'allDayEvent', title: 'All Day Event', task: ['addEvent'], required: false}

                    ],
                }
        },
    methods: {
        CheckinDatabase:function(item,name){
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
        updateFieldValue: function(value) {
            if(this.selected || this.selected == 0) {
                if (this.fielddata[value] || "0" == this.fielddata[value]) {
                    this.fielddata[value] += ' {{' + this[value] + '}}';
                } else {
                    this.fielddata[value] = '{{' + this[value] + '}}';
                }
            }
        },
        getCalendarList: function() {

            if(!this.fielddata.googleaccountID) {
                return;
            }
            var that = this;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_googlecalendar_list',
                'accountid':this.fielddata.googleaccountID,
                '_nonce': awp.nonce
            };
            //console.log(listRequestData);
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                //console.log(response.data);
                that.fielddata.calendarList = response.data;
                that.listLoading = false;
            });
            

            
            
        },
    },
    created: function() { },
    mounted: function() {
        var that = this;
        if (typeof this.fielddata.calendarId == 'undefined') {
            this.fielddata.calendarId = '';
        }
        if (typeof this.fielddata.allDayEvent == 'undefined') {
            this.fielddata.allDayEvent = false;
        }
        if (typeof this.fielddata.allDayEvent != 'undefined') {
            if(this.fielddata.allDayEvent == "false") {
                this.fielddata.allDayEvent = false;
            }
        }

        if (typeof this.fielddata.title == 'undefined') {
            this.fielddata.title = '';
        }
        if (typeof this.fielddata.description == 'undefined') {
            this.fielddata.description = '';
        }
        if (typeof this.fielddata.start == 'undefined') {
            this.fielddata.start = '';
        }
        if (typeof this.fielddata.end == 'undefined') {
            this.fielddata.end = '';
        }
        if (typeof this.fielddata.timezone == 'undefined') {
            this.fielddata.timezone = '';
        }
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }

        this.googleCalendarloading = true;
        var googleCalendartRequestData = {
            'action': 'awp_get_gcalendar_accounts',
            '_nonce': awp.nonce,
        };


        jQuery.post( ajaxurl, googleCalendartRequestData, function( response ) {
            that.fielddata.gsheetaccounts = response.data;
            that.googleCalendarloading = false;
        });
    

    },
    watch: {},
    template: '#googlecalendar-action-template'
});

Vue.component('googlesheets', {
    props: ["trigger", "action", "fielddata"],
    components: {
    "vue-select": VueSelect.VueSelect
    },
    data: function () {
        return {
            googleSheetloading: false,
            listLoading: false,
            worksheetLoading: false,
            fields: this.getCols(),
        }
    },

    methods: {
        CheckinDatabase:function(item,name){
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
        getSpreadSheets: function() {
            if(!this.fielddata.googleaccountID) {
                return;
            }
            this.$set(this.fielddata, 'spreadsheetId', "");
            var that = this;
            this.listLoading = true;
            var listRequestData = {
                'action': 'awp_get_spreadsheet_list',
                'accountid':this.fielddata.googleaccountID,
                '_nonce': awp.nonce
            };
            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.$set(that.fielddata, 'spreadsheetList', vueArrayObjectMaker(response.data));
                //that.fielddata.spreadsheetList = vueArrayObjectMaker(response.data);
                that.listLoading = false;

            });
            
        },
        getWorksheets: function() {
            

            if(!this.fielddata.spreadsheetId) {
                return;
            }
            
            this.$set(this.fielddata, 'worksheetId','');
            console.log(this.fielddata.worksheetId);
            this.fields = [];
            var that = this;
            this.worksheetLoading = true;
            var listData = {
                'action': 'awp_googlesheets_get_worksheets',
                '_nonce': awp.nonce,
                'spreadsheetId': this.fielddata.spreadsheetId,
                 'accountid':this.fielddata.googleaccountID,
                'task': this.action.task
            };
            jQuery.post( ajaxurl, listData, function( response ) {
                that.$set(that.fielddata, 'worksheetList',response.data);
                that.worksheetLoading = false;
            });
        },
        getCols:function(){
            var temp =[];

            if(this.fielddata.worksheetId == 0 || this.fielddata.worksheetId) {
        
                
                var that = this;
                this.$set(that.fielddata, 'worksheetName', this.fielddata.worksheetId);
                
                var requestData = {
                    'action': 'awp_googlesheets_get_headers',
                    '_nonce': awp.nonce,
                    'spreadsheetId': this.fielddata.spreadsheetId,
                    'worksheetName': this.fielddata.worksheetName,
                     'accountid':this.fielddata.googleaccountID,
                    'task': this.action.task
                };
                jQuery.post( ajaxurl, requestData, function( response ) {
                    if(response.success) {
                        
                        if(Object.keys(response.data).length) {
                            for(var key in response.data) {
                         
                                temp.push({value: key, title: response.data[key], task: ['add_row'], required: false});
                            }
                        }
                        else{
                            
                            temp.push({value: "A", title: "Col A", task: ['add_row'], required: false});
                            
                            temp.push({value: "B", title: "Col B", task: ['add_row'], required: false});
                            
                            temp.push({value: "C", title: "Col C", task: ['add_row'], required: false});
                            
                            temp.push({value: "D", title: "Col D", task: ['add_row'], required: false});
                            
                            temp.push({value: "E", title: "Col E", task: ['add_row'], required: false});
                            
                            temp.push({value: "F", title: "Col F", task: ['add_row'], required: false});
                            
                            temp.push({value: "G", title: "Col G", task: ['add_row'], required: false});
                            
                            temp.push({value: "H", title: "Col H", task: ['add_row'], required: false});
                            
                            temp.push({value: "I", title: "Col I", task: ['add_row'], required: false});
                        }
                    }
                  
                });


            }
            return temp;

        },
        getHeaders: function() {

            if(this.fielddata.worksheetId == 0 || this.fielddata.worksheetId) {
          
                this.$set(this, 'fields', []);
                var that = this;
                this.worksheetLoading = true;
                this.$set(this.fielddata, 'worksheetName',this.fielddata.worksheetId);
                var requestData = {
                    'action': 'awp_googlesheets_get_headers',
                    '_nonce': awp.nonce,
                    'spreadsheetId': this.fielddata.spreadsheetId,
                    'worksheetName': this.fielddata.worksheetName,
                     'accountid':this.fielddata.googleaccountID,
                    'task': this.action.task
                };
                jQuery.post( ajaxurl, requestData, function( response ) {
                    if(response.success) {
                        
                        if(Object.keys(response.data).length) {
                            for(var key in response.data) {

                                that.fielddata[key] = '';
                                that.fields.push({value: key, title: response.data[key], task: ['add_row'], required: false});
                            }
                        }
                        else{
                            that.fielddata["A"] = '';
                            that.fields.push({value: "A", title: "Col A", task: ['add_row'], required: false});
                            that.fielddata["B"] = '';
                            that.fields.push({value: "B", title: "Col B", task: ['add_row'], required: false});
                            that.fielddata["C"] = '';
                            that.fields.push({value: "C", title: "Col C", task: ['add_row'], required: false});
                            that.fielddata["D"] = '';
                            that.fields.push({value: "D", title: "Col D", task: ['add_row'], required: false});
                            that.fielddata["E"] = '';
                            that.fields.push({value: "E", title: "Col E", task: ['add_row'], required: false});
                            that.fielddata["F"] = '';
                            that.fields.push({value: "F", title: "Col F", task: ['add_row'], required: false});
                            that.fielddata["G"] = '';
                            that.fields.push({value: "G", title: "Col G", task: ['add_row'], required: false});
                            that.fielddata["H"] = '';
                            that.fields.push({value: "H", title: "Col H", task: ['add_row'], required: false});
                            that.fielddata["I"] = '';
                            that.fields.push({value: "I", title: "Col I", task: ['add_row'], required: false});
                        }
                    }
                    that.worksheetLoading = false;
                });
            }
        },
        refreshLists:function(){
            this.getSpreadSheets();
        },

        addSpreadSheet:function(){
            var that=this;
            Swal.fire({
              title: 'Enter Spreadsheet Name',
              html: '<input type="text" name="spredsheet_add" class="spreadsheet_add" id="spreadsheet_add" required>',
              showCancelButton: true,
              customClass: {
               title:'swal-title-set',
               popup:'swal-wide-set', 
              },
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Create Spreadsheet',
              showLoaderOnConfirm: true,
              preConfirm: (login) => {
                if(jQuery("#spreadsheet_add").val()==""){
                    return Swal.showValidationMessage(
                      `Spreadsheet name cannot be empty`
                    );
                }


                var googleSheetRequestData = {
                    'action': 'awp_googlesheets_create_spreadsheet',
                    '_nonce': awp.nonce,
                    'accountId':this.fielddata.googleaccountID,
                    'task': this.action.task,
                    'spreadsheetName':jQuery("#spreadsheet_add").val(),
                };

                var form_data = new FormData();
                for ( var key in googleSheetRequestData ) {
                    form_data.append(key, googleSheetRequestData[key]);
                }

                return fetch(ajaxurl,{
                      method: 'POST',
                      contentType : false,
                      body: form_data,
                    }
                )
                .then(response => {

                    if (!response.ok) {
                        return Swal.showValidationMessage(
                            `Please try again`
                        );
                    }
                   
                    this.getSpreadSheets();
                });



                
              },

            }).then((result) => {
              
            });    
        },

        addWorksheet:function(){
            var that=this;
            Swal.fire({
              title: 'Enter Worksheet Name',
              html: '<input type="text" name="worksheet_add" class="worksheet_add" id="worksheet_add" required>',
              showCancelButton: true,
              customClass: {
               title:'swal-title-set',
               popup:'swal-wide-set', 
              },
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Create Worksheet',
              showLoaderOnConfirm: true,
              preConfirm: (login) => {
                if(jQuery("#worksheet_add").val()==""){
                    return Swal.showValidationMessage(
                      `Worksheet name cannot be empty`
                    );
                }

                if(this.fielddata.spreadsheetId==""){
                    return Swal.showValidationMessage(
                      `Please select spreadsheet first`
                    );
                }


                var googleSheetRequestData = {
                    'action': 'awp_googlesheets_create_worksheet',
                    '_nonce': awp.nonce,
                    'accountId':this.fielddata.googleaccountID,
                    'task': this.action.task,
                    'worksheetName':jQuery("#worksheet_add").val(),
                    'spreadsheetId':this.fielddata.spreadsheetId,
                };

                var form_data = new FormData();
                for ( var key in googleSheetRequestData ) {
                    form_data.append(key, googleSheetRequestData[key]);
                }

                return fetch(ajaxurl,{
                      method: 'POST',
                      contentType : false,
                      body: form_data,
                    }
                )
                .then(response => {

                    if (!response.ok) {
                        return Swal.showValidationMessage(
                            `Please try again`
                        );
                    }
                   
                    this.getWorksheets();
                });



                
              },

            }).then((result) => {
              
            });    
        }

    },
    created: function() {
    },
    updated:function () {
        makedropable(this.fielddata);
        
    },
    mounted: function() {
        
        var that = this;

        if (typeof this.fielddata.spreadsheetId == 'undefined') {
            this.$set(this.fielddata, 'spreadsheetId','');
        }

        if (typeof this.fielddata.worksheetId == 'undefined') {
            this.$set(this.fielddata, 'worksheetId','');
        }
        
        if (typeof this.fielddata.spreadsheetId == 'undefined') {
            this.$set(this.fielddata, 'spreadsheetId','');
        }

        if(typeof this.fielddata.worksheetName == 'undefined') {
            this.$set(this.fielddata, 'worksheetName','');
        }
        if(typeof this.fielddata.googleaccountID == 'undefined') {
            this.$set(this.fielddata, 'googleaccounts','');
        }
        if(typeof this.worksheetLoading == 'undefined') {
            this.worksheetLoading = false;
        }
        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.$set(this.fielddata, 'activePlatformId','');
        }
        
        if (this.fielddata.worksheetId !== '') {
            //console.log(this.fields);
            //console.log("coming ere");
            //console.log(this.getCols);
            //this.getHeaders();
        }
        this.googleSheetloading = true;
        var googleSheetRequestData = {
            'action': 'awp_get_gsheet_accounts',
            '_nonce': awp.nonce,
        };


        jQuery.post( ajaxurl, googleSheetRequestData, function( response ) {
            that.$set(that.fielddata, 'gsheetaccounts',response.data);
            that.googleSheetloading = false;
        });
        

        if(this.fielddata.spreadsheetId && this.fielddata.worksheetName ) {
            var that = this;
            this.worksheetLoading = true;
            var requestData = {
                'action': 'awp_googlesheets_get_headers',
                '_nonce': awp.nonce,
                'spreadsheetId': this.fielddata.spreadsheetId,
                'worksheetName': this.fielddata.worksheetName,
                'task': this.action.task
            };
            jQuery.post( ajaxurl, requestData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        for(var key in response.data) {
                            that.fields.push({value: key, title: response.data[key], task: ['add_row'], required: false});
                        }
                    }
                }
                that.worksheetLoading = false;
            });
        }
    },
    watch: {
        
    },
    template: '#googlesheets-action-template'
});

Vue.component('klaviyopro', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {value: 'title', title: 'Title', task: ['subscribe'], required: false},
                {value: 'organization', title: 'Organization', task: ['subscribe'], required: false},
                {value: 'phoneNumber', title: 'Phone Number', task: ['subscribe'], required: false},
                {value: 'address1', title: 'Address 1', task: ['subscribe'], required: false},
                {value: 'address2', title: 'Address 2', task: ['subscribe'], required: false},
                {value: 'region', title: 'Region', task: ['subscribe'], required: false},
                {value: 'zip', title: 'ZIP', task: ['subscribe'], required: false},
                {value: 'country', title: 'Country', task: ['subscribe'], required: false},
                {value: 'customFields', title: 'Custom Properties', task: ['subscribe'], required: false, description: 'Use key=value format, example: Age=25. For multiple fields use comma, example: Age=25,Country=USA (without space)'}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        
        //reset activeplatformid value when new component mounts
        if(typeof editable_field == 'undefined' && this.fielddata.activePlatformId){
            this.fielddata.activePlatformId='';
        }
        this.fielddata=setComponentFieldData(this.fielddata,this.fields);
        this.listLoading = true;

        var listRequestData = {
            'action': 'awp_get_klaviyo_list',
            '_nonce': awp.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#klaviyopro-action-template'
});

function awp_delete_integration(e,spot_count){
    e = e || window.event;
    if(spot_count>0){
        delete_confirm="Please be aware any integration Spots using this account will no longer work.";
    }
    else{
        delete_confirm="Are you sure to delete this account?";
    }
    

    if(confirm(delete_confirm)) {
            return;
    } else {
            e.preventDefault();
    }
}

function awp_delete_integration2(e){
    e = e || window.event;
    delete_confirm="Are you sure to delete this integration?";
    
    if(confirm(delete_confirm)) {
            return;
    } else {
            e.preventDefault();
    }
}

function awp_download_csv_file(csv) {  

    var hiddenElement = document.createElement('a');  
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);  
    hiddenElement.target = '_blank';  
      
    //provide the name for the CSV file to be downloaded  
    hiddenElement.download = 'Automatehub Logs '+new Date().toJSON().slice(0,10)+'.csv';  
    hiddenElement.click();  
}  


jQuery(document).ready(function() {

    triggerStaticFieldsWork();
    jQuery(document).on('click','.dx-button-content',function(){
        var $this = jQuery(this);
        jQuery('.showdropdownlist').hide();
        $this.parent().parent().next('.showdropdownlist').show();
        jQuery('.basedonfieldlist').hide();
    }); 

    jQuery(document).on('click','.dx-additonal-condition',function(){
        var $this = jQuery(this);
        $this.hide();
        jQuery('.dynamiccross').show();
        
        $this.next('.basedonfieldlist').show();
        jQuery('.showdropdownlist').hide().addClass('test');
    });

    jQuery(document).on('click','.dynamiccross',function(){
        var $this = jQuery(this);
        
        $this.parent().hide();
        $this.parent().prev('.dx-additonal-condition').show();
        $this.hide();
        // $this.prev('.basedonfieldlist').hide();
        // $this.prev('.dx-additonal-condition').show();
        // jQuery('.showdropdownlist').hide().addClass('test');
    });


    jQuery(document).on('click','.log-export-btn',function(){
        var $this = jQuery(this);
        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());
        var data = {
                'action': 'awp_export_log',
                '_nonce': awp.nonce,
        };
        data = jQuery.extend(params, data);
        jQuery.post( ajaxurl, data, function( response ) {
            awp_download_csv_file(response);
         
        });
    });



    jQuery(document).on('focusin','input[name="awp_sperse_api_key"], input[name="aws_sperse_api_key"] ',function(){
        var $this = jQuery(this);
      const type = $this.attr('type') === 'password' ? 'text' : 'password';
        $this.attr('type', type);

    });
    jQuery(document).on('focusout','input[name="awp_sperse_api_key"], input[name="aws_sperse_api_key"]',function(){
        var $this = jQuery(this);     
      const type = 'password';
        $this.attr('type', type);
    });

    if(typeof int_id == 'undefined'){
        jQuery(document).on("click",".fx-controls-in-zone .delete-confirm",function(event){

            var $this = jQuery('#'+event.target.id);
            $this =jQuery(this).closest('li');

            //special handling for static fields
            //check is it is static field
            if($this.find(".staticTag")){
                if($this.find(".staticTag").text() == 'Insert Static Value'){
                    $this.remove();
                    //console.log(jQuery(this));
                    //console.log(jQuery(this).closest("li").siblings().length);
                    if(!jQuery(this).closest("li").siblings().length){
                        jQuery(this).closest('.form_field_dropable').removeClass('sperse_dropped');
                    }
                    return;
                }
                
            }
            //end

            var forfieldname = $this.attr('data-name');
            var tomappedfieldname = $this.attr('data-field');
            var field_data = awpNewIntegration.fieldData;

            var already_value = field_data[tomappedfieldname];
            var filteredfordisplaystored = field_data[tomappedfieldname+'dis'];
            var mapped_pr_value = '{{'+forfieldname;
            var maaped_value = '{{'+forfieldname+'}}';  
            

            if(tomappedfieldname=='answerQ1' || tomappedfieldname=='answerQ2' || tomappedfieldname=='answerQ3' ){
            		maaped_value='';
            		filteredfordisplaystored=[];
            }else{
	             if(already_value){
	                 maaped_value = already_value.replace(maaped_value,'');
	             }
	             if(filteredfordisplaystored){
	                 filteredfordisplaystored.remove(mapped_pr_value);
	             }

            }
            field_data[tomappedfieldname] = maaped_value;
            field_data[tomappedfieldname+'dis'] = filteredfordisplaystored;


            //saving new values after deleting the selected one
            awpNewIntegration.fieldData = field_data;
            jQuery(".sperse_reverse_draggable li[data-name='"+forfieldname+"']").css({
                'left':'unset',
                'top' :'unset',
                'display':'block'
            }); 

            if(!jQuery(this).closest("li").siblings().length){
                jQuery(this).closest('.form_field_dropable').removeClass('sperse_dropped')
            }
            
            //saving new value in textbox as well
            jQuery($this.parent().parent().find("input")).val(maaped_value);
    
            jQuery(this).closest("li[data-name='"+forfieldname+"']").remove();
 
            
            jQuery("li[data-name='"+forfieldname+"']").find('.field-actions').addClass('hide');
            if(!(maaped_value)){
                jQuery("input[data-field='"+tomappedfieldname+"']").closest('.form_field_dropable').removeClass('sperse_dropped'); 
            }


        });

    	//var back_field_data = awpNewIntegration.fieldData;
	    jQuery(document).on('change','#ActionTaskList',function(){
	        var selected_value = jQuery(this).val();
	    	var platform_list = jQuery('#PlatformList').val();
	    	if(selected_value && platform_list=='sperse' && (jQuery('.sperse_reverse_draggable ul').children('.end-draggable').length==0)){
	    		var back_field_data = awpNewIntegration.fieldData;
	    	}
	    	if(selected_value && platform_list=='sperse' && (jQuery('.sperse_reverse_draggable ul').children('.end-draggable').length>0)){
	    		if(confirm('WARNING : Dragged Form Fields will reset.')){
	    			var fielsd = awpNewIntegration.fieldData;
	    			jQuery('.sperse_reverse_draggable ul').children('.end-draggable').each(function(){
		    			var key = jQuery(this).attr('data-field');
		    			fielsd[key]='';
		    			fielsd[key+'dis']=[];
		    		});
		    		awpNewIntegration.fieldData = fielsd;
		    		jQuery('.sperse_reverse_draggable ul').children('.end-draggable').find('.field-actions').addClass('hide');
		    		jQuery('.sperse_reverse_draggable ul').children('.end-draggable').removeClass('end-draggable').removeAttr('style').css('position', 'relative');
		    		jQuery('.form_field_dropable').removeClass('sperse_dropped');		    	
	    		}
	    	}
             if(Object.keys(awpNewIntegration.trigger.forms).length==1){

                awpNewIntegration.changedForm(jQuery("#form_default"));
            }
	    });
	}
    else{
        //this part is added for special handling of static dblclick fields remove button for edit integration page only as on edit integration page fields are created with in vue components so "insert static value" could not be accessed with vue component 
        // this code only remove "static fields that do no given any value" otherwise if static field is given proper value it is added on vue component this handled from that part of the code
            jQuery(document).on("click",".fx-controls-in-zone .delete-confirm",function(event){
                var $this = jQuery('#'+event.target.id);
                $this =jQuery(this).closest('li');

                //special handling for static fields
                //check is it is static field
                if($this.find(".staticTag")){
                    if($this.find(".staticTag").text() == 'Insert Static Value'){
                        $this.remove();
                        //console.log(jQuery(this));
                        //console.log(jQuery(this).closest("li").siblings().length);
                        if(!jQuery(this).closest("li").siblings().length){
                            jQuery(this).closest('.form_field_dropable').removeClass('sperse_dropped');
                        }
                        return;
                    }
                    
                }
                //end    
            });
            
    }
    makedropable();
   
    jQuery(document).on('click',"#appsearchbtn",function(event){
         if(jQuery('#appsearchbox').val() != ''){
            window.location.href=window.location.href+'&search='+jQuery('#appsearchbox').val();
         }
         
    });

    jQuery(document).on('click',".tablinks",function(event){
          var i, tabcontent, tablinks;
          tabcontent = jQuery(".tabcontent");
          var target_id = jQuery(this).attr('data-content');
          jQuery(".tabcontent").each(function(){
            jQuery(this).hide();
          });
          jQuery(".tablinks").each(function(){
            jQuery(this).removeClass('active');
          });
        jQuery('#'+target_id).show();
        jQuery(this).addClass('active');
    });
    jQuery('#defaultOpen').trigger('click');

/*    if(jQuery("#awp_codebreaker_user_id").length>0){
        jQuery('#awp_codebreaker_user_id').select2({
          placeholder: 'Select an option'
        }).on('select2:open',function(){
            $('.select2-dropdown--above').attr('id','fix');
            $('#fix').removeClass('select2-dropdown--above');
            $('#fix').addClass('select2-dropdown--below');

        });;
    }*/

function enable_search_form_fields(){
    if(jQuery('.sperse_reverse_draggable').length>0) {
        //console.log(jQuery('.form_fields .filter-input-wrap').length)
        jQuery('.form_fields ul').before("<div class='filter-input-wrap'><input class='filter-input' placeholder='Type here to search by field name in your source form'></input></div>");
        function filter(filter, query) {
            query = jQuery.trim(query);
            jQuery(filter).each(function () {
            (jQuery(this).text().search(new RegExp(query, "i")) < 0) ? jQuery(this).hide().removeClass('name') :
            jQuery(this).show().addClass('name');
                });
            }            
        jQuery('.filter-input').on('input', function (event) {
            if (event.keyCode == 27 || jQuery(this).val() == '') {
            jQuery(this).val('');
            jQuery('.form_fields li').removeClass('name').show().addClass('name');
            }
            else {
            filter('.form_fields li', jQuery(this).val());
            }
        });
    }
}
enable_search_form_fields();
});



function isObjEmpty (obj) {
    for (key in obj) return false;
    return true
  };




/* new part */ 

    // Burger
    if (jQuery('.header__burger').length) {
        let headerNav = jQuery('.menu-top-wrap ul');
        let burger = jQuery('.header__burger');

        burger.on('click', function (e) {
            headerNav.toggleClass('is-show');
            burger.toggleClass('header__burger--active');
            e.stopPropagation();
        });

        headerNav.on('click', function (e) {
            e.stopPropagation();
        });

        jQuery('.menu-top-wrap ul a').on('click', function (e) {
            headerNav.removeClass('is-show');
            burger.removeClass('header__burger--active');
            e.stopPropagation();
        });

        jQuery(document).on('click', function () {
            headerNav.removeClass('is-show');
            burger.removeClass('header__burger--active');
        });
    }

    // Scroll to top
    if (jQuery('.scrollup').length) {
        let scrollBtn = jQuery('.scrollup');
        jQuery(window).on('scroll', function () {
            if (jQuery(this).scrollTop() > 50) {
                scrollBtn.removeClass('scrollup__hide');
            } else {
                scrollBtn.addClass('scrollup__hide');
            }
        });
        scrollBtn.on('click', function (e) {
            e.preventDefault();
            jQuery('body,html').animate({ scrollTop: 0 }, 500);
        });
    }

//  Integrations toggler
    if (jQuery('.view-toggler').length) {
        let toggler = jQuery('.view-toggler');
        let leftForm = jQuery('.form-wrapper-left');
        let rightForm = jQuery('.form-wrapper-right');
		let formTitles = jQuery('.table-form-left h3, .table-form-right h3');
		        
        toggler.on('click', function(){
            jQuery(this).toggleClass('is-active');
            leftForm.slideToggle();
            rightForm.slideToggle();
			formTitles.toggleClass('is-active');
        });
    }

if (jQuery('.nav-tab__toggler').length) {
	let tabsToggler = jQuery('.nav-tab__toggler');
	let tabsButtons = jQuery('.nav-tab-wrapper .nav-tab');
		
	tabsToggler.on('click', function(){
		jQuery(this).toggleClass('is-active');
		tabsButtons.toggleClass('is-active');
	})
}




function setComponentFieldData (fielddata,fields){
    for (var i = 0; i < fields.length; i++) {
        temp=fields[i].value;
        if(!fielddata.hasOwnProperty(temp)){
            fielddata[temp]='';
        }
    }

    // list id was used on multiple platforms so for double save we are declaring it to be on save side
    if(!fielddata.hasOwnProperty('listId')){
            fielddata['listId']='';
        }
    return fielddata;
}
var debug;
function triggerCopyFieldsWork(){
    var copyCount=0;
    //copy element work
    jQuery(document).on("click",".sperse_inner .copy-confirm",function(event){
        copyCount=copyCount+1;
        var box=`
        <li data-name="" data-fname="" data-field="" class="copied_li_tag form_fields_name fx-controls-in-zone ui-draggable ui-draggable-handle" >
            <div class="field-actions">
                <a type="remove" id="" data-name="" data-field="" title="Remove Element" class="copied-del-btn del-button btn formbuilder-icon-cancel delete-confirm">
                
                </a>
            </div> 
            <span class="copiedTag input-group-addon fx-dragdrop-handle"></span>
        </li>`;

        that=jQuery(this);
        debug=jQuery(this);
        var originalElement=that.parent().parent();
        // var ul=originalElement.parent();
        // if(ul.find('li:last').length){
        //     jQuery(originalElement[0].outerHTML).insertAfter(ul.find('li:last'));
        // }
        if(typeof editable_field == 'undefined'){
            var textbox=that.parent().parent().parent().parent().find('input[type=hidden]');
        }
        else{
            var textbox=that.parent().parent().parent().find('input[type=hidden]');
        }
        var dataAttrVal='copied_'+copyCount+'_'+originalElement.attr('data-name');
        var droppedFieldName=textbox.attr('data-field');
        var existing=textbox.val();
        var newVal=existing+'{{'+dataAttrVal+"}}";
        if(typeof editable_field == 'undefined'){
            
            //setting up data-attributes and text for the box (var box=) on new integration page only
            
            var libox=jQuery(box);
            libox.attr('data-name',dataAttrVal);
            libox.attr('data-fname',dataAttrVal);
            libox.attr('data-field',droppedFieldName);
            var removebtn=libox.find('.copied-del-btn');
            removebtn.attr('data-name',dataAttrVal);
            removebtn.attr('data-field',droppedFieldName);
            var spandisplay=libox.find('.copiedTag');
            spandisplay.text(originalElement.find('.fx-dragdrop-handle').text()).show();
            var ul=originalElement.parent();
            if(ul.find('li:last').length){
                jQuery(libox).insertAfter(ul.find('li:last'));
            }
            textbox.val(newVal);
        }
        else{
            jQuery('.static_li_tag').hide();
        }
            
        
        var field_data = awpNewIntegration.fieldData;
        field_data[droppedFieldName]=newVal;
        newArr=newVal.split('}}');
        const disarray = newArr.filter((a) => a);
        field_data[droppedFieldName+'dis']=disarray;

        makedropable();
    });
}

triggerCopyFieldsWork();


function triggerStaticFieldsWork(){

jQuery('.sperse_inner').off('dblclick',);
jQuery('.sperse_inner').on('dblclick', function (e) {
    //prevent dbl click trigger inside child
    //console.log(e.target);
    //console.log(e.currentTarget);

    if(e.target !== e.currentTarget && (!jQuery(event.target).hasClass('sortable') ) ){
        return;
    }

    that=jQuery(this);
    var box=`
    <li data-name="" data-fname="" data-field="" class="static_li_tag form_fields_name fx-controls-in-zone ui-draggable ui-draggable-handle" >
        <div class="field-actions">
            
            <a type="remove" id="" data-name="" data-field="" title="Remove Element" class="static-del-btn del-button btn formbuilder-icon-cancel delete-confirm">
            
            </a>
        </div> 
        <span class="staticTag input-group-addon fx-dragdrop-handle">Insert Static Value</span>
    </li>`;


    if(that.find('li:last').length){
        jQuery(box).insertAfter(that.find('li:last'));
    }
    else{
        if(typeof editable_field == 'undefined'){
            that.find('ul').append(box);
        }
        else{
            jQuery(box).insertBefore(that.find('ul'));
        }
        
        
    }
    that.parent().addClass('sperse_dropped');
    
 
});


jQuery('.form-select-account-wrapper').off('click');
jQuery('.form-select-account-wrapper').on('click',".staticTag", function (e) {
    that=jQuery(this); 
    //if this button already holds a value then do not change it
    if(that.text() != 'Insert Static Value'){
        return;
    }
    that.hide();
    that.parent().append(`<input class="valuestorer" type="text">`);
});


jQuery('.form-select-account-wrapper').off('focusout');
jQuery('.form-select-account-wrapper').on('focusout',".valuestorer", function (e) {
    that=jQuery(this);
    var val=that.val(); 
    if(val==''){
        that.parent().find('.field-actions a[type="remove"]').trigger("click");
        return;
    }else{
        that.parent().find('.field-actions').prepend(`<a type="copy"   id="" data-name="" data-field="" class="copy-img del-button btn formbuilder-icon-cancel copy-confirm" title="Copy Element"></a>`);
    }
    var backend_unique_val=val.replace(/ /g,'_');
    if(typeof editable_field == 'undefined'){
        var textbox=that.parent().parent().parent().find('input[type=hidden]');
    }
    else{
        var textbox=that.parent().parent().find('input[type=hidden]');
    }
    
    var droppedFieldName=textbox.attr('data-field');

    that.hide();

    dataAttrVal='static_'+backend_unique_val;
    var existing=textbox.val();
    var newVal=existing+'{{'+dataAttrVal+"}}";
    
    if(typeof editable_field == 'undefined'){
        
        //setting up data-attributes and text for the box (var box=) on new integration page only
        var libox=that.parent();
        libox.attr('data-name',dataAttrVal);
        libox.attr('data-fname',dataAttrVal);
        libox.attr('data-field',droppedFieldName);
        var removebtn=that.parent().find('.static-del-btn');
        removebtn.attr('data-name',dataAttrVal);
        removebtn.attr('data-field',droppedFieldName);
        var spandisplay=that.parent().find('.staticTag');
        spandisplay.text(val).show();
        
        textbox.val(newVal);
    }
    else{
        jQuery('.static_li_tag').hide();
    }


    
        
    
    var field_data = awpNewIntegration.fieldData;
    field_data[droppedFieldName]=newVal;
    newArr=newVal.split('}}');
    const disarray = newArr.filter((a) => a);
    field_data[droppedFieldName+'dis']=disarray;

    
    makedropable();

    


});


}








jQuery('.awp-integration-quickedit').on('click', function (event) {
    event.preventDefault();
    //handling for existing opened quickedits
        jQuery(".original-row").show();
        jQuery(".my-row").hide();
    //

    let self = jQuery(this);
    let tr=self.parents('tr');
    let integrationID=self.attr('data-integration-id');
    let existingName=self.attr('data-integration-existing-name');
    let submitUrl=self.attr('data-integration-submit-url');
    tr.addClass("original-row");
    tr.hide();

    let mytr=`<tr class="my-row">
                <th></th>
                <td><label>Integration Name:</label></td>
                <td><input data-submit-url="`+submitUrl+`" class="quick-edit-text" style="margin-left: 15px;" type=text value="`+existingName+`"/></td>
                <td></td>
                <td></td>
                <td></td>
                <td><div class="btn btn-primary cancel-integration" >cancel</div></td>
                <td><div class="btn btn-primary update-integration" >Update</div></td>
              </tr>`;
    tr.after(mytr);

});

jQuery('.wp-list-table').on('click','.update-integration', function (event) {
    let self=jQuery(this);
    let textbox=self.parent().parent().find('.quick-edit-text');
    let integrationName=encodeURIComponent(textbox.val());

    let url=textbox.attr('data-submit-url')+'&integration_name='+integrationName;
    
    window.location.replace(url);

});

jQuery('.wp-list-table').on('click','.cancel-integration', function (event) {
    let self=jQuery(this);
    let mytr=self.parent().parent();
    mytr.hide();
    jQuery(".original-row").show();
   

});




// jQuery('.form-select-account-wrapper').on('click',".static-del-btn", function (e) {
//     that=jQuery(this); 
//     that.parent().parent().remove();
//     var textbox=that.parent().parent().parent().find('input[type=hidden]');
//     var existing=textbox.val();
//     var newVal=existing+'{{static_'+val+"}}";
//     textbox.val(newVal);
    
// });


jQuery('.logFilter').click(function(e){
    e.preventDefault();
    var from=jQuery('select[name="log_form_provider"]').val();
    var to=jQuery('select[name="log_action_provider"]').val();
    if(from=="" && to==""){
        from=jQuery('select[name="log_form_provider"]').eq(1).val();
        to=jQuery('select[name="log_action_provider"]').eq(1).val();
    }
    window.location.href ='?page=automate_hub_log&form_provider='+from+'&action_provider='+to;
});



jQuery(document).on("mouseenter", ".sperse_inner li", function(e) {
    if(jQuery(this).find("a[type='copy']").length){
        if(!jQuery(this).find("a[type='copy']").attr('id').includes('copied')){
            jQuery(this).find("a[type='copy']").removeClass('copy-img');
            jQuery(this).find("a[type='copy']").addClass('show-copy-img');  
         }
    }
});

jQuery(document).on("mouseleave", ".sperse_inner li", function(e) {
    if(jQuery(this).find("a[type='copy']").length){
        if(!jQuery(this).find("a[type='copy']").attr('id').includes('copied')){
            jQuery(this).find("a[type='copy']").addClass('copy-img');
            jQuery(this).find("a[type='copy']").removeClass('show-copy-img');   
        }    
    }

     
});




function addNextStep(){
    nextStep=true;

    message="This step will be saved as it is and you will be moved to the next one";
    if(awpNewIntegration.trigger.formProviderId==""|| awpNewIntegration.trigger.formId ==""  || awpNewIntegration.action.actionProviderId == "" || awpNewIntegration.action.task ==""){
        message='Please complete this step first';
        nextStep=false;
    }

    if(!nextStep){
        Swal.fire({
            title:'Cannot move to next step',
            text:'This integration is not complete',
            icon:'error',
        });
    }
    else{

        // Swal.fire({
        //   title: 'Are you sure?',
        //   text: message,
        //   icon: 'warning',
        //   showCancelButton: true,
        //   confirmButtonColor: '#3085d6',
        //   cancelButtonColor: '#d33',
        //   confirmButtonText: 'Go to next step'
        // }).then((result) => {
        //   if (result.isConfirmed) {

        //     if(nextStep){
        //         window.isSibling=true;
        //         jQuery("input[name='save_integration']").click();
        //     }
        //   }
        // });    

    }

    
    window.isSibling=false;
}


/**
 * Popup video in apps header
 */
jQuery("#videobtn").click(function(){
    jQuery("#myModal").modal('show');
    jQuery("#dynamicappname").text(jQuery("#videobtn").data('appname'));
});
jQuery("#exclusive-offer-btn").click(function(){
    jQuery("#promobox").modal('show');
});
jQuery('#myModal').on('hidden.bs.modal', function () {
    // do something…
          jQuery('#popup-iframe-parent').html(`<iframe  src="https://sperse.io/setupvideo.php?appname=<?php echo sanitize_text_field($appname);?>" height="650" scrolling="no" marginwidth="0" marginheight="0"  loading="lazy" frameborder="0" style="border:none;overflow: hidden;"></iframe>`);
});


/**
 * context menu
 */
 var menu = document.querySelector('.menu-platform');
 var menu_form_provider=document.querySelector('.menu-form-source');
 var menu_form_name=document.querySelector('.menu-form-name');
 
 function showMenu(x, y,menu){
     menu.style.left = x + 'px';
     menu.style.top = y + 'px';
     menu.classList.add('menu-show');
 }
 
 function hideMenu(menu){
     menu.classList.remove('menu-show');
 }
 
 function onContextMenu(e,menuType){
     e.preventDefault();
     left=e.pageX;
     top=e.pageY;
     //dropdown_location=left-(col_width*0.56);
 
     showMenu( ( e.pageX-150 ),(e.pageY-20),menuType );
     //document.addEventListener('mousedown', onMouseDown, false);
 }
 
 function onMouseDown(e){
     hideMenu();
     document.removeEventListener('mousedown', onMouseDown);
 }
 jQuery(".form_provider .column_integration_id .form_provider_text").mouseover(function(e){
   onContextMenu(e,menu_form_provider);
 });
 jQuery(".form_name .form_name_text").mouseover(function(e){
   onContextMenu(e,menu_form_name);
 });
 jQuery(".action_provider .column_integration_id .action_provider_text").mouseover(function(e){
   onContextMenu(e,menu);
 });
 
 
 
 jQuery(".column-action_provider").mouseleave(function(e){
     if (jQuery('.menu:hover').length == 0) {
         hideMenu(menu);
     }
   
 });
 
 jQuery(".column-form_provider").mouseleave(function(e){
     if (jQuery('.menu:hover').length == 0) {
         hideMenu(menu_form_provider);
     }
   
 });
 jQuery(".column-form_name").mouseleave(function(e){
     if (jQuery('.menu:hover').length == 0) {
         hideMenu(menu_form_name);
     }
   
 });
 
 jQuery(".menu").mouseleave(function(e){
     if (jQuery('.menu:hover').length == 0) {
         hideMenu(menu_form_provider);
         hideMenu(menu_form_name);
         hideMenu(menu);
 
         jQuery('.menu-edit-integration').removeClass('menu-show');
         jQuery('.menu-delete-integration').removeClass('menu-show');
     }
   
 });
 
 
 
 //document.addEventListener('contextmenu', onContextMenu, false);
 
 
 //jquery for hover items edit,delete
 jQuery(".edit .edit-integration-href").mouseover(function(e){
     jQuery('.menu-edit-integration').addClass('menu-show');
   
 });
 jQuery(".edit .edit-integration-href").mouseleave(function(e){
     setTimeout(() => {
 
         if (jQuery('.menu:hover').length == 0) {
             jQuery('.menu-edit-integration').removeClass('menu-show');
         }
 
     }, 200);
     
     
   
 });
 jQuery(".delete .delete-integration-href").mouseover(function(e){
     jQuery('.menu-delete-integration').addClass('menu-show');
   
 });
 jQuery(".delete .delete-integration-href").mouseleave(function(e){
     setTimeout(() => { 
 
         if (jQuery('.menu:hover').length == 0) {
             jQuery('.menu-delete-integration').removeClass('menu-show');
         }
 
     }, 200);
     
     
   
 });
 


function vueArrayObjectMaker(data){
    if( 
    typeof data === 'object' &&
    !Array.isArray(data) &&
    data !== null){
    
        let objectsArray = [];
        let lists = data;
        for (var key in lists) {
            if (lists.hasOwnProperty(key)) {
                objectsArray.push({id: key, name: lists[key]});
            }
        }

        return objectsArray;
    }


    return data;
    
}

function myFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("navTabSearch");
    filter = input.value.toUpperCase();
    ul = document.getElementById("navTabWrapper");
    li = ul.getElementsByTagName("a");
	
	for (let i of li){
		      if (i.innerText.toUpperCase().indexOf(filter) > -1) {
				  console.log(i.innerText);
            i.style.display = "";
        } else {
            i.style.display = "none";
        }
	}
}


params = new URLSearchParams(window.location.search);
if(params.has('page') && params.get('page') == 'awp_app_directory'){
    var data = {
        'action': 'awp_refresh_app_directory',
        '_nonce': awp.nonce,
    };
    jQuery.post( ajaxurl, data, function( response ) {
        console.log(response);  
    });
}   




jQuery('#awp-new-integration').on('click','.rssfeedbtn', function (event) {
    let self=jQuery(this);
    
    if(jQuery("#rssfeedtxt").val() == '' || jQuery("#rssfeedtxt").val() == undefined){
        jQuery(".rsserrormsg").text("Please enter url");
        return;
    }


    var data = {
        'formProviderId': awpNewIntegration.trigger.formProviderId,
        'formId': awpNewIntegration.trigger.formId,
        'action': 'awp_get_form_fields',
        '_nonce': awp.nonce,
        'awp_feed_url':jQuery("#rssfeedtxt").val(),
        'awp_feed_cron':jQuery("#rssfeedInterval").val(),

    };
    jQuery.post( ajaxurl, data, function( response ) {
        var fields             = response.data;
        if(response.data == ""){
            jQuery(".rsserrormsg").text("Valid RSS Feed not found");
        }
        var cleanobj =  awpNewIntegration.cleanObj(fields);
        awpNewIntegration.trigger.formFields = cleanobj;
        awpNewIntegration.trigger.backupformFields = cleanobj;
        awpNewIntegration.fieldLoading = false;
        awpNewIntegration.checkDroppedFields();
    });
   

});

// var allapp=[{"id":2,"name":"Agile CRM","slug":"agilecrm","created_at":"2021-12-20 19:15:50"},{"id":3,"name":"Airtable","slug":"airtable","created_at":"2021-12-20 19:15:50"},{"id":4,"name":"Asana","slug":"asana","created_at":"2021-12-20 22:13:36"},{"id":14,"name":"Benchmark","slug":"benchmark","created_at":"2021-12-20 22:23:12"},{"id":13,"name":"Basecamp 3","slug":"basecamp3","created_at":"2021-12-20 22:23:12"},{"id":12,"name":"Aweber","slug":"aweber","created_at":"2021-12-20 22:23:12"},{"id":11,"name":"Autopilot","slug":"autopilot","created_at":"2021-12-20 22:23:12"},{"id":15,"name":"Calendly","slug":"calendly","created_at":"2021-12-20 22:23:12"},{"id":16,"name":"Campaign Monitor","slug":"campaignmonitor","created_at":"2021-12-20 22:23:12"},{"id":17,"name":"Capsule CRM","slug":"capsulecrm","created_at":"2021-12-20 22:23:12"},{"id":18,"name":"Clinchpad","slug":"clinchpad","created_at":"2021-12-20 22:23:12"},{"id":19,"name":"Close","slug":"close","created_at":"2021-12-20 22:23:12"},{"id":20,"name":"Convertkit","slug":"convertkit","created_at":"2021-12-20 22:23:12"},{"id":21,"name":"Copper","slug":"copper","created_at":"2021-12-20 22:23:12"},{"id":22,"name":"Curated","slug":"curated","created_at":"2021-12-20 22:23:12"},{"id":23,"name":"Direct IQ","slug":"directiq","created_at":"2021-12-20 22:23:12"},{"id":24,"name":"Drip","slug":"drip","created_at":"2021-12-20 22:23:12"},{"id":25,"name":"Elastic Email","slug":"elasticemail","created_at":"2021-12-20 22:23:12"},{"id":26,"name":"Email Octopus","slug":"emailoctopus","created_at":"2021-12-20 22:23:12"},{"id":27,"name":"Everwebinar","slug":"everwebinar","created_at":"2021-12-20 22:23:12"},{"id":28,"name":"freshworks","slug":"freshworks","created_at":"2021-12-20 22:23:12"},{"id":29,"name":"getresponse","slug":"getresponse","created_at":"2021-12-20 22:23:12"},{"id":30,"name":"googlecalendar","slug":"googlecalendar","created_at":"2021-12-20 22:23:12"},{"id":31,"name":"googlesheets","slug":"googlesheets","created_at":"2021-12-20 22:23:12"},{"id":32,"name":"highlevel","slug":"highlevel","created_at":"2021-12-20 22:23:12"},{"id":33,"name":"hubspot","slug":"hubspot","created_at":"2021-12-20 22:23:12"},{"id":34,"name":"Asana","slug":"influencersoft","created_at":"2021-12-20 22:23:12"},{"id":35,"name":"Asana","slug":"insightly","created_at":"2021-12-20 22:23:12"},{"id":36,"name":"Asana","slug":"jetwebinar","created_at":"2021-12-20 22:23:12"},{"id":37,"name":"Asana","slug":"jumplead","created_at":"2021-12-20 22:23:12"},{"id":38,"name":"Asana","slug":"kartra","created_at":"2021-12-20 22:23:12"},{"id":39,"name":"Asana","slug":"keap","created_at":"2021-12-20 22:23:12"},{"id":40,"name":"Asana","slug":"klaviyo","created_at":"2021-12-20 22:23:12"},{"id":41,"name":"Asana","slug":"lemlist","created_at":"2021-12-20 22:23:12"},{"id":42,"name":"Asana","slug":"lifterlms","created_at":"2021-12-20 22:23:12"},{"id":43,"name":"Asana","slug":"liondesk","created_at":"2021-12-20 22:23:12"},{"id":44,"name":"Asana","slug":"mailchimp","created_at":"2021-12-20 22:23:12"},{"id":45,"name":"Asana","slug":"mailerlite","created_at":"2021-12-20 22:23:12"},{"id":46,"name":"Asana","slug":"mailgun","created_at":"2021-12-20 22:23:12"},{"id":47,"name":"Asana","slug":"mailify","created_at":"2021-12-20 22:23:12"},{"id":48,"name":"Asana","slug":"mailjet","created_at":"2021-12-20 22:23:12"},{"id":49,"name":"Asana","slug":"messagebird","created_at":"2021-12-20 22:23:12"},{"id":50,"name":"Asana","slug":"moonmail","created_at":"2021-12-20 22:23:12"},{"id":51,"name":"Asana","slug":"moosend","created_at":"2021-12-20 22:23:12"},{"id":52,"name":"Asana","slug":"omnisend","created_at":"2021-12-20 22:23:12"},{"id":53,"name":"Asana","slug":"ontraport","created_at":"2021-12-20 22:23:12"},{"id":54,"name":"Asana","slug":"pabbly","created_at":"2021-12-20 22:23:12"},{"id":55,"name":"Asana","slug":"pipedrive","created_at":"2021-12-20 22:23:12"},{"id":56,"name":"Asana","slug":"pushover","created_at":"2021-12-20 22:23:12"},{"id":57,"name":"Asana","slug":"revue","created_at":"2021-12-20 22:23:12"},{"id":58,"name":"Asana","slug":"sendfox","created_at":"2021-12-20 22:23:12"},{"id":59,"name":"Asana","slug":"sendgrid","created_at":"2021-12-20 22:23:12"},{"id":60,"name":"Asana","slug":"sendinblue","created_at":"2021-12-20 22:23:12"},{"id":61,"name":"Asana","slug":"sendpulse","created_at":"2021-12-20 22:23:12"},{"id":62,"name":"Asana","slug":"sendy","created_at":"2021-12-20 22:23:12"},{"id":63,"name":"Asana","slug":"smartsheet","created_at":"2021-12-20 22:23:12"},{"id":64,"name":"Asana","slug":"trello","created_at":"2021-12-20 22:24:28"},{"id":65,"name":"Asana","slug":"twilio","created_at":"2021-12-20 22:24:29"},{"id":66,"name":"Asana","slug":"webhookin","created_at":"2021-12-20 22:24:29"},{"id":67,"name":"Asana","slug":"webhookout","created_at":"2021-12-20 22:24:29"},{"id":68,"name":"Asana","slug":"webinarjam","created_at":"2021-12-20 22:24:29"},{"id":69,"name":"Asana","slug":"woodpecker","created_at":"2021-12-20 22:24:29"},{"id":70,"name":"Asana","slug":"zapier","created_at":"2021-12-20 22:24:29"},{"id":71,"name":"Asana","slug":"zoho","created_at":"2021-12-20 22:24:42"},{"id":72,"name":"Sperse","slug":"sperse","created_at":"2021-12-21 21:54:08"},{"id":73,"name":"Postmark","slug":"postmark","created_at":"2022-01-21 21:27:00"},{"id":74,"name":"Contacts+","slug":"contactsplus","created_at":"2022-01-21 21:34:06"},{"id":75,"name":"Teachable","slug":"teachable","created_at":"2022-01-21 21:34:06"},{"id":76,"name":"Google Drive","slug":"googledrive","created_at":"2022-01-21 21:34:07"},{"id":77,"name":"Monday.com","slug":"monday","created_at":"2022-01-21 21:34:07"},{"id":78,"name":"Shopify","slug":"shopify","created_at":"2022-01-21 21:34:07"},{"id":79,"name":"Baremetrics","slug":"baremetrics","created_at":"2022-02-16 20:35:54"},{"id":80,"name":"Customer","slug":"customer","created_at":"2022-02-16 20:35:55"},{"id":81,"name":"Kajabi","slug":"kajabi","created_at":"2022-02-18 23:06:12"},{"id":82,"name":"Active Campaign","slug":"activecampaign","created_at":"2022-02-18 23:06:13"},{"id":83,"name":"Intercom","slug":"intercom","created_at":"2022-02-28 15:39:22"},{"id":84,"name":"Salesmate","slug":"salesmate","created_at":"2022-03-29 17:07:20"},{"id":85,"name":"Todoist","slug":"todoist","created_at":"2022-03-30 12:23:33"},{"id":86,"name":"Eventbrite","slug":"eventbrite","created_at":"2022-03-30 12:48:54"},{"id":87,"name":"Company Hub","slug":"companyhub","created_at":"2022-04-08 12:58:52"},{"id":88,"name":"Drift","slug":"drift","created_at":"2022-04-08 13:09:00"},{"id":89,"name":"Esputnik","slug":"esputnik","created_at":"2022-04-08 13:18:03"},{"id":90,"name":"Followupboss","slug":"followupboss","created_at":"2022-04-08 13:22:35"},{"id":91,"name":"Go4client","slug":"go4client","created_at":"2022-04-08 13:25:38"},{"id":92,"name":"Chargebee","slug":"chargebee","created_at":"2022-05-06 15:17:58"},{"id":93,"name":"Fivetran","slug":"fivetran","created_at":"2022-05-06 15:17:58"},{"id":94,"name":"Gotowebinar","slug":"gotowebinar","created_at":"2022-05-06 15:17:59"},{"id":95,"name":"Klipfolio","slug":"klipfolio","created_at":"2022-05-06 15:17:59"},{"id":96,"name":"Wealthbox","slug":"wealthbox","created_at":"2022-05-06 15:18:00"},{"id":97,"name":"Zulip","slug":"zulip","created_at":"2022-05-06 15:18:00"},{"id":98,"name":"Firstpromoter","slug":"firstpromoter","created_at":"2022-05-06 15:18:00"},{"id":99,"name":"Helpscout","slug":"helpscout","created_at":"2022-05-06 15:18:00"},{"id":100,"name":"Gotomeeting","slug":"gotomeeting","created_at":"2022-05-06 15:18:01"},{"id":101,"name":"Cleverreach","slug":"cleverreach","created_at":"2022-05-06 15:18:01"},{"id":102,"name":"Callrail","slug":"callrail","created_at":"2022-05-06 15:18:01"},{"id":103,"name":"Salesflare","slug":"salesflare","created_at":"2022-09-13 08:45:27"},{"id":104,"name":"Clickup","slug":"clickup","created_at":"2022-09-13 08:50:05"},{"id":105,"name":"Versel","slug":"versel","created_at":"2022-09-13 08:53:24"},{"id":106,"name":"Vercel","slug":"vercel","created_at":"2022-09-13 08:54:20"},{"id":107,"name":"Squarespace","slug":"squarespace","created_at":"2022-09-13 08:55:54"},{"id":108,"name":"Appcues","slug":"appcues","created_at":"2022-11-10 07:47:51"},{"id":109,"name":"Calcom","slug":"calcom","created_at":"2022-11-10 07:47:51"},{"id":110,"name":"Clockify","slug":"clockify","created_at":"2022-11-10 07:47:51"},{"id":111,"name":"Helpwise","slug":"helpwise","created_at":"2022-11-10 07:47:52"},{"id":112,"name":"LiveAgent","slug":"liveagent","created_at":"2022-11-10 07:47:52"},{"id":113,"name":"OneHash","slug":"onehash","created_at":"2022-11-10 07:47:52"},{"id":114,"name":"Sellsy","slug":"sellsy","created_at":"2022-11-10 07:47:52"},{"id":115,"name":"Simvoly","slug":"simvoly","created_at":"2022-11-10 07:47:53"},{"id":116,"name":"Trigger","slug":"trigger","created_at":"2022-11-10 07:47:53"},{"id":117,"name":"Testmonitor","slug":"testmonitor","created_at":"2022-11-15 06:15:03"},{"id":118,"name":"Growmatik","slug":"growmatik","created_at":"2022-11-15 06:15:03"},{"id":119,"name":"Productlift","slug":"productlift","created_at":"2022-11-16 11:01:02"},{"id":120,"name":"Readwise","slug":"readwise","created_at":"2022-12-05 09:35:38"}];




//  var allapps=[];

//     jQuery( ".col-app" ).each(function( index ) {
//   var datatype=jQuery(this).data('type');
//   var datacat=(jQuery(this).data('category'));
//   var apptext=jQuery(this).find('.app-desc').text();
//   var appname=jQuery(this).find('.title-app').text().toLowerCase();
//   appname=appname.replace(/\s/g, '');

//   if(datacat === undefined){datacat="app";}
//   for (var i = 0  ; i < allapp.length; i++) {

//       if(allapp[i].slug && allapp[i].slug == appname){
//         var app={'datatype':datatype,'datacat':datacat,'apptext':apptext,id:allapp[i].id}
//         allapps.push(app); 
//       }
//     }  
  
   
   
// });
// console.log(allapps);

//  var data = {
//         'apps': allapps,
        
//         'action': 'directory_upload',
//         '_nonce': awp.nonce,
        

//     };
//     jQuery.post( ajaxurl, data, function( response ) {
//         console.log(response);
//     });

