import{r as c,j as t}from"./main.js";import{e as j,f as b,R as l,$ as I,g as _,h as w,i as y,c as C,_ as r}from"./bi-490-178.js";import{E as F,a as k}from"./bi-389-719.js";import{c as f,s as E}from"./bi-266-720.js";import{I as M}from"./bi-811-721.js";import{h as p,c as v}from"./bi-500-788.js";import{M as N}from"./bi-202-789.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function B({allIntegURL:g}){const u=j();b();const[a,S]=l(I),[e,i]=l(_),[m,o]=c.useState(!1),[x,s]=c.useState({show:!1}),d=w(y),h=()=>{if(!v(e)){s({show:!0,msg:r("Please map mandatory fields","bit-integrations")});return}E({flow:a,allIntegURL:g,conf:e,history:u,edit:1,setIsLoading:o,setSnackbar:s})};return t.jsxs("div",{style:{width:900},children:[t.jsx(C,{snack:x,setSnackbar:s}),t.jsxs("div",{className:"flx mt-3",children:[t.jsx("b",{className:"wdt-200 d-in-b",children:r("Integration Name:","bit-integrations")}),t.jsx("input",{className:"btcd-paper-inp w-5",onChange:n=>p(n,e,i),name:"name",value:e.name,type:"text",placeholder:r("Integration Name...","bit-integrations")})]}),t.jsx("br",{}),!f(a.triggered_entity)&&t.jsx(F,{setSnackbar:s}),f(a.triggered_entity)&&t.jsx(k,{setSnackbar:s}),t.jsx(N,{formID:a.triggered_entity_id,formFields:d,handleInput:n=>p(n,e,i,o,s),mauticConf:e,setMauticConf:i,isLoading:m,setIsLoading:o,setSnackbar:s}),t.jsx(M,{edit:!0,saveConfig:h,disabled:e.field_map.length<1,isLoading:m,dataConf:e,setDataConf:i,formFields:d}),t.jsx("br",{})]})}export{B as default};
