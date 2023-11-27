var b=Object.defineProperty,p=Object.defineProperties;var F=Object.getOwnPropertyDescriptors;var o=Object.getOwnPropertySymbols;var f=Object.prototype.hasOwnProperty,y=Object.prototype.propertyIsEnumerable;var h=(t,i,e)=>i in t?b(t,i,{enumerable:!0,configurable:!0,writable:!0,value:e}):t[i]=e,a=(t,i)=>{for(var e in i||(i={}))f.call(i,e)&&h(t,e,i[e]);if(o)for(var e of o(i))y.call(i,e)&&h(t,e,i[e]);return t},n=(t,i)=>p(t,F(i));import{_ as l,b as m,a as d}from"./bi-490-178.js";const q=(t,i,e)=>{const c=a({},i),{name:s}=t.target;t.target.value!==""?c[s]=t.target.value:delete c[s],e(a({},c))},w=(t,i,e,c)=>{e(!0);const s={public_id:t.public_id,secret_key:t.secret_key,action_name:t.actionName};m(s,"suite_dash_fetch_all_fields").then(r=>{r&&r.success?(i(_=>{const u=a({},_);return u.field_map=[{formField:"",suiteDashFormField:""}],r.data&&(u.suiteDashFields=r.data,u.field_map=k(u)),u}),c({show:!0,msg:l("SuiteDash fields refreshed","bit-integrations")})):c({show:!0,msg:l("SuiteDash fields refresh failed. please try again","bit-integrations")}),e(!1)}).catch(()=>e(!1))},k=t=>{const i=(t==null?void 0:t.suiteDashFields)&&(t==null?void 0:t.suiteDashFields.filter(e=>e.required===!0&&e.key!=="owner"&&e.key!=="pipeline"));return i.length>0?i.map(e=>({formField:"",suiteDashFormField:e.key})):[{formField:"",suiteDashFormField:""}]},A=t=>!((t!=null&&t.field_map?t.field_map.filter(e=>!e.formField||!e.suiteDashFormField||e.formField==="custom"&&!e.customValue||e.suiteDashFormField==="customFieldKey"&&!e.customFieldKey):[]).length>0),K=(t,i,e,c,s,r)=>{if(!t.public_id||!t.secret_key){e({public_id:t.public_id?"":l("Public Id can't be empty","bit-integrations"),secret_key:t.secret_key?"":l("Secret Key can't be empty","bit-integrations")});return}e({}),r(n(a({},s),{auth:!0}));const _={public_id:t.public_id,secret_key:t.secret_key};m(_,"suite_dash_authentication").then(u=>{if(u&&u.success){c(!0),r(n(a({},s),{auth:!1})),d.success(l("Authorized successfully","bit-integrations"));return}r(n(a({},s),{auth:!1})),d.error(l("Authorized failed, Please enter valid Public Id & Secret Key","bit-integrations"))})},S=(t,i,e)=>{e(n(a({},e),{companies:!0}));const c={public_id:t.public_id,secret_key:t.secret_key};m(c,"suite_dash_fetch_all_companies").then(s=>{if(s&&s.success){if(s.data){i(r=>(r.companies=s.data,r)),e(n(a({},e),{companies:!1})),d.success(l("Companies fetched successfully","bit-integrations"));return}e(n(a({},e),{companies:!1})),d.error(l("Companies Not Found!","bit-integrations"));return}e(n(a({},e),{companies:!1})),d.error(l("Companies fetching failed","bit-integrations"))})};export{A as c,S as g,q as h,w as r,K as s};
