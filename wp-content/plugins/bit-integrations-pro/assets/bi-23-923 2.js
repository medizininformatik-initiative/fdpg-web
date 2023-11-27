var _=Object.defineProperty;var N=Object.getOwnPropertySymbols;var L=Object.prototype.hasOwnProperty,R=Object.prototype.propertyIsEnumerable;var F=(e,s,i)=>s in e?_(e,s,{enumerable:!0,configurable:!0,writable:!0,value:i}):e[s]=i,r=(e,s)=>{for(var i in s||(s={}))L.call(s,i)&&F(e,i,s[i]);if(N)for(var i of N(s))R.call(s,i)&&F(e,i,s[i]);return e};import{r as T,j as t,L as y}from"./main.js";import{a7 as M,_ as a,d as k,h as A,B as S}from"./bi-490-178.js";import{S as g}from"./bi-652-725.js";function U({gravitecConf:e,setGravitecConf:s,loading:i,setLoading:l}){var m,n;const[c,d]=T.useState({show:!1,action:()=>{}}),p=(o,j)=>{var x;const u=r({},e);j==="button"&&((x=o.target)!=null&&x.checked?(u.selectedButtonTitle===void 0&&(u.selectedButtonTitle=""),u.selectedButtonURL===void 0&&(u.selectedButtonURL=""),u.actions.button=!0):(d({show:!1}),delete u.actions.button)),d({show:j}),s(u)},h=()=>{d({show:!1})},b=(o,j)=>{s(u=>{const x=r({},u);return x[j]=o,x})};return t.jsxs("div",{className:"pos-rel d-flx flx-wrp",children:[e.actionName==="notification"&&t.jsx(M,{checked:((m=e==null?void 0:e.selectedButtonTitle)==null?void 0:m.length)&&((n=e==null?void 0:e.selectedButtonURL)==null?void 0:n.length)||!1,onChange:o=>p(o,"button"),className:"wdt-200 mt-4 mr-2",value:"button",title:a("Add Button","bit - integrations"),subTitle:a("Add Button")}),t.jsxs(k,{className:"custom-conf-mdl",mainMdlCls:"o-v",btnClass:"blue",btnTxt:a("Ok","bit-integrations"),show:c.show==="button",close:h,action:h,title:a("Add Button","bit-integrations"),children:[t.jsx("div",{className:"btcd-hr mt-2 mb-2"}),t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:a("Button Title:","bit-integrations")})}),t.jsx("input",{className:"btcd-paper-inp mt-1",onChange:o=>b(o.target.value,"selectedButtonTitle"),name:"button_title",value:e.selectedButtonTitle,type:"text",placeholder:a("Button Title...","bit-integrations")}),t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:a("Button URL:","bit-integrations")})}),t.jsx("input",{className:"btcd-paper-inp mt-1",onChange:o=>b(o.target.value,"selectedButtonURL"),name:"button_url",value:e.selectedButtonURL,type:"text",placeholder:a("Button URL...","bit-integrations")})]})]})}const B=(e,s,i)=>{const l=r({},s);l.field_map.splice(e,0,{}),i(r({},l))},$=(e,s,i)=>{const l=r({},s);l.field_map.length>1&&l.field_map.splice(e,1),i(r({},l))},w=(e,s,i,l)=>{const c=r({},i);c.field_map[s][e.target.name]=e.target.value,e.target.value==="custom"&&(c.field_map[s].customValue=""),l(r({},c))};function q({i:e,formFields:s,field:i,gravitecConf:l,setGravitecConf:c}){var m;const d=(l==null?void 0:l.notificationFields)&&(l==null?void 0:l.notificationFields.filter(n=>n.required===!0))||[],p=(l==null?void 0:l.notificationFields)&&(l==null?void 0:l.notificationFields.filter(n=>n.required===!1))||[],h=A(S),{isPro:b}=h;return t.jsx("div",{className:"flx mt-2 mb-2 btcbi-field-map",children:t.jsxs("div",{className:"pos-rel flx",children:[t.jsxs("div",{className:"flx integ-fld-wrp",children:[t.jsxs("select",{className:"btcd-paper-inp mr-2",name:"formField",value:i.formField||"",onChange:n=>w(n,e,l,c),children:[t.jsx("option",{value:"",children:a("Select Field","bit-integrations")}),t.jsx("optgroup",{label:"Form Fields",children:s==null?void 0:s.map(n=>t.jsx("option",{value:n.name,children:n.label},`ff-rm-${n.name}`))}),t.jsx("option",{value:"custom",children:a("Custom...","bit-integrations")}),t.jsx("optgroup",{label:`General Smart Codes ${b?"":"(PRO)"}`,children:b&&((m=g)==null?void 0:m.map(n=>t.jsx("option",{value:n.name,children:n.label},`ff-rm-${n.name}`)))})]}),i.formField==="custom"&&t.jsx(CustomField,{field:i,index:e,conf:l,setConf:c,fieldValue:"customValue",fieldLabel:"Custom Value",className:"mr-2"}),t.jsxs("select",{className:"btcd-paper-inp",disabled:e<d.length,name:"gravitecFormField",value:e<d.length?d[e].key||"":i.gravitecFormField||"",onChange:n=>w(n,e,l,c),children:[t.jsx("option",{value:"",children:a("Select Field","bit-integrations")}),e<d.length?t.jsx("option",{value:d[e].key,children:d[e].label},d[e].key):p.map(({key:n,label:o})=>t.jsx("option",{value:n,children:o},n))]})]}),e>=d.length&&t.jsxs(t.Fragment,{children:[t.jsx("button",{onClick:()=>B(e,l,c),className:"icn-btn sh-sm ml-2 mr-1",type:"button",children:"+"}),t.jsx("button",{onClick:()=>$(e,l,c),className:"icn-btn sh-sm ml-1",type:"button","aria-label":"btn",children:t.jsx("span",{className:"btcd-icn icn-trash-2"})})]})]})})}function O({formFields:e,gravitecConf:s,setGravitecConf:i,loading:l,setLoading:c,isLoading:d,setIsLoading:p,setSnackbar:h}){const b=m=>{const n=r({},s),{name:o}=m.target;m.target.value!==""?n[o]=m.target.value:delete n[o],i(n)};return t.jsxs(t.Fragment,{children:[t.jsx("br",{}),t.jsx("b",{className:"wdt-200 d-in-b",children:a("Select Action:","bit-integrations")}),t.jsxs("select",{onChange:b,name:"actionName",value:s.actionName,className:"btcd-paper-inp w-5",children:[t.jsx("option",{value:"",children:a("Select an action","bit-integrations")}),t.jsx("option",{value:"notification","data-action_name":"notification",children:a("Push Notification","bit-integrations")})]}),d&&t.jsx(y,{style:{display:"flex",justifyContent:"center",alignItems:"center",height:100,transform:"scale(0.7)"}}),s.actionName&&!d&&t.jsxs("div",{children:[t.jsx("br",{}),t.jsx("div",{className:"mt-5",children:t.jsx("b",{className:"wdt-100",children:a("Field Map","bit-integrations")})}),t.jsx("br",{}),t.jsx("div",{className:"btcd-hr mt-1"}),t.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[t.jsx("div",{className:"txt-dp",children:t.jsx("b",{children:a("Form Fields","bit-integrations")})}),t.jsx("div",{className:"txt-dp",children:t.jsx("b",{children:a("Gravitec Fields","bit-integrations")})})]}),s==null?void 0:s.field_map.map((m,n)=>t.jsx(q,{i:n,field:m,gravitecConf:s,formFields:e,setGravitecConf:i,setSnackbar:h},`rp-m-${n+9}`)),t.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:t.jsx("button",{onClick:()=>B(s.field_map.length,s,i),className:"icn-btn sh-sm",type:"button",children:"+"})}),t.jsx("br",{}),t.jsx("br",{}),t.jsx("div",{className:"mt-4",children:t.jsx("b",{className:"wdt-100",children:a("Actions","bit-integrations")})}),t.jsx("div",{className:"btcd-hr mt-1"}),t.jsx(U,{gravitecConf:s,setGravitecConf:i,formFields:e,loading:l,setLoading:c})]})]})}export{O as G};
