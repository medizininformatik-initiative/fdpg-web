import{r as d,j as e}from"./main.js";import{e as j,f as b,R as m,g as I,$ as S,h as _,i as w,c as C,j as c}from"./bi-490-178.js";import{I as y}from"./bi-811-721.js";import{c as l,s as E}from"./bi-266-720.js";import{h as k}from"./bi-226-880.js";import{S as v}from"./bi-595-881.js";import{E as F,a as N}from"./bi-389-719.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";function z({allIntegURL:f}){const p=j(),{id:P,formID:g}=b(),[s,a]=m(I),[o,x]=m(S),n=_(w),[i,r]=d.useState(!1),[u,t]=d.useState({show:!1});return e.jsxs("div",{style:{width:900},children:[e.jsx(C,{snack:u,setSnackbar:t}),e.jsxs("div",{className:"flx mt-3",children:[e.jsx("b",{className:"wdt-200 d-in-b",children:c("Integration Name:","bit-integrations")}),e.jsx("input",{className:"btcd-paper-inp w-5",onChange:h=>k(h,s,a),name:"name",value:s.name,type:"text",placeholder:c("Integration Name...","bit-integrations")})]}),e.jsx("br",{}),!l(o.triggered_entity)&&e.jsx(F,{setSnackbar:t}),l(o.triggered_entity)&&e.jsx(N,{setSnackbar:t}),e.jsx(v,{formID:g,formFields:n,sendPulseConf:s,setSendPulseConf:a,isLoading:i,setIsLoading:r,setSnackbar:t}),e.jsx(y,{edit:!0,saveConfig:()=>E({flow:o,setFlow:x,allIntegURL:f,history:p,conf:s,edit:1,setIsLoading:r,setSnackbar:t}),disabled:s.field_map.length<1,isLoading:i,dataConf:s,setDataConf:a,formFields:n}),e.jsx("br",{})]})}export{z as default};
