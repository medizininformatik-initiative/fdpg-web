import{r as e,j as s}from"./main.js";import{e as x,f as g,c as S}from"./bi-490-178.js";import{S as b}from"./bi-224-925.js";import{e as j}from"./bi-266-720.js";import{W as k}from"./bi-918-762.js";import H from"./bi-516-314.js";import"./bi-805-737.js";import"./bi-475-763.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-568-764.js";function D({formFields:r,setFlow:i,flow:m,allIntegURL:n}){const c=x(),{formID:p}=g(),[t,d]=e.useState(1),[f,o]=e.useState({show:!1}),[u,h]=e.useState(!1),[a,l]=e.useState({name:"Web Hooks",type:"Web Hooks",method:"POST",url:""});return s.jsxs("div",{children:[s.jsx(S,{snack:f,setSnackbar:o}),s.jsx("div",{className:"txt-center mt-2",children:s.jsx(b,{step:2,active:t})}),s.jsx("div",{className:"btcd-stp-page",style:{width:t===1&&900,height:t===1&&"auto"},children:s.jsx(H,{formID:p,formFields:r,webHooks:a,setWebHooks:l,step:t,setStep:d,setSnackbar:o,create:!0})}),s.jsx("div",{className:"btcd-stp-page",style:{width:t===2&&"100%",height:t===2&&"auto"},children:s.jsx(k,{step:t,saveConfig:()=>j(m,i,n,a,c,"","",h),isLoading:u})})]})}export{D as default};
