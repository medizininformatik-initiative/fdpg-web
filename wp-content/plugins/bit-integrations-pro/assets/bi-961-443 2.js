var C=Object.defineProperty;var b=Object.getOwnPropertySymbols;var I=Object.prototype.hasOwnProperty,N=Object.prototype.propertyIsEnumerable;var g=(s,e,t)=>e in s?C(s,e,{enumerable:!0,configurable:!0,writable:!0,value:t}):s[e]=t,S=(s,e)=>{for(var t in e||(e={}))I.call(e,t)&&g(s,t,e[t]);if(b)for(var t of b(e))N.call(e,t)&&g(s,t,e[t]);return s};import{r as l,j as a}from"./main.js";import{e as w,c as q,_ as E,a as u}from"./bi-490-178.js";import{S as F}from"./bi-224-925.js";import{e as L}from"./bi-266-720.js";import M from"./bi-82-328.js";import{I as P}from"./bi-811-721.js";import{S as T,h as z,c as A}from"./bi-616-797.js";import"./bi-475-763.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function Z({formFields:s,setFlow:e,flow:t,allIntegURL:f}){const h=w(),[m,r]=l.useState(!1),[o,y]=l.useState(1),[j,p]=l.useState({show:!1}),v=[{key:"email",label:"Email",required:!0},{key:"name",label:"Name",required:!1}],[n,i]=l.useState({name:"Sendy",type:"Sendy",api_key:"",sendy_url:"",field_map:[{formField:"",sendyField:""}],subscriberFields:v,actions:{}}),k=()=>{r(!0),L(t,e,f,n,h,"","",r).then(d=>{var x;d.success?(u.success((x=d.data)==null?void 0:x.msg),h.push(f)):u.error(d.data||d)})},_=c=>{if(!A(n)){u.error("Please map mandatory fields");return}n.field_map.length>0&&y(c)};return document.querySelector(".btcd-s-wrp").scrollTop=0,a.jsxs("div",{children:[a.jsx(q,{snack:j,setSnackbar:p}),a.jsx("div",{className:"txt-center mt-2",children:a.jsx(F,{step:3,active:o})}),a.jsx(M,{sendyConf:n,setSendyConf:i,step:o,setstep:y,isLoading:m,setIsLoading:r,setSnackbar:p}),a.jsxs("div",{className:"btcd-stp-page",style:S({},o===2&&{width:900,height:"auto",overflow:"visible"}),children:[a.jsx(T,{formFields:s,handleInput:c=>z(c,n,i),sendyConf:n,setSendyConf:i,isLoading:m,setIsLoading:r,setSnackbar:p}),a.jsxs("button",{onClick:()=>_(3),className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[E("Next","bit-integrations")," "," ",a.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),a.jsx(P,{step:o,saveConfig:()=>k(),isLoading:m,dataConf:n,setDataConf:i,formFields:s})]})}export{Z as default};
