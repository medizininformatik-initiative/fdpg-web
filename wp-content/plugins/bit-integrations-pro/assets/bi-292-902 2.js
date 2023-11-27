var h=Object.defineProperty,k=Object.defineProperties;var F=Object.getOwnPropertyDescriptors;var d=Object.getOwnPropertySymbols;var y=Object.prototype.hasOwnProperty,b=Object.prototype.propertyIsEnumerable;var m=(t,a,e)=>a in t?h(t,a,{enumerable:!0,configurable:!0,writable:!0,value:e}):t[a]=e,i=(t,a)=>{for(var e in a||(a={}))y.call(a,e)&&m(t,e,a[e]);if(d)for(var e of d(a))b.call(a,e)&&m(t,e,a[e]);return t},r=(t,a)=>k(t,F(a));import{_ as u,b as o,a as n}from"./bi-490-178.js";const A=(t,a,e)=>{const c=i({},a),{name:s}=t.target;t.target.value!==""?c[s]=t.target.value:delete c[s],e(i({},c))},S=t=>{let a=[];t.actionName==="task"&&(a=t==null?void 0:t.taskFields);const e=a==null?void 0:a.filter(c=>c.required===!0);return e.length>0?e.map(c=>({formField:"",clickupFormField:c.key})):[{formField:"",clickupFormField:""}]},P=t=>!((t!=null&&t.field_map?t.field_map.filter(e=>!e.formField||!e.clickupFormField||e.formField==="custom"&&!e.customValue||e.clickupFormField==="customFieldKey"&&!e.customFieldKey):[]).length>0),q=(t,a,e,c,s,l)=>{if(!t.api_key){e({api_key:t.api_key?"":u("Api Key can't be empty","bit-integrations")});return}e({}),l(r(i({},s),{auth:!0}));const f={api_key:t.api_key};o(f,"clickup_authentication").then(_=>{if(_&&_.success){c(!0),l(r(i({},s),{auth:!1})),n.success(u("Authorized successfully","bit-integrations"));return}l(r(i({},s),{auth:!1})),n.error(u("Authorized failed, Please enter valid API key","bit-integrations"))})},w=(t,a,e)=>{e(r(i({},e),{customFields:!0}));const c={api_key:t.api_key,action:t.actionName,list_id:t.selectedList};o(c,"clickup_fetch_custom_fields").then(s=>{if(s&&s.success){a(l=>{const f=i({},l);return f.default||(f.default={}),s.data&&(f.customFields=s.data),f}),e(r(i({},e),{customFields:!1})),s.data?n.success(u("Custom fields also fetched successfully","bit-integrations")):n.error(u("No custom fields found","bit-integrations"));return}e(r(i({},e),{customFields:!1})),n.error(u("Custom fields fetching failed","bit-integrations"))})},v=(t,a,e)=>{e(r(i({},e),{Teams:!0}));const c={api_key:t.api_key,action_name:t.actionName};o(c,"clickup_fetch_all_Teams").then(s=>{if(s&&s.success){const l=i({},t);s.data&&(l.Teams=s.data),a(l),e(r(i({},e),{Teams:!1})),t.actionName==="task"&&n.success(u("Teams fetched successfully","bit-integrations"));return}e(r(i({},e),{Teams:!1})),t.actionName==="task"&&n.error(u("Teams fetching failed","bit-integrations"))})},K=(t,a,e)=>{e(r(i({},e),{Spaces:!0}));const c={api_key:t.api_key,action_name:t.actionName,team_id:t.selectedTeam};o(c,"clickup_fetch_all_Spaces").then(s=>{if(s&&s.success){const l=i({},t);s.data&&(l.Spaces=s.data),a(l),e(r(i({},e),{Spaces:!1})),t.actionName==="task"&&n.success(u("Spaces fetched successfully","bit-integrations"));return}e(r(i({},e),{Spaces:!1})),t.actionName==="task"&&n.error(u("Spaces fetching failed","bit-integrations"))})},g=(t,a,e)=>{e(r(i({},e),{Folders:!0}));const c={api_key:t.api_key,action_name:t.actionName,space_id:t.selectedSpace};o(c,"clickup_fetch_all_Folders").then(s=>{if(s&&s.success){const l=i({},t);s.data&&(l.Folders=s.data),a(l),e(r(i({},e),{Folders:!1})),t.actionName==="task"&&n.success(u("Folders fetched successfully","bit-integrations"));return}e(r(i({},e),{Folders:!1})),t.actionName==="task"&&n.error(u("Folders fetching failed","bit-integrations"))})},z=(t,a,e)=>{e(r(i({},e),{Lists:!0}));const c={api_key:t.api_key,action_name:t.actionName,folder_id:t.selectedFolder};o(c,"clickup_fetch_all_Lists").then(s=>{if(s&&s.success){const l=i({},t);s.data&&(l.Lists=s.data),a(l),e(r(i({},e),{Lists:!1})),t.actionName==="task"&&n.success(u("Lists fetched successfully","bit-integrations"));return}e(r(i({},e),{Lists:!1})),t.actionName==="task"&&n.error(u("Lists fetching failed","bit-integrations"))})};export{v as a,K as b,P as c,g as d,z as e,w as f,S as g,A as h,q as i};
