var F=Object.defineProperty;var j=Object.getOwnPropertySymbols;var N=Object.prototype.hasOwnProperty,w=Object.prototype.propertyIsEnumerable;var v=(a,s,t)=>s in a?F(a,s,{enumerable:!0,configurable:!0,writable:!0,value:t}):a[s]=t,S=(a,s)=>{for(var t in s||(s={}))N.call(s,t)&&v(a,t,s[t]);if(j)for(var t of j(s))w.call(s,t)&&v(a,t,s[t]);return a};import{r as o,j as i}from"./main.js";import{e as q,c as E,_ as L,a as m}from"./bi-490-178.js";import{S as R}from"./bi-224-925.js";import{e as T}from"./bi-266-720.js";import{I as C}from"./bi-811-721.js";import M from"./bi-543-352.js";import{h as P,c as z}from"./bi-580-848.js";import{G as A}from"./bi-851-849.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function U({formFields:a,setFlow:s,flow:t,allIntegURL:p}){const u=q(),[y,f]=o.useState(!1),[g,h]=o.useState({list:!1,field:!1,auth:!1,tags:!1,customFields:!1}),[n,x]=o.useState(1),[I,d]=o.useState({show:!1}),k=[{key:"email",label:"Email",required:!0},{key:"name",label:"Name",required:!1}],[e,r]=o.useState({name:"GetResponse",type:"GetResponse",auth_token:"",field_map:[{formField:"",getResponseFormField:""}],contactsFields:k,campaignId:"",getResponseFields:[],campaigns:[],tags:[],selectedTags:[],actions:{}}),_=()=>{f(!0),T(t,s,p,e,u,"","",f).then(l=>{var b;l.success?(m.success((b=l.data)==null?void 0:b.msg),u.push(p)):m.error(l.data||l)})},G=c=>{if(!z(e)){m.error("Please map mandatory fields");return}e.field_map.length>0&&x(c)};return document.querySelector(".btcd-s-wrp").scrollTop=0,i.jsxs("div",{children:[i.jsx(E,{snack:I,setSnackbar:d}),i.jsx("div",{className:"txt-center mt-2",children:i.jsx(R,{step:3,active:n})}),i.jsx(M,{getResponseConf:e,setGetResponseConf:r,step:n,setstep:x,loading:g,setLoading:h,setSnackbar:d}),i.jsxs("div",{className:"btcd-stp-page",style:S({},n===2&&{width:900,height:"auto",overflow:"visible"}),children:[i.jsx(A,{formFields:a,handleInput:c=>P(c,e,r),getResponseConf:e,setGetResponseConf:r,loading:g,setLoading:h,setSnackbar:d}),(e==null?void 0:e.campaignId)&&i.jsxs("button",{onClick:()=>G(3),disabled:!(e!=null&&e.campaignId),className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[L("Next","bit-integrations")," "," ",i.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),(e==null?void 0:e.campaignId)&&i.jsx(C,{step:n,saveConfig:()=>_(),isLoading:y,dataConf:e,setDataConf:r,formFields:a})]})}export{U as default};
