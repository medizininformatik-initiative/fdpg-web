import{r as l,j as t}from"./main.js";import{e as b,f as I,R as m,g as _,$ as w,h as C,i as k,c as y,_ as c}from"./bi-490-178.js";import{E,a as F}from"./bi-389-719.js";import{c as d,s as M}from"./bi-266-720.js";import{I as P}from"./bi-811-721.js";import{h as v,M as N,c as S}from"./bi-433-756.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function B({allIntegURL:f}){const p=b(),{formID:g}=I(),[e,a]=m(_),[o,x]=m(w),i=C(k),[n,r]=l.useState(!1),[h,s]=l.useState({show:!1}),u=()=>{if(!S(e)){s({show:!0,msg:"Please map all required fields to continue."});return}M({flow:o,setFlow:x,allIntegURL:f,conf:e,history:p,edit:1,setIsLoading:r,setSnackbar:s})};return t.jsxs("div",{style:{width:900},children:[t.jsx(y,{snack:h,setSnackbar:s}),t.jsxs("div",{className:"flx mt-3",children:[t.jsx("b",{className:"wdt-200 d-in-b",children:c("Integration Name:","bit-integrations")}),t.jsx("input",{className:"btcd-paper-inp w-6",onChange:j=>v(j,e,a),name:"name",value:e.name,type:"text",placeholder:c("Integration Name...","bit-integrations")})]}),t.jsx("br",{}),t.jsx("br",{}),!d(o.triggered_entity)&&t.jsx(E,{setSnackbar:s}),d(o.triggered_entity)&&t.jsx(F,{setSnackbar:s}),t.jsx(N,{formID:g,formFields:i,mailPoetConf:e,setMailPoetConf:a,isLoading:n,step:2,setIsLoading:r,setSnackbar:s,edit:e.lists}),t.jsx(P,{edit:!0,saveConfig:u,disabled:e.lists===""||e.field_map.length<1,isLoading:n,dataConf:e,setDataConf:a,formFields:i}),t.jsx("br",{})]})}export{B as default};
