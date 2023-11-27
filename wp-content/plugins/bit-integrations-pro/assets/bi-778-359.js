var z=Object.defineProperty;var g=Object.getOwnPropertySymbols;var _=Object.prototype.hasOwnProperty,k=Object.prototype.propertyIsEnumerable;var u=(a,t,i)=>t in a?z(a,t,{enumerable:!0,configurable:!0,writable:!0,value:i}):a[t]=i,o=(a,t)=>{for(var i in t||(t={}))_.call(t,i)&&u(a,i,t[i]);if(g)for(var i of g(t))k.call(t,i)&&u(a,i,t[i]);return a};import{r as j,j as e}from"./main.js";import{h as E,B as P,_ as s,D as f,E as R}from"./bi-490-178.js";import{a as C,g as T}from"./bi-450-863.js";function B({flowID:a,pCloudConf:t,setPCloudConf:i,step:m,setStep:N,isLoading:b,setIsLoading:v,setSnackbar:y,redirectLocation:S,isInfo:n}){const[r,I]=j.useState(!1),[l,h]=j.useState({clientId:"",clientSecret:""}),w=E(P),A=()=>{T(t,i,"fetch"),N(2),document.querySelector(".btcd-s-wrp").scrollTop=0},c=d=>{const x=o({},t),p=o({},l);p[d.target.name]="",x[d.target.name]=d.target.value,h(p),i(x)};return e.jsxs("div",{className:"btcd-stp-page",style:{width:m===1&&900,height:m===1&&"auto"},children:[e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:s("Integration Name:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:c,name:"name",value:t.name,type:"text",placeholder:s("Integration Name...","bit-integrations"),disabled:n}),e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:s("Authorized Redirect URIs:","bit-integrations")})}),e.jsx(f,{value:S||`${w.api.base}/redirect`,className:"field-key-cpy w-6 ml-0",readOnly:n,setSnackbar:y}),e.jsxs("small",{className:"d-blk mt-3",children:[s("To Get Client Id & Secret, Please Visit","bit-integrations")," ",e.jsx("a",{className:"btcd-link",href:"https://docs.pcloud.com/my_apps/",target:"_blank",rel:"noreferrer",children:s("pCloud API apps","bit-integrations")})]}),e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:s("PCloud Client Id:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:c,name:"clientId",value:t.clientId,type:"text",placeholder:s("Client Id...","bit-integrations"),disabled:n}),e.jsx("div",{style:{color:"red"},children:l.clientId}),e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:s("PCloud Client Secret:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:c,name:"clientSecret",value:t.clientSecret,type:"text",placeholder:s("Client Secret...","bit-integrations"),disabled:n}),e.jsx("div",{style:{color:"red"},children:l.clientSecret}),!n&&e.jsxs(e.Fragment,{children:[e.jsxs("button",{onClick:()=>C(t,i,I,v,h),className:"btn btcd-btn-lg green sh-sm flx",type:"button",disabled:r||b,children:[r?s("Authorized ✔","bit-integrations"):s("Authorize","bit-integrations"),b&&e.jsx(R,{size:"20",clr:"#022217",className:"ml-2"})]}),e.jsx("br",{}),e.jsxs("button",{onClick:A,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",disabled:!r,children:[s("Next","bit-integrations"),e.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]})]})}export{B as default};
