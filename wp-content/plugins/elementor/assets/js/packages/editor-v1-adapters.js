(function(){"use strict";var __webpack_modules__=({"react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
(function(module){module.exports=window["React"];})});var __webpack_module_cache__={};function __webpack_require__(moduleId){var cachedModule=__webpack_module_cache__[moduleId];if(cachedModule!==undefined){return cachedModule.exports;}
var module=__webpack_module_cache__[moduleId]={exports:{}};__webpack_modules__[moduleId](module,module.exports,__webpack_require__);return module.exports;}!function(){__webpack_require__.d=function(exports,definition){for(var key in definition){if(__webpack_require__.o(definition,key)&&!__webpack_require__.o(exports,key)){Object.defineProperty(exports,key,{enumerable:true,get:definition[key]});}}};}();!function(){__webpack_require__.o=function(obj,prop){return Object.prototype.hasOwnProperty.call(obj,prop);}}();!function(){__webpack_require__.r=function(exports){if(typeof Symbol!=='undefined'&&Symbol.toStringTag){Object.defineProperty(exports,Symbol.toStringTag,{value:'Module'});}
Object.defineProperty(exports,'__esModule',{value:true});};}();var __webpack_exports__={};!function(){
/*!*******************************************************************!*\
  !*** ./node_modules/@elementor/editor-v1-adapters/dist/index.mjs ***!
  \*******************************************************************/
__webpack_require__.r(__webpack_exports__);__webpack_require__.d(__webpack_exports__,{"commandEndEvent":function(){return commandEndEvent;},"commandStartEvent":function(){return commandStartEvent;},"dispatchReadyEvent":function(){return dispatchReadyEvent;},"editModeChangeEvent":function(){return editModeChangeEvent;},"flushListeners":function(){return flushListeners;},"getCurrentEditMode":function(){return getCurrentEditMode;},"isReady":function(){return isReady;},"isRouteActive":function(){return isRouteActive;},"listenTo":function(){return listenTo;},"openRoute":function(){return openRoute;},"routeCloseEvent":function(){return routeCloseEvent;},"routeOpenEvent":function(){return routeOpenEvent;},"runCommand":function(){return runCommand;},"setReady":function(){return setReady;},"useIsPreviewMode":function(){return useIsPreviewMode;},"useIsRouteActive":function(){return useIsRouteActive;},"useListenTo":function(){return useListenTo;},"useRouteStatus":function(){return useRouteStatus;},"v1ReadyEvent":function(){return v1ReadyEvent;},"windowEvent":function(){return windowEvent;}});var react__WEBPACK_IMPORTED_MODULE_0__=__webpack_require__(/*! react */"react");function isJQueryDeferred(value){return!!value&&"object"===typeof value&&Object.hasOwn(value,"promise")&&Object.hasOwn(value,"then")&&Object.hasOwn(value,"fail");}
function promisifyJQueryDeferred(deferred){return new Promise((resolve,reject)=>{deferred.then(resolve,reject);});}
function runCommand(command,args){const extendedWindow=window;if(!extendedWindow.$e?.run){return Promise.reject("`$e.run()` is not available");}
const result=extendedWindow.$e.run(command,args);if(result instanceof Promise){return result;}
if(isJQueryDeferred(result)){return promisifyJQueryDeferred(result);}
return Promise.resolve(result);}
function openRoute(route){const extendedWindow=window;if(!extendedWindow.$e?.route){return Promise.reject("`$e.route()` is not available");}
try{return Promise.resolve(extendedWindow.$e.route(route));}catch(e){return Promise.reject(e);}}
var commandStartEvent=(command)=>{return{type:"command",name:command,state:"before"};};var commandEndEvent=(command)=>{return{type:"command",name:command,state:"after"};};var routeOpenEvent=(route)=>{return{type:"route",name:route,state:"open"};};var routeCloseEvent=(route)=>{return{type:"route",name:route,state:"close"};};var windowEvent=(event)=>{return{type:"window-event",name:event};};var v1ReadyEvent=()=>{return windowEvent("elementor/initialized");};var editModeChangeEvent=()=>{return windowEvent("elementor/edit-mode/change");};var ready=false;function isReady(){return ready;}
function setReady(value){ready=value;}
function dispatchReadyEvent(){return getV1LoadingPromise().then(()=>{setReady(true);window.dispatchEvent(new CustomEvent("elementor/initialized"));});}
function getV1LoadingPromise(){const v1LoadingPromise=window.__elementorEditorV1LoadingPromise;if(!v1LoadingPromise){return Promise.reject("Elementor Editor V1 is not loaded");}
return v1LoadingPromise;}
function normalizeEvent(e){if(e instanceof CustomEvent&&e.detail?.command){return{type:"command",command:e.detail.command,args:e.detail.args,originalEvent:e};}
if(e instanceof CustomEvent&&e.detail?.route){return{type:"route",route:e.detail.route,originalEvent:e};}
return{type:"window-event",event:e.type,originalEvent:e};}
var callbacksByEvent=new Map();var abortController=new AbortController();function listenTo(eventDescriptors,callback){if(!Array.isArray(eventDescriptors)){eventDescriptors=[eventDescriptors];}
const cleanups=eventDescriptors.map((event)=>{const{type,name}=event;switch(type){case"command":return registerCommandListener(name,event.state,callback);case"route":return registerRouteListener(name,event.state,callback);case"window-event":return registerWindowEventListener(name,callback);}});return()=>{cleanups.forEach((cleanup)=>cleanup());};}
function flushListeners(){abortController.abort();callbacksByEvent.clear();setReady(false);abortController=new AbortController();}
function registerCommandListener(command,state,callback){return registerWindowEventListener(`elementor/commands/run/${state}`,(e)=>{const shouldRunCallback=e.type==="command"&&e.command===command;if(shouldRunCallback){callback(e);}});}
function registerRouteListener(route,state,callback){return registerWindowEventListener(`elementor/routes/${state}`,(e)=>{const shouldRunCallback=e.type==="route"&&e.route.startsWith(route);if(shouldRunCallback){callback(e);}});}
function registerWindowEventListener(event,callback){const isFirstListener=!callbacksByEvent.has(event);if(isFirstListener){callbacksByEvent.set(event,[]);addListener(event);}
callbacksByEvent.get(event)?.push(callback);return()=>{const callbacks=callbacksByEvent.get(event);if(!callbacks?.length){return;}
const filtered=callbacks.filter((cb)=>cb!==callback);callbacksByEvent.set(event,filtered);};}
function addListener(event){window.addEventListener(event,makeEventHandler(event),{signal:abortController.signal});}
function makeEventHandler(event){return(e)=>{if(!isReady()){return;}
const normalizedEvent=normalizeEvent(e);callbacksByEvent.get(event)?.forEach((callback)=>{callback(normalizedEvent);});};}
function useListenTo(event,getSnapshot,deps=[]){const[snapshot,setSnapshot]=(0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(()=>getSnapshot());(0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(()=>{const updateState=()=>setSnapshot(getSnapshot());updateState();return listenTo(event,updateState);},deps);return snapshot;}
function isRouteActive(route){const extendedWindow=window;return!!extendedWindow.$e?.routes?.isPartOf(route);}
function getCurrentEditMode(){const extendedWindow=window;return extendedWindow.elementor?.channels?.dataEditMode?.request?.("activeMode");}
function useIsPreviewMode(){return useListenTo(editModeChangeEvent(),()=>getCurrentEditMode()==="preview");}
function useIsRouteActive(route){return useListenTo([routeOpenEvent(route),routeCloseEvent(route)],()=>isRouteActive(route),[route]);}
function useRouteStatus(route,{blockOnKitRoutes=true,blockOnPreviewMode=true}={}){const isRouteActive2=useIsRouteActive(route);const isKitRouteActive=useIsRouteActive("panel/global");const isPreviewMode=useIsPreviewMode();const isActive=isRouteActive2&&!(blockOnPreviewMode&&isPreviewMode);const isBlocked=blockOnPreviewMode&&isPreviewMode||blockOnKitRoutes&&isKitRouteActive;return{isActive,isBlocked};}}();(window.__UNSTABLE__elementorPackages=window.__UNSTABLE__elementorPackages||{}).editorV1Adapters=__webpack_exports__;})();