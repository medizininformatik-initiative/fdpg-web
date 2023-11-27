/*! elementor - v3.14.0 - 18-06-2023 */
(()=>{"use strict";var e,t,o,n={38003:e=>{e.exports=wp.i18n}},i={};function __webpack_require__(e){var t=i[e];if(void 0!==t)return t.exports;var o=i[e]={exports:{}};return n[e](o,o.exports,__webpack_require__),o.exports}o=__webpack_require__(38003).__,e=jQuery,t={cacheElements:function cacheElements(){var t=this;t.isElementorMode=ElementorGutenbergSettings.isElementorMode,t.cache={},t.cache.$gutenberg=e("#editor"),t.cache.$switchMode=e(e("#elementor-gutenberg-button-switch-mode").html()),t.cache.$switchModeButton=t.cache.$switchMode.find("#elementor-switch-mode-button"),t.bindEvents(),t.toggleStatus(),wp.data.subscribe((function(){setTimeout((function(){t.buildPanel()}),1)}))},buildPanel:function buildPanel(){var t=this;t.cache.$gutenberg.find("#elementor-switch-mode").length||t.cache.$gutenberg.find(".edit-post-header-toolbar").append(t.cache.$switchMode),e("#elementor-editor").length||(t.cache.$editorPanel=e(e("#elementor-gutenberg-panel").html()),t.cache.$gurenbergBlockList=t.cache.$gutenberg.find(".editor-block-list__layout, .editor-post-text-editor, .block-editor-block-list__layout"),t.cache.$gurenbergBlockList.after(t.cache.$editorPanel),t.cache.$editorPanelButton=t.cache.$editorPanel.find("#elementor-go-to-edit-page-link"),t.cache.$editorPanelButton.on("click",(function(o){o.preventDefault(),t.animateLoader(),"auto-draft"===wp.data.select("core/editor").getCurrentPost().status&&(wp.data.select("core/editor").getEditedPostAttribute("title")||wp.data.dispatch("core/editor").editPost({title:"Elementor #"+e("#post_ID").val()}),wp.data.dispatch("core/editor").savePost()),t.redirectWhenSave()})))},bindEvents:function bindEvents(){var e=this;e.cache.$switchModeButton.on("click",(function(){e.isElementorMode?elementorCommon.dialogsManager.createWidget("confirm",{message:o("Please note that you are switching to WordPress default editor. Your current layout, design and content might break.","elementor"),headerMessage:o("Back to WordPress Editor","elementor"),strings:{confirm:o("Continue","elementor"),cancel:o("Cancel","elementor")},defaultOption:"confirm",onConfirm:function onConfirm(){var t=wp.data.dispatch("core/editor");t.editPost({gutenberg_elementor_mode:!1}),t.savePost(),e.isElementorMode=!e.isElementorMode,e.toggleStatus()}}).show():(e.isElementorMode=!e.isElementorMode,e.toggleStatus(),e.cache.$editorPanelButton.trigger("click"))}))},redirectWhenSave:function redirectWhenSave(){var e=this;setTimeout((function(){wp.data.select("core/editor").isSavingPost()?e.redirectWhenSave():location.href=ElementorGutenbergSettings.editLink}),300)},animateLoader:function animateLoader(){this.cache.$editorPanelButton.addClass("elementor-animate")},toggleStatus:function toggleStatus(){jQuery("body").toggleClass("elementor-editor-active",this.isElementorMode).toggleClass("elementor-editor-inactive",!this.isElementorMode)},init:function init(){this.cacheElements()}},e((function(){t.init()}))})();