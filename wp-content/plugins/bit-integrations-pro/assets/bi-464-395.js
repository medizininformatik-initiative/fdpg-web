var k=Object.defineProperty;var b=Object.getOwnPropertySymbols;var C=Object.prototype.hasOwnProperty,_=Object.prototype.propertyIsEnumerable;var w=(s,t,e)=>t in s?k(s,t,{enumerable:!0,configurable:!0,writable:!0,value:e}):s[t]=e,y=(s,t)=>{for(var e in t||(t={}))C.call(t,e)&&w(s,e,t[e]);if(b)for(var e of b(t))_.call(t,e)&&w(s,e,t[e]);return s};import{r as d,j as o}from"./main.js";import{e as I,c as N,_ as B,a as p}from"./bi-490-178.js";import{S as F}from"./bi-224-925.js";import{e as q}from"./bi-266-720.js";import{h as E}from"./bi-707-742.js";import L from"./bi-787-337.js";import{I as M}from"./bi-811-721.js";import{T as P}from"./bi-730-743.js";import"./bi-369-724.js";import"./bi-422-718.js";function W({formFields:s,setFlow:t,flow:e,allIntegURL:u}){const h=I(),[m,a]=d.useState(!1),[r,x]=d.useState(1),[j,f]=d.useState({show:!1}),T=[{key:"To",label:"To",required:!0},{key:"Body",label:"Message Body",required:!0}],[i,n]=d.useState({name:"Twilio",type:"Twilio",sid:"",token:"",body:"",to:"",from_num:"",field_map:[{formField:"",twilioField:"To"},{formField:"",twilioField:"Body"}],twilioFields:T}),v=()=>{a(!0),q(e,t,u,i,h,"","",a).then(c=>{var g;c.success?(p.success((g=c.data)==null?void 0:g.msg),h.push(u)):p.error(c.data||c)})},S=l=>{if(i.to===""&&i.body===""){p.error("Please select To and Body field , it is required");return}x(l)};return o.jsxs("div",{children:[o.jsx(N,{snack:j,setSnackbar:f}),o.jsx("div",{className:"txt-center mt-2",children:o.jsx(F,{step:3,active:r})}),o.jsx(L,{twilioConf:i,setTwilioConf:n,step:r,setstep:x,isLoading:m,setIsLoading:a,setSnackbar:f}),o.jsxs("div",{className:"btcd-stp-page",style:y({},r===2&&{width:900,height:"auto",overflow:"visible"}),children:[o.jsx(P,{formFields:s,handleInput:l=>E(l,i,n),twilioConf:i,setTwilioConf:n,isLoading:m,setIsLoading:a,setSnackbar:f}),o.jsxs("button",{onClick:()=>S(3),className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[B("Next","bit-integrations")," "," ",o.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),o.jsx(M,{step:r,saveConfig:()=>v(),isLoading:m,dataConf:i,setDataConf:n,formFields:s})]})}export{W as default};
