var z=Object.defineProperty;var g=Object.getOwnPropertySymbols;var A=Object.prototype.hasOwnProperty,_=Object.prototype.propertyIsEnumerable;var u=(r,t,s)=>t in r?z(r,t,{enumerable:!0,configurable:!0,writable:!0,value:s}):r[t]=s,o=(r,t)=>{for(var s in t||(t={}))A.call(t,s)&&u(r,s,t[s]);if(g)for(var s of g(t))_.call(t,s)&&u(r,s,t[s]);return r};import{r as j,j as e}from"./main.js";import{_ as a,E}from"./bi-490-178.js";import{a as T}from"./bi-725-807.js";function D({formID:r,fluentSupportConf:t,setFluentSupportConf:s,step:m,setstep:N,isLoading:b,setIsLoading:w,setSnackbar:v,redirectLocation:U,isInfo:i}){const[n,y]=j.useState(!1),[l,h]=j.useState({api_key:""}),k=()=>{N(2),document.querySelector(".btcd-s-wrp").scrollTop=0},c=d=>{const p=o({},t),x=o({},l);x[d.target.name]="",p[d.target.name]=d.target.value,h(x),s(p)};return e.jsxs("div",{className:"btcd-stp-page",style:{width:m===1&&900,height:m===1&&"auto"},children:[e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:a("Integration Name:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:c,name:"name",value:t.name,type:"text",placeholder:a("Integration Name...","bit-integrations"),disabled:i}),e.jsxs("small",{className:"d-blk mt-5",children:[a("To get access Token , Please Visit","bit-integrations")," ",e.jsx("a",{className:"btcd-link",href:`${t.siteUrl}/wp-admin/users.php`,target:"_blank",rel:"noreferrer",children:a("Fluent Support Console","bit-integrations")})]}),e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:a("User Name:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:c,name:"userName",value:t.userName,type:"text",placeholder:a("User Name...","bit-integrations"),disabled:i}),e.jsx("div",{style:{color:"red"},children:l.userName}),e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:a("password:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:c,name:"password",value:t.password,type:"text",placeholder:a("password...","bit-integrations"),disabled:i}),e.jsx("div",{style:{color:"red"},children:l.password}),!i&&e.jsxs(e.Fragment,{children:[e.jsxs("button",{onClick:()=>T(t,s,h,y,w,v),className:"btn btcd-btn-lg green sh-sm flx",type:"button",disabled:n||b,children:[n?a("Authorized ✔","bit-integrations"):a("Authorize","bit-integrations"),b&&e.jsx(E,{size:"20",clr:"#022217",className:"ml-2"})]}),e.jsx("br",{}),e.jsxs("button",{onClick:k,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",disabled:!n,children:[a("Next","bit-integrations"),e.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]})]})}export{D as default};
