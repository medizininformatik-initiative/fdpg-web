var C=Object.defineProperty,E=Object.defineProperties;var w=Object.getOwnPropertyDescriptors;var f=Object.getOwnPropertySymbols;var k=Object.prototype.hasOwnProperty,v=Object.prototype.propertyIsEnumerable;var g=(s,t,e)=>t in s?C(s,t,{enumerable:!0,configurable:!0,writable:!0,value:e}):s[t]=e,h=(s,t)=>{for(var e in t||(t={}))k.call(t,e)&&g(s,e,t[e]);if(f)for(var e of f(t))v.call(t,e)&&g(s,e,t[e]);return s},x=(s,t)=>E(s,w(t));import{r as l,j as a}from"./main.js";import{e as N,h as u,$ as S,R as M,g as F,i as L,c as R,_ as j}from"./bi-490-178.js";import{E as $,a as U}from"./bi-389-719.js";import{c as _}from"./bi-266-720.js";import{I as W}from"./bi-811-721.js";import{h as D,s as H}from"./bi-196-844.js";import{M as K}from"./bi-254-845.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-200-835.js";import"./bi-652-725.js";import"./bi-528-726.js";function Z({allIntegURL:s}){const t=N(),[e,n]=l.useState({show:!1}),i=u(S),[o,r]=M(F),[b,y]=l.useState({name:"",authKey:""}),c=u(L),[d,p]=l.useState({auth:!1,list:!1,page:!1,update:!1}),I=m=>{p(x(h({},d),{update:m}))};return a.jsxs("div",{style:{width:900},children:[a.jsx(R,{snack:e,setSnackbar:n}),a.jsxs("div",{className:"flx mt-3",children:[a.jsx("b",{className:"wdt-200 d-in-b",children:j("Integration Name:","bit-integrations")}),a.jsx("input",{className:"btcd-paper-inp w-5",name:"name",onChange:m=>D(m,o,r,b,y),value:o.name,type:"text",placeholder:j("Integration Name...","bit-integrations")})]}),a.jsxs("div",{className:"my-3",children:[!_(i.triggered_entity)&&a.jsx($,{setSnackbar:n}),_(i.triggered_entity)&&a.jsx(U,{setSnackbar:n})]}),a.jsx(K,{moosendConf:o,setMoosendConf:r,formFields:c,loading:d,setLoading:p}),a.jsx(W,{edit:!0,saveConfig:()=>{H(i,s,o,t,{edit:1},I)},isLoading:d.update,disabled:o.field_map.length<1,dataConf:o,setDataConf:r,formFields:c})]})}export{Z as default};
