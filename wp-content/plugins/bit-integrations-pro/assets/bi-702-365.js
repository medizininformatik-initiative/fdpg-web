var I=Object.defineProperty;var y=Object.getOwnPropertySymbols;var P=Object.prototype.hasOwnProperty,C=Object.prototype.propertyIsEnumerable;var A=(i,t,s)=>t in i?I(i,t,{enumerable:!0,configurable:!0,writable:!0,value:s}):i[t]=s,m=(i,t)=>{for(var s in t||(t={}))P.call(t,s)&&A(i,s,t[s]);if(y)for(var s of y(t))C.call(t,s)&&A(i,s,t[s]);return i};import{r as b,j as e}from"./main.js";import{B as E}from"./bi-475-763.js";import{_ as a,E as f,ad as T,b as K}from"./bi-490-178.js";import{a as F}from"./bi-541-874.js";function V({formID:i,convertKitConf:t,setConvertKitConf:s,step:u,setstep:N,setSnackbar:x,isInfo:d,isLoading:c,setIsLoading:h}){const[o,v]=b.useState(!1),[p,g]=b.useState({name:"",api_secret:""}),[S,_]=b.useState(!1),z=()=>{const r=m({},t);if(!r.name||!r.api_secret){g({name:r.name?"":a("Integration name cann't be empty","bit-integrations"),api_secret:r.api_secret?"":a("Access API Secret Key cann't be empty","bit-integrations")});return}h("auth");const l={api_secret:r.api_secret};K(l,"convertKit_authorize").then(n=>{n!=null&&n.success&&(v(!0),x({show:!0,msg:a("Authorized Successfully","bit-integrations")})),_(!0),h(!1)})},j=r=>{const l=m({},t),n=m({},p);n[r.target.name]="",l[r.target.name]=r.target.value,g(n),s(l)},k=()=>{setTimeout(()=>{document.getElementById("btcd-settings-wrp").scrollTop=0},300),F(t,s,h,x),N(2)},w=`
            <h4>Get api secret key</h4>
            <ul>
                <li>First go to your ConvertKit dashboard.</li>
                <li>Click "Settings", Then click "Advanced"</li>
            </ul>`;return e.jsxs("div",{className:"btcd-stp-page",style:{width:u===1&&900,height:u===1&&"auto"},children:[e.jsx("div",{className:"mt-3 wdt-200",children:e.jsx("b",{children:a("Integration Name:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:j,name:"name",value:t.name,type:"text",placeholder:a("Integration Name...","bit-integrations"),disabled:d}),e.jsx("div",{style:{color:"red",fontSize:"15px"},children:p.name}),e.jsx("div",{className:"mt-3 wdt-200",children:e.jsx("b",{children:a("Access API Secret Key:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:j,name:"api_secret",value:t.api_secret,type:"text",placeholder:a("Access API Secret Key...","bit-integrations"),disabled:d}),e.jsx("div",{style:{color:"red",fontSize:"15px"},children:p.api_secret}),e.jsxs("small",{className:"d-blk mt-3",children:[a("To Get API Secret Key, Please Visit","bit-integrations")," ",e.jsx("a",{className:"btcd-link",href:"https://app.convertkit.com/account_settings/advanced_settings",target:"_blank",rel:"noreferrer",children:a("ConvertKit API Token","bit-integrations")})]}),e.jsx("br",{}),e.jsx("br",{}),c==="auth"&&e.jsxs("div",{className:"flx mt-5",children:[e.jsx(f,{size:25,clr:"#022217",className:"mr-2"}),"Checking API Secret Key!!!"]}),S&&!o&&!c&&e.jsxs("div",{className:"flx mt-5",style:{color:"red"},children:[e.jsx("span",{className:"btcd-icn mr-2",style:{fontSize:30,marginTop:-5},children:"×"}),"Sorry, API Secret key is invalid"]}),!d&&e.jsxs(e.Fragment,{children:[e.jsxs("button",{onClick:z,className:"btn btcd-btn-lg green sh-sm flx",type:"button",disabled:o||c,children:[o?a("Authorized ✔","bit-integrations"):a("Authorize","bit-integrations"),c&&e.jsx(f,{size:20,clr:"#022217",className:"ml-2"})]}),e.jsx("br",{}),e.jsxs("button",{onClick:()=>k(),className:"btn f-right btcd-btn-lg green sh-sm flx",type:"button",disabled:!o,children:[a("Next","bit-integrations"),e.jsx(E,{className:"ml-1 rev-icn"})]})]}),e.jsx(T,{note:w})]})}export{V as default};
