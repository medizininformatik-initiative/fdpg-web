var k=Object.defineProperty;var j=Object.getOwnPropertySymbols;var I=Object.prototype.hasOwnProperty,w=Object.prototype.propertyIsEnumerable;var S=(s,e,t)=>e in s?k(s,e,{enumerable:!0,configurable:!0,writable:!0,value:t}):s[e]=t,v=(s,e)=>{for(var t in e||(e={}))I.call(e,t)&&S(s,t,e[t]);if(j)for(var t of j(e))w.call(e,t)&&S(s,t,e[t]);return s};import{r,j as i}from"./main.js";import{e as N,c as T,_ as L,a as p}from"./bi-490-178.js";import{S as E}from"./bi-224-925.js";import{e as M}from"./bi-266-720.js";import{I as P}from"./bi-811-721.js";import q from"./bi-916-370.js";import{h as z,c as F}from"./bi-636-883.js";import{A as B}from"./bi-147-884.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function $({formFields:s,setFlow:e,flow:t,allIntegURL:b}){const f=N(),[y,u]=r.useState(!1),[o,h]=r.useState({auth:!1,customFields:!1,bases:!1,tables:!1,airtableFields:!1}),[l,g]=r.useState(1),[A,m]=r.useState({show:!1}),[a,n]=r.useState({name:"Airtable",type:"Airtable",auth_token:"",field_map:[{formField:"",airtableFormField:""}],airtableFields:[],bases:[],selectedBase:"",selectedTable:"",actions:{}}),C=()=>{u(!0),M(t,e,b,a,f,"","",u).then(d=>{var x;d.success?(p.success((x=d.data)==null?void 0:x.msg),f.push(b)):p.error(d.data||d)})},_=c=>{if(!F(a)){p.error("Please map mandatory airtableFields");return}a.field_map.length>0&&g(c)};return document.querySelector(".btcd-s-wrp").scrollTop=0,i.jsxs("div",{children:[i.jsx(T,{snack:A,setSnackbar:m}),i.jsx("div",{className:"txt-center mt-2",children:i.jsx(E,{step:3,active:l})}),i.jsx(q,{airtableConf:a,setAirtableConf:n,step:l,setStep:g,loading:o,setLoading:h,setSnackbar:m}),i.jsxs("div",{className:"btcd-stp-page",style:v({},l===2&&{width:900,height:"auto",overflow:"visible"}),children:[i.jsx(B,{formFields:s,handleInput:c=>z(c,a,n),airtableConf:a,setAirtableConf:n,loading:o,setLoading:h,setSnackbar:m}),o.airtableFields&&a.selectedTable&&i.jsxs("button",{onClick:()=>_(3),disabled:!F(a),className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[L("Next","bit-integrations")," "," ",i.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),o.airtableFields&&a.selectedTable&&i.jsx(P,{step:l,saveConfig:()=>C(),isLoading:y,dataConf:a,setDataConf:n,formFields:s})]})}export{$ as default};
