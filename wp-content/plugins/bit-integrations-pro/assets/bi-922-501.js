import{r as i,j as e}from"./main.js";import{e as j,f as b,c as I,j as D}from"./bi-490-178.js";import{B as S}from"./bi-475-763.js";import{S as k}from"./bi-224-925.js";import{e as v}from"./bi-266-720.js";import{I as y}from"./bi-811-721.js";import _ from"./bi-924-383.js";import{c as w}from"./bi-904-908.js";import{D as N}from"./bi-388-909.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";function R({formFields:m,setFlow:l,flow:d,allIntegURL:f}){const g=j(),{formID:c}=b(),[o,r]=i.useState(!1),[s,p]=i.useState(1),[u,a]=i.useState({show:!1}),[t,n]=i.useState({name:"Drip",type:"Drip",api_token:"",field_map:[{formField:"",dripField:""}],actions:{}}),h=x=>{if(setTimeout(()=>{document.getElementById("btcd-settings-wrp").scrollTop=0},300),x===3){if(!w(t)){a({show:!0,msg:"Please map all required fields to continue."});return}if(!(t!=null&&t.campaignId)){a({show:!0,msg:"Please select campaign to continue."});return}t.name!==""&&t.field_map.length>0&&p(3)}};return e.jsxs("div",{children:[e.jsx(I,{snack:u,setSnackbar:a}),e.jsx("div",{className:"txt-center mt-2",children:e.jsx(k,{step:3,active:s})}),e.jsx(_,{formID:c,dripConf:t,setDripConf:n,step:s,setstep:p,isLoading:o,setIsLoading:r,setSnackbar:a}),e.jsxs("div",{className:"btcd-stp-page",style:{width:s===2&&900,height:s===2&&"auto"},children:[e.jsx(N,{formID:c,formFields:m,dripConf:t,setDripConf:n,isLoading:o,setIsLoading:r,setSnackbar:a}),e.jsxs("button",{onClick:()=>h(3),disabled:!(t!=null&&t.campaignId)||t.field_map.length<1,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[D("Next","bit-integrations")," "," ",e.jsx(S,{className:"ml-1 rev-icn"})]})]}),e.jsx(y,{step:s,saveConfig:()=>v(d,l,f,t,g,"","",r),isLoading:o,dataConf:t,setDataConf:n,formFields:m})]})}export{R as default};
