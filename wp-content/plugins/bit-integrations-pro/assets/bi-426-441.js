import{r,j as t}from"./main.js";import{e as j,f as I,c as S,_ as p}from"./bi-490-178.js";import{B as w}from"./bi-475-763.js";import{S as D}from"./bi-224-925.js";import{g as y,s as _}from"./bi-266-720.js";import{I as Z}from"./bi-811-721.js";import C from"./bi-112-327.js";import{h as N,c as v,b as z}from"./bi-568-795.js";import{Z as F}from"./bi-815-796.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function K({formFields:l,setFlow:h,flow:g,allIntegURL:u}){const x=j(),{formID:i}=I(),[f,a]=r.useState(!1),[n,c]=r.useState(1),[k,s]=r.useState({show:!1}),[e,m]=r.useState({name:"Zoho Desk",type:"Zoho Desk",clientId:"",clientSecret:"",orgId:"",department:"",field_map:[{formField:"",zohoFormField:""}],actions:{}});r.useEffect(()=>{window.opener&&y("zohoDesk")},[]);const b=o=>{var d;if(o===3){if(!v(e)){s({show:!0,msg:p("Please map mandatory fields","bit-integrations")});return}if(!((d=e.actions)!=null&&d.ticket_owner)){s({show:!0,msg:p("Please select a ticket owner","bit-integrations")});return}e.department!==""&&e.table!==""&&e.field_map.length>0&&c(o)}else c(o),o===2&&!e.department&&z(i,e,m,a,s)};return t.jsxs("div",{children:[t.jsx(S,{snack:k,setSnackbar:s}),t.jsx("div",{className:"txt-center mt-2",children:t.jsx(D,{step:3,active:n})}),t.jsx(C,{formID:i,deskConf:e,setDeskConf:m,step:n,setstep:c,isLoading:f,setIsLoading:a,setSnackbar:s}),t.jsxs("div",{className:"btcd-stp-page",style:{width:n===2&&900,height:n===2&&"auto"},children:[t.jsx(F,{formID:i,formFields:l,handleInput:o=>N(o,e,m,i,a,s),deskConf:e,setDeskConf:m,isLoading:f,setIsLoading:a,setSnackbar:s}),t.jsxs("button",{onClick:()=>b(3),disabled:e.department===""||e.table===""||e.field_map.length<1,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[p("Next","bit-integrations"),t.jsx(w,{className:"ml-1 rev-icn"})]})]}),t.jsx(Z,{step:n,saveConfig:()=>_({flow:g,setFlow:h,allIntegURL:u,history:x,conf:e,setIsLoading:a,setSnackbar:s})})]})}export{K as default};
