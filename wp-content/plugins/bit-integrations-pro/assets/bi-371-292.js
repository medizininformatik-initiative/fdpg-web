import{r as m,j as t}from"./main.js";import{e as u,f as C,R as c,g as b,$ as I,h as _,i as w,c as y,j as d}from"./bi-490-178.js";import{E,a as k}from"./bi-389-719.js";import{c as f,s as v}from"./bi-266-720.js";import{I as F}from"./bi-811-721.js";import{h as M}from"./bi-497-916.js";import{C as N}from"./bi-220-917.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";function q({allIntegURL:p}){const l=u(),{formID:g}=C(),[s,a]=c(b),[o,x]=c(I),n=_(w),[i,r]=m.useState(!1),[h,e]=m.useState({show:!1});return t.jsxs("div",{style:{width:900},children:[t.jsx(y,{snack:h,setSnackbar:e}),t.jsxs("div",{className:"flx mt-3",children:[t.jsx("b",{className:"wdt-200 d-in-b",children:d("Integration Name:","bit-integrations")}),t.jsx("input",{className:"btcd-paper-inp w-5",onChange:j=>M(j,s,a),name:"name",value:s.name,type:"text",placeholder:d("Integration Name...","bit-integrations")})]}),t.jsx("br",{}),!f(o.triggered_entity)&&t.jsx(E,{setSnackbar:e}),f(o.triggered_entity)&&t.jsx(k,{setSnackbar:e}),t.jsx(N,{formID:g,formFields:n,campaignMonitorConf:s,setCampaignMonitorConf:a,isLoading:i,setIsLoading:r,setSnackbar:e}),t.jsx(F,{edit:!0,saveConfig:()=>v({flow:o,setFlow:x,allIntegURL:p,history:l,conf:s,edit:1,setIsLoading:r,setSnackbar:e}),disabled:s.field_map.length<1,isLoading:i,dataConf:s,setDataConf:a,formFields:n}),t.jsx("br",{})]})}export{q as default};
