var P=Object.defineProperty;var L=Object.getOwnPropertySymbols;var F=Object.prototype.hasOwnProperty,V=Object.prototype.propertyIsEnumerable;var v=(i,e,r)=>e in i?P(i,e,{enumerable:!0,configurable:!0,writable:!0,value:r}):i[e]=r,j=(i,e)=>{for(var r in e||(e={}))F.call(e,r)&&v(i,r,e[r]);if(L)for(var r of L(e))V.call(e,r)&&v(i,r,e[r]);return i};import{j as t,r as d,_ as U,L as Z}from"./main.js";import{L as p,C as q,u as G,_ as o,S as E,b as y,a as x,c as J,d as K,T as Q,M as X}from"./bi-490-178.js";import{E as Y}from"./bi-826-717.js";import{T as tt}from"./bi-422-718.js";function et({size:i=30,stroke:e=2}){return t.jsxs("svg",{width:i,height:i,viewBox:"0 0 30 30",children:[t.jsx("ellipse",{className:"svg-icn",strokeWidth:e,strokeMiterlimit:"10",cx:"15",cy:"15",rx:"11",ry:"11.09"}),t.jsx("ellipse",{className:"svg-icn",strokeWidth:2,strokeMiterlimit:"10",cx:"15",cy:"11",rx:"1",ry:"1"}),t.jsx("line",{className:"svg-icn",strokeWidth:e-.5,strokeLinecap:"round",strokeLinejoin:"round",x1:"15",y1:"18",x2:"15",y2:"21.72"})]})}function st({size:i}){return t.jsxs("svg",{id:"Layer_1","data-name":"Layer 1",xmlns:"http://www.w3.org/2000/svg",width:i,height:i,viewBox:"0 0 215 215",children:[t.jsx("rect",{className:"cls-1",fill:"none",strokeWidth:"16",stroke:"currentColor",strokeMiterlimit:"10",x:"19.91",y:"20.9",width:"174",height:"174",rx:"85"}),t.jsx("path",{fill:"currentColor",className:"cls-2",stroke:"currentColor",strokeMiterlimit:"10",strokeWidth:"4",d:"M138.3,151.06,99.23,113.52a6,6,0,0,1-1.85-4.34V56.47a6,6,0,0,1,6-6h0a6,6,0,0,1,6,6v47.59a6,6,0,0,0,1.85,4.33l35.37,34a6,6,0,0,1,.17,8.51h0A6,6,0,0,1,138.3,151.06Z"})]})}function nt(i){const e=d.useRef(null),r=d.useRef(null),[c,g]=d.useState(!1),w=f=>{c&&e.current&&!e.current.contains(f.target)&&g(!1),document.removeEventListener("click",w)};return d.useEffect(()=>{r.current.parentNode.parentNode.parentNode.style.zIndex=c?"10":"auto",c||r.current.blur(),document.addEventListener("click",w)},[c]),t.jsxs("div",{className:"btcd-menu",children:[t.jsx("button",{ref:r,className:"btcd-menu-btn btcd-mnu sh-sm",onClick:()=>g(f=>!f),"aria-label":"toggle menu",type:"button"}),t.jsxs("div",{ref:e,className:`btcd-menu-list ${c?"btcd-m-a":""}`,children:[t.jsxs(p,{to:`/flow/action/info/${i.id}/${i.name}`,type:"button",className:"flx","aria-label":"actions",children:[t.jsx(et,{size:16}),"  Info"]}),t.jsxs(p,{to:`/flow/action/log/${i.id}/${i.name}`,type:"button",className:"flx","aria-label":"actions",children:[t.jsx(st,{size:16}),"  Timeline"]}),t.jsxs(p,{to:`/flow/action/edit/${i.id}`,type:"button",className:"flx","aria-label":"actions",children:[t.jsx(Y,{size:16}),"  Edit"]}),t.jsxs("button",{type:"button","aria-label":"actions",className:"flx",onClick:i.dup,children:[t.jsx(q,{size:16}),"  Clone"]}),t.jsxs("button",{type:"button","aria-label":"actions",className:"flx",onClick:i.del,children:[t.jsx(tt,{size:16}),"  Delete"]})]})]})}const it=d.lazy(()=>U(()=>import("./bi-497-716.js"),["./bi-497-716.js","./main.js","./bi-490-178.js","./bi-979-928.css"],import.meta.url));function at({isLicenseActive:i}){var T;const{data:e,isLoading:r}=G({payload:{},action:"flow/list",method:"get"}),[c,g]=d.useState(!r&&e.success&&((T=e==null?void 0:e.data)!=null&&T.integrations)?e.data.integrations:[]),[w,f]=d.useState({show:!1}),[a,k]=d.useState({show:!1,btnTxt:""});d.useState({show:!1,msg:""});const[D,N]=d.useState({show:i===!1}),[I,_]=d.useState([{width:250,minWidth:80,Header:o("Trigger","bit-integrations"),accessor:"triggered_entity"},{width:250,minWidth:80,Header:o("Action Name","bit-integrations"),accessor:"name"},{width:200,minWidth:200,Header:o("Created At","bit-integrations"),accessor:"created_at"},{width:70,minWidth:60,Header:o("Status","bit-integrations"),accessor:"status",Cell:s=>t.jsx(E,{className:"flx",action:n=>M(n,s.row.original.id),checked:Number(s.row.original.status)===1})}]);d.useEffect(()=>{var s;!r&&e.success&&((s=e==null?void 0:e.data)!=null&&s.integrations)&&g(e.data.integrations)},[e]),d.useEffect(()=>{const s=I.filter(n=>n.accessor!=="t_action"&&n.accessor!=="status");s.push({width:70,minWidth:60,Header:o("Status","bit-integrations"),accessor:"status",Cell:n=>t.jsx(E,{className:"flx",action:u=>M(u,n.row.original.id),checked:Number(n.row.original.status)===1})}),s.push({sticky:"right",width:100,minWidth:60,Header:"Actions",accessor:"t_action",Cell:n=>t.jsx(nt,{id:n.row.original.id,name:n.row.original.name,index:n.row.id,del:()=>O(n.row.original.id,n.row.index),dup:()=>z(n.row.original.id,n.row.index)})}),_([...s])},[c]);const M=(s,n)=>{const u=s.target.checked,h=[...c],l=h.find(m=>m.id===n);l.status=u===!0?"1":"0",g(h),y({id:n,status:u},"flow/toggleStatus").then(m=>{x.success(o(m.data,"bit-integrations"))}).catch(()=>{x.error(o("Something went wrong","bit-integrations"))})},W=(s,n)=>{const u=y({id:s},"flow/delete").then(h=>{if(h.success){const l=[...c];return l.splice(n,1),g(l),"Integration deleted successfully"}return h.data});x.promise(u,{success:h=>h,error:o("Error Occurred","bit-integrations"),loading:o("delete...")})},A=s=>{const n=y({id:s},"flow/clone").then(u=>{if(u.success){const h=u.data,l=[...c],b=l.find(R=>R.id===s),m={id:h.id,name:`duplicate of ${b.name}`,triggered_entity:b.triggered_entity,status:b.status,created_at:h.created_at};return l.push(m),g(l),"Integration clone successfully"}return u.data});x.promise(n,{success:u=>u,error:o("Error Occurred","bit-integrations"),loading:o("cloning...")})},$=d.useCallback(s=>{const n=[],u=[];for(let l=0;l<s.length;l+=1)n.push(s[l].id),u.push(s[l].original.id);const h=y({flowID:u},"flow/bulk-delete").then(l=>{if(l.success){const b=[...c];for(let m=n.length-1;m>=0;m-=1)b.splice(Number(n[m]),1);return g(b),"Integration Deleted Successfully"}return l.data});x.promise(h,{success:l=>l,error:o("Error Occurred","bit-integrations"),loading:o("delete...")})},[c]),H=d.useCallback(s=>{_(s)},[]),C=()=>{a.show=!1,k(j({},a))},O=(s,n)=>{a.action=()=>{W(s,n),C()},a.btnTxt=o("Delete","bit-integrations"),a.btn2Txt=null,a.btnClass="",a.body=o("Are you sure to delete this Integration?","bit-integrations"),a.show=!0,k(j({},a))},z=s=>{a.action=()=>{A(s),C()},a.btnTxt=o("Clone","bit-integration"),a.btn2Txt=null,a.btnClass="blue",a.body=o("Are you sure to clone this Integration ?","bitform"),a.show=!0,k(j({},a))},B={display:"flex",height:"82vh",justifyContent:"center",alignItems:"center"},S=s=>{N({show:!0})};return r?t.jsx(Z,{style:B}):t.jsxs("div",{id:"all-forms",children:[t.jsx(J,{snack:w,setSnackbar:f}),t.jsx(K,{show:a.show,body:a.body,action:a.action,close:C,btnTxt:a.btnTxt,btn2Txt:a.btn2Txt,btn2Action:a.btn2Action,btnClass:a.btnClass}),c&&(c!=null&&c.length)?t.jsxs(t.Fragment,{children:[t.jsxs("div",{className:"af-header flx flx-between",children:[t.jsx("h2",{children:o("Integrations","bit-integrations")}),i?t.jsx(p,{to:"/flow/new",className:"btn round btcd-btn-lg dp-blue",children:o("Create Integration","bit-integrations")}):t.jsx("button",{className:"btn round btcd-btn-lg dp-blue",onClick:s=>S(),children:o("Create Integration","bit-integrations")})]}),t.jsx("div",{className:"forms",children:t.jsx(Q,{className:"f-table btcd-all-frm",height:500,columns:I,data:c,rowSeletable:!0,resizable:!0,columnHidable:!0,setTableCols:H,setBulkDelete:$,search:!0})})]}):t.jsx(it,{isLicenseActive:i,actionHandler:S}),t.jsxs(X,{sm:!0,show:D.show,setModal:()=>N({show:!1}),title:o("Please active your license","bit-integrations"),className:"pro-modal",children:[t.jsx("h4",{className:"txt-center mt-5",children:o("Please active your license to make unlimited integrations .","bit-integrations")}),t.jsx("div",{className:"txt-center",children:t.jsx("a",{href:`${window.btcbi.licenseURL}`,rel:"noreferrer",children:t.jsx("button",{className:"btn btn-lg blue",type:"button",children:o("Active license","bit-integrations")})})})]})]})}var ut=d.memo(at);export{ut as default};
