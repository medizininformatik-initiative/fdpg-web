var q=Object.defineProperty;var f=Object.getOwnPropertySymbols;var j=Object.prototype.hasOwnProperty,C=Object.prototype.propertyIsEnumerable;var p=(s,e,t)=>e in s?q(s,e,{enumerable:!0,configurable:!0,writable:!0,value:t}):s[e]=t,u=(s,e)=>{for(var t in e||(e={}))j.call(e,t)&&p(s,t,e[t]);if(f)for(var t of f(e))C.call(e,t)&&p(s,t,e[t]);return s};import{r as n,j as a}from"./main.js";import{e as _,_ as v,a as S}from"./bi-490-178.js";import{S as G}from"./bi-224-925.js";import{s as N}from"./bi-266-720.js";import{I}from"./bi-811-721.js";import w from"./bi-56-318.js";import{G as F,h as L,c as P}from"./bi-248-794.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-475-763.js";import"./bi-652-725.js";import"./bi-528-726.js";function V({formFields:s,setFlow:e,flow:t,allIntegURL:y}){const b=_(),[d,i]=n.useState(!1),[o,m]=n.useState(1),[E,g]=n.useState({show:!1}),h=[{key:"name",label:"Name",required:!1},{key:"email",label:"Email",required:!0},{key:"phone",label:"Phone",required:!1},{key:"gender",label:"Gender",required:!1},{key:"country",label:"Country",required:!1},{key:"city",label:"City",required:!1},{key:"company_name",label:"Company Name",required:!1},{key:"industry",label:"Industry",required:!1},{key:"job_title",label:"Job Title",required:!1},{key:"last_name",label:"Last Name",required:!1},{key:"postal_code",label:"Postal Code",required:!1},{key:"state",label:"State",required:!1}],[r,l]=n.useState({name:"Getgist",type:"Getgist",api_key:"",field_map:[{formField:"",getgistFormField:""}],actions:{},gistFields:h}),k=()=>{i(!0),N({flow:t,setFlow:e,allIntegURL:y,conf:r,history:b,setIsLoading:i,setSnackbar:g})},x=c=>{if(!P(r)){S.error("Please map mandatory fields");return}r.field_map.length>0&&m(c)};return document.querySelector(".btcd-s-wrp").scrollTop=0,a.jsxs("div",{children:[a.jsx("div",{className:"txt-center mt-2",children:a.jsx(G,{step:3,active:o})}),a.jsx(w,{getgistConf:r,setGetgistConf:l,step:o,setstep:m,isLoading:d,setIsLoading:i}),a.jsxs("div",{className:"btcd-stp-page",style:u({},o===2&&{width:900,height:"auto",overflow:"visible"}),children:[a.jsx(F,{formFields:s,handleInput:c=>L(c,r,l),getgistConf:r,setGetgistConf:l,isLoading:d,setIsLoading:i}),a.jsxs("button",{onClick:()=>x(3),className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[v("Next","bit-integrations")," "," ",a.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),a.jsx(I,{step:o,saveConfig:()=>k(),isLoading:d,dataConf:r,setDataConf:l,formFields:s})]})}export{V as default};
