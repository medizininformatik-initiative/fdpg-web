var v=Object.defineProperty;var g=Object.getOwnPropertySymbols;var y=Object.prototype.hasOwnProperty,z=Object.prototype.propertyIsEnumerable;var x=(n,t,s)=>t in n?v(n,t,{enumerable:!0,configurable:!0,writable:!0,value:s}):n[t]=s,l=(n,t)=>{for(var s in t||(t={}))y.call(t,s)&&x(n,s,t[s]);if(g)for(var s of g(t))z.call(t,s)&&x(n,s,t[s]);return n};import{r as j,j as e}from"./main.js";import{_ as a,E as S,ad as _}from"./bi-490-178.js";import{a as E}from"./bi-935-782.js";function q({formID:n,slackConf:t,setSlackConf:s,step:o,setstep:k,isLoading:d,setIsLoading:N,setSnackbar:A,redirectLocation:I,isInfo:i}){const[r,w]=j.useState(!1),[m,b]=j.useState({accessToken:""}),T=()=>{k(2),document.querySelector(".btcd-s-wrp").scrollTop=0},h=c=>{const p=l({},t),u=l({},m);u[c.target.name]="",p[c.target.name]=c.target.value,b(u),s(p)},f=`
            <h4>Get Access Token few step</h4>
            <ul>
                <li>First create app.</li>
                <li>Add an OAuth Scope <b>'channels:read, channels:write, chat:write, files:read, files:write'</b>.</li>
                <li>Generate Access Token clicking <b> 'install to Workspace'</b>.</li>
            </ul>`;return e.jsxs("div",{className:"btcd-stp-page",style:{width:o===1&&900,height:o===1&&"auto"},children:[e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:a("Integration Name:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:h,name:"name",value:t.name,type:"text",placeholder:a("Integration Name...","bit-integrations"),disabled:i}),e.jsxs("small",{className:"d-blk mt-5",children:[a("To get access Token , Please Visit","bit-integrations")," ",e.jsx("a",{className:"btcd-link",href:"https://api.slack.com/apps?new_app=1/",target:"_blank",rel:"noreferrer",children:a("Slack Console","bit-integrations")})]}),e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:a("Access Token:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:h,name:"accessToken",value:t.accessToken,type:"text",placeholder:a("Access Token...","bit-integrations"),disabled:i}),e.jsx("div",{style:{color:"red"},children:m.accessToken}),!i&&e.jsxs(e.Fragment,{children:[e.jsxs("button",{onClick:()=>E(t,s,b,w,N,A),className:"btn btcd-btn-lg green sh-sm flx",type:"button",disabled:r||d,children:[r?a("Authorized ✔","bit-integrations"):a("Authorize","bit-integrations"),d&&e.jsx(S,{size:"20",clr:"#022217",className:"ml-2"})]}),e.jsx("br",{}),e.jsxs("button",{onClick:T,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",disabled:!r,children:[a("Next","bit-integrations"),e.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),e.jsx(_,{note:f})]})}export{q as default};
