document.addEventListener("DOMContentLoaded",function(){function getParentUrl(){var isInIframe=(parent!==window),parentUrl=document.location.href;if(isInIframe){parentUrl=document.referrer;}
return parentUrl;}
var myCookie=getCookie("_referringURL");if(myCookie===undefined){if(document.referrer.indexOf(document.location.hostname)===-1){var now=new Date();var time=now.getTime();time+=43200*1000;var value=document.referrer;var entyrUrl=getParentUrl();now.setTime(time);document.cookie='_referringURL='+value+
'; expires='+now.toUTCString()+
'; path=/';document.cookie='_entryUrl='+entyrUrl+
'; expires='+now.toUTCString()+
'; path=/';}}else{if(document.referrer.indexOf(document.location.hostname)===-1){if(document.referrer!==""){var now=new Date();var time=now.getTime();time+=43200*1000;var value=document.referrer;var entyrUrl=getParentUrl();now.setTime(time);document.cookie='_referringURL='+value+
'; expires='+now.toUTCString()+
'; path=/';document.cookie='_entryUrl='+entyrUrl+
'; expires='+now.toUTCString()+
'; path=/';}}}
function getCookie(name){var value="; "+document.cookie;var parts=value.split("; "+name+"=");if(parts.length==2)return parts.pop().split(";").shift();}});