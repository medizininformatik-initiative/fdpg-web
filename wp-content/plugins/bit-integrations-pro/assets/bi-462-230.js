import{r as c,j as t}from"./main.js";import{e as j,f as b,R as d,g as I,$ as _,h as w,i as C,c as k,_ as l}from"./bi-490-178.js";import{E as y,a as E}from"./bi-389-719.js";import{c as f,s as F}from"./bi-266-720.js";import{I as v}from"./bi-811-721.js";import{Z as N}from"./bi-384-802.js";import{h as p,c as S}from"./bi-521-803.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function q({allIntegURL:x}){const g=j(),{formID:h}=b(),[s,o]=d(I),[a,R]=d(_),m=w(C),[n,i]=c.useState(!1),[u,e]=c.useState({show:!1});return t.jsxs("div",{style:{width:900},children:[t.jsx(k,{snack:u,setSnackbar:e}),t.jsxs("div",{className:"flx mt-3",children:[t.jsx("b",{className:"wdt-200 d-in-b",children:l("Integration Name:","bit-integrations")}),t.jsx("input",{className:"btcd-paper-inp w-5",onChange:r=>p(r,s,o),name:"name",value:s.name,type:"text",placeholder:l("Integration Name...","bit-integrations")})]}),t.jsx("br",{}),t.jsx("br",{}),!f(a.triggered_entity)&&t.jsx(y,{setSnackbar:e}),f(a.triggered_entity)&&t.jsx(E,{setSnackbar:e}),t.jsx(N,{formID:h,formFields:m,handleInput:r=>p(r,s,o,i,e),zoomConf:s,setZoomConf:o,isLoading:n,setIsLoading:i,setSnackbar:e}),t.jsx(v,{edit:!0,saveConfig:()=>F({flow:a,allIntegURL:x,conf:s,history:g,edit:1,setIsLoading:i,setSnackbar:e}),disabled:s.field_map.length<2||n||!s.id||!S(s)||s.selectedActions==null,isLoading:n,dataConf:s,setDataConf:o,formFields:m}),t.jsx("br",{})]})}export{q as default};
