var T=Object.defineProperty;var N=Object.getOwnPropertySymbols;var A=Object.prototype.hasOwnProperty,S=Object.prototype.propertyIsEnumerable;var F=(e,l,s)=>l in e?T(e,l,{enumerable:!0,configurable:!0,writable:!0,value:s}):e[l]=s,h=(e,l)=>{for(var s in l||(l={}))A.call(l,s)&&F(e,s,l[s]);if(N)for(var s of N(l))S.call(l,s)&&F(e,s,l[s]);return e};import{r as $,j as t,L as j}from"./main.js";import{a7 as q,_ as d,d as I,a8 as P,h as _,B as M}from"./bi-490-178.js";import{g as v,a as V,b as w}from"./bi-664-914.js";import{S as O}from"./bi-652-725.js";import{M as B}from"./bi-369-724.js";const R=(e,l,s)=>{const a=h({},l);a.field_map.splice(e,0,{}),s(h({},a))},E=(e,l,s)=>{const a=h({},l);a.field_map.length>1&&a.field_map.splice(e,1),s(h({},a))},y=(e,l,s,a)=>{const i=h({},s);i.field_map[l][e.target.name]=e.target.value,e.target.value==="custom"&&(i.field_map[l].customValue=""),e.target.value==="customFieldKey"&&(i.field_map[l].customFieldKey=""),console.log(i),a(h({},i))},K=(e,l,s,a,i)=>{const n=h({},s);n.field_map[l][i]=e.target.value,a(h({},n))};function G({lionDeskConf:e,setLionDeskConf:l,loading:s,setLoading:a}){var u,x;const[i,n]=$.useState({show:!1,action:()=>{}}),p=(r,m)=>{var o;const c=h({},e);m==="tag"&&((o=r.target)!=null&&o.checked?(v(e,l,a),c.actions.tag=!0):(n({show:!1}),delete c.actions.tag)),n({show:m}),l(h({},c))},b=()=>{n({show:!1})},g=(r,m)=>{const c=h({},e);c[m]=r,l(h({},c))};return t.jsxs("div",{className:"pos-rel d-flx flx-wrp",children:[e.actionName==="contact"&&t.jsx(q,{checked:((u=e==null?void 0:e.selectedTag)==null?void 0:u.length)||!1,onChange:r=>p(r,"tag"),className:"wdt-200 mt-4 mr-2",value:"tag",title:d("Add Tags","bit - integrations"),subTitle:d("Add tags")}),t.jsxs(I,{className:"custom-conf-mdl",mainMdlCls:"o-v",btnClass:"blue",btnTxt:d("Ok","bit-integrations"),show:i.show==="tag",close:b,action:b,title:d("Tags","bit-integrations"),children:[t.jsx("div",{className:"btcd-hr mt-2 mb-2"}),t.jsx("div",{className:"mt-2",children:d("Select Tag","bit-integrations")}),s.tags?t.jsx(j,{style:{display:"flex",justifyContent:"center",alignItems:"center",height:45,transform:"scale(0.5)"}}):t.jsxs("div",{className:"flx flx-between mt-2",children:[t.jsx(P,{options:(x=e==null?void 0:e.tags)==null?void 0:x.map(r=>({label:r.tag,value:r.tag})),className:"msl-wrp-options",defaultValue:e==null?void 0:e.selectedTag,onChange:r=>g(r,"selectedTag")}),t.jsx("button",{onClick:()=>v(e,l,a),className:"icn-btn sh-sm ml-2 mr-2 tooltip",style:{"--tooltip-txt":`${d("Refresh Tags","bit-integrations")}'`},type:"button",children:"↻"})]})]})]})}function H({field:e,index:l,conf:s,setConf:a,fieldValue:i,fieldLabel:n,className:p}){return t.jsx(B,{onChange:b=>K(b,l,s,a,i),label:d(n,"bit-integrations"),className:p,type:"text",value:e[i],placeholder:d(n,"bit-integrations")})}function z({i:e,formFields:l,field:s,lionDeskConf:a,setLionDeskConf:i}){var r,m;let n=[];a.actionName==="campaign"?n=a==null?void 0:a.campaignFields:a.actionName==="contact"&&(n=a==null?void 0:a.contactFields);const p=n.filter(c=>c.required===!0)||[],g=[...n.filter(c=>c.required===!1)||[],...(a==null?void 0:a.customFields)||[]];((r=a==null?void 0:a.field_map)==null?void 0:r.length)===1&&s.lionDeskFormField===""&&i(c=>(c.field_map=V(c),c));const u=_(M),{isPro:x}=u;return t.jsx("div",{className:"flx mt-2 mb-2 btcbi-field-map",children:t.jsxs("div",{className:"pos-rel flx",children:[t.jsxs("div",{className:"flx integ-fld-wrp",children:[t.jsxs("select",{className:"btcd-paper-inp mr-2",name:"formField",value:s.formField||"",onChange:c=>y(c,e,a,i),children:[t.jsx("option",{value:"",children:d("Select Field","bit-integrations")}),t.jsx("optgroup",{label:"Form Fields",children:l==null?void 0:l.map(c=>t.jsx("option",{value:c.name,children:c.label},`ff-rm-${c.name}`))}),t.jsx("option",{value:"custom",children:d("Custom...","bit-integrations")}),t.jsx("optgroup",{label:`General Smart Codes ${x?"":"(PRO)"}`,children:x&&((m=O)==null?void 0:m.map(c=>t.jsx("option",{value:c.name,children:c.label},`ff-rm-${c.name}`)))})]}),s.formField==="custom"&&t.jsx(H,{field:s,index:e,conf:a,setConf:i,fieldValue:"customValue",fieldLabel:"Custom Value",className:"mr-2"}),t.jsxs("select",{className:"btcd-paper-inp",disabled:e<p.length,name:"lionDeskFormField",value:e<p.length?p[e].key||"":s.lionDeskFormField||"",onChange:c=>y(c,e,a,i),children:[t.jsx("option",{value:"",children:d("Select Field","bit-integrations")}),e<p.length?t.jsx("option",{value:p[e].key,children:p[e].label},p[e].key):g.map(({key:c,label:o})=>t.jsx("option",{value:c,children:o},c))]})]}),e>=p.length&&t.jsxs(t.Fragment,{children:[t.jsx("button",{onClick:()=>R(e,a,i),className:"icn-btn sh-sm ml-2 mr-1",type:"button",children:"+"}),t.jsx("button",{onClick:()=>E(e,a,i),className:"icn-btn sh-sm ml-1",type:"button","aria-label":"btn",children:t.jsx("span",{className:"btcd-icn icn-trash-2"})})]})]})})}function Z({formFields:e,handleInput:l,lionDeskConf:s,setLionDeskConf:a,loading:i,setLoading:n,isLoading:p,setIsLoading:b,setSnackbar:g}){const u=_(M),x=r=>{const m=h({},s),{name:c}=r.target;m.field_map=[{formField:"",lionDeskFormField:""}],r.target.value!==""?(m[c]=r.target.value,r.target.value==="contact"&&w(m,a,b,u)):delete m[c],a(h({},m))};return t.jsxs(t.Fragment,{children:[t.jsx("br",{}),t.jsx("b",{className:"wdt-200 d-in-b",children:d("Select Action:","bit-integrations")}),t.jsxs("select",{onChange:x,name:"actionName",value:s.actionName,className:"btcd-paper-inp w-5",children:[t.jsx("option",{value:"",children:d("Select an action","bit-integrations")}),t.jsx("option",{value:"contact","data-action_name":"contact",children:d("Create Contact","bit-integrations")}),t.jsx("option",{value:"campaign","data-action_name":"campaign",children:d("Create Campaign","bit-integrations")})]}),(i.CRMPipelines||i.CRMOwners||i.CRMContacts)&&t.jsx(j,{style:{display:"flex",justifyContent:"center",alignItems:"center",height:100,transform:"scale(0.7)"}}),p&&t.jsx(j,{style:{display:"flex",justifyContent:"center",alignItems:"center",height:100,transform:"scale(0.7)"}}),s.actionName&&!p&&t.jsxs("div",{children:[t.jsx("br",{}),t.jsxs("div",{className:"mt-5",children:[t.jsx("b",{className:"wdt-100",children:d("Field Map","bit-integrations")}),s.actionName==="contact"&&t.jsx("button",{onClick:()=>w(s,a,b,u),className:"icn-btn sh-sm ml-2 mr-2 tooltip",style:{"--tooltip-txt":`'${d("Refresh Fields","bit-integrations")}'`},type:"button",disabled:i.CRMPipelines,children:"↻"})]}),t.jsx("br",{}),t.jsx("div",{className:"btcd-hr mt-1"}),t.jsxs("div",{className:"flx flx-around mt-2 mb-2 btcbi-field-map-label",children:[t.jsx("div",{className:"txt-dp",children:t.jsx("b",{children:d("Form Fields","bit-integrations")})}),t.jsx("div",{className:"txt-dp",children:t.jsx("b",{children:d("LionDesk Fields","bit-integrations")})})]}),s==null?void 0:s.field_map.map((r,m)=>t.jsx(z,{i:m,field:r,lionDeskConf:s,formFields:e,setLionDeskConf:a,setSnackbar:g},`rp-m-${m+9}`)),t.jsx("div",{className:"txt-center btcbi-field-map-button mt-2",children:t.jsx("button",{onClick:()=>R(s.field_map.length,s,a),className:"icn-btn sh-sm",type:"button",children:"+"})}),t.jsx("br",{}),t.jsx("br",{}),t.jsx("div",{className:"mt-4",children:t.jsx("b",{className:"wdt-100",children:d("Actions","bit-integrations")})}),t.jsx("div",{className:"btcd-hr mt-1"}),t.jsx(G,{lionDeskConf:s,setLionDeskConf:a,formFields:e,loading:i,setLoading:n})]})]})}export{Z as L};
