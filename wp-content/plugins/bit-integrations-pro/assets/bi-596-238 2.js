import{r as n,j as t}from"./main.js";import{e as I,R as c,$ as _,g as w,h as y,i as C,c as F,_ as r}from"./bi-490-178.js";import{E as k,a as E}from"./bi-389-719.js";import{c as f,s as v}from"./bi-266-720.js";import{I as S}from"./bi-811-721.js";import{h as p,c as N}from"./bi-544-818.js";import{V as L}from"./bi-824-819.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function B({allIntegURL:g}){const u=I(),[a,R]=c(_),[e,o]=c(w),[d,m]=n.useState(!1),[h,x]=n.useState({list:!1,field:!1,auth:!1}),[b,s]=n.useState({show:!1}),l=y(C),j=()=>{if(!N(e)){s({show:!0,msg:r("Please map mandatory fields","bit-integrations")});return}v({flow:a,allIntegURL:g,conf:e,history:u,edit:1,setIsLoading:m,setSnackbar:s})};return t.jsxs("div",{style:{width:900},children:[t.jsx(F,{snack:b,setSnackbar:s}),t.jsxs("div",{className:"flx mt-3",children:[t.jsx("b",{className:"wdt-200 d-in-b",children:r("Integration Name:","bit-integrations")}),t.jsx("input",{className:"btcd-paper-inp w-5",onChange:i=>p(i,e,o),name:"name",value:e.name,type:"text",placeholder:r("Integration Name...","bit-integrations")})]}),t.jsx("br",{}),!f(a.triggered_entity)&&t.jsx(k,{setSnackbar:s}),f(a.triggered_entity)&&t.jsx(E,{setSnackbar:s}),t.jsx(L,{formID:a.triggered_entity_id,formFields:l,handleInput:i=>p(i,e,o),vboutConf:e,setVboutConf:o,isLoading:d,setIsLoading:m,loading:h,setLoading:x,setSnackbar:s}),t.jsx(S,{edit:!0,saveConfig:j,disabled:e.field_map.length<1,isLoading:d,dataConf:e,setDataConf:o,formFields:l}),t.jsx("br",{})]})}export{B as default};
