import{r as n,j as t}from"./main.js";import{e as b,f as I,R as l,$ as _,g as S,h as w,i as C,c as F,_ as r}from"./bi-490-178.js";import{E as k,a as E}from"./bi-389-719.js";import{c,s as v}from"./bi-266-720.js";import{I as N}from"./bi-811-721.js";import{h as f,c as g}from"./bi-168-868.js";import{S as L}from"./bi-507-869.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";function G({allIntegURL:p}){const x=b();I();const[a,R]=l(_),[e,i]=l(S),[h,$]=n.useState(!1),[u,m]=n.useState({auth:!1}),[j,s]=n.useState({show:!1}),d=w(C),y=()=>{if(!g(e)){s({show:!0,msg:r("Please map mandatory fields","bit-integrations")});return}v({flow:a,allIntegURL:p,conf:e,history:x,edit:1,setLoading:m,setSnackbar:s})};return t.jsxs("div",{style:{width:900},children:[t.jsx(F,{snack:j,setSnackbar:s}),t.jsxs("div",{className:"flx mt-3",children:[t.jsx("b",{className:"wdt-200 d-in-b",children:r("Integration Name:","bit-integrations")}),t.jsx("input",{className:"btcd-paper-inp w-5",onChange:o=>f(o,e,i),name:"name",value:e.name,type:"text",placeholder:r("Integration Name...","bit-integrations")})]}),t.jsx("br",{}),!c(a.triggered_entity)&&t.jsx(k,{setSnackbar:s}),c(a.triggered_entity)&&t.jsx(E,{setSnackbar:s}),t.jsx(L,{formID:a.triggered_entity_id,formFields:d,handleInput:o=>f(o,e,i),smailyConf:e,setSmailyConf:i,loading:u,setLoading:m,setSnackbar:s}),t.jsx(N,{edit:!0,saveConfig:y,disabled:!g(e),isLoading:h,dataConf:e,setDataConf:i,formFields:d}),t.jsx("br",{})]})}export{G as default};
