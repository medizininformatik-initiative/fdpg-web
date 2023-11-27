var u=Object.defineProperty;var x=Object.getOwnPropertySymbols;var F=Object.prototype.hasOwnProperty,f=Object.prototype.propertyIsEnumerable;var h=(l,s,t)=>s in l?u(l,s,{enumerable:!0,configurable:!0,writable:!0,value:t}):l[s]=t,c=(l,s)=>{for(var t in s||(s={}))F.call(s,t)&&h(l,t,s[t]);if(x)for(var t of x(s))f.call(s,t)&&h(l,t,s[t]);return l};import{j as e}from"./main.js";import{h as N,B as _,_ as r,af as w,a7 as v}from"./bi-490-178.js";import{S as y}from"./bi-652-725.js";import{g as k}from"./bi-348-775.js";const j=(l,s,t)=>{const a=c({},s);a.field_map.splice(l,0,{}),t(c({},a))},g=(l,s,t)=>{const a=c({},s);a.field_map.length>1&&a.field_map.splice(l,1),t(c({},a))};function S({i:l,formFields:s,field:t,googleDriveConf:a,setGoogleDriveConf:n}){var o;const{isPro:d}=N(_),m=(i,p)=>{const b=c({},a);b.field_map[p][i.target.name]=i.target.value,n(c({},b))};return e.jsx("div",{className:"flx mt-2 mb-2 btcbi-field-map",children:e.jsxs("div",{className:"pos-rel flx",children:[e.jsxs("div",{className:"flx integ-fld-wrp",children:[e.jsxs("select",{className:"btcd-paper-inp mr-2",name:"formField",value:t.formField||"",onChange:i=>m(i,l),children:[e.jsx("option",{value:"",children:r("Select Field","bit-integrations")}),e.jsx("optgroup",{label:"Form Fields",children:s==null?void 0:s.filter(i=>i.type==="file").map(i=>e.jsx("option",{value:i.name,children:i.label},`ff-rm-${i.name}`))}),e.jsx("optgroup",{label:`General Smart Codes ${d?"":"(PRO)"}`,children:d&&((o=y)==null?void 0:o.map(i=>e.jsx("option",{value:i.name,children:i.label},`ff-rm-${i.name}`)))})]}),e.jsxs("select",{className:"btcd-paper-inp",name:"googleDriveFormField",value:t.googleDriveFormField,onChange:i=>m(i,l),children:[e.jsx("option",{value:"",children:r("Select Folder","bit-integrations")}),w(a.foldersList,"name","ASC").map(({name:i,id:p})=>e.jsx("option",{value:p,children:i},p))]})]}),e.jsx("button",{onClick:()=>j(l,a,n),className:"icn-btn sh-sm ml-2 mr-1",type:"button",children:"+"}),e.jsx("button",{onClick:()=>g(l,a,n),className:"icn-btn sh-sm ml-1",type:"button","aria-label":"btn",children:e.jsx("span",{className:"btcd-icn icn-trash-2"})})]})})}function $({googleDriveConf:l,setGoogleDriveConf:s}){var a;const t=(n,d)=>{const m=c({},l);d==="deleteFile"&&(n.target.checked?m.actions.delete_from_wp=!0:delete m.actions.delete_from_wp),s(c({},m))};return e.jsx("div",{className:"pos-rel d-flx w-8",children:e.jsx(v,{checked:((a=l.actions)==null?void 0:a.delete_from_wp)||!1,onChange:n=>t(n,"deleteFile"),className:"mt-4 mr-2",value:"delete_from_wp",title:r("Delete File From Wordpress","bit-integrations"),subTitle:r("Delete file from Wordpress after upload in GoogleDrive","bit-integrations")})})}function T({flowID:l,formFields:s,googleDriveConf:t,setGoogleDriveConf:a}){return e.jsxs(e.Fragment,{children:[e.jsxs("div",{className:"mt-5",children:[e.jsx("b",{className:"wdt-100",children:r("Field Map","bit-integrations")}),e.jsx("button",{onClick:()=>k(l,t,a),className:"icn-btn sh-sm ml-2 mr-2 tooltip",style:{"--tooltip-txt":`'${r("Fetch All GoogleDrive Folders","bit-integrations")}'`},type:"button",children:"↻"})]}),e.jsx("div",{className:"btcd-hr mt-1"}),e.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("File Input","bit-integrations")})}),e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("GoogleDrive Folder","bit-integrations")})})]}),t==null?void 0:t.field_map.map((n,d)=>e.jsx(S,{i:d,field:n,formFields:s,googleDriveConf:t,setGoogleDriveConf:a},`rp-m-${d+9}`)),e.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:e.jsx("button",{onClick:()=>j(t.field_map.length,t,a),className:"icn-btn sh-sm",type:"button",children:"+"})}),e.jsx("br",{}),e.jsx("br",{}),e.jsx("div",{className:"mt-4",children:e.jsx("b",{className:"wdt-100",children:r("Actions","bit-integrations")})}),e.jsx("div",{className:"btcd-hr mt-1"}),e.jsx($,{googleDriveConf:t,setGoogleDriveConf:a})]})}export{T as G};
