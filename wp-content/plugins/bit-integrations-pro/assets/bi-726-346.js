var S=Object.defineProperty;var u=Object.getOwnPropertySymbols;var I=Object.prototype.hasOwnProperty,_=Object.prototype.propertyIsEnumerable;var x=(e,a,i)=>a in e?S(e,a,{enumerable:!0,configurable:!0,writable:!0,value:i}):e[a]=i,m=(e,a)=>{for(var i in a||(a={}))I.call(a,i)&&x(e,i,a[i]);if(u)for(var i of u(a))_.call(a,i)&&x(e,i,a[i]);return e};import{r as j,j as t}from"./main.js";import{B as R}from"./bi-475-763.js";import{_ as n,D as C,E as k,ad as T}from"./bi-490-178.js";import{c as E}from"./bi-283-838.js";function D({constantContactConf:e,setConstantContactConf:a,step:i,setstep:N,isLoading:p,setIsLoading:f,setSnackbar:l,redirectLocation:y,isInfo:s}){const[r,v]=j.useState(!1),[c,b]=j.useState({dataCenter:"",clientId:"",clientSecret:""}),z="account_read account_update contact_data offline_access campaign_data",A=()=>{setTimeout(()=>{document.getElementById("btcd-settings-wrp").scrollTop=0},300),N(2)},o=d=>{const h=m({},e),g=m({},c);g[d.target.name]="",h[d.target.name]=d.target.value,b(g),a(h)},w=`
  <h4> Step of get API Key(Client Id) And Client Secret:</h4>
  <ul>
    <li>Goto <a href="https://app.constantcontact.com/pages/dma/portal/?_ga=2.91540634.1868552181.1667660766-5cc88792-fd06-40a8-9b8c-e27659667215">Constant Contact Application</a></li>
    <li>Then create a new application.</li>
    <li>Select  <b>(Authorization Code Flow and Implicit Flow)</b> and <b>(Rotating Refresh Tokens or Long Lived Refresh Tokens).</b></li>
    <li>Copy the <b>Authorized Redirect URIs</b> from here and paste it into the Constant Contact application form.</li> 
    <li>Then generate <b>Client Secret</b> from the Constant Contact application</li>
    <li>Copy the <b>Client Id</b> and <b>Client Secret</b> from Constant Contact application and paste into this authorization form.</li>
    <li>Finally, click <b>Authorize</b> button.</li>
</ul>
`;return t.jsxs("div",{className:"btcd-stp-page",style:{width:i===1&&900,height:i===1&&"auto"},children:[t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:n("Integration Name:","bit-integrations")})}),t.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:o,name:"name",value:e.name,type:"text",placeholder:n("Integration Name...","bit-integrations"),disabled:s}),t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:n("Homepage URL:","bit-integrations")})}),t.jsx(C,{value:`${window.location.origin}`,className:"field-key-cpy w-6 ml-0",readOnly:s,setSnackbar:l}),t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:n("Authorized Redirect URIs:","bit-integrations")})}),t.jsx(C,{value:y||`${btcbi.api.base}/redirect`,setSnackbar:l,className:"field-key-cpy w-6 ml-0",readOnly:s}),t.jsxs("small",{className:"d-blk mt-5",children:[n("To get Client ID and SECRET , Please Visit","bit-integrations")," ",t.jsx("a",{className:"btcd-link",href:"https://app.constantcontact.com/pages/dma/portal/?_ga=2.91540634.1868552181.1667660766-5cc88792-fd06-40a8-9b8c-e27659667215",target:"_blank",rel:"noreferrer",children:n("Constant Contact Application","bit-integrations")})]}),t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:n("Client id:","bit-integrations")})}),t.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:o,name:"clientId",value:e.clientId,type:"text",placeholder:n("Client id...","bit-integrations"),disabled:s}),t.jsx("div",{style:{color:"red",fontSize:"15px"},children:c.clientId}),t.jsx("div",{className:"mt-3",children:t.jsx("b",{children:n("Client secret:","bit-integrations")})}),t.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:o,name:"clientSecret",value:e.clientSecret,type:"text",placeholder:n("Client secret...","bit-integrations"),disabled:s}),t.jsx("div",{style:{color:"red",fontSize:"15px"},children:c.clientSecret}),!s&&t.jsxs(t.Fragment,{children:[t.jsxs("button",{onClick:()=>E("constantContact","cContact",z,e,a,b,v,f,l,btcbi),className:"btn btcd-btn-lg green sh-sm flx",type:"button",disabled:r||p.auth,children:[r?n("Authorized ✔","bit-integrations"):n("Authorize","bit-integrations"),p.auth&&t.jsx(k,{size:20,clr:"#022217",className:"ml-2"})]}),t.jsx("br",{}),t.jsxs("button",{onClick:A,className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",disabled:!r,children:[n("Next","bit-integrations"),t.jsx(R,{className:"ml-1 rev-icn"})]})]}),t.jsx(T,{note:w})]})}export{D as default};
