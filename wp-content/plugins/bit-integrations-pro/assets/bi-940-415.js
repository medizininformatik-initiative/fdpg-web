import{r as e,j as t}from"./main.js";import{e as u,f as x,c as g}from"./bi-490-178.js";import{S as y}from"./bi-224-925.js";import{e as S}from"./bi-266-720.js";import j from"./bi-516-314.js";import{W as k}from"./bi-918-762.js";import"./bi-805-737.js";import"./bi-475-763.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-568-764.js";function D({formFields:r,setFlow:i,flow:n,allIntegURL:p}){const c=u(),{formID:m}=x(),[s,b]=e.useState(1),[l,o]=e.useState({show:!1}),[d,h]=e.useState(!1),[a,f]=e.useState({name:"Pabbly Web Hooks",type:"Pabbly",method:"POST",url:"",apiConsole:"https://connect.pabbly.com/dashboard"});return t.jsxs("div",{children:[t.jsx(g,{snack:l,setSnackbar:o}),t.jsx("div",{className:"txt-center mt-2",children:t.jsx(y,{step:2,active:s})}),t.jsx("div",{className:"btcd-stp-page",style:{width:s===1&&1100,height:s===1&&"auto"},children:t.jsx(j,{formID:m,formFields:r,webHooks:a,setWebHooks:f,step:s,setStep:b,setSnackbar:o,create:!0})}),t.jsx("div",{className:"btcd-stp-page",style:{width:s===2&&"100%",height:s===2&&"auto"},children:t.jsx(k,{step:s,saveConfig:()=>S(n,i,p,a,c,"","",h),isLoading:d})})]})}export{D as default};
