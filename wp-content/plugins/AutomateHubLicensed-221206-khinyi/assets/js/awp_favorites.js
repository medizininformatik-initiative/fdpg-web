function add_favorite_platform(platform){
    var data = {
        'action': 'add_platform_favourite',
        '_nonce': awp.nonce,
        'platform': platform
    };
    jQuery.post( ajaxurl, data, function( response ) {
        var resp = response.data;
        console.log(resp);
    });
}

function remove_favorite_platform(platform){
    var data = {
        'action': 'remove_platform_favourite',
        '_nonce': awp.nonce,
        'platform': platform
    };
    jQuery.post( ajaxurl, data, function( response ) {
        var resp = response.data;
        console.log(resp);
    });
}

function triggerFavorites(){
    var data = {
        'action': 'get_platform_favourite',
        '_nonce': awp.nonce
    };
    jQuery.post( ajaxurl, data, function( response ) {


        try {

            response=JSON.parse(response);
            var resp = response.data;
            jQuery('.title-app').each(function(i, obj) {
                title=jQuery(obj).text().toLowerCase();
                title = title.replace(/\s/g, '');
                for (var i = 0; i < resp.length; i++) {
                    if(title == resp[i].platform.toLowerCase()){
    
                        currentVal=jQuery(obj).parent().attr("data-category");
                        jQuery(obj).parent().attr("data-category",currentVal+",favourite");
                        jQuery(obj).siblings(".heart").html('<i class="fa fa-heart" aria-hidden="true"></i>').addClass("liked");
                    }
                }
            });
            
        } catch (error) {

            console.log(response);
            
        }


       
    });
}

function triggerFormProviderSelect(formProviderName,formName){
        awpNewIntegration.trigger.formProviderId=formProviderName;
        awpNewIntegration.changeFormProvider1('',formName);
}

function triggerFormSelect(formName){
    awpNewIntegration.trigger.formId=formName;
}

function triggerActionProviderSelect(actionProvider){
    awpNewIntegration.action.actionProviderId=actionProvider;
}
function triggerTaskSelect(task){
    awpNewIntegration.action.task=task;
}
