var g=Object.defineProperty;var x=Object.getOwnPropertySymbols;var F=Object.prototype.hasOwnProperty,f=Object.prototype.propertyIsEnumerable;var j=(s,l,t)=>l in s?g(s,l,{enumerable:!0,configurable:!0,writable:!0,value:t}):s[l]=t,c=(s,l)=>{for(var t in l||(l={}))F.call(l,t)&&j(s,t,l[t]);if(x)for(var t of x(l))f.call(l,t)&&j(s,t,l[t]);return s};import{j as e}from"./main.js";import{h as N,B as v,_ as r,af as _,a7 as w}from"./bi-490-178.js";import{g as y}from"./bi-517-773.js";import{S as k}from"./bi-652-725.js";const u=(s,l,t)=>{const i=c({},l);i.field_map.splice(s,0,{}),t(c({},i))},S=(s,l,t)=>{const i=c({},l);i.field_map.length>1&&i.field_map.splice(s,1),t(c({},i))};function $({i:s,formFields:l,field:t,dropboxConf:i,setDropboxConf:n}){var h;const m=N(v),{isPro:d}=m,b=(a,o)=>{const p=c({},i);p.field_map[o][a.target.name]=a.target.value,n(c({},p))};return e.jsx("div",{className:"flx mt-2 mb-2 btcbi-field-map",children:e.jsxs("div",{className:"pos-rel flx",children:[e.jsxs("div",{className:"flx integ-fld-wrp",children:[e.jsxs("select",{className:"btcd-paper-inp mr-2",name:"formField",value:t.formField||"",onChange:a=>b(a,s),children:[e.jsx("option",{value:"",children:r("Select Field","bit-integrations")}),e.jsx("optgroup",{label:"Form Fields",children:l==null?void 0:l.filter(a=>a.type==="file").map(a=>e.jsx("option",{value:a.name,children:a.label},`ff-rm-${a.name}`))}),e.jsx("optgroup",{label:`General Smart Codes ${d?"":"(PRO)"}`,children:d&&((h=k)==null?void 0:h.map(a=>e.jsx("option",{value:a.name,children:a.label},`ff-rm-${a.name}`)))})]}),e.jsxs("select",{className:"btcd-paper-inp",name:"dropboxFormField",value:t.dropboxFormField,onChange:a=>b(a,s),children:[e.jsx("option",{value:"",children:r("Select Folder","bit-integrations")}),_(i.foldersList,"lower_path","ASC").map(({name:a,lower_path:o})=>e.jsx("option",{value:o,children:o.substring(1).split("/").map(p=>p.replace("/",">")).join(" > ")},o))]})]}),e.jsx("button",{onClick:()=>u(s,i,n),className:"icn-btn sh-sm ml-2 mr-1",type:"button",children:"+"}),e.jsx("button",{onClick:()=>S(s,i,n),className:"icn-btn sh-sm ml-1",type:"button","aria-label":"btn",children:e.jsx("span",{className:"btcd-icn icn-trash-2"})})]})})}function A({dropboxConf:s,setDropboxConf:l}){var i;const t=(n,m)=>{const d=c({},s);m==="deleteFile"&&(n.target.checked?d.actions.delete_from_wp=!0:delete d.actions.delete_from_wp),l(c({},d))};return e.jsx("div",{className:"pos-rel d-flx w-8",children:e.jsx(w,{checked:((i=s.actions)==null?void 0:i.delete_from_wp)||!1,onChange:n=>t(n,"deleteFile"),className:"mt-4 mr-2",value:"delete_from_wp",title:r("Delete File From Wordpress","bit-integrations"),subTitle:r("Delete file from Wordpress after upload in Dropbox","bit-integrations")})})}function I({flowID:s,formFields:l,dropboxConf:t,setDropboxConf:i}){return e.jsxs(e.Fragment,{children:[e.jsxs("div",{className:"mt-5",children:[e.jsx("b",{className:"wdt-100",children:r("Field Map","bit-integrations")}),e.jsx("button",{onClick:()=>y(s,t,i),className:"icn-btn sh-sm ml-2 mr-2 tooltip",style:{"--tooltip-txt":`'${r("Fetch All Dropbox Folders","bit-integrations")}'`},type:"button",children:"↻"})]}),e.jsx("div",{className:"btcd-hr mt-1"}),e.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("File Input","bit-integrations")})}),e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:r("Dropbox Folder","bit-integrations")})})]}),t==null?void 0:t.field_map.map((n,m)=>e.jsx($,{i:m,field:n,formFields:l,dropboxConf:t,setDropboxConf:i},`rp-m-${m+9}`)),e.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:e.jsx("button",{onClick:()=>u(t.field_map.length,t,i),className:"icn-btn sh-sm",type:"button",children:"+"})}),e.jsx("br",{}),e.jsx("br",{}),e.jsx("div",{className:"mt-4",children:e.jsx("b",{className:"wdt-100",children:r("Actions","bit-integrations")})}),e.jsx("div",{className:"btcd-hr mt-1"}),e.jsx(A,{dropboxConf:t,setDropboxConf:i})]})}export{I as D};
