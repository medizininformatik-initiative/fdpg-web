var A=Object.defineProperty;var k=Object.getOwnPropertySymbols;var B=Object.prototype.hasOwnProperty,U=Object.prototype.propertyIsEnumerable;var M=(l,a,i)=>a in l?A(l,a,{enumerable:!0,configurable:!0,writable:!0,value:i}):l[a]=i,h=(l,a)=>{for(var i in a||(a={}))B.call(a,i)&&M(l,i,a[i]);if(k)for(var i of k(a))U.call(a,i)&&M(l,i,a[i]);return l};import{j as e}from"./main.js";import{b as O,a as E,_ as r,h as T,B as $}from"./bi-490-178.js";import{T as S}from"./bi-422-718.js";import{S as V}from"./bi-652-725.js";import{T as w}from"./bi-528-726.js";const o=(l,a,i,s)=>{const t=h({},i);t[l].splice(a,0,{}),s(h({},t))},q=(l,a,i,s)=>{const t=h({},i);t[l].length>1&&t[l].splice(a,1),s(h({},t))},f=(l,a,i,s,t)=>{const d=h({},s);d[l][i][a.target.name]=a.target.value,t(h({},d))},K=l=>!((l!=null&&l.post_map?l.post_map.filter(i=>!i.formField&&i.postField&&i.required):[]).length>0),Q=l=>!((l!=null&&l.acf_map?l.acf_map.filter(i=>!i.formField&&i.acfField&&i.required):[]).length>0),W=l=>!((l!=null&&l.metabox_map?l.metabox_map.filter(i=>!i.formField&&i.metaboxField&&i.required):[]).length>0),X=(l,a)=>{const i=O({},"post-types/list").then(s=>{if(s&&s.success){const{data:t}=s;return t&&a(t),t!==0?"Successfully refresh Post Types.":" Post Types not found"}});E.promise(i,{success:s=>s,error:r("Error Occurred","bit-integrations"),loading:r("Loading Post Types...")})};function _({i:l,type:a,formFields:i,field:s,postConf:t,setPostConf:d,customFields:x,fieldType:u}){var c;const p={acf:{propName:"acf_map",fldName:"acfField"},acfFile:{propName:"acf_file_map",fldName:"acfFileUpload"},metabox:{propName:"metabox_map",fldName:"metaboxField"},metaboxFile:{propName:"metabox_file_map",fldName:"metaboxFileUpload"}},{propName:m,fldName:b}=p[a],g=(n,y,R,z)=>{const F=h({},R);F[m][y].customValue=n,z(h({},F))},N=x.length>0&&!!x.find(n=>n.key===s[b]&&n.required),v=T($),{isPro:j}=v;return e.jsxs("div",{className:"flx mt-2 mb-2 btcbi-field-map",children:[e.jsxs("div",{className:"flx integ-fld-wrp",children:[e.jsxs("select",{className:"btcd-paper-inp mr-2",name:"formField",value:s.formField||"",onChange:n=>f(m,n,l,t,d),children:[e.jsx("option",{value:"",children:r("Select Field","bit-integrations")}),u==="fields"?e.jsxs(e.Fragment,{children:[e.jsx("optgroup",{label:"Form Fields",children:i==null?void 0:i.map(n=>n.type!=="file"&&e.jsx("option",{value:n.name,children:n.label},`ff-zhcrm-${n.name}`))}),e.jsx("option",{value:"custom",children:r("Custom...","bit-integrations")}),e.jsx("optgroup",{label:`General Smart Codes ${j?"":"(PRO)"}`,children:j&&((c=V)==null?void 0:c.map(n=>e.jsx("option",{value:n.name,children:n.label},`ff-zhcrm-${n.name}`)))})]}):i==null?void 0:i.map(n=>n.type==="file"&&e.jsx("option",{value:n.name,children:n.label},`ff-zhcrm-${n.name}`))]}),s.formField==="custom"&&e.jsx(w,{onChange:n=>g(n,l,t,d),label:r("Custom Value","bit-integrations"),className:"mr-2",type:"text",value:s.customValue||"",placeholder:r("Custom Value","bit-integrations"),formFields:i}),e.jsxs("select",{className:"btcd-paper-inp",name:b,value:s[b]||"",onChange:n=>f(m,n,l,t,d),disabled:N,children:[e.jsx("option",{value:"",children:r("Select Field","bit-integrations")}),x.length>0&&(x==null?void 0:x.map(n=>e.jsx("option",{value:n.key,children:`${n.name}`},`${n.key}-1`)))]})]}),!N&&e.jsxs(e.Fragment,{children:[e.jsx("button",{onClick:()=>o(m,l,t,d),className:"icn-btn sh-sm ml-2 mr-1",type:"button",children:"+"}),e.jsx("button",{onClick:()=>q(m,l,t,d),className:"icn-btn sh-sm ml-1",type:"button","aria-label":"btn",children:e.jsx(S,{})})]})]})}function Y({formFields:l,postConf:a,setPostConf:i,acfFields:s,mbFields:t}){var d,x,u,p;return e.jsx("div",{children:e.jsxs("div",{style:{width:900},children:[e.jsxs("div",{children:[e.jsxs("div",{children:[e.jsx("div",{className:"mt-3 mb-1",children:e.jsx("b",{children:r("ACF Fields Mapping ","bit-integrations")})}),e.jsx("div",{className:"btcd-hr"}),e.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("Form Fields","bit-integrations")})}),e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("ACF Fields","bit-integrations")})})]})]}),(d=a==null?void 0:a.acf_map)==null?void 0:d.map((m,b)=>e.jsx(_,{i:b,type:"acf",field:m,formFields:l,postConf:a,setPostConf:i,customFields:s==null?void 0:s.fields,fieldType:"fields"},`analytics-m-${b+9}`)),e.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:e.jsx("button",{onClick:()=>o("acf_map",a.acf_map.length,a,i),className:"icn-btn sh-sm",type:"button",children:"+"})})]}),e.jsxs("div",{children:[e.jsxs("div",{children:[e.jsx("div",{className:"mt-3 mb-1",children:e.jsx("b",{children:r("ACF File Upload Fields Mapping","bit-integrations")})}),e.jsx("div",{className:"btcd-hr"}),e.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("Form Fields","bit-integrations")})}),e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("ACF Fields","bit-integrations")})})]})]}),(x=a==null?void 0:a.acf_file_map)==null?void 0:x.map((m,b)=>e.jsx(_,{i:b,type:"acfFile",field:m,formFields:l,postConf:a,setPostConf:i,customFields:s==null?void 0:s.files,fieldType:"file"},`analytics-m-${b+9}`)),e.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:e.jsx("button",{onClick:()=>{var m;return o("acf_file_map",(m=a==null?void 0:a.acf_file_map)==null?void 0:m.length,a,i)},className:"icn-btn sh-sm",type:"button",children:"+"})})]}),e.jsx("br",{}),e.jsx("br",{}),e.jsxs("div",{children:[e.jsxs("div",{children:[e.jsx("div",{className:"mt-3 mb-1",children:e.jsx("b",{children:r("MetaBox Fields Mapping","bit-integrations")})}),e.jsx("div",{className:"btcd-hr"}),e.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("Form Fields","bit-integrations")})}),e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("MB Fields","bit-integrations")})})]})]}),(u=a==null?void 0:a.metabox_map)==null?void 0:u.map((m,b)=>e.jsx(_,{i:b,type:"metabox",field:m,formFields:l,postConf:a,setPostConf:i,customFields:t==null?void 0:t.fields,fieldType:"fields"},`analytics-m-${b+9}`)),e.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:e.jsx("button",{onClick:()=>o("metabox_map",a.metabox_map.length,a,i),className:"icn-btn sh-sm",type:"button",children:"+"})})]}),e.jsxs("div",{children:[e.jsxs("div",{children:[e.jsx("div",{className:"mt-3 mb-1",children:e.jsx("b",{children:r("MetaBox File Upload Fields Mapping","bit-integrations")})}),e.jsx("div",{className:"btcd-hr"}),e.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("Form Fields","bit-integrations")})}),e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("MB Fields","bit-integrations")})})]})]}),(p=a==null?void 0:a.metabox_file_map)==null?void 0:p.map((m,b)=>e.jsx(_,{i:b,type:"metaboxFile",field:m,formFields:l,postConf:a,setPostConf:i,customFields:t==null?void 0:t.files,fieldType:"file"},`analytics-m-${b+9}`)),e.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:e.jsx("button",{onClick:()=>o("metabox_file_map",a.metabox_file_map.length,a,i),className:"icn-btn sh-sm",type:"button",children:"+"})})]})]})})}function Z({i:l,type:a,formFields:i,field:s,postConf:t,setPostConf:d,customFields:x}){var j;const u={acf:{propName:"acf_map",fldName:"acfField"},post:{propName:"post_map",fldName:"postField"},acfFile:{propName:"acf_file_map",fldName:"acfFileUpload"}},{propName:p,fldName:m}=u[a],b=(c,n)=>{const y=h({},t);y[p][n].customValue=c,d(y)},g=!!x.find(c=>c.key===s[m]&&c.required),N=T($),{isPro:v}=N;return e.jsxs("div",{className:"flx mt-2 mb-2 btcbi-field-map",children:[e.jsxs("div",{className:"flx integ-fld-wrp",children:[e.jsxs("select",{className:"btcd-paper-inp mr-2",name:"formField",value:s.formField||"",onChange:c=>f(p,c,l,t,d),children:[e.jsx("option",{value:"",children:r("Select Field","bit-integrations")}),e.jsx("optgroup",{label:"Form Fields",children:i==null?void 0:i.map(c=>e.jsx("option",{value:c.name,children:c.label},`ff-zhcrm-${c.name}`))}),e.jsx("option",{value:"custom",children:r("Custom...","bit-integrations")}),e.jsx("optgroup",{label:`General Smart Codes ${v?"":"(PRO)"}`,children:v&&((j=V)==null?void 0:j.map(c=>e.jsx("option",{value:c.name,children:c.label},`ff-zhcrm-${c.name}`)))})]}),s.formField==="custom"&&e.jsx(w,{onChange:c=>b(c,l),label:r("Custom Value","bit-integrations"),className:"mr-2",type:"text",value:s.customValue||"",placeholder:r("type # to use trigger field","bit-integrations"),formFields:i}),e.jsxs("select",{className:"btcd-paper-inp",name:m,value:s[m]||"",onChange:c=>f(p,c,l,t,d),disabled:g,children:[e.jsx("option",{value:"",children:r("Select Field","bit-integrations")}),x==null?void 0:x.map(c=>e.jsx("option",{value:c.key,children:`${c.name}`},`${c.key}-1`))]})]}),!g&&e.jsxs(e.Fragment,{children:[e.jsx("button",{onClick:()=>o(p,l,t,d),className:"icn-btn sh-sm ml-2 mr-1",type:"button",children:"+"}),e.jsx("button",{onClick:()=>q(p,l,t,d),className:"icn-btn sh-sm ml-1",type:"button","aria-label":"btn",children:e.jsx(S,{})})]})]})}export{Y as C,Z as F,o as a,Q as b,K as c,W as d,X as r};
