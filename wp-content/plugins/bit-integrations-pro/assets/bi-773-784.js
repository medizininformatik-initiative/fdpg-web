var F=Object.defineProperty;var x=Object.getOwnPropertySymbols;var v=Object.prototype.hasOwnProperty,_=Object.prototype.propertyIsEnumerable;var j=(s,d,t)=>d in s?F(s,d,{enumerable:!0,configurable:!0,writable:!0,value:t}):s[d]=t,p=(s,d)=>{for(var t in d||(d={}))v.call(d,t)&&j(s,t,d[t]);if(x)for(var t of x(d))_.call(d,t)&&j(s,t,d[t]);return s};import{j as e}from"./main.js";import{h as w,B as y,_ as c,a8 as M}from"./bi-490-178.js";import{g as S,a as V}from"./bi-454-783.js";import{S as $}from"./bi-652-725.js";import{h as k}from"./bi-266-720.js";import{T as R}from"./bi-528-726.js";const N=(s,d,t)=>{const n=p({},d);n.field_map.splice(s,0,{}),t(p({},n))},q=(s,d,t)=>{const n=p({},d);n.field_map.length>1&&n.field_map.splice(s,1),t(p({},n))},g=(s,d,t,n)=>{const l=p({},t);l.field_map[d][s.target.name]=s.target.value,s.target.value==="custom"&&(l.field_map[d].customValue=""),n(p({},l))};function L({i:s,formFields:d,field:t,elasticEmailConf:n,setElasticEmailConf:l}){var a,b;if(((a=n==null?void 0:n.field_map)==null?void 0:a.length)===1&&t.elasticEmailField===""){const r=p({},n),h=S(r);r.field_map=h,l(r)}const i=(n==null?void 0:n.elasticEmailFields.filter(r=>r.required===!0))||[],m=(n==null?void 0:n.elasticEmailFields.filter(r=>r.required===!1))||[],u=w(y),{isPro:o}=u;return e.jsx("div",{className:"flx mt-2 mb-2 btcbi-field-map",children:e.jsxs("div",{className:"pos-rel flx",children:[e.jsxs("div",{className:"flx integ-fld-wrp",children:[e.jsxs("select",{className:"btcd-paper-inp mr-2",name:"formField",value:t.formField||"",onChange:r=>g(r,s,n,l),children:[e.jsx("option",{value:"",children:c("Select Field","bit-integrations")}),e.jsx("optgroup",{label:"Form Fields",children:d==null?void 0:d.map(r=>e.jsx("option",{value:r.name,children:r.label},`ff-rm-${r.name}`))}),e.jsx("option",{value:"custom",children:c("Custom...","bit-integrations")}),e.jsx("optgroup",{label:`General Smart Codes ${o?"":"(PRO)"}`,children:o&&((b=$)==null?void 0:b.map(r=>e.jsx("option",{value:r.name,children:r.label},`ff-rm-${r.name}`)))})]}),t.formField==="custom"&&e.jsx(R,{onChange:r=>k(r,s,n,l),label:c("Custom Value","bit-integrations"),className:"mr-2",type:"text",value:t.customValue,placeholder:c("Custom Value","bit-integrations"),formFields:d}),e.jsxs("select",{className:"btcd-paper-inp",disabled:s<i.length,name:"elasticEmailField",value:s<i.length?i[s].label||"":t.elasticEmailField||"",onChange:r=>g(r,s,n,l),children:[e.jsx("option",{value:"",children:c("Select Field","bit-integrations")}),s<i.length?e.jsx("option",{value:i[s].key,children:i[s].label},i[s].key):m.map(({key:r,label:h})=>e.jsx("option",{value:r,children:h},r))]})]}),s>=i.length&&e.jsxs(e.Fragment,{children:[e.jsx("button",{onClick:()=>N(s,n,l),className:"icn-btn sh-sm ml-2 mr-1",type:"button",children:"+"}),e.jsx("button",{onClick:()=>q(s,n,l),className:"icn-btn sh-sm ml-1",type:"button","aria-label":"btn",children:e.jsx("span",{className:"btcd-icn icn-trash-2"})})]})]})})}function z({formFields:s,handleInput:d,elasticEmailConf:t,setElasticEmailConf:n,isLoading:l,setIsLoading:i,setSnackbar:m}){var o;const u=a=>{const b=p({},t);b.list_id=a?a.split(","):[],n(p({},b))};return e.jsxs(e.Fragment,{children:[e.jsx("br",{}),e.jsxs("div",{className:"flx",children:[e.jsx("b",{className:"wdt-200 d-in-b",children:c("Lists:","bit-integrations")}),e.jsx(M,{defaultValue:t.list_id,className:"btcd-paper-drpdwn w-5",options:((o=t==null?void 0:t.default)==null?void 0:o.lists)&&t.default.lists.map(a=>({label:a.listName,value:a.listName.toString()})),onChange:a=>u(a)}),e.jsx("button",{onClick:()=>V(t,n,i),className:"icn-btn sh-sm ml-2 mr-2 tooltip",style:{"--tooltip-txt":`'${c("Fetch All Recipients","bit-integrations")}'`},type:"button",disabled:l,children:"↻"})]}),e.jsx("br",{}),e.jsx("div",{className:"mt-5",children:e.jsx("b",{className:"wdt-100",children:c("Field Map","bit-integrations")})}),e.jsx("div",{className:"btcd-hr mt-1"}),e.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:c("Form Fields","bit-integrations")})}),e.jsx("div",{className:"txt-dp",children:e.jsx("b",{children:c("Elastic Email Fields","bit-integrations")})})]}),t.list_id&&(t==null?void 0:t.field_map.map((a,b)=>e.jsx(L,{i:b,field:a,elasticEmailConf:t,formFields:s,setElasticEmailConf:n,setSnackbar:m},`rp-m-${b+9}`))),e.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:e.jsx("button",{onClick:()=>N(t.field_map.length,t,n),className:"icn-btn sh-sm",type:"button",children:"+"})}),e.jsx("br",{}),e.jsx("br",{})]})}export{z as E};
