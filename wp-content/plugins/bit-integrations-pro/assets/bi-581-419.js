import{r as e,j as s}from"./main.js";import{e as x,f as S,c as j}from"./bi-490-178.js";import{S as g}from"./bi-224-925.js";import{s as A}from"./bi-266-720.js";import b from"./bi-516-314.js";import{W as k}from"./bi-918-762.js";import"./bi-805-737.js";import"./bi-475-763.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-568-764.js";function T({formFields:p,setFlow:r,flow:i,allIntegURL:n}){const m=x(),{formID:c}=S(),[t,d]=e.useState(1),[f,o]=e.useState({show:!1}),[h,l]=e.useState(!1),[a,u]=e.useState({name:"Ants & Apps",type:"Ant Apps",method:"POST",url:"",apiConsole:"https://my.antsandapps.com/"});return s.jsxs("div",{children:[s.jsx(j,{snack:f,setSnackbar:o}),s.jsx("div",{className:"txt-center mt-2",children:s.jsx(g,{step:2,active:t})}),s.jsx("div",{className:"btcd-stp-page",style:{width:t===1&&1100,height:t===1&&"auto"},children:s.jsx(b,{formID:c,formFields:p,webHooks:a,setWebHooks:u,step:t,setStep:d,setSnackbar:o,create:!0})}),s.jsx("div",{className:"btcd-stp-page",style:{width:t===2&&900,height:t===2&&"auto"},children:s.jsx(k,{step:t,saveConfig:()=>A({flow:i,setFlow:r,allIntegURL:n,conf:a,history:m,setIsLoading:l,setSnackbar:o}),isLoading:h})})]})}export{T as default};
