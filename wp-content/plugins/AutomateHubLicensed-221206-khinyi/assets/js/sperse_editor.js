jQuery(document).ready(function(){

var lists = document.querySelectorAll('.cv-icon-list');
        for (var i = 0; i < lists.length; i++) {
            var list = lists[i];
            var parent = list.parentElement;
            parent.appendChild(list.cloneNode(1));
        }
        lists = document.querySelectorAll('.cv-icon-list');
        for (var i = 0; i < lists.length; i++) {
            var list = lists[i];
            list.classList.add('active');
        }
     
     
     jQuery('.v-select.style-chooser-formProvider.vs--single.vs--searchable').on('click', function(){
            changeFormProviderDDStyling();
        });
     jQuery('.v-select.style-chooser-formProvider.vs--single.vs--searchable input').on('focus', function(){
            changeFormProviderDDStyling();
        });
    
    jQuery('.v-select.style-chooser.vs--single.vs--searchable').on('click', function(){
            changePlatformDDStyling();
        });
     jQuery('.v-select.style-chooser.vs--single.vs--searchable input').on('focus', function(){
            changePlatformDDStyling();
        });    
    
        
        
    function changeFormProviderDDStyling(){
        jQuery('.ProvidersActive').each(function(){ 
            let styleid =  jQuery(this).parent('li').attr('id'); 
            // jQuery('#'+styleid).attr('style', 'background: #cfffe8 !important; margin: 0');
            if(document.getElementById(styleid) !== null){
                document.getElementById(styleid).style.background="#cfffe8";
                document.getElementById(styleid).style.margin="0";    
                    
            }
            });
        jQuery('.ProvidersInactive').each(function(){ 
            let styleid =  jQuery(this).parent('li').attr('id'); 
            // jQuery('#'+styleid).attr('style', 'background: #ffe8d4 !important; margin: 0');
            if(document.getElementById(styleid) !== null){
                document.getElementById(styleid).style.background="#ffe8d4";
                document.getElementById(styleid).style.margin="0";    
                    
            }
                
            });
    }
    function changePlatformDDStyling(){
        jQuery('.SelectedPlatform').each(function(){ 
            let styleid =  jQuery(this).parent('li').attr('id'); 
            
            // jQuery('#'+styleid).attr('style', 'background: #ffe8d4 !important; margin: 0');
                if(document.getElementById(styleid) !== null){
                    document.getElementById(styleid).style.background="#ffe8d4";
                    document.getElementById(styleid).style.margin="0";    
                    
                }
                
            });
        
        // let selectedPlatformClass = jQuery('.SelectedPlatform').parent('li').attr('class');
        // if(selectedPlatformClass!==undefined){
        //     selectedPlatformClass = selectedPlatformClass.replaceAll(" ", ",");
        //     // jQuery('.'+selectedPlatformClass).attr('style', 'background: #ffe8d4 !important; margin: 0');
        //     document.querySelector('.'+selectedPlatformClass).style.background="#ffe8d4";
        //     document.querySelector('.'+selectedPlatformClass).style.margin="0";
        // }    
        
        // let availablePlatformsClass = jQuery('.AvailablePlatforms').parent('li').attr('class');
        // if(availablePlatformsClass!==undefined){
        //     availablePlatformsClass = availablePlatformsClass.replaceAll(" ", ",");
        //     // jQuery('.'+availablePlatformsClass).attr('style', 'background: #e8ffec !important; margin: 0');
        //     document.querySelector('.'+availablePlatformsClass).style.background="#e8ffec";
        //     document.querySelector('.'+availablePlatformsClass).style.margin="0";
        // }
        
        jQuery('.AvailablePlatforms').each(function(){ 
            let styleid =  jQuery(this).parent('li').attr('id'); 
            // jQuery('#'+styleid).attr('style', 'background: #e8ffec !important; margin: 0');
            // console.log(document.getElementById(styleid).style.background!=="#e8ffec");
            if(document.getElementById(styleid) !== null){
                document.getElementById(styleid).style.background="#e8ffec";
                document.getElementById(styleid).style.margin="0";
            }
            
                
            });
    }
    
    // document.querySelectorAll('.v-select.style-chooser.vs--single.vs--searchable')[0].addEventListener('click',function(){
    // setTimeout(function(){
                   
    //             jQuery('.SelectedPlatform').each(function(){ 
    //                 let styleid =  jQuery(this).parent('li').attr('id'); 
    //                 document.getElementById(styleid).style.background="#ffe8d4";
    //                 document.getElementById(styleid).style.margin="0";
                        
    //                 });
    //             jQuery('.AvailablePlatforms').each(function(){ 
    //                 let styleid =  jQuery(this).parent('li').attr('id'); 
    //                 // document.getElementById(styleid).style.background="#e8ffec";
    //                 // document.getElementById(styleid).style.margin="0";
                        
    //                 });    
    
    //             }, 100);
    // })



});


    
jQuery("#preleft").dblclick(function(){
  jQuery("#sendRequestBtn").show();
  jQuery(this).parent().append("<textarea id='requestSent' style='width: 100%;height:"+jQuery(this).height()+"px;'>"+jQuery(this).text()+"</textarea>");
  jQuery(this).hide();
});


jQuery("#sendRequestBtn").on("click", function(){
  jQuery("#requestSent").attr("disabled", true);
  data=jQuery("#requestSent").val();
  var url_string = document.location.href; //window.location.href
  var url = new URL(url_string); 
  var log_id = url.searchParams.get("id");
  var data = {
            'action': 'awp_log_resend_request',
            'data':data,
            'log_id':log_id,
            '_nonce': awpObj.nonce
        };

   jQuery.post( ajaxurl, data, function( response ) {
        response=JSON.parse(response);
        if(response.success){
          url.searchParams.set("id",response.log_id);
          window.location.href=url.toString();
        }
        else{
          alert(response.msg);
        }
        
       
   });

});
