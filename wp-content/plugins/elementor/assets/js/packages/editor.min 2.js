!function(){"use strict";var e={d:function(t,n){for(var o in n)e.o(n,o)&&!e.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:n[o]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r:function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t),e.d(t,{init:function(){return w},injectIntoTop:function(){return d}});var n=window.__UNSTABLE__elementorPackages.locations,o=window.React,r=window.ReactDOM,i=window.wp.i18n,c=window.__UNSTABLE__elementorPackages.editorDocuments,a=window.__UNSTABLE__elementorPackages.ui,s=window.__UNSTABLE__elementorPackages.store,u=window.__UNSTABLE__elementorPackages.editorV1Adapters,{Slot:l,inject:d}=(0,n.createLocation)();function m(){return function(){const e=(0,c.useActiveDocument)(),t=(0,c.useHostDocument)(),n=e&&"kit"!==e.type.value?e:t;(0,o.useEffect)((()=>{if(void 0===n?.title)return;const e=(0,i.__)('Edit "%s" with Elementor',"elementor").replace("%s",n.title);window.document.title=e}),[n?.title])}(),function(){const e=(0,c.useHostDocument)();(0,o.useEffect)((()=>{if(!e?.id)return;const t=new URL(window.location.href);t.searchParams.set("post",e.id.toString()),t.searchParams.delete("active-document"),history.replaceState({},"",t)}),[e?.id])}(),o.createElement(l,null)}function _(){return window.elementor?.getPreferences?.("ui_theme")||"auto"}function f({children:e}){const t=function(){const[e,t]=(0,o.useState)((()=>_()));return(0,o.useEffect)((()=>(0,u.listenTo)((0,u.v1ReadyEvent)(),(()=>t(_())))),[]),(0,o.useEffect)((()=>(0,u.listenTo)((0,u.commandEndEvent)("document/elements/settings"),(e=>{const n=e;n.args?.settings&&"ui_theme"in n.args.settings&&t(_())}))),[]),e}();return o.createElement(a.ThemeProvider,{colorScheme:t},e)}function w(e){const t=(0,s.createStore)();(0,u.dispatchReadyEvent)(),r.render(o.createElement(s.StoreProvider,{store:t},o.createElement(a.DirectionProvider,{rtl:(0,i.isRTL)()},o.createElement(f,null,o.createElement(m,null)))),e)}(window.__UNSTABLE__elementorPackages=window.__UNSTABLE__elementorPackages||{}).editor=t}();