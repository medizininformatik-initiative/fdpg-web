(function(){"use strict";var __webpack_modules__=({"react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
(function(module){module.exports=window["React"];}),"@elementor/editor-app-bar":
/*!*****************************************************************!*\
  !*** external ["__UNSTABLE__elementorPackages","editorAppBar"] ***!
  \*****************************************************************/
(function(module){module.exports=window["__UNSTABLE__elementorPackages"]["editorAppBar"];}),"@elementor/editor-v1-adapters":
/*!*********************************************************************!*\
  !*** external ["__UNSTABLE__elementorPackages","editorV1Adapters"] ***!
  \*********************************************************************/
(function(module){module.exports=window["__UNSTABLE__elementorPackages"]["editorV1Adapters"];}),"@elementor/icons":
/*!**********************************************************!*\
  !*** external ["__UNSTABLE__elementorPackages","icons"] ***!
  \**********************************************************/
(function(module){module.exports=window["__UNSTABLE__elementorPackages"]["icons"];}),"@elementor/store":
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
var module=__webpack_module_cache__[moduleId]={exports:{}};__webpack_modules__[moduleId](module,module.exports,__webpack_require__);return module.exports;}!function(){__webpack_require__.r=function(exports){if(typeof Symbol!=='undefined'&&Symbol.toStringTag){Object.defineProperty(exports,Symbol.toStringTag,{value:'Module'});}
Object.defineProperty(exports,'__esModule',{value:true});};}();var __webpack_exports__={};!function(){
/*!******************************************************************!*\
  !*** ./node_modules/@elementor/editor-responsive/dist/index.mjs ***!
  \******************************************************************/
__webpack_require__.r(__webpack_exports__);var _elementor_store__WEBPACK_IMPORTED_MODULE_0__=__webpack_require__(/*! @elementor/store */"@elementor/store");var _elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_1__=__webpack_require__(/*! @elementor/editor-v1-adapters */"@elementor/editor-v1-adapters");var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__=__webpack_require__(/*! @wordpress/i18n */"@wordpress/i18n");var _elementor_editor_app_bar__WEBPACK_IMPORTED_MODULE_3__=__webpack_require__(/*! @elementor/editor-app-bar */"@elementor/editor-app-bar");var react__WEBPACK_IMPORTED_MODULE_4__=__webpack_require__(/*! react */"react");var _elementor_ui__WEBPACK_IMPORTED_MODULE_5__=__webpack_require__(/*! @elementor/ui */"@elementor/ui");var _elementor_icons__WEBPACK_IMPORTED_MODULE_6__=__webpack_require__(/*! @elementor/icons */"@elementor/icons");var initialState={entities:{},activeId:null};function createSlice(){return(0,_elementor_store__WEBPACK_IMPORTED_MODULE_0__.addSlice)({name:"breakpoints",initialState,reducers:{init(state,action){state.activeId=action.payload.activeId;state.entities=normalizeEntities(action.payload.entities);},activateBreakpoint(state,action){if(state.entities[action.payload]){state.activeId=action.payload;}}}});}
function normalizeEntities(entities){return entities.reduce((acc,breakpoint)=>{return{...acc,[breakpoint.id]:breakpoint};},{});}
function syncStore(slice){syncInitialization(slice);syncOnChange(slice);}
function syncInitialization(slice){const{init:init2}=slice.actions;(0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_1__.listenTo)((0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_1__.v1ReadyEvent)(),()=>{(0,_elementor_store__WEBPACK_IMPORTED_MODULE_0__.dispatch)(init2({entities:getBreakpoints(),activeId:getActiveBreakpoint()}));});}
function syncOnChange(slice){const{activateBreakpoint}=slice.actions;(0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_1__.listenTo)(deviceModeChangeEvent(),()=>{const activeBreakpoint=getActiveBreakpoint();(0,_elementor_store__WEBPACK_IMPORTED_MODULE_0__.dispatch)(activateBreakpoint(activeBreakpoint));});}
function getBreakpoints(){const{breakpoints}=window.elementor?.config?.responsive||{};if(!breakpoints){return[];}
const entities=Object.entries(breakpoints).filter(([,breakpoint])=>breakpoint.is_enabled).map(([id,{value,direction,label}])=>{return{id,label,width:value,type:direction==="min"?"min-width":"max-width"};});entities.push({id:"desktop",label:(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Desktop","elementor")});return entities;}
function getActiveBreakpoint(){const extendedWindow=window;return extendedWindow.elementor?.channels?.deviceMode?.request?.("currentMode")||null;}
function deviceModeChangeEvent(){return(0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_1__.windowEvent)("elementor/device-mode/change");}
var selectEntities=(state)=>state.breakpoints.entities;var selectActiveId=(state)=>state.breakpoints.activeId;var selectActiveBreakpoint=(0,_elementor_store__WEBPACK_IMPORTED_MODULE_0__.createSelector)(selectEntities,selectActiveId,(entities,activeId)=>activeId&&entities[activeId]?entities[activeId]:null);var selectSortedBreakpoints=(0,_elementor_store__WEBPACK_IMPORTED_MODULE_0__.createSelector)(selectEntities,(entities)=>{const byWidth=(a,b)=>{return a.width&&b.width?b.width-a.width:0;};const all=Object.values(entities);const defaults=all.filter((breakpoint)=>!breakpoint.width);const minWidth=all.filter((breakpoint)=>breakpoint.type==="min-width");const maxWidth=all.filter((breakpoint)=>breakpoint.type==="max-width");return[...minWidth.sort(byWidth),...defaults,...maxWidth.sort(byWidth)];});function useBreakpoints(){const all=(0,_elementor_store__WEBPACK_IMPORTED_MODULE_0__.useSelector)(selectSortedBreakpoints);const active=(0,_elementor_store__WEBPACK_IMPORTED_MODULE_0__.useSelector)(selectActiveBreakpoint);return{all,active};}
function useBreakpointsActions(){const activate=(0,react__WEBPACK_IMPORTED_MODULE_4__.useCallback)((device)=>{return(0,_elementor_editor_v1_adapters__WEBPACK_IMPORTED_MODULE_1__.runCommand)("panel/change-device-mode",{device});},[]);return{activate};}
function BreakpointsSwitcher(){const{all,active}=useBreakpoints();const{activate}=useBreakpointsActions();if(!all.length||!active){return null;}
const onChange=(_,value)=>activate(value);return react__WEBPACK_IMPORTED_MODULE_4__.createElement(_elementor_ui__WEBPACK_IMPORTED_MODULE_5__.Tabs,{value:active.id,onChange,"aria-label":(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("Switch Device","elementor")},all.map(({id,label,type,width})=>{const Icon=iconsMap[id];const title=labelsMap[type||"default"].replace("%s",label).replace("%d",width?.toString()||"");return react__WEBPACK_IMPORTED_MODULE_4__.createElement(_elementor_ui__WEBPACK_IMPORTED_MODULE_5__.Tab,{value:id,key:id,"aria-label":title,icon:react__WEBPACK_IMPORTED_MODULE_4__.createElement(Tooltip,{title},react__WEBPACK_IMPORTED_MODULE_4__.createElement(Icon,null))});}));}
function Tooltip(props){return react__WEBPACK_IMPORTED_MODULE_4__.createElement(_elementor_ui__WEBPACK_IMPORTED_MODULE_5__.Tooltip,{PopperProps:{sx:{"&.MuiTooltip-popper .MuiTooltip-tooltip.MuiTooltip-tooltipPlacementBottom":{mt:7}}},...props});}
var iconsMap={widescreen:_elementor_icons__WEBPACK_IMPORTED_MODULE_6__.WidescreenIcon,desktop:_elementor_icons__WEBPACK_IMPORTED_MODULE_6__.DesktopIcon,laptop:_elementor_icons__WEBPACK_IMPORTED_MODULE_6__.LaptopIcon,tablet_extra:_elementor_icons__WEBPACK_IMPORTED_MODULE_6__.TabletLandscapeIcon,tablet:_elementor_icons__WEBPACK_IMPORTED_MODULE_6__.TabletPortraitIcon,mobile_extra:_elementor_icons__WEBPACK_IMPORTED_MODULE_6__.MobileLandscapeIcon,mobile:_elementor_icons__WEBPACK_IMPORTED_MODULE_6__.MobilePortraitIcon};var labelsMap={default:"%s","min-width":(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("%s (%dpx and up)","elementor"),"max-width":(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)("%s (up to %dpx)","elementor")};function init(){initStore();registerAppBarUI();}
function initStore(){const slice=createSlice();syncStore(slice);}
function registerAppBarUI(){(0,_elementor_editor_app_bar__WEBPACK_IMPORTED_MODULE_3__.injectIntoResponsive)({id:"responsive-breakpoints-switcher",filler:BreakpointsSwitcher,options:{priority:20}});}
init();}();(window.__UNSTABLE__elementorPackages=window.__UNSTABLE__elementorPackages||{}).editorResponsive=__webpack_exports__;})();