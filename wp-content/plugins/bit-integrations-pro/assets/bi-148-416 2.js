import{r as e,j as t}from"./main.js";import{e as x,f as g,c as S}from"./bi-490-178.js";import{S as j}from"./bi-224-925.js";import{e as b}from"./bi-266-720.js";import k from"./bi-516-314.js";import{W as v}from"./bi-918-762.js";import"./bi-805-737.js";import"./bi-475-763.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-568-764.js";function D({formFields:r,setFlow:n,flow:i,allIntegURL:c}){const m=x(),{formID:p}=g(),[s,d]=e.useState(1),[h,o]=e.useState({show:!1}),[f,l]=e.useState(!1),[a,u]=e.useState({name:"N8n Web Hooks",type:"N8n",method:"POST",url:"",apiConsole:"https://connect.n8n.com/dashboard"});return t.jsxs("div",{children:[t.jsx(S,{snack:h,setSnackbar:o}),t.jsx("div",{className:"txt-center mt-2",children:t.jsx(j,{step:2,active:s})}),t.jsx("div",{className:"btcd-stp-page",style:{width:s===1&&1100,height:s===1&&"auto"},children:t.jsx(k,{formID:p,formFields:r,webHooks:a,setWebHooks:u,step:s,setStep:d,setSnackbar:o,create:!0})}),t.jsx("div",{className:"btcd-stp-page",style:{width:s===2&&"100%",height:s===2&&"auto"},children:t.jsx(v,{step:s,saveConfig:()=>b(i,n,c,a,m,"","",l),isLoading:f})})]})}export{D as default};
