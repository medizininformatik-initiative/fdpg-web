var z=Object.defineProperty;var x=Object.getOwnPropertySymbols;var S=Object.prototype.hasOwnProperty,_=Object.prototype.propertyIsEnumerable;var g=(t,a,s)=>a in t?z(t,a,{enumerable:!0,configurable:!0,writable:!0,value:s}):t[a]=s,o=(t,a)=>{for(var s in a||(a={}))S.call(a,s)&&g(t,s,a[s]);if(x)for(var s of x(a))_.call(a,s)&&g(t,s,a[s]);return t};import{r as j,j as e}from"./main.js";import{_ as i,E as I,ad as P}from"./bi-490-178.js";import{b as K,a as w}from"./bi-544-818.js";function q({vboutConf:t,setVboutConf:a,step:s,setstep:y,loading:n,setLoading:d,setSnackbar:f,isInfo:r}){const[l,A]=j.useState(!1),[h,m]=j.useState({name:"",auth_token:""}),N=()=>{t!=null&&t.default,y(2),t.list_id||w(t,a,n,d),document.querySelector(".btcd-s-wrp").scrollTop=0},b=c=>{const p=o({},t),u=o({},h);u[c.target.name]="",p[c.target.name]=c.target.value,m(u),a(p)},k=`
    <h4> Step of get API Key:</h4>
    <ul>
      <li>Goto Settings and click on <a href="https://app.vbout.com/Settings">API Integrations</a></li>
      <li>Copy the <b>Key</b> and paste into <b>API Key</b> field of your authorization form.</li>
      <li>Finally, click <b>Authorize</b> button.</li>
  </ul>
  `;return e.jsxs("div",{className:"btcd-stp-page",style:{width:s===1&&900,height:s===1&&"auto"},children:[e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:i("Integration Name:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:b,name:"name",value:t.name,type:"text",placeholder:i("Integration Name...","bit-integrations"),disabled:r}),e.jsxs("small",{className:"d-blk mt-3",children:[i("To Get API Key, Please Visit","bit-integrations")," ",e.jsx("a",{className:"btcd-link",href:"https://app.vbout.com/Settings",target:"_blank",rel:"noreferrer",children:i("Vbout API Key","bit-integrations")})]}),e.jsx("div",{className:"mt-3",children:e.jsx("b",{children:i("API Key:","bit-integrations")})}),e.jsx("input",{className:"btcd-paper-inp w-6 mt-1",onChange:b,name:"auth_token",value:t.auth_token,type:"text",placeholder:i("API Key...","bit-integrations"),disabled:r}),e.jsx("div",{style:{color:"red",fontSize:"15px"},children:h.auth_token}),!r&&e.jsxs("div",{children:[e.jsxs("button",{onClick:()=>K(t,a,m,A,n,d),className:"btn btcd-btn-lg green sh-sm flx",type:"button",disabled:l||n.auth,children:[l?i("Authorized ✔","bit-integrations"):i("Authorize","bit-integrations"),n.auth&&e.jsx(I,{size:"20",clr:"#022217",className:"ml-2"})]}),e.jsx("br",{}),e.jsxs("button",{onClick:N,className:"btn ml-auto btcd-btn-lg green sh-sm flx",type:"button",disabled:!l,children:[i("Next","bit-integrations"),e.jsx("div",{className:"btcd-icn icn-arrow_back rev-icn d-in-b"})]})]}),e.jsx(P,{note:k})]})}export{q as default};
