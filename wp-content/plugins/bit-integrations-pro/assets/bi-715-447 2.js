var _=Object.defineProperty;var u=Object.getOwnPropertySymbols;var I=Object.prototype.hasOwnProperty,S=Object.prototype.propertyIsEnumerable;var b=(o,s,e)=>s in o?_(o,s,{enumerable:!0,configurable:!0,writable:!0,value:e}):o[s]=e,h=(o,s)=>{for(var e in s||(s={}))I.call(s,e)&&b(o,e,s[e]);if(u)for(var e of u(s))S.call(s,e)&&b(o,e,s[e]);return o};import{r as p,j as t}from"./main.js";import{e as k,f as y,h as w,B as C,c as N,_ as L}from"./bi-490-178.js";import{S as M}from"./bi-224-925.js";import{s as P}from"./bi-266-720.js";import{I as R}from"./bi-811-721.js";import A from"./bi-207-333.js";import{h as E,c as T,b as q}from"./bi-752-808.js";import{B as D}from"./bi-171-809.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-475-763.js";import"./bi-652-725.js";import"./bi-528-726.js";function tt({formFields:o,setFlow:s,flow:e,allIntegURL:x}){const g=k(),{formID:l}=y(),j=w(C),{siteURL:F}=j,[r,n]=p.useState(!1),[c,f]=p.useState(1),[v,a]=p.useState({show:!1}),[i,m]=p.useState({name:"Bit Form",type:"Bit Form",domainName:F,api_key:"",id:"",field_map:[{formField:"",BitFormMapField:""}],address_field:[],actions:{}}),B=()=>{var d;if(setTimeout(()=>{document.getElementById("btcd-settings-wrp").scrollTop=0},300),(d=i.actions)!=null&&d.address&&!T(i)){a({show:!0,msg:"Please map address required fields to continue."});return}if(!q(i)){a({show:!0,msg:"Please map fields to continue."});return}i.listId!==""&&f(3)};return t.jsxs("div",{children:[t.jsx(N,{snack:v,setSnackbar:a}),t.jsx("div",{className:"txt-center mt-2",children:t.jsx(M,{step:3,active:c})}),t.jsx(A,{formID:l,bitFormConf:i,setBitFormConf:m,step:c,setstep:f,isLoading:r,setIsLoading:n,setSnackbar:a}),t.jsxs("div",{className:"btcd-stp-page",style:h({},c===2&&{width:900,height:"auto",overflow:"visible"}),children:[t.jsx(D,{formFields:o,handleInput:d=>E(d,i,m,l,n),bitFormConf:i,setBitFormConf:m,isLoading:r,setIsLoading:n,setSnackbar:a}),!i.id&&t.jsxs(t.Fragment,{children:[t.jsx("br",{}),t.jsx("br",{}),t.jsx("br",{})]}),t.jsxs("button",{onClick:()=>B(),disabled:i.field_map.length<2||r,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[L("Next","bit-integrations")," "," ",t.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),t.jsx(R,{step:c,saveConfig:()=>P({flow:e,setFlow:s,allIntegURL:x,history:g,conf:i,setIsLoading:n,setSnackbar:a}),isLoading:r,dataConf:i,setDataConf:m,formFields:o})]})}export{tt as default};
