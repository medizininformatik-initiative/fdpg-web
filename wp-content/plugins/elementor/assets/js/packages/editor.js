(function(){"use strict";var __webpack_modules__=({"react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
(function(module){module.exports=window["React"];}),"react-dom":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
(function(module){module.exports=window["ReactDOM"];}),"@elementor/editor-documents":
/*!********************************************************************!*\
  !*** external ["__UNSTABLE__elementorPackages","editorDocuments"] ***!
  \********************************************************************/
(function(module){module.exports=window["__UNSTABLE__elementorPackages"]["editorDocuments"];}),"@elementor/editor-v1-adapters":
/*!*********************************************************************!*\
  !*** external ["__UNSTABLE__elementorPackages","editorV1Adapters"] ***!
  \*********************************************************************/
(function(module){module.exports=window["__UNSTABLE__elementorPackages"]["editorV1Adapters"];}),"@elementor/locations":
/*!**************************************************************!*\
  !*** external ["__UNSTABLE__elementorPackages","locations"] ***!
  \**************************************************************/
(function(module){module.exports=window["__UNSTABLE__elementorPackages"]["locations"];}),"@elementor/store":
/*!**********************************************************!*\
  !*** external ["__UNSTABLE__elementorPackages","store"] ***!
  \**********************************************************/
(function(module){module.exports=window["__UNSTABLE__elementorPackages"]["store"];}),"@elementor/ui":
/*!*******************************************************!*\
  !*** external ["__UNSTABLE__elementorPackages","ui"] ***!
  \*******************************************************/
(function(module){module.exports=window["__UNSTABLE__elementorPackages"]["ui"];}),"@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
(function(module){module.exports=window["wp"]["i18n"];})});var __webpack_module_cache__={};function __webpack_require__(moduleId){var cachedModule=__webpack_module_cache__[moduleId];if(cachedModule!==undefined){return cachedModule.exports;}
var module=__webpack_module_cache__[moduleId]={exports:{}};__webpack_modules__[moduleId](module,module.exports,__webpack_require__);return module.exports;}!function(){__webpack_require__.d=function(exports,definition){for(var key in definition){if(__webpack_require__.o(definition,key)&&!__webpack_require__.o(exports,key)){Object.defineProperty(exports,key,{enumerable:true,get:definition[key]});}}};}();!function(){__webpack_require__.o=function(obj,prop){return Object.prototype.hasOwnProperty.call(obj,prop);}}();!function(){__webpack_require__.r=function(exports){if(typeof Symbol!=='undefined'&&Symbol.toStringTag){Object.defineProperty(exports,Symbol.toStringTag,{value:'Module'});}
Object.defineProperty(exports,'__esModule',{value:true});};}();var __webpack_exports__={};!function(){
/*!*******************************************************!*\
  !*** ./node_modules/@elementor/editor/dist/index.mjs ***!
  \*******************************************************/
__webpack_require__.r(__webpack_exports__);__webpack_require__.d(__webpack_exports__,{"init":function(){return init;},"injectIntoTop":function(){return injectIntoTop;}});var _elementor_locations__WEBPACK_IMPORTED_MODULE_0__=__webpack_require__(/*! @elementor/locations */"@elementor/locations");var react__WEBPACK_IMPORTED_MODULE_1__=__webpack_require__(/*! react */"react");var react_dom__WEBPACK_IMPORTED_MODULE_2__=__webpack_require__(/*! react-dom */"react-dom");var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__=__webpack_require__(/*! @wordpress/i18n */"@wordpress/i18n");var _elementor_editor_documents__WEBPACK_IMPORTED_MODULE_4__=__webpack_require__(/*! @elementor/editor-documents */"@elementor/editor-documents");var _elementor_ui__WEBPACK_IMPORTED_MODULE_5__=__webpack_require__(/*! @elementor/ui */"@elementor/ui");var _elementor_store__WEBPACK_IMPORTED_MODULE_6__=__webpack_require__(/*! @elementor/store */"@elementor/store");var _elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_7__=__webpack_require__(/*! @elementor/editor-v1-adapters */"@elementor/editor-v1-adapters");var{Slot:TopSlot,inject:injectIntoTop}=(0,_elementor_locations__WEBPACK_IMPORTED_MODULE_0__.createLocation)();function useSyncDocumentTitle(){const activeDocument=(0,_elementor_editor_documents__WEBPACK_IMPORTED_MODULE_4__.useActiveDocument)();const hostDocument=(0,_elementor_editor_documents__WEBPACK_IMPORTED_MODULE_4__.useHostDocument)();const document=activeDocument&&activeDocument.type.value!=="kit"?activeDocument:hostDocument;(0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(()=>{if(document?.title===void 0){return;}
const title=(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Edit "%s" with Elementor',"elementor").replace("%s",document.title);window.document.title=title;},[document?.title]);}
function useSyncDocumentQueryParams(){const hostDocument=(0,_elementor_editor_documents__WEBPACK_IMPORTED_MODULE_4__.useHostDocument)();(0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(()=>{if(!hostDocument?.id){return;}
const url=new URL(window.location.href);url.searchParams.set("post",hostDocument.id.toString());url.searchParams.delete("active-document");history.replaceState({},"",url);},[hostDocument?.id]);}
function Shell(){useSyncDocumentTitle();useSyncDocumentQueryParams();return react__WEBPACK_IMPORTED_MODULE_1__.createElement(TopSlot,null);}
function useColorScheme(){const[colorScheme,setColorScheme]=(0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(()=>getV1ColorScheme());(0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(()=>{return(0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_7__.listenTo)((0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_7__.v1ReadyEvent)(),()=>setColorScheme(getV1ColorScheme()));},[]);(0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(()=>{return(0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_7__.listenTo)((0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_7__.commandEndEvent)("document/elements/settings"),(e)=>{const event=e;const isColorScheme=event.args?.settings&&"ui_theme"in event.args.settings;if(isColorScheme){setColorScheme(getV1ColorScheme());}});},[]);return colorScheme;}
function getV1ColorScheme(){return window.elementor?.getPreferences?.("ui_theme")||"auto";}
function ThemeProvider({children}){const colorScheme=useColorScheme();return react__WEBPACK_IMPORTED_MODULE_1__.createElement(_elementor_ui__WEBPACK_IMPORTED_MODULE_5__.ThemeProvider,{colorScheme},children);}
function init(domElement){const store=(0,_elementor_store__WEBPACK_IMPORTED_MODULE_6__.createStore)();(0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_7__.dispatchReadyEvent)();react_dom__WEBPACK_IMPORTED_MODULE_2__.render(react__WEBPACK_IMPORTED_MODULE_1__.createElement(_elementor_store__WEBPACK_IMPORTED_MODULE_6__.StoreProvider,{store},react__WEBPACK_IMPORTED_MODULE_1__.createElement(_elementor_ui__WEBPACK_IMPORTED_MODULE_5__.DirectionProvider,{rtl:(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.isRTL)()},react__WEBPACK_IMPORTED_MODULE_1__.createElement(ThemeProvider,null,react__WEBPACK_IMPORTED_MODULE_1__.createElement(Shell,null)))),domElement);}}();(window.__UNSTABLE__elementorPackages=window.__UNSTABLE__elementorPackages||{}).editor=__webpack_exports__;})();