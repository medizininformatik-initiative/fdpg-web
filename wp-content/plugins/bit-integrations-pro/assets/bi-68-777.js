var y=Object.defineProperty;var b=Object.getOwnPropertySymbols;var S=Object.prototype.hasOwnProperty,$=Object.prototype.propertyIsEnumerable;var f=(e,t,a)=>t in e?y(e,t,{enumerable:!0,configurable:!0,writable:!0,value:a}):e[t]=a,l=(e,t)=>{for(var a in t||(t={}))S.call(t,a)&&f(e,a,t[a]);if(b)for(var a of b(t))$.call(t,a)&&f(e,a,t[a]);return e};import{_ as r,a as d,b as C}from"./bi-490-178.js";const w=(e,t,a)=>{const o=l({},t),{name:s}=e.target;e.target.value!==""?o[s]=e.target.value:delete o[s],a(l({},o))},v=e=>!((e?e.filter(a=>!a.formField||!a.googleCalendarFormField):[]).length>0),L=(e,t,a)=>{const o={flowID:e!=null?e:null,clientId:t.clientId,clientSecret:t.clientSecret,tokenDetails:t.tokenDetails},s=C(o,"googleCalendar_get_all_lists").then(i=>{if(i&&i.success){const n=l({},t);return i.data.googleCalendarLists&&(n.calendarLists=i.data.googleCalendarLists,n.tokenDetails=i.data.tokenDetails),a(n),"Google Calendar List refreshed successfully"}else return"Google Calendar List refresh failed. please try again"});d.promise(s,{success:i=>i,error:r("Error Occurred","bit-integrations"),loading:r("Loading Google Calendar List...","bit-integrations")})},A=(e,t,a,o,s)=>{if(!e.clientId||!e.clientSecret){s({clientId:e.clientId?"":r("Client Id can't be empty","bit-integrations"),clientSecret:e.clientSecret?"":r("Client Secret can't be empty","bit-integrations")});return}o(!0);const n=`https://accounts.google.com/o/oauth2/v2/auth?scope=https://www.googleapis.com/auth/calendar&access_type=offline&prompt=consent&response_type=code&state=${encodeURIComponent(window.location.href)}/redirect&redirect_uri=${encodeURIComponent(`${btcbi.api.base}/redirect`)}&client_id=${e.clientId}`,g=window.open(n,"googleCalendar","width=400,height=609,toolbar=off"),_=setInterval(()=>{if(g.closed){clearInterval(_);let c={},h=!1;const p=localStorage.getItem("__googleCalendar");if(p&&(h=!0,c=JSON.parse(p),localStorage.removeItem("__googleCalendar")),!c.code||c.error||!c||!h){const u=c.error?`Cause: ${c.error}`:"";d.error(`${r("Authorization failed","bit-integrations")} ${u}. ${r("please try again","bit-integrations")}`),o(!1)}else{const u=l({},e);u.accountServer=c["accounts-server"],k(c,u,t,a,o)}}},500)},k=(e,t,a,o,s)=>{const i=l({},e);i.clientId=t.clientId,i.clientSecret=t.clientSecret,i.redirectURI=`${btcbi.api.base}/redirect`,C(i,"googleCalendar_authorization").then(n=>{if(n&&n.success){const g=l({},t);g.tokenDetails=n.data,a(g),o(!0),d.success(r("Authorized Successfully","bit-integrations"))}else n&&n.data&&n.data.data||!n.success&&typeof n.data=="string"?d.error(`${r("Authorization failed Cause:","bit-integrations")}${n.data.data||n.data}. ${r("please try again","bit-integrations")}`):d.error(r("Authorization failed. please try again","bit-integrations"));s(!1)})};export{A as a,v as c,L as g,w as h};
