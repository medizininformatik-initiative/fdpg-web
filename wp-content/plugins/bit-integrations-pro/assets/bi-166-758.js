var I=Object.defineProperty;var F=Object.getOwnPropertySymbols;var R=Object.prototype.hasOwnProperty,k=Object.prototype.propertyIsEnumerable;var _=(r,c,e)=>c in r?I(r,c,{enumerable:!0,configurable:!0,writable:!0,value:e}):r[c]=e,h=(r,c)=>{for(var e in c||(c={}))R.call(c,e)&&_(r,e,c[e]);if(F)for(var e of F(c))k.call(c,e)&&_(r,e,c[e]);return r};import{j as t,L as $}from"./main.js";import{a7 as T,_ as l,h as U,B as O,a8 as M}from"./bi-490-178.js";import{h as V,a as L}from"./bi-266-720.js";import{r as y,a as S,b as f}from"./bi-517-757.js";import{T as q}from"./bi-422-718.js";import{S as D}from"./bi-652-725.js";import{T as z}from"./bi-528-726.js";function A({sendinBlueConf:r,setSendinBlueConf:c,setIsLoading:e,setSnackbar:s}){var b,x;const p=(d,j)=>{const m=h({},r);j==="update"&&(d.target.checked?m.actions.update=!0:delete m.actions.update),j==="double_optin"&&(d.target.checked?(m.actions.double_optin=!0,m.templateId="",m.redirectionUrl="",y(m,c,s)):(delete m.actions.double_optin,delete m.templateId,delete m.redirectionUrl)),c(h({},m))};return t.jsxs("div",{className:"pos-rel d-flx w-8",children:[t.jsx(T,{checked:((b=r.actions)==null?void 0:b.update)||!1,onChange:d=>p(d,"update"),className:"wdt-200 mt-4 mr-2",value:"user_share",title:l("Update Sendinblue","bit-integrations"),subTitle:l("Update Responses with Sendinblue existing email?","bit-integrations")}),t.jsx(T,{checked:((x=r.actions)==null?void 0:x.double_optin)||!1,onChange:d=>p(d,"double_optin"),className:"wdt-200 mt-4 mr-2",value:"double_optin",title:l("Double Opt-in","bit-integrations"),subTitle:l("Double Opt-In for confirm subscription.","bit-integrations")})]})}function E({i:r,formFields:c,field:e,sendinBlueConf:s,setSendinBlueConf:p}){var g,u,v,i;const b=e.required,x=((g=s==null?void 0:s.default)==null?void 0:g.fields)&&Object.values((u=s==null?void 0:s.default)==null?void 0:u.fields).filter(a=>!a.required),d=U(O),{isPro:j}=d,m=a=>{const o=h({},s);o.field_map.splice(a,0,{}),p(o)},N=a=>{const o=h({},s);o.field_map.length>1&&o.field_map.splice(a,1),p(o)},n=(a,o)=>{const w=h({},s);w.field_map[o][a.target.name]=a.target.value,a.target.value==="custom"&&(w.field_map[o].customValue=""),p(w)};return t.jsxs("div",{className:b?"mt-2 mr-1 flx w-9":"flx mt-2 mb-2 btcbi-field-map",children:[t.jsxs("select",{className:"btcd-paper-inp mr-2",name:"formField",value:e.formField||"",onChange:a=>n(a,r),children:[t.jsx("option",{value:"",children:l("Select Field","bit-integrations")}),t.jsx("optgroup",{label:"Form Fields",children:c.map(a=>a.type!=="file"&&t.jsx("option",{value:a.name,children:a.label},`ff-zhcrm-${a.name}`))}),t.jsx("option",{value:"custom",children:l("Custom...","bit-integrations")}),t.jsx("optgroup",{label:`General Smart Codes ${j?"":"(PRO)"}`,children:j&&((v=D)==null?void 0:v.map(a=>t.jsx("option",{value:a.name,children:a.label},`ff-rm-${a.name}`)))})]}),e.formField==="custom"&&t.jsx(z,{onChange:a=>V(a,r,s,p),label:l("Custom Value","bit-integrations"),className:"mr-2",type:"text",value:e.customValue,placeholder:l("Custom Value","bit-integrations"),formFields:c}),t.jsxs("select",{className:"btcd-paper-inp",name:"sendinBlueField",value:e.sendinBlueField,onChange:a=>n(a,r),disabled:b,children:[t.jsx("option",{value:"",children:l("Select Field","bit-integrations")}),b?((i=s==null?void 0:s.default)==null?void 0:i.fields)&&Object.values(s.default.fields).map(a=>t.jsx("option",{value:a.fieldId,children:a.fieldName},`${a.fieldId}-1`)):x&&x.map(a=>t.jsx("option",{value:a.fieldId,children:a.fieldName},`${a.fieldId}-1`))]}),!b&&t.jsxs(t.Fragment,{children:[t.jsx("button",{onClick:()=>m(r),className:"icn-btn sh-sm ml-2",type:"button",children:"+"}),t.jsx("button",{onClick:()=>N(r),className:"icn-btn sh-sm ml-2",type:"button","aria-label":"btn",children:t.jsx(q,{})})]})]})}function K({formID:r,formFields:c,sendinBlueConf:e,setSendinBlueConf:s,isLoading:p,setIsLoading:b,setSnackbar:x,error:d,setError:j}){var g,u,v;const m=i=>{const a=h({},e);i?(a.lists=i?i.split(","):[],!a.default.fields&&f(a,s,b,x)):delete a.lists,s(h({},a))},N=()=>{var i;return(i=e==null?void 0:e.default)!=null&&i.sblueList?Object.values(e.default.sblueList).map(a=>({label:a.name,value:String(a.id)})):[]},n=i=>{const a=h({},e),o=h({},d);o[i.target.name]="",a[i.target.name]=i.target.value,j(o),s(h({},a))};return t.jsxs(t.Fragment,{children:[t.jsx("br",{}),t.jsxs("div",{className:"flx",children:[t.jsx("b",{className:"wdt-200 d-in-b",children:l("List: ","bit-integrations")}),t.jsx(M,{defaultValue:e==null?void 0:e.lists,className:"btcd-paper-drpdwn w-5",options:N(),onChange:i=>m(i)}),t.jsx("button",{onClick:()=>S(e,s,b,x),className:"icn-btn sh-sm ml-2 mr-2 tooltip",style:{"--tooltip-txt":`'${l("Refresh Sendinblue Lists","bit-integrations")}'`},type:"button",disabled:p,children:"↻"})]}),t.jsx("br",{}),t.jsx("br",{}),p&&t.jsx($,{style:{display:"flex",justifyContent:"center",alignItems:"center",height:100,transform:"scale(0.7)"}}),((g=e==null?void 0:e.lists)==null?void 0:g.length)!==0&&t.jsxs(t.Fragment,{children:[t.jsx("div",{className:"mt-4",children:t.jsx("b",{className:"wdt-100",children:l("Map Fields","bit-integrations")})}),t.jsx("div",{className:"btcd-hr mt-1"}),t.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[t.jsx("div",{className:"txt-dp",children:t.jsx("b",{children:l("Form Fields","bit-integrations")})}),t.jsx("div",{className:"txt-dp",children:t.jsx("b",{children:l("Sendinblue Fields","bit-integrations")})})]}),e.field_map.map((i,a)=>t.jsx(E,{i:a,field:i,sendinBlueConf:e,formFields:c,setSendinBlueConf:s},`sendinblue-m-${a+9}`)),t.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:t.jsx("button",{onClick:()=>L(e.field_map.length,e,s),className:"icn-btn sh-sm",type:"button",children:"+"})}),t.jsx("br",{}),t.jsx("br",{}),((u=e.actions)==null?void 0:u.double_optin)&&t.jsxs(t.Fragment,{children:[t.jsxs("div",{className:"flx",children:[t.jsx("b",{className:"wdt-150 d-in-b",children:l("Template: ","bit-integrations")}),t.jsxs("div",{className:"w-5",children:[t.jsxs("select",{onChange:n,name:"templateId",value:e==null?void 0:e.templateId,className:"btcd-paper-inp",children:[t.jsx("option",{value:"",children:l("Select Template","bit-integrations")}),((v=e==null?void 0:e.default)==null?void 0:v.sblueTemplates)&&Object.values(e.default.sblueTemplates).map(i=>t.jsx("option",{value:i.id||e.templateId,children:i.name},`sendinblue-${i.id+2}`))]}),t.jsx("div",{style:{color:"red",fontSize:"15px",marginTop:"3px"},children:d.templateId})]}),t.jsx("button",{onClick:()=>y(e,s,x),className:"icn-btn sh-sm ml-2 mr-2 tooltip",style:{"--tooltip-txt":`'${l("Refresh Sendinblue Templates","bit-integrations")}'`},type:"button",disabled:p,children:"↻"})]}),t.jsxs("small",{className:"d-blk mt-5 ml-30",children:[l("To create and activate double optin email template , Please follow","bit-integrations")," ",t.jsx("a",{className:"btcd-link",href:"https://help.sendinblue.com/hc/en-us/articles/211244629#h_01EWZJQBND3M8XTA37V018WX62",target:"_blank",rel:"noreferrer",children:l("How to create and active doi template","bit-integrations")})]}),t.jsx("br",{}),t.jsx("br",{}),t.jsxs("div",{className:"flx",children:[t.jsx("b",{className:"wdt-150 d-in-b",children:l("RedirectionUrl:","bit-integrations")}),t.jsxs("div",{className:"w-5",children:[t.jsx("input",{type:"url",className:"btcd-paper-inp",placeholder:"Redirection URL",onChange:n,value:(e==null?void 0:e.redirectionUrl)||"",name:"redirectionUrl"}),t.jsx("div",{style:{color:"red",fontSize:"15px",marginTop:"3px"},children:d.redirectionUrl})]})]}),t.jsx("br",{}),t.jsx("br",{})]}),t.jsx("div",{className:"mt-4",children:t.jsx("b",{className:"wdt-100",children:l("Actions","bit-integrations")})}),t.jsx("div",{className:"btcd-hr mt-1"}),t.jsx(A,{sendinBlueConf:e,setSendinBlueConf:s,setIsLoading:b,setSnackbar:x})]})]})}export{K as S};
