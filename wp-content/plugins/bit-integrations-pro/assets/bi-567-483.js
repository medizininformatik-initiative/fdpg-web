import{r as i,j as e}from"./main.js";import{e as j,f as k,c as B,j as I}from"./bi-490-178.js";import{B as S}from"./bi-475-763.js";import{S as b}from"./bi-224-925.js";import{e as v}from"./bi-266-720.js";import{I as y}from"./bi-811-721.js";import M from"./bi-631-366.js";import{c as _}from"./bi-736-876.js";import{B as w}from"./bi-788-877.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";function R({formFields:c,setFlow:p,flow:d,allIntegURL:f}){const u=j(),{formID:m}=k(),[o,r]=i.useState(!1),[s,l]=i.useState(1),[h,a]=i.useState({show:!1}),[t,n]=i.useState({name:"BenchMark",type:"BenchMark",api_secret:"",field_map:[{formField:"",benchMarkField:""}],actions:{}}),g=x=>{if(setTimeout(()=>{document.getElementById("btcd-settings-wrp").scrollTop=0},300),x===3){if(!_(t)){a({show:!0,msg:"Please map all required fields to continue."});return}if(!(t!=null&&t.listId)){a({show:!0,msg:"Please select list to continue."});return}t.name!==""&&t.field_map.length>0&&l(3)}};return e.jsxs("div",{children:[e.jsx(B,{snack:h,setSnackbar:a}),e.jsx("div",{className:"txt-center mt-2",children:e.jsx(b,{step:3,active:s})}),e.jsx(M,{formID:m,benchMarkConf:t,setBenchMarkConf:n,step:s,setstep:l,isLoading:o,setIsLoading:r,setSnackbar:a}),e.jsxs("div",{className:"btcd-stp-page",style:{width:s===2&&900,height:s===2&&"auto"},children:[e.jsx(w,{formID:m,formFields:c,benchMarkConf:t,setBenchMarkConf:n,isLoading:o,setIsLoading:r,setSnackbar:a}),e.jsxs("button",{onClick:()=>g(3),disabled:!(t!=null&&t.listId)||t.field_map.length<1,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[I("Next","bit-integrations")," "," ",e.jsx(S,{className:"ml-1 rev-icn"})]})]}),e.jsx(y,{step:s,saveConfig:()=>v(d,p,f,t,u,"","",r),isLoading:o,dataConf:t,setDataConf:n,formFields:c})]})}export{R as default};
