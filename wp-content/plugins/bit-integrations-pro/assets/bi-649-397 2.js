var I=Object.defineProperty;var h=Object.getOwnPropertySymbols;var M=Object.prototype.hasOwnProperty,R=Object.prototype.propertyIsEnumerable;var g=(o,t,e)=>t in o?I(o,t,{enumerable:!0,configurable:!0,writable:!0,value:e}):o[t]=e,x=(o,t)=>{for(var e in t||(t={}))M.call(t,e)&&g(o,e,t[e]);if(h)for(var e of h(t))R.call(t,e)&&g(o,e,t[e]);return o};import{r as i,j as a}from"./main.js";import{e as k,f as _,c as Z,_ as b}from"./bi-490-178.js";import{S as N}from"./bi-224-925.js";import{g as z,s as E}from"./bi-266-720.js";import{I as F}from"./bi-811-721.js";import L from"./bi-816-297.js";import{h as P,c as T}from"./bi-186-735.js";import{Z as A}from"./bi-345-736.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-805-737.js";import"./bi-579-738.js";import"./bi-699-739.js";import"./bi-826-717.js";import"./bi-652-725.js";import"./bi-528-726.js";function st({formFields:o,setFlow:t,flow:e,allIntegURL:C}){const j=k(),{formID:l}=_(),[p,r]=i.useState(!1),[c,f]=i.useState(1),[S,n]=i.useState({show:!1}),[u,v]=i.useState(0),[s,m]=i.useState({name:"Zoho CRM",type:"Zoho CRM",clientId:"",clientSecret:"",module:"",layout:"",field_map:[{formField:"",zohoFormField:""}],relatedlists:[],actions:{}});i.useEffect(()=>{window.opener&&z("zohoCRM")},[]);const y=()=>{E({flow:e,setFlow:t,allIntegURL:C,conf:s,history:j,setIsLoading:r,setSnackbar:n})},w=d=>{if(!T(s)){n({show:!0,msg:b("Please map mandatory fields","bit-integrations")});return}s.module&&s.layout&&s.field_map.length>0&&f(d)};return document.querySelector(".btcd-s-wrp").scrollTop=0,a.jsxs("div",{children:[a.jsx(Z,{snack:S,setSnackbar:n}),a.jsx("div",{className:"txt-center mt-2",children:a.jsx(N,{step:3,active:c})}),a.jsx(L,{formID:l,crmConf:s,setCrmConf:m,step:c,setstep:f,isLoading:p,setIsLoading:r,setSnackbar:n}),a.jsxs("div",{className:"btcd-stp-page",style:x({},c===2&&{width:900,height:"auto",overflow:"visible"}),children:[a.jsx(A,{tab:u,settab:v,formID:l,formFields:o,handleInput:d=>P(d,u,s,m,l,r,n),crmConf:s,setCrmConf:m,isLoading:p,setIsLoading:r,setSnackbar:n}),a.jsxs("button",{onClick:()=>w(3),disabled:s.module===""||s.layout===""||s.field_map.length<1,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[b("Next","bit-integrations")," "," ",a.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),a.jsx(F,{step:c,saveConfig:()=>y(),isLoading:p,dataConf:s,setDataConf:m,formFields:o})]})}export{st as default};
