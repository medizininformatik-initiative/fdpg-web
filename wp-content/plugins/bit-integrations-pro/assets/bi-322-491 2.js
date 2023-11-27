var h=Object.defineProperty;var D=Object.getOwnPropertySymbols;var M=Object.prototype.hasOwnProperty,F=Object.prototype.propertyIsEnumerable;var k=(a,l,r)=>l in a?h(a,l,{enumerable:!0,configurable:!0,writable:!0,value:r}):a[l]=r,c=(a,l)=>{for(var r in l||(l={}))M.call(l,r)&&k(a,r,l[r]);if(D)for(var r of D(l))F.call(l,r)&&k(a,r,l[r]);return a};import{r as i,j as s}from"./main.js";import{e as x,c as B,_ as j,a as t}from"./bi-490-178.js";import{S as H}from"./bi-224-925.js";import{e as Y}from"./bi-266-720.js";import{I as U}from"./bi-811-721.js";import v from"./bi-58-373.js";import{h as G,c as I,l as w}from"./bi-974-890.js";import{I as W}from"./bi-2-891.js";import"./bi-369-724.js";import"./bi-422-718.js";import"./bi-652-725.js";function se({formFields:a,setFlow:l,flow:r,allIntegURL:E}){const A=x(),[q,y]=i.useState(!1),[T,b]=i.useState({}),[d,f]=i.useState(1),[p,u]=i.useState({show:!1}),O=[{key:"ORGANISATION_NAME",label:"Name",required:!0},{key:"PHONE",label:"Phone",required:!1},{key:"PHONE_FAX",label:"Fax",required:!1},{key:"WEBSITE",label:"Website",required:!1},{key:"SOCIAL_FACEBOOK",label:"Facebook",required:!1},{key:"SOCIAL_LINKEDIN",label:"Linkedin",required:!1},{key:"SOCIAL_TWITTER",label:"Twitter",required:!1},{key:"ADDRESS_BILLING_STREET",label:"Address Billing Street",required:!1},{key:"ADDRESS_BILLING_CITY",label:"Address Billing City",required:!1},{key:"ADDRESS_BILLING_STATE",label:"Address Billing State",required:!1},{key:"ADDRESS_BILLING_COUNTRY",label:"Address Billing Country",required:!1},{key:"ADDRESS_BILLING_POSTCODE",label:"Address Billing Postcode",required:!1},{key:"ADDRESS_SHIP_STREET",label:"Address Shipping Street",required:!1},{key:"ADDRESS_SHIP_STATE",label:"Address Shipping State",required:!1},{key:"ADDRESS_SHIP_POSTCODE",label:"Address Shipping Postcode",required:!1},{key:"ADDRESS_SHIP_COUNTRY",label:"Address Shipping Country",required:!1}],R=[{key:"TITLE",label:"Task Name",required:!0},{key:"DUE_DATE",label:"Due Date",required:!1},{key:"COMPLETED_DATE_UTC",label:"Completed Date",required:!1},{key:"DETAILS",label:"Description",required:!1},{key:"PERCENT_COMPLETE",label:"Progress ( % )",required:!1},{key:"START_DATE",label:"Start Date",required:!1}],N=[{key:"FIRST_NAME",label:"First Name",required:!1},{key:"LAST_NAME",label:"Last Name",required:!0},{key:"TITLE",label:"Title",required:!1},{key:"ORGANISATION_NAME",label:"Organization",required:!1},{key:"LEAD_RATING",label:"Lead Rating",required:!1},{key:"EMAIL",label:"Email Address",required:!1},{key:"PHONE",label:"Phone",required:!1},{key:"MOBILE",label:"Mobile Phone",required:!1},{key:"FAX",label:"Fax",required:!1},{key:"WEBSITE",label:"Website",required:!1},{key:"INDUSTRY",label:"Industry",required:!1},{key:"EMPLOYEE_COUNT",label:"Number of Employees",required:!1},{key:"ADDRESS_STREET",label:"Address Street",required:!1},{key:"ADDRESS_CITY",label:"Address City",required:!1},{key:"ADDRESS_STATE",label:"Address State",required:!1},{key:"ADDRESS_POSTCODE",label:"Address Postcode",required:!1},{key:"ADDRESS_COUNTRY",label:"Address Country",required:!1},{key:"LEAD_DESCRIPTION",label:"Description",required:!1}],m=[{key:"FIRST_NAME",label:"First Name",required:!0},{key:"EMAIL_ADDRESS",label:"Email",required:!0},{key:"LAST_NAME",label:"Last Name",required:!1},{key:"TITLE",label:"Title",required:!1},{key:"PHONE",label:"Phone",required:!1},{key:"PHONE_FAX",label:"Fax",required:!1},{key:"DATE_OF_BIRTH",label:"Date of Birth",required:!1},{key:"SOCIAL_FACEBOOK",label:"Facebook",required:!1},{key:"SOCIAL_LINKEDIN",label:"Linkedin",required:!1},{key:"SOCIAL_TWITTER",label:"Twitter",required:!1},{key:"ADDRESS_MAIL_STREET",label:"Address Mail Street",required:!1},{key:"ADDRESS_MAIL_CITY",label:"Address Mail City",required:!1},{key:"ADDRESS_MAIL_STATE",label:"Address Mail State",required:!1},{key:"ADDRESS_MAIL_COUNTRY",label:"Address Mail Country",required:!1},{key:"ADDRESS_MAIL_POSTCODE",label:"Address Mail Postcode",required:!1},{key:"ADDRESS_OTHER_STREET",label:"Address Other Street",required:!1},{key:"ADDRESS_OTHER_STATE",label:"Address Other State",required:!1},{key:"ADDRESS_OTHER_POSTCODE",label:"Address Other Postcode",required:!1},{key:"ADDRESS_OTHER_COUNTRY",label:"Address Other Country",required:!1}],L=[{key:"OPPORTUNITY_NAME",label:"Opportunity Name",required:!0},{key:"OPPORTUNITY_DETAILS",label:"Opportunity Details",required:!1},{key:"BID_AMOUNT",label:"Bid Amount",required:!1},{key:"ACTUAL_CLOSE_DATE",label:"Actual Close Date",required:!1},{key:"PROBABILITY",label:"Probability Of Winning",required:!1},{key:"FORECAST_CLOSE_DATE",label:"Forecast Close Date",required:!1}],C=[{key:"PROJECT_NAME",label:"Project Name",required:!0},{key:"PROJECT_DETAILS",label:"Description",required:!1}],[e,o]=i.useState({name:"Insightly",type:"Insightly",api_key:"",api_url:"",field_map:[{formField:"",insightlyFormField:""}],actionName:"",organisationFields:O,contactFields:m,taskFields:R,leadFields:N,opportunityFields:L,projectFields:C,actions:{}}),P=()=>{y(!0),Y(r,l,E,e,A,"","",y).then(n=>{var _;n.success?(t.success((_=n.data)==null?void 0:_.msg),A.push(E)):t.error(n.data||n)})},g=S=>{if(!I(e)){t.error("Please map mandatory fields");return}if(e.actionName==="opportunity"||e.actionName==="project"){if(!e.selectedCRMPipeline){t.error("Please select a pipeline");return}if(!e.selectedCRMPipelineStages){t.error("Please select a pipeline stage");return}}e.field_map.length>0&&f(S)};return document.querySelector(".btcd-s-wrp").scrollTop=0,s.jsxs("div",{children:[s.jsx(B,{snack:p,setSnackbar:u}),s.jsx("div",{className:"txt-center mt-2",children:s.jsx(H,{step:3,active:d})}),s.jsx(v,{insightlyConf:e,setInsightlyConf:o,step:d,setStep:f,loading:T,setLoading:b,setSnackbar:u}),s.jsxs("div",{className:"btcd-stp-page",style:c({},d===2&&{width:900,height:"auto",overflow:"visible"}),children:[s.jsx(W,{formFields:a,handleInput:S=>G(S,e,o),insightlyConf:e,setInsightlyConf:o,loading:T,setLoading:b,setSnackbar:u}),(e==null?void 0:e.actionName)&&s.jsxs("button",{onClick:()=>g(3),disabled:!(I(e)&&w(e)),className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",children:[j("Next","bit-integrations")," "," ",s.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),(e==null?void 0:e.actionName)&&s.jsx(U,{step:d,saveConfig:()=>P(),isLoading:q,dataConf:e,setDataConf:o,formFields:a})]})}export{se as default};
