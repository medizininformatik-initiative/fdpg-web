import{r as n,j as s}from"./main.js";import{e as g,f as I,R as r,g as _,$ as w,h as k,i as y,c as E,_ as m}from"./bi-490-178.js";import{E as F,a as N}from"./bi-389-719.js";import{c,s as v}from"./bi-266-720.js";import{I as S}from"./bi-811-721.js";import{h as G,c as R}from"./bi-68-777.js";import{G as $}from"./bi-291-778.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-699-739.js";import"./bi-826-717.js";function C({allIntegURL:d}){const p=g(),{id:l}=I(),[t,a]=r(_),[i,x]=r(w),o=k(y),[f,h]=n.useState(!1),[j,e]=n.useState({show:!1}),u=()=>{v({flow:i,setFlow:x,allIntegURL:d,conf:t,history:p,edit:1,setIsLoading:h,setSnackbar:e})};return s.jsxs("div",{style:{width:900},className:"p-2",children:[s.jsx(E,{snack:j,setSnackbar:e}),s.jsxs("div",{className:"flx mt-3",children:[s.jsx("b",{className:"wdt-200 d-in-b",children:m("Integration Name:","bit-integrations")}),s.jsx("input",{className:"btcd-paper-inp w-5",onChange:b=>G(b,t,a),name:"name",value:t.name,type:"text",placeholder:m("Integration Name...","bit-integrations")})]}),s.jsx("br",{}),s.jsx("br",{}),!c(i.triggered_entity)&&s.jsx(F,{setSnackbar:e}),c(i.triggered_entity)&&s.jsx(N,{setSnackbar:e}),s.jsx($,{flowID:l,formFields:o,googleCalendarConf:t,setGoogleCalendarConf:a}),s.jsx(S,{edit:!0,saveConfig:u,disabled:!(t!=null&&t.calendarId)||!(t!=null&&t.timeZone)||!R(t==null?void 0:t.field_map),isLoading:f,dataConf:t,setDataConf:a,formFields:o}),s.jsx("br",{})]})}export{C as default};
