import{r,j as t}from"./main.js";import{e as b,f as I,R as c,g as v,$ as w,h as _,i as y,c as E,_ as m}from"./bi-490-178.js";import{E as k,a as C}from"./bi-389-719.js";import{c as d,s as D}from"./bi-266-720.js";import{I as F}from"./bi-811-721.js";import{h as N}from"./bi-162-731.js";import{O as S}from"./bi-899-732.js";import"./bi-369-724.js";import"./bi-422-718.js";function T({allIntegURL:l}){const f=b(),{id:x}=I(),[e,a]=c(v),[o,p]=c(w),g=_(y),[n,i]=r.useState(!1),[h,s]=r.useState({show:!1}),j=()=>{D({flow:o,setFlow:p,allIntegURL:l,conf:e,history:f,edit:1,setIsLoading:i,setSnackbar:s})};return t.jsxs("div",{style:{width:900},children:[t.jsx(E,{snack:h,setSnackbar:s}),t.jsxs("div",{className:"flx mt-3",children:[t.jsx("b",{className:"wdt-200 d-in-b",children:m("Integration Name:","bit-integrations")}),t.jsx("input",{className:"btcd-paper-inp w-5",onChange:u=>N(u,e,a),name:"name",value:e.name,type:"text",placeholder:m("Integration Name...","bit-integrations")})]}),t.jsx("br",{}),t.jsx("br",{}),!d(o.triggered_entity)&&t.jsx(k,{setSnackbar:s}),d(o.triggered_entity)&&t.jsx(C,{setSnackbar:s}),t.jsx(S,{flowID:x,formFields:g,oneDriveConf:e,setOneDriveConf:a,isLoading:n,setIsLoading:i,setSnackbar:s}),t.jsx(F,{edit:!0,saveConfig:j,disabled:!e.actions.attachments||!e.folder,isLoading:n}),t.jsx("br",{})]})}export{T as default};
