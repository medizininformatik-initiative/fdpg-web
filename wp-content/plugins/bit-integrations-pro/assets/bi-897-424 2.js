import{r as a,j as t}from"./main.js";import{e as j,f as b,c as E,_ as S}from"./bi-490-178.js";import{B as y}from"./bi-475-763.js";import{S as C}from"./bi-224-925.js";import{e as I}from"./bi-266-720.js";import{I as k}from"./bi-811-721.js";import v from"./bi-386-317.js";import{c as _}from"./bi-602-770.js";import{E as N}from"./bi-310-771.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";import"./bi-528-726.js";function G({formFields:r,setFlow:l,flow:f,allIntegURL:g}){const d=j(),{formID:i}=b(),[c,m]=a.useState(!1),[s,p]=a.useState(1),[h,n]=a.useState({show:!1}),[e,o]=a.useState({name:"Encharge",type:"Encharge",tags:"",api_key:"",field_map:[{formField:"",enChargeFields:""}],actions:{}}),u=x=>{if(setTimeout(()=>{document.getElementById("btcd-settings-wrp").scrollTop=0},300),x===3){if(!_(e)){n({show:!0,msg:"Please map all required fields to continue."});return}e.name!==""&&e.field_map.length>0&&p(3)}};return t.jsxs("div",{children:[t.jsx(E,{snack:h,setSnackbar:n}),t.jsx("div",{className:"txt-center mt-2",children:t.jsx(C,{step:3,active:s})}),t.jsx(v,{formID:i,enchargeConf:e,setEnchargeConf:o,step:s,setstep:p,isLoading:c,setIsLoading:m,setSnackbar:n}),t.jsxs("div",{className:"btcd-stp-page",style:{width:s===2&&900,minHeight:s===2&&"200",height:s===2&&"auto"},children:[t.jsx(N,{formID:i,formFields:r,enchargeConf:e,setEnchargeConf:o}),t.jsxs("button",{onClick:()=>u(3),disabled:e.field_map.length<1,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[S("Next","bit-integrations")," ",t.jsx(y,{className:"ml-1 rev-icn"})]})]}),t.jsx(k,{step:s,saveConfig:()=>I(f,l,g,e,d,"","",m),isLoading:c,dataConf:e,setDataConf:o,formFields:r})]})}export{G as default};
