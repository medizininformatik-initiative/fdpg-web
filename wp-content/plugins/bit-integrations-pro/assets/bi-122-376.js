var A=Object.defineProperty;var u=Object.getOwnPropertySymbols;var _=Object.prototype.hasOwnProperty,k=Object.prototype.propertyIsEnumerable;var x=(e,a,s)=>a in e?A(e,a,{enumerable:!0,configurable:!0,writable:!0,value:s}):e[a]=s,o=(e,a)=>{for(var s in a||(a={}))_.call(a,s)&&x(e,s,a[s]);if(u)for(var s of u(a))k.call(a,s)&&x(e,s,a[s]);return e};import{r as g,j as t}from"./main.js";import{_ as i,E as w}from"./bi-490-178.js";import{z as I}from"./bi-576-895.js";function f({zendeskConf:e,setZendeskConf:a,step:s,setStep:j,loading:r,setLoading:N,isInfo:n}){const[l,y]=g.useState(!1),[m,d]=g.useState({api_key:""}),v=()=>{e!=null&&e.default,j(2),document.querySelector(".btcd-s-wrp").scrollTop=0},b=c=>{const p=o({},e),h=o({},m);h[c.target.name]="",p[c.target.name]=c.target.value,d(h),a(p)};return t.jsxs("div",{className:"btcd-stp-page",style:{width:s===1&&900,height:s===1&&"auto"},children:[t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:i("Integration Name:","bit-integrations")})}),t.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:b,name:"name",value:e.name,type:"text",placeholder:i("Integration Name...","bit-integrations"),disabled:n}),t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:i("API Key:","bit-integrations")})}),t.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:b,name:"api_key",value:e.api_key,type:"text",placeholder:i("API Token...","bit-integrations"),disabled:n}),t.jsx("div",{style:{color:"red",fontSize:"15px"},children:m.api_key}),t.jsxs("small",{className:"d-blk mt-3",children:[i("To Get API token, Please Visit","bit-integrations")," ",t.jsx("a",{className:"btcd-link",href:"https://app.futuresimple.com/settings/oauth",target:"_blank",rel:"noreferrer",children:i("Zendesk API Token","bit-integrations")})]}),t.jsx("br",{}),t.jsx("br",{}),!n&&t.jsxs("div",{children:[t.jsxs("button",{onClick:()=>I(e,a,d,y,r,N),className:"btn btcd-btn-lg green sh-sm flx",type:"button",disabled:l||r.auth,children:[l?i("Authorized ✔","bit-integrations"):i("Authorize","bit-integrations"),r.auth&&t.jsx(w,{size:"20",clr:"#022217",className:"ml-2"})]}),t.jsx("br",{}),t.jsxs("button",{onClick:v,className:"btn ml-auto btcd-btn-lg green sh-sm flx",type:"button",disabled:!l,children:[i("Next","bit-integrations"),t.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]})]})}export{f as default};
