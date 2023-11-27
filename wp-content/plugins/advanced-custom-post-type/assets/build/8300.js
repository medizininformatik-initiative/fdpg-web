"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[8300],{

/***/ 28300:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _useSearchParams = __webpack_require__(91512);

var _reactRedux = __webpack_require__(28216);

var _misc = __webpack_require__(53154);

var _fetchPosts = __webpack_require__(64574);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _metaTypes = __webpack_require__(81895);

var _fetchTerms = __webpack_require__(57348);

var _VisualBuilder = __webpack_require__(51666);

var _VisualBuilder2 = _interopRequireDefault(_VisualBuilder);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Template = function Template() {

    // manage local state
    var _useParams = (0, _reactRouterDom.useParams)(),
        belongsTo = _useParams.belongsTo,
        template = _useParams.template,
        find = _useParams.find,
        metaFieldId = _useParams.metaFieldId;

    var queryString = (0, _useSearchParams.useSearchParams)();

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostsReducer;
    }),
        fetchedPosts = _useSelector.fetched,
        loadingPosts = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTermsReducer;
    }),
        fetchedTerms = _useSelector2.fetched,
        loadingTerms = _useSelector2.loading;

    // manage local state


    var didMountRef = (0, _react.useRef)(false);

    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        fetchedSuccess = _useState2[0],
        setFetchedSuccess = _useState2[1];

    var showPreviewLink = !(template === 'related' || template === 'meta_field');

    var exitLink = function exitLink() {

        if (queryString.refer) {
            return "/admin.php?page=advanced-custom-post-type#/" + queryString.refer;
        }

        if (belongsTo === _metaTypes.metaTypes.TAXONOMY) {
            return '/admin.php?page=advanced-custom-post-type#/taxonomies';
        }

        return "/admin.php?page=advanced-custom-post-type";
    };

    // meta title
    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)(template + ": manage template");

        if (belongsTo === _metaTypes.metaTypes.CUSTOM_POST_TYPE) {
            dispatch((0, _fetchPosts.fetchPosts)(find));
        } else if (belongsTo === _metaTypes.metaTypes.TAXONOMY) {
            dispatch((0, _fetchTerms.fetchTerms)(find));
        } else {
            setFetchedSuccess(true);
        }
    }, []);

    // handle fetch outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loadingTerms && !loadingPosts) {
                setFetchedSuccess(true);
            }
        } else {
            didMountRef.current = true;
        }
    }, [loadingPosts, loadingTerms]);

    if (!fetchedSuccess) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    return wp.element.createElement(_VisualBuilder2.default, {
        belongsTo: belongsTo,
        template: template,
        find: find,
        metaFieldId: metaFieldId,
        postList: fetchedPosts,
        taxonomyList: fetchedTerms,
        showPreviewLink: showPreviewLink,
        exitLink: exitLink()
    });
};

exports["default"] = Template;

/***/ }),

/***/ 61374:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _fetchPreviewLink = __webpack_require__(84938);

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRedux = __webpack_require__(28216);

var _metaTypes = __webpack_require__(81895);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Canvas = function Canvas(_ref) {
    var template = _ref.template,
        belongsTo = _ref.belongsTo,
        find = _ref.find,
        postList = _ref.postList,
        showPreviewLink = _ref.showPreviewLink,
        setLeadingPostId = _ref.setLeadingPostId,
        leadingPostId = _ref.leadingPostId,
        leadingTermId = _ref.leadingTermId,
        setLeadingTermId = _ref.setLeadingTermId,
        taxonomyList = _ref.taxonomyList;


    var dispatch = (0, _reactRedux.useDispatch)();

    return wp.element.createElement(
        "div",
        { className: "editor-canvas" },
        showPreviewLink && postList.length > 0 && belongsTo === _metaTypes.metaTypes.CUSTOM_POST_TYPE && wp.element.createElement(
            "div",
            { className: "gjs-meta" },
            wp.element.createElement(
                "div",
                { className: "gjs-meta-element" },
                wp.element.createElement(
                    "span",
                    null,
                    "Leading post:"
                ),
                wp.element.createElement(
                    "select",
                    {
                        onChange: function onChange(e) {
                            setLeadingPostId(parseInt(e.target.value));
                        }
                    },
                    postList.map(function (post, index) {
                        return wp.element.createElement(
                            "option",
                            { key: index, value: post.id },
                            post.title
                        );
                    })
                ),
                wp.element.createElement(
                    "button",
                    {
                        onClick: function onClick(e) {
                            e.preventDefault();
                            dispatch((0, _fetchPreviewLink.fetchPreviewLink)(belongsTo, find, template, leadingPostId));
                        },
                        className: "primary"
                    },
                    (0, _useTranslation2.default)("Preview")
                )
            )
        ),
        showPreviewLink && taxonomyList.length > 0 && belongsTo === _metaTypes.metaTypes.TAXONOMY && wp.element.createElement(
            "div",
            { className: "gjs-meta" },
            wp.element.createElement(
                "div",
                { className: "gjs-meta-element" },
                wp.element.createElement(
                    "span",
                    null,
                    (0, _useTranslation2.default)("Leading term:")
                ),
                wp.element.createElement(
                    "select",
                    {
                        onChange: function onChange(e) {
                            setLeadingTermId(parseInt(e.target.value));
                        }
                    },
                    taxonomyList.map(function (taxonomy, index) {
                        return wp.element.createElement(
                            "option",
                            { key: index, value: taxonomy.term_id },
                            taxonomy.name
                        );
                    })
                ),
                wp.element.createElement(
                    "button",
                    {
                        onClick: function onClick(e) {
                            e.preventDefault();
                            dispatch((0, _fetchPreviewLink.fetchPreviewLink)(belongsTo, find, template, leadingTermId));
                        },
                        className: "primary"
                    },
                    (0, _useTranslation2.default)("Preview")
                )
            )
        ),
        wp.element.createElement(
            "div",
            { className: "gjs-editor" },
            wp.element.createElement(
                "p",
                null,
                (0, _useTranslation2.default)("No content yet. Drag components in this canvas and start building something awesome!")
            )
        )
    );
};

exports["default"] = Canvas;

/***/ }),

/***/ 25170:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Sidebar = function Sidebar(_ref) {
    var blockQueryHandle = _ref.blockQueryHandle;


    return wp.element.createElement(
        "div",
        { className: "gjs-sidebar", id: "gjs-sidebar" },
        wp.element.createElement("div", { className: "gjs-panel-switcher" }),
        wp.element.createElement(
            "div",
            { className: "gjs-filter-block" },
            wp.element.createElement("input", {
                type: "text",
                placeholder: "Filter blocks",
                onChange: function onChange(e) {
                    if (blockQueryHandle) {
                        blockQueryHandle(e.target.value);
                    }
                }
            })
        ),
        wp.element.createElement(
            "div",
            { className: "gjs-panels" },
            wp.element.createElement("div", { className: "gjs-blocks-container" }),
            wp.element.createElement("div", { className: "gjs-layers-container", style: { display: "none" } }),
            wp.element.createElement("div", { className: "gjs-styles-container", style: { display: "none" } }),
            wp.element.createElement("div", { className: "gjs-traits-container", style: { display: "none" } })
        )
    );
};

exports["default"] = Sidebar;

/***/ }),

/***/ 36903:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Top = function Top() {

    // globals
    var globals = document.globals;

    return wp.element.createElement(
        "div",
        { className: "gjs-top", id: "gjs-top" },
        wp.element.createElement(
            "div",
            { className: "logo" },
            wp.element.createElement(
                "svg",
                { width: "36", height: "36", viewBox: "0 0 634 572", fill: "none", xmlns: "http://www.w3.org/2000/svg" },
                wp.element.createElement("path", { fillRule: "evenodd", clipRule: "evenodd", d: "M465.039 115.5L339.505 42.9755C333.314 39.3991 325.686 39.3991 319.495 42.9755L193.961 115.5L319.495 188.024C325.686 191.601 333.314 191.601 339.505 188.024L465.039 115.5ZM359.515 8.34015C340.943 -2.3891 318.057 -2.3891 299.485 8.34015L114 115.5L299.485 222.66C318.057 233.389 340.943 233.389 359.515 222.66L545 115.5L359.515 8.34015Z", fill: "#777" }),
                wp.element.createElement("path", { fillRule: "evenodd", clipRule: "evenodd", d: "M520.34 209.498L394.765 281.952C388.572 285.524 384.758 292.131 384.76 299.28L384.801 444.258L510.376 371.805C516.568 368.232 520.383 361.625 520.381 354.476L520.34 209.498ZM374.775 247.305C356.197 258.024 344.754 277.844 344.76 299.292L344.82 513.507L530.366 406.452C548.944 395.733 560.387 375.913 560.381 354.465L560.32 140.25L374.775 247.305Z", fill: "#777" }),
                wp.element.createElement("path", { opacity: "0.5", fillRule: "evenodd", clipRule: "evenodd", d: "M275.34 444.259L275.381 299.281C275.383 292.131 271.568 285.525 265.376 281.952L139.801 209.498L139.76 354.476C139.758 361.625 143.572 368.232 149.765 371.805L275.34 444.259ZM315.381 299.292C315.387 277.844 303.944 258.024 285.366 247.305L99.8202 140.25L99.7599 354.465C99.7538 375.913 111.197 395.733 129.775 406.452L315.32 513.507L315.381 299.292Z", fill: "#777" })
            ),
            wp.element.createElement(
                "span",
                { className: "label" },
                wp.element.createElement(
                    "span",
                    { className: "a" },
                    "A"
                ),
                "CPT"
            ),
            wp.element.createElement(
                "span",
                { className: "version" },
                "v ",
                globals.plugin_version
            )
        ),
        wp.element.createElement("div", { className: "gjs-devices" }),
        wp.element.createElement("div", { className: "gjs-basic-actions" })
    );
};

exports["default"] = Top;

/***/ }),

/***/ 49809:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.addBlocks = undefined;

var _objects = __webpack_require__(54040);

var addBlocks = exports.addBlocks = function addBlocks(editor, blocks) {

    blocks.map(function (block) {
        if (isValidBlock(block)) {
            editor.BlockManager.add(block.id, {
                label: "\n                    <div>\n                        <div class=\"gjs-block__media\">\n                            " + block.icon + "\n                        </div>\n                        <div class=\"gjs-block-label\">\n                            " + block.label + "\n                        </div>\n                    </div> \n                ",
                category: block.category,
                content: block.content
            });
        }
    });
};

/**
 *
 * @param block
 * @return {boolean}
 */
var isValidBlock = function isValidBlock(block) {

    if (!(0, _objects.isset)(block, "id")) {
        return false;
    }

    if (!(0, _objects.isset)(block, "category")) {
        return false;
    }

    if (!(0, _objects.isset)(block, "content")) {
        return false;
    }

    if (!(0, _objects.isset)(block, "label")) {
        return false;
    }

    if (!(0, _objects.isset)(block, "icon")) {
        return false;
    }

    return true;
};

/***/ }),

/***/ 44317:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.addCommands = undefined;

var _objects = __webpack_require__(54040);

var _reactToastify = __webpack_require__(39249);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var addCommands = exports.addCommands = function addCommands(editor, exitLink) {

    editor.Commands.add('set-device-desktop', {
        run: function run(editor) {
            return editor.setDevice('Desktop');
        }
    });

    editor.Commands.add('set-device-tablet', {
        run: function run(editor) {
            return editor.setDevice('Tablet');
        }
    });

    editor.Commands.add('set-device-mobile', {
        run: function run(editor) {
            return editor.setDevice('Mobile');
        }
    });

    editor.Commands.add('show-layers', {
        getRowEl: function getRowEl(editor) {
            return editor.getContainer().closest('.gjs-main');
        },
        getLayersEl: function getLayersEl(row) {
            return row.querySelector('.gjs-layers-container');
        },
        run: function run(editor, sender) {
            var lmEl = this.getLayersEl(this.getRowEl(editor));
            lmEl.style.display = '';
        },
        stop: function stop(editor, sender) {
            var lmEl = this.getLayersEl(this.getRowEl(editor));
            lmEl.style.display = 'none';
        }
    });

    editor.Commands.add('show-styles', {
        getRowEl: function getRowEl(editor) {
            return editor.getContainer().closest('.gjs-main');
        },
        getStyleEl: function getStyleEl(row) {
            return row.querySelector('.gjs-styles-container');
        },
        run: function run(editor, sender) {
            var smEl = this.getStyleEl(this.getRowEl(editor));
            smEl.style.display = '';
        },
        stop: function stop(editor, sender) {
            var smEl = this.getStyleEl(this.getRowEl(editor));
            smEl.style.display = 'none';
        }
    });

    editor.Commands.add('show-traits', {
        getTraitsEl: function getTraitsEl(editor) {
            var row = editor.getContainer().closest('.gjs-main');
            return row.querySelector('.gjs-traits-container');
        },
        run: function run(editor, sender) {
            this.getTraitsEl(editor).style.display = '';
        },
        stop: function stop(editor, sender) {
            this.getTraitsEl(editor).style.display = 'none';
        }
    });

    editor.Commands.add('show-blocks', {
        getTraitsEl: function getTraitsEl(editor) {
            var row = editor.getContainer().closest('.gjs-main');
            return row.querySelector('.gjs-blocks-container');
        },
        run: function run(editor, sender) {
            this.getTraitsEl(editor).style.display = '';
        },
        stop: function stop(editor, sender) {
            this.getTraitsEl(editor).style.display = 'none';
        }
    });

    editor.on("run:core:preview", function () {
        document.getElementById("gjs-top").classList.add('hidden');
        document.getElementById("gjs-sidebar").classList.add('hidden');
    });

    editor.on("stop:core:preview", function () {
        document.getElementById("gjs-top").classList.remove('hidden');
        document.getElementById("gjs-sidebar").classList.remove('hidden');
    });

    editor.Commands.add('save', {
        run: function run(editor) {
            editor.store({}).then(function (res) {
                if ((0, _objects.isset)(res, "assets") && (0, _objects.isset)(res, "styles") && (0, _objects.isset)(res, "pages")) {
                    _reactToastify.toast.success((0, _useTranslation2.default)("Template saved successfully"));
                }
            }).catch(function (err) {
                _reactToastify.toast.success((0, _useTranslation2.default)("Error during saving the template"));
            });
        }
    });

    editor.Commands.add('exit', {
        run: function run(editor) {
            var globals = document.globals;
            document.body.style.overflow = 'scroll';
            window.location.replace("" + globals.admin_url + exitLink);
        }
    });
};

/***/ }),

/***/ 66811:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var addGoogleFonts = exports.addGoogleFonts = function addGoogleFonts(editor) {

    editor.on('load', function () {
        var styleManager = editor.StyleManager;
        var fontProperty = styleManager.getProperty('typography', 'font-family');

        var fontList = [fontProperty.addOption({ value: "'Lato', sans-serif", name: 'Lato' }), fontProperty.addOption({ value: "'Montserrat', sans-serif", name: 'Montserrat' }), fontProperty.addOption({ value: "'Open Sans', sans-serif", name: 'Open Sans' }), fontProperty.addOption({ value: "'Oswald', sans-serif", name: 'Oswald' }), fontProperty.addOption({ value: "'Poppins', sans-serif", name: 'Poppins' }), fontProperty.addOption({ value: "'Raleway', sans-serif", name: 'Raleway' }), fontProperty.addOption({ value: "'Roboto', sans-serif", name: 'Roboto' })];

        fontProperty.set('list', fontList);
        styleManager.render();
    });
};

/***/ }),

/***/ 5582:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.addPanels = undefined;

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var addPanels = exports.addPanels = function addPanels(editor) {

    editor.Panels.addPanel({
        id: 'basic-actions',
        el: '.gjs-basic-actions',
        buttons: [{
            id: 'visibility',
            active: true,
            className: 'btn-toggle-borders',
            label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M19 19h2v2h-2zm-8 0h2v2h-2zm4 0h2v2h-2zm-8 0h2v2H7zm-4 0h2v2H3zM3 7h2v2H3zm0 8h2v2H3zm0-4h2v2H3zm0-8h2v2H3zm4 0h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zm4 0h2v2h-2zm0 4h2v2h-2zm0 4h2v2h-2zm0 4h2v2h-2zm-8-8h2v2h-2zm0 8h2v2h-2zm-4-4h2v2H7zm8 0h2v2h-2zm-4 0h2v2h-2z"/></svg>',
            command: 'sw-visibility',
            attributes: { title: (0, _useTranslation2.default)("Toggle guides") }
        }, {
            id: 'preview',
            active: false,
            className: 'btn-toggle-borders',
            label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M21.87 11.5c-.64-1.11-4.16-6.68-10.14-6.5c-5.53.14-8.73 5-9.6 6.5a1 1 0 0 0 0 1c.63 1.09 4 6.5 9.89 6.5h.25c5.53-.14 8.74-5 9.6-6.5a1 1 0 0 0 0-1ZM12.22 17c-4.31.1-7.12-3.59-8-5c1-1.61 3.61-4.9 7.61-5c4.29-.11 7.11 3.59 8 5c-1.03 1.61-3.61 4.9-7.61 5Z"/><path fill="currentColor" d="M12 8.5a3.5 3.5 0 1 0 3.5 3.5A3.5 3.5 0 0 0 12 8.5Zm0 5a1.5 1.5 0 1 1 1.5-1.5a1.5 1.5 0 0 1-1.5 1.5Z"/></svg>',
            command: 'core:preview',
            attributes: { title: (0, _useTranslation2.default)('Preview') }
        }, {
            id: 'fullscreen',
            active: false,
            className: 'btn-toggle-borders',
            label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"/></svg>',
            command: 'core:fullscreen',
            attributes: { title: (0, _useTranslation2.default)('Fullscreen mode') }
        }, {
            id: 'export',
            active: false,
            className: 'btn-open-export',
            label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m7.375 16.781l1.25-1.562L4.601 12l4.024-3.219l-1.25-1.562l-5 4a1 1 0 0 0 0 1.562l5 4zm9.25-9.562l-1.25 1.562L19.399 12l-4.024 3.219l1.25 1.562l5-4a1 1 0 0 0 0-1.562l-5-4zm-1.649-4.003l-4 18l-1.953-.434l4-18z"/></svg>',
            command: 'export-template',
            context: 'export-template',
            attributes: { title: (0, _useTranslation2.default)('Export HTML/CSS code') }
        }, {
            id: 'undo',
            label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M9 10h6c1.654 0 3 1.346 3 3s-1.346 3-3 3h-3v2h3c2.757 0 5-2.243 5-5s-2.243-5-5-5H9V5L4 9l5 4v-3z"/></svg>',
            command: 'core:undo',
            attributes: { title: (0, _useTranslation2.default)('Undo') }
        }, {
            id: 'redo',
            label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M9 18h3v-2H9c-1.654 0-3-1.346-3-3s1.346-3 3-3h6v3l5-4l-5-4v3H9c-2.757 0-5 2.243-5 5s2.243 5 5 5z"/></svg>',
            command: 'core:redo',
            attributes: { title: (0, _useTranslation2.default)('Redo') }
        }, {
            id: 'save',
            className: 'save',
            command: 'save',
            label: '<button class="primary">' + (0, _useTranslation2.default)("Save") + '</button>',
            attributes: { title: (0, _useTranslation2.default)("Save") }
        }, {
            id: 'exit',
            className: 'exit',
            command: 'exit',
            label: '<button class="primary-o">' + (0, _useTranslation2.default)("Exit") + '</button>',
            attributes: { title: (0, _useTranslation2.default)("Exit") }
        }]
    });
};

/***/ }),

/***/ 15823:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var addStyles = exports.addStyles = function addStyles(editor) {

    editor.StyleManager.addProperty('extra', { extend: 'filter' });
    editor.StyleManager.addProperty('extra', { extend: 'filter', property: 'backdrop-filter' });
};

/***/ }),

/***/ 664:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.addTraits = undefined;

var _metaTypes = __webpack_require__(81895);

var _functions = __webpack_require__(56232);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var addTraits = exports.addTraits = function addTraits(editor) {

    var domc = editor.DomComponents;

    var surroundingTags = [{ id: 'h1', name: 'h1' }, { id: 'h2', name: 'h2' }, { id: 'h3', name: 'h3' }, { id: 'h4', name: 'h4' }, { id: 'p', name: 'p' }, { id: 'span', name: 'span' }, { id: 'div', name: 'div' }];

    var styles = {
        wp: "",
        wc: "color: #8155b0;",
        acpt: "color: #00c39b;",
        theme: "background: #f0f0f1;\n                border: 1px solid #c3c4c7;\n                border-radius: 2px;\n                padding: 10px;\n                color: #3c434a;\n                text-align: center;\n                font-size: 14px;\n                font-family: 'Monaco', monospace;\n                "
    };

    // Basics
    domc.addType('div', {
        model: {
            defaults: {
                id: 'div',
                tagName: 'div',
                removable: true,
                draggable: true,
                stylable: true,
                highlightable: true,
                selectable: true,
                editable: true,
                components: ["<div>" + (0, _useTranslation2.default)("Drag your component(s) here") + "</div>"]
            }
        }
    });

    // Theme element
    domc.addType('theme-el', {
        model: {
            defaults: {
                tagName: 'div'
            }
        },
        view: {
            onRender: function onRender() {
                var model = this.model,
                    el = this.el;

                el.setAttribute('style', styles.theme);
            }
        }
    });

    // WooCommerce containers
    domc.addType('wp-before-main-content', {
        model: {
            defaults: {
                id: 'wp-before-main-content',
                tagName: 'wp-before-main-content',
                removable: true,
                draggable: true,
                stylable: true,
                highlightable: true,
                selectable: true,
                editable: true,
                components: ["<p>" + (0, _useTranslation2.default)("Drag your loop component(s) here") + "</p>"]
            }
        }
    });

    domc.addType('wc-before-product-summary', {
        model: {
            defaults: {
                id: 'wc-before-product-summary',
                tagName: 'wc-before-product-summary',
                removable: true,
                draggable: true,
                stylable: true,
                highlightable: true,
                selectable: true,
                editable: true,
                components: ["<p>" + (0, _useTranslation2.default)("Drag your loop component(s) here") + "</p>"]
            }
        }
    });

    domc.addType('wc-product-summary', {
        model: {
            defaults: {
                id: 'wc-product-summary',
                tagName: 'wc-product-summary',
                removable: true,
                draggable: true,
                stylable: true,
                highlightable: true,
                selectable: true,
                editable: true,
                components: ["<p>" + (0, _useTranslation2.default)("Drag your loop component(s) here") + "</p>"]
            }
        }
    });

    domc.addType('wc-after-product-summary', {
        model: {
            defaults: {
                id: 'wc-after-product-summary',
                tagName: 'wc-after-product-summary',
                removable: true,
                draggable: true,
                stylable: true,
                highlightable: true,
                selectable: true,
                editable: true,
                components: ["<p>" + (0, _useTranslation2.default)("Drag your loop component(s) here") + "</p>"]
            }
        }
    });

    // WooCommerce element
    domc.addType('wc-el', {
        model: {
            defaults: {
                tagName: 'span',
                textable: true,
                traits: [{
                    type: "text",
                    label: "id",
                    name: "id",
                    placeholder: "12345",
                    changeProp: true
                }, {
                    type: "text",
                    label: "title",
                    name: "title",
                    placeholder: (0, _useTranslation2.default)((0, _useTranslation2.default)("eg. Text here")),
                    changeProp: true
                }, {
                    type: 'select',
                    label: 'Surrounding tag',
                    name: 'tag',
                    changeProp: true,
                    options: surroundingTags
                }]
            },
            init: function init() {
                this.on('change:id', this.handleIdChange);
                this.on('change:title', this.handleTitleChange);
                this.on('change:tag', this.handleTagChange);
            },
            handleIdChange: function handleIdChange() {
                this.setAttributes({ id: this.changed.id });
            },
            handleTitleChange: function handleTitleChange() {
                this.setAttributes({ title: this.changed.title });
            },
            handleTagChange: function handleTagChange() {
                this.set("tagName", this.changed.tag);
            }
        },
        view: {
            onRender: function onRender() {
                var model = this.model,
                    el = this.el;


                el.setAttribute('style', styles.wc);
            }
        }
    });

    // WP elements
    domc.addType('wp-el', {
        model: {
            defaults: {
                tagName: 'span',
                textable: true,
                traits: [{
                    type: "text",
                    label: "id",
                    name: "id",
                    placeholder: "12345",
                    changeProp: true
                }, {
                    type: "text",
                    label: "title",
                    name: "title",
                    placeholder: (0, _useTranslation2.default)((0, _useTranslation2.default)("eg. Text here")),
                    changeProp: true
                }, {
                    type: 'select',
                    label: 'Surrounding tag',
                    name: 'tag',
                    changeProp: true,
                    options: surroundingTags
                }]
            },
            init: function init() {
                this.on('change:id', this.handleIdChange);
                this.on('change:title', this.handleTitleChange);
                this.on('change:tag', this.handleTagChange);
            },
            handleIdChange: function handleIdChange() {
                this.setAttributes({ id: this.changed.id });
            },
            handleTitleChange: function handleTitleChange() {
                this.setAttributes({ title: this.changed.title });
            },
            handleTagChange: function handleTagChange() {
                this.set("tagName", this.changed.tag);
            }
        },
        view: {
            onRender: function onRender() {
                var model = this.model,
                    el = this.el;

                el.setAttribute('style', styles.wp);
            }
        }
    });

    domc.addType('wp-breadcrumb', {
        model: {
            defaults: {
                tagName: 'div',
                textable: true,
                traits: [{
                    type: "text",
                    label: "id",
                    name: "id",
                    placeholder: "12345",
                    changeProp: true
                }, {
                    type: "text",
                    label: "title",
                    name: "title",
                    placeholder: (0, _useTranslation2.default)("eg. Text here"),
                    changeProp: true
                }, {
                    type: 'select',
                    label: 'Separator',
                    name: 'separator',
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { name: "»", id: "raquo" }, { name: ">", id: "gt" }, { name: "/", id: "/" }]
                }, {
                    type: 'select',
                    label: 'Surrounding tag',
                    name: 'tag',
                    changeProp: true,
                    options: surroundingTags
                }]
            },
            init: function init() {
                this.on('change:id', this.handleIdChange);
                this.on('change:title', this.handleTitleChange);
                this.on('change:separator', this.handleSeparatorChange);
                this.on('change:tag', this.handleTagChange);
            },
            handleIdChange: function handleIdChange() {
                this.setAttributes({ id: this.changed.id });
            },
            handleTitleChange: function handleTitleChange() {
                this.setAttributes({ title: this.changed.title });
            },
            handleSeparatorChange: function handleSeparatorChange() {
                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/separator="[a-zA-Z0-9;&>»\-\/.\s+]*"/i, "");

                if (this.changed.separator !== "null") {
                    newContent = "{{" + newContent + " separator=\"" + this.changed.separator + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            },
            handleTagChange: function handleTagChange() {
                this.set("tagName", this.changed.tag);
            }
        },
        view: {
            onRender: function onRender() {
                var model = this.model,
                    el = this.el;

                el.setAttribute('style', styles.wp);
            }
        }
    });

    domc.addType('acpt-loop', {
        model: {
            defaults: {
                id: 'acpt-loop',
                attributes: {
                    type: '',
                    find: '',
                    order_by: 'date',
                    sort_by: 'ASC',
                    per_page: 12,
                    per_row: 3
                },
                tagName: 'acpt-loop',
                removable: true,
                draggable: true,
                stylable: true,
                highlightable: true,
                selectable: true,
                editable: true,
                components: ["<p>" + (0, _useTranslation2.default)("Drag your loop component(s) here") + "</p>"],
                traits: [{
                    type: 'select',
                    label: 'Condition',
                    name: 'belongs_to',
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { name: "custom post type", id: _metaTypes.metaTypes.CUSTOM_POST_TYPE }, { name: "taxonomy", id: _metaTypes.metaTypes.TAXONOMY }, { name: "repeater meta field", id: "meta_field" }, { name: "flexible field block", id: "flex_block" }]
                }, {
                    type: 'select',
                    label: (0, _useTranslation2.default)('Condition value'),
                    name: 'find',
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }]
                }, {
                    type: 'number',
                    label: (0, _useTranslation2.default)('Elements per page'),
                    name: 'per_page',
                    default: 12,
                    min: 1,
                    max: 20,
                    step: 1,
                    changeProp: true
                }, {
                    type: 'number',
                    label: (0, _useTranslation2.default)('Elements per row'),
                    name: 'per_row',
                    default: 3,
                    min: 1,
                    max: 6,
                    step: 1,
                    changeProp: true
                }, {
                    type: 'text',
                    label: (0, _useTranslation2.default)('No records message'),
                    name: 'no_records',
                    default: (0, _useTranslation2.default)('Sorry, no posts matched your criteria.'),
                    changeProp: true
                }, {
                    type: 'select',
                    label: (0, _useTranslation2.default)('Show pagination'),
                    name: 'pagination',
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("Yes"), id: 1 }, { name: (0, _useTranslation2.default)("No"), id: 0 }]
                }, {
                    type: 'select',
                    label: 'Order by',
                    name: (0, _useTranslation2.default)('order_by'),
                    changeProp: true,
                    options: []
                }, {
                    type: 'select',
                    label: 'Sort by',
                    name: 'sort_by',
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { name: (0, _useTranslation2.default)("ASC"), id: "ASC" }, { name: (0, _useTranslation2.default)("DESC"), id: "DESC" }]
                }]
            },
            init: function () {
                var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
                    var belongsTo, find, findOptions, orderByOptions;
                    return regeneratorRuntime.wrap(function _callee$(_context) {
                        while (1) {
                            switch (_context.prev = _context.next) {
                                case 0:

                                    this.listenTo(this, 'change:belongs_to', this.handleBelongsToChange);
                                    this.listenTo(this, 'change:find', this.handleFindChange);

                                    belongsTo = this.attributes.belongs_to;
                                    find = this.attributes.find;

                                    if (!(typeof belongsTo !== 'undefined')) {
                                        _context.next = 14;
                                        break;
                                    }

                                    _context.next = 7;
                                    return (0, _functions.fetchFindFromBelongsTo)(belongsTo);

                                case 7:
                                    findOptions = _context.sent;


                                    this.updateTrait("find", {
                                        options: findOptions
                                    });

                                    if (!(typeof find !== 'undefined')) {
                                        _context.next = 14;
                                        break;
                                    }

                                    _context.next = 12;
                                    return (0, _functions.fetchMetaFieldsFromBelongsTo)(belongsTo, find);

                                case 12:
                                    orderByOptions = _context.sent;


                                    this.updateTrait("order_by", {
                                        options: orderByOptions
                                    });

                                case 14:

                                    this.on('change:pagination', this.handlePaginationChange);
                                    this.on('change:no_records', this.handleNoRecordsChange);
                                    this.on('change:per_page', this.handlePerPageChange);
                                    this.on('change:per_row', this.handlePerRowChange);
                                    this.on('change:order_by', this.handleOrderByChange);
                                    this.on('change:sort_by', this.handleSortByChange);

                                case 20:
                                case "end":
                                    return _context.stop();
                            }
                        }
                    }, _callee, this);
                }));

                function init() {
                    return _ref.apply(this, arguments);
                }

                return init;
            }(),
            handleBelongsToChange: function () {
                var _ref2 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee2() {
                    var newBelongsTo, findOptions;
                    return regeneratorRuntime.wrap(function _callee2$(_context2) {
                        while (1) {
                            switch (_context2.prev = _context2.next) {
                                case 0:
                                    newBelongsTo = this.changed.belongs_to;
                                    _context2.next = 3;
                                    return (0, _functions.fetchFindFromBelongsTo)(newBelongsTo);

                                case 3:
                                    findOptions = _context2.sent;


                                    if (newBelongsTo === _metaTypes.metaTypes.CUSTOM_POST_TYPE) {
                                        this.set('tagName', 'acpt-loop');
                                    } else if (newBelongsTo === _metaTypes.metaTypes.TAXONOMY) {
                                        this.set('tagName', 'acpt-tax-loop');
                                    } else if (newBelongsTo === 'meta_field') {
                                        this.set('tagName', 'acpt-field-loop');
                                    } else if (newBelongsTo === 'flex_block') {
                                        this.set('tagName', 'acpt-block-loop');
                                    }

                                    this.updateTrait("find", {
                                        options: findOptions
                                    });

                                    this.setAttributes({
                                        belongs_to: newBelongsTo,
                                        find: this.attributes.find,
                                        pagination: this.attributes.pagination,
                                        per_page: this.attributes.per_page,
                                        per_row: this.attributes.per_row,
                                        order_by: this.attributes.order_by,
                                        sort_by: this.attributes.sort_by,
                                        no_records: this.attributes.no_records
                                    });

                                case 7:
                                case "end":
                                    return _context2.stop();
                            }
                        }
                    }, _callee2, this);
                }));

                function handleBelongsToChange() {
                    return _ref2.apply(this, arguments);
                }

                return handleBelongsToChange;
            }(),
            handleFindChange: function () {
                var _ref3 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee3() {
                    var belongsTo, newFind, orderByOptions;
                    return regeneratorRuntime.wrap(function _callee3$(_context3) {
                        while (1) {
                            switch (_context3.prev = _context3.next) {
                                case 0:
                                    belongsTo = this.attributes.belongs_to;
                                    newFind = this.changed.find;
                                    _context3.next = 4;
                                    return (0, _functions.fetchMetaFieldsFromBelongsTo)(belongsTo, newFind);

                                case 4:
                                    orderByOptions = _context3.sent;


                                    this.updateTrait("order_by", {
                                        options: orderByOptions
                                    });

                                    this.setAttributes({
                                        belongs_to: belongsTo,
                                        find: newFind,
                                        pagination: this.attributes.pagination,
                                        per_page: this.attributes.per_page,
                                        per_row: this.attributes.per_row,
                                        order_by: this.attributes.order_by,
                                        sort_by: this.attributes.sort_by,
                                        no_records: this.attributes.no_records
                                    });

                                case 7:
                                case "end":
                                    return _context3.stop();
                            }
                        }
                    }, _callee3, this);
                }));

                function handleFindChange() {
                    return _ref3.apply(this, arguments);
                }

                return handleFindChange;
            }(),
            handlePaginationChange: function handlePaginationChange() {
                this.setAttributes({
                    belongs_to: this.attributes.belongs_to,
                    find: this.attributes.find,
                    pagination: this.changed.pagination,
                    per_page: this.attributes.per_page,
                    per_row: this.attributes.per_row,
                    order_by: this.attributes.order_by,
                    sort_by: this.attributes.sort_by,
                    no_records: this.changed.no_records
                });
            },
            handleNoRecordsChange: function handleNoRecordsChange() {
                this.setAttributes({
                    belongs_to: this.attributes.belongs_to,
                    find: this.changed.find,
                    pagination: this.attributes.pagination,
                    per_page: this.attributes.per_page,
                    per_row: this.attributes.per_row,
                    order_by: this.attributes.order_by,
                    sort_by: this.attributes.sort_by,
                    no_records: this.changed.no_records
                });
            },
            handlePerPageChange: function handlePerPageChange() {
                this.setAttributes({
                    belongs_to: this.attributes.belongs_to,
                    find: this.attributes.find,
                    pagination: this.attributes.pagination,
                    per_page: this.changed.per_page,
                    per_row: this.attributes.per_row,
                    order_by: this.attributes.order_by,
                    sort_by: this.attributes.sort_by,
                    no_records: this.attributes.no_records
                });
            },
            handlePerRowChange: function handlePerRowChange() {
                this.setAttributes({
                    belongs_to: this.attributes.belongs_to,
                    find: this.attributes.find,
                    pagination: this.attributes.pagination,
                    per_page: this.attributes.per_page,
                    per_row: this.changed.per_row,
                    order_by: this.attributes.order_by,
                    sort_by: this.attributes.sort_by,
                    no_records: this.attributes.no_records
                });
            },
            handleOrderByChange: function handleOrderByChange() {
                this.setAttributes({
                    belongs_to: this.attributes.belongs_to,
                    find: this.attributes.find,
                    pagination: this.attributes.pagination,
                    per_page: this.attributes.per_page,
                    per_row: this.attributes.per_row,
                    order_by: this.changed.order_by,
                    sort_by: this.attributes.sort_by,
                    no_records: this.attributes.no_records
                });
            },
            handleSortByChange: function handleSortByChange() {
                this.setAttributes({
                    belongs_to: this.attributes.belongs_to,
                    find: this.attributes.find,
                    pagination: this.attributes.pagination,
                    per_page: this.attributes.per_page,
                    per_row: this.attributes.per_row,
                    order_by: this.attributes.order_by,
                    sort_by: this.changed.sort_by,
                    no_records: this.attributes.no_records
                });
            }
        }
    });

    domc.addType('wp-permalink', {
        model: {
            defaults: {
                tagName: 'span',
                textable: true,
                traits: [{
                    type: "text",
                    label: "id",
                    name: "id",
                    placeholder: "12345",
                    changeProp: true
                }, {
                    type: "text",
                    label: "title",
                    name: "title",
                    placeholder: (0, _useTranslation2.default)("eg. Text here"),
                    changeProp: true
                }, {
                    type: 'select',
                    label: 'Surrounding tag',
                    name: 'tag',
                    changeProp: true,
                    options: surroundingTags
                }, {
                    type: 'select',
                    label: 'Link target',
                    name: 'target',
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { name: "Opens in a new window or tab", id: "_blank" }, { name: "Opens in the same frame as it was clicked", id: "_self" }, { name: "Opens in the parent frame", id: "_parent" }, { name: "Opens in the full body of the window", id: "_top" }]
                }, {
                    type: "text",
                    label: "Permalink text",
                    name: "anchor",
                    placeholder: (0, _useTranslation2.default)("eg. Text here"),
                    changeProp: true
                }]
            },
            init: function init() {
                this.on('change:id', this.handleIdChange);
                this.on('change:title', this.handleTitleChange);
                this.on('change:tag', this.handleTagChange);
                this.on('change:target', this.handleTargetChange);
                this.on('change:anchor', this.handleAnchorTextChange);
            },
            handleIdChange: function handleIdChange() {
                this.setAttributes({ id: this.changed.id });
            },
            handleTitleChange: function handleTitleChange() {
                this.setAttributes({ title: this.changed.title });
            },
            handleTagChange: function handleTagChange() {
                this.set("tagName", this.changed.tag);
            },
            handleAnchorTextChange: function handleAnchorTextChange() {
                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/anchor="[a-z_]*"/i, "");

                if (this.changed.anchor !== "null") {
                    newContent = "{{" + newContent + " anchor=\"" + this.changed.anchor + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            },
            handleTargetChange: function handleTargetChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/target="[a-z_]*"/i, "");

                if (this.changed.target !== "null") {
                    newContent = "{{" + newContent + " target=\"" + this.changed.target + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            }
        },
        view: {
            onRender: function onRender() {
                var model = this.model,
                    el = this.el;

                el.setAttribute('style', styles.wp);
            }
        }
    });

    domc.addType('wp-date', {
        model: {
            defaults: {
                tagName: 'span',
                textable: true,
                traits: [{
                    type: "text",
                    label: "id",
                    name: "id",
                    placeholder: "12345",
                    changeProp: true
                }, {
                    type: "text",
                    label: "title",
                    name: "title",
                    placeholder: (0, _useTranslation2.default)("eg. Text here"),
                    changeProp: true
                }, {
                    type: 'select',
                    label: 'Surrounding tag',
                    name: 'tag',
                    changeProp: true,
                    options: surroundingTags
                }, {
                    type: 'select',
                    label: 'Date format',
                    name: 'format',
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { id: "d-M-y", name: "dd-mmm-yy (ex. 28-OCT-90)" }, { id: "d-M-Y", name: "dd-mmm-yyyy (ex. 28-OCT-1990)" }, { id: "d M y", name: "mmm yy (ex. 28 OCT 90)" }, { id: "d M Y", name: "mmm yyyy (ex. 28 OCT 1990)" }, { id: "d/m/Y", name: "dd/mm/yy (ex. 28/10/90)" }, { id: "d/m/Y", name: "dd/mm/yyyy (ex. 28/10/1990)" }, { id: "m/d/y", name: "mm/dd/yy (ex. 10/28/90)" }, { id: "m/d/Y", name: "mm/dd/yyyy (ex. 10/28/1990)" }, { id: "d.m.Y", name: "dd.mm.yy (ex. 28.10.90)" }, { id: "d.m.Y", name: "dd.mm.yyyy (ex. 28.10.1990)" }]
                }]
            },
            init: function init() {
                this.on('change:id', this.handleIdChange);
                this.on('change:title', this.handleTitleChange);
                this.on('change:tag', this.handleTagChange);
                this.on('change:format', this.handleDateFormatChange);
            },
            handleIdChange: function handleIdChange() {
                this.setAttributes({ id: this.changed.id });
            },
            handleTitleChange: function handleTitleChange() {
                this.setAttributes({ title: this.changed.title });
            },
            handleTagChange: function handleTagChange() {
                this.set("tagName", this.changed.tag);
            },
            handleDateFormatChange: function handleDateFormatChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/format="[a-zA-Z_\s\-\/\.]*"/i, "");

                if (this.changed.format !== "null") {
                    newContent = "{{" + newContent + " format=\"" + this.changed.format + "\"}}";
                } else {
                    newContent = "{{" + newContent.trim() + "}}";
                }

                this.set("content", newContent.trim());
            }
        },
        view: {
            onRender: function onRender() {
                var model = this.model,
                    el = this.el;

                el.setAttribute('style', styles.wp);
            }
        }
    });

    domc.addType('wp-thumbnail', {
        model: {
            defaults: {
                tagName: 'span',
                textable: true,
                traits: [{
                    type: "text",
                    label: "id",
                    name: "id",
                    placeholder: "12345",
                    changeProp: true
                }, {
                    type: "text",
                    label: "title",
                    name: "title",
                    placeholder: (0, _useTranslation2.default)("eg. Text here"),
                    changeProp: true
                }, {
                    type: 'select',
                    label: 'Surrounding tag',
                    name: 'tag',
                    changeProp: true,
                    options: surroundingTags
                }, {
                    type: 'select',
                    label: 'Format',
                    name: 'format',
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { name: (0, _useTranslation2.default)("Thumbnail"), id: "thumbnail" }, { name: (0, _useTranslation2.default)("Medium"), id: "medium" }, { name: (0, _useTranslation2.default)("Large"), id: "large" }, { name: (0, _useTranslation2.default)("Full size"), id: "full" }]
                }]
            },
            init: function init() {
                this.on('change:id', this.handleIdChange);
                this.on('change:title', this.handleTitleChange);
                this.on('change:tag', this.handleTagChange);
                this.on('change:format', this.handleFormatChange);
            },
            handleIdChange: function handleIdChange() {
                this.setAttributes({ id: this.changed.id });
            },
            handleTitleChange: function handleTitleChange() {
                this.setAttributes({ title: this.changed.title });
            },
            handleTagChange: function handleTagChange() {
                this.set("tagName", this.changed.tag);
            },
            handleFormatChange: function handleFormatChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/format="[a-zA-Z_\s\-\/\.]*"/i, "");

                if (this.changed.format !== "null") {
                    newContent = "{{" + newContent + " format=\"" + this.changed.format + "\"}}";
                } else {
                    newContent = "{{" + newContent.trim() + "}}";
                }

                this.set("content", newContent.trim());
            }
        },
        view: {
            onRender: function onRender() {
                var model = this.model,
                    el = this.el;


                el.setAttribute('style', styles.wp);
            }
        }
    });

    // ACPT element
    domc.addType('acpt-meta', {
        model: {
            defaults: {
                tagName: 'span',
                textable: true,
                traits: [{
                    type: "text",
                    label: "id",
                    name: "id",
                    placeholder: "12345",
                    changeProp: true
                }, {
                    type: "text",
                    label: "title",
                    name: "title",
                    placeholder: (0, _useTranslation2.default)("eg. Text here"),
                    changeProp: true
                }, {
                    type: 'select',
                    label: (0, _useTranslation2.default)('Surrounding tag'),
                    name: 'tag',
                    changeProp: true,
                    options: surroundingTags
                }, {
                    type: "text",
                    label: "Width",
                    name: (0, _useTranslation2.default)("width"),
                    placeholder: (0, _useTranslation2.default)("eg. 100%"),
                    changeProp: true
                }, {
                    type: "text",
                    label: (0, _useTranslation2.default)("Height"),
                    name: (0, _useTranslation2.default)("height"),
                    placeholder: (0, _useTranslation2.default)("eg. 300px"),
                    changeProp: true
                }, {
                    type: 'select',
                    label: (0, _useTranslation2.default)('Link target'),
                    name: (0, _useTranslation2.default)('target'),
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { name: (0, _useTranslation2.default)("Opens in a new window or tab"), id: "_blank" }, { name: (0, _useTranslation2.default)("Opens in the same frame as it was clicked"), id: "_self" }, { name: (0, _useTranslation2.default)("Opens in the parent frame"), id: "_parent" }, { name: (0, _useTranslation2.default)("Opens in the full body of the window"), id: "_top" }]
                }, {
                    type: 'select',
                    label: (0, _useTranslation2.default)('Date format'),
                    name: (0, _useTranslation2.default)('date'),
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { id: "d-M-y", name: "dd-mmm-yy (ex. 28-OCT-90)" }, { id: "d-M-Y", name: "dd-mmm-yyyy (ex. 28-OCT-1990)" }, { id: "d M y", name: "mmm yy (ex. 28 OCT 90)" }, { id: "d M Y", name: "mmm yyyy (ex. 28 OCT 1990)" }, { id: "d/m/Y", name: "dd/mm/yy (ex. 28/10/90)" }, { id: "d/m/Y", name: "dd/mm/yyyy (ex. 28/10/1990)" }, { id: "m/d/y", name: "mm/dd/yy (ex. 10/28/90)" }, { id: "m/d/Y", name: "mm/dd/yyyy (ex. 10/28/1990)" }, { id: "d.m.Y", name: "dd.mm.yy (ex. 28.10.90)" }, { id: "d.m.Y", name: "dd.mm.yyyy (ex. 28.10.1990)" }]
                }, {
                    type: 'number',
                    label: (0, _useTranslation2.default)('Elements to display'),
                    name: (0, _useTranslation2.default)('per_page'),
                    default: 3,
                    min: 1,
                    max: 6,
                    step: 1,
                    changeProp: true
                }, {
                    type: 'select',
                    label: (0, _useTranslation2.default)('What you want to display?'),
                    name: (0, _useTranslation2.default)('render'),
                    changeProp: true,
                    options: [{ name: (0, _useTranslation2.default)("--Select---"), id: null }, { id: "value", name: (0, _useTranslation2.default)("Value") }, { id: "label", name: (0, _useTranslation2.default)("Label") }]
                }]
            },
            init: function init() {
                this.on('change:id', this.handleIdChange);
                this.on('change:title', this.handleTitleChange);
                this.on('change:tag', this.handleTagChange);
                this.on('change:width', this.handleWidthChange);
                this.on('change:height', this.handleHeightChange);
                this.on('change:date', this.handleDateChange);
                this.on('change:target', this.handleTargetChange);
                this.on('change:elements', this.handleElementsChange);
                this.on('change:render', this.handleRenderChange);
            },
            handleIdChange: function handleIdChange() {
                this.setAttributes({
                    id: this.changed.id,
                    title: this.attributes.title

                });
            },
            handleTitleChange: function handleTitleChange() {
                this.setAttributes({
                    id: this.attributes.id,
                    title: this.changed.title
                });
            },
            handleTagChange: function handleTagChange() {
                this.set("tagName", this.changed.tag);
            },
            handleWidthChange: function handleWidthChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/width="[a-z0-9]*"/i, "");

                if (this.changed.width !== "") {
                    newContent = "{{" + newContent + " width=\"" + this.changed.width + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            },
            handleHeightChange: function handleHeightChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/height="[a-z0-9]*"/i, "");

                if (this.changed.height !== "") {
                    newContent = "{{" + newContent + " height=\"" + this.changed.height + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            },
            handleDateChange: function handleDateChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/date-format="[a-zA-Z_\s\-\/\.]*"/i, "");

                if (this.changed.date !== "null") {
                    newContent = "{{" + newContent + " date-format=\"" + this.changed.date + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            },
            handleTargetChange: function handleTargetChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/target="[a-z_]*"/i, "");

                if (this.changed.target !== "null") {
                    newContent = "{{" + newContent + " target=\"" + this.changed.target + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            },
            handleElementsChange: function handleElementsChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/elements="[1-6]*"/i, "");

                if (this.changed.elements !== "null") {
                    newContent = "{{" + newContent + " elements=\"" + this.changed.elements + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            },
            handleRenderChange: function handleRenderChange() {

                var newContent = this.attributes.content;
                newContent = newContent.replace("{{", "");
                newContent = newContent.replace("}}", "");
                newContent = newContent.replace(/render="[a-z_]*"/i, "");

                if (this.changed.render !== "null") {
                    newContent = "{{" + newContent + " render=\"" + this.changed.render + "\"}}";
                } else {
                    newContent = "{{" + newContent + "}}";
                }

                this.set("content", newContent.trim());
            }
        },
        view: {
            onRender: function onRender() {
                // @TODO ajax call for dynamic render it
                var model = this.model,
                    el = this.el;

                el.setAttribute('style', styles.acpt);
            }
        }
    });
};

/***/ }),

/***/ 6573:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var blockManager = exports.blockManager = function blockManager() {

    return {
        appendTo: '.gjs-blocks-container'
    };
};

/***/ }),

/***/ 14894:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.deviceManager = undefined;

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var deviceManager = exports.deviceManager = function deviceManager() {

    return {
        devices: [{
            name: (0, _useTranslation2.default)('Desktop'),
            width: '' // default size
        }, {
            name: (0, _useTranslation2.default)('Tablet'),
            width: '678px', // this value will be used on canvas width
            widthMedia: '800px' // this value will be used in CSS @media
        }, {
            name: (0, _useTranslation2.default)('Mobile'),
            width: '320px', // this value will be used on canvas width
            widthMedia: '480px' // this value will be used in CSS @media
        }]
    };
};

/***/ }),

/***/ 89327:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var layerManager = exports.layerManager = function layerManager() {

    return {
        appendTo: '.gjs-layers-container',
        sortable: true,
        hidable: true
    };
};

/***/ }),

/***/ 61428:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var panels = exports.panels = function panels() {

    return {
        defaults: [{
            id: 'panel-devices',
            el: '.gjs-devices',
            buttons: [{
                id: 'device-desktop',
                className: 'btn-toggle-borders',
                label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M20 3H4c-1.103 0-2 .897-2 2v11c0 1.103.897 2 2 2h7v2H8v2h8v-2h-3v-2h7c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zM4 14V5h16l.002 9H4z"/></svg>',
                command: 'set-device-desktop',
                active: true
            }, {
                id: 'device-tablet',
                className: 'btn-toggle-borders',
                label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16"><g fill="currentColor"><path d="M1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4zm-1 8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8z"/><path d="M14 8a1 1 0 1 0-2 0a1 1 0 0 0 2 0z"/></g></svg>',
                command: 'set-device-tablet',
                active: false
            }, {
                id: 'device-mobile',
                className: 'btn-toggle-borders',
                label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M16.75 2h-10c-1.103 0-2 .897-2 2v16c0 1.103.897 2 2 2h10c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zm-10 18V4h10l.002 16H6.75z"/><circle cx="11.75" cy="18" r="1" fill="currentColor"/></svg>',
                command: 'set-device-mobile',
                active: false
            }]
        }, {
            id: 'panel-switcher',
            el: '.gjs-panel-switcher',
            buttons: [{
                id: 'show-layers',
                active: false,
                label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M22 7.999a1 1 0 0 0-.516-.874l-9.022-5a1.003 1.003 0 0 0-.968 0l-8.978 4.96a1 1 0 0 0-.003 1.748l9.022 5.04a.995.995 0 0 0 .973.001l8.978-5A1 1 0 0 0 22 7.999zm-9.977 3.855L5.06 7.965l6.917-3.822l6.964 3.859l-6.918 3.852z"/><path fill="currentColor" d="M20.515 11.126L12 15.856l-8.515-4.73l-.971 1.748l9 5a1 1 0 0 0 .971 0l9-5l-.97-1.748z"/><path fill="currentColor" d="M20.515 15.126L12 19.856l-8.515-4.73l-.971 1.748l9 5a1 1 0 0 0 .971 0l9-5l-.97-1.748z"/></svg>',
                command: 'show-layers'
            }, {
                id: 'show-style',
                active: false,
                label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M13.707 2.293a.999.999 0 0 0-1.414 0l-5.84 5.84c-.015-.001-.029-.009-.044-.009a.997.997 0 0 0-.707.293L4.288 9.831a2.985 2.985 0 0 0-.878 2.122c0 .802.313 1.556.879 2.121l.707.707l-2.122 2.122A2.92 2.92 0 0 0 2 19.012a2.968 2.968 0 0 0 1.063 2.308c.519.439 1.188.68 1.885.68c.834 0 1.654-.341 2.25-.937l2.04-2.039l.707.706c1.134 1.133 3.109 1.134 4.242.001l1.415-1.414a.997.997 0 0 0 .293-.707c0-.026-.013-.05-.015-.076l5.827-5.827a.999.999 0 0 0 0-1.414l-8-8zm-.935 16.024a1.023 1.023 0 0 1-1.414-.001l-1.414-1.413a.999.999 0 0 0-1.414 0l-2.746 2.745a1.19 1.19 0 0 1-.836.352a.91.91 0 0 1-.594-.208A.978.978 0 0 1 4 19.01a.959.959 0 0 1 .287-.692l2.829-2.829a.999.999 0 0 0 0-1.414L5.701 12.66a.99.99 0 0 1-.292-.706c0-.268.104-.519.293-.708l.707-.707l7.071 7.072l-.708.706zm1.889-2.392L8.075 9.339L13 4.414L19.586 11l-4.925 4.925z"/></svg>',
                command: 'show-styles'
            }, {
                id: 'show-blocks',
                active: true,
                label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><rect width="18" height="12" x="3" y="8" rx="1"/><path d="M10 8V5c0-.6-.4-1-1-1H6a1 1 0 0 0-1 1v3m14 0V5c0-.6-.4-1-1-1h-3a1 1 0 0 0-1 1v3"/></g></svg>',
                command: 'show-blocks'
            }, {
                id: 'show-traits',
                active: false,
                label: '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M12 16c2.206 0 4-1.794 4-4s-1.794-4-4-4s-4 1.794-4 4s1.794 4 4 4zm0-6c1.084 0 2 .916 2 2s-.916 2-2 2s-2-.916-2-2s.916-2 2-2z"/><path fill="currentColor" d="m2.845 16.136l1 1.73c.531.917 1.809 1.261 2.73.73l.529-.306A8.1 8.1 0 0 0 9 19.402V20c0 1.103.897 2 2 2h2c1.103 0 2-.897 2-2v-.598a8.132 8.132 0 0 0 1.896-1.111l.529.306c.923.53 2.198.188 2.731-.731l.999-1.729a2.001 2.001 0 0 0-.731-2.732l-.505-.292a7.718 7.718 0 0 0 0-2.224l.505-.292a2.002 2.002 0 0 0 .731-2.732l-.999-1.729c-.531-.92-1.808-1.265-2.731-.732l-.529.306A8.1 8.1 0 0 0 15 4.598V4c0-1.103-.897-2-2-2h-2c-1.103 0-2 .897-2 2v.598a8.132 8.132 0 0 0-1.896 1.111l-.529-.306c-.924-.531-2.2-.187-2.731.732l-.999 1.729a2.001 2.001 0 0 0 .731 2.732l.505.292a7.683 7.683 0 0 0 0 2.223l-.505.292a2.003 2.003 0 0 0-.731 2.733zm3.326-2.758A5.703 5.703 0 0 1 6 12c0-.462.058-.926.17-1.378a.999.999 0 0 0-.47-1.108l-1.123-.65l.998-1.729l1.145.662a.997.997 0 0 0 1.188-.142a6.071 6.071 0 0 1 2.384-1.399A1 1 0 0 0 11 5.3V4h2v1.3a1 1 0 0 0 .708.956a6.083 6.083 0 0 1 2.384 1.399a.999.999 0 0 0 1.188.142l1.144-.661l1 1.729l-1.124.649a1 1 0 0 0-.47 1.108c.112.452.17.916.17 1.378c0 .461-.058.925-.171 1.378a1 1 0 0 0 .471 1.108l1.123.649l-.998 1.729l-1.145-.661a.996.996 0 0 0-1.188.142a6.071 6.071 0 0 1-2.384 1.399A1 1 0 0 0 13 18.7l.002 1.3H11v-1.3a1 1 0 0 0-.708-.956a6.083 6.083 0 0 1-2.384-1.399a.992.992 0 0 0-1.188-.141l-1.144.662l-1-1.729l1.124-.651a1 1 0 0 0 .471-1.108z"/></svg>',
                command: 'show-traits'
            }]
        }]
    };
};

/***/ }),

/***/ 82489:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.plugins = undefined;

var _grapesjsBlocksBasic = __webpack_require__(88426);

var _grapesjsBlocksBasic2 = _interopRequireDefault(_grapesjsBlocksBasic);

var _grapesjsCustomCode = __webpack_require__(67026);

var _grapesjsCustomCode2 = _interopRequireDefault(_grapesjsCustomCode);

var _grapesjsPluginToolbox = __webpack_require__(41356);

var _grapesjsPluginToolbox2 = _interopRequireDefault(_grapesjsPluginToolbox);

var _grapesjsStyleFilter = __webpack_require__(44227);

var _grapesjsStyleFilter2 = _interopRequireDefault(_grapesjsStyleFilter);

var _grapesjsRteExtensions = __webpack_require__(9321);

var _grapesjsRteExtensions2 = _interopRequireDefault(_grapesjsRteExtensions);

var _grapesjsTabs = __webpack_require__(96120);

var _grapesjsTabs2 = _interopRequireDefault(_grapesjsTabs);

__webpack_require__(9690);

__webpack_require__(35744);

__webpack_require__(83752);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var plugins = exports.plugins = function plugins() {

    return [function (editor) {
        return (0, _grapesjsBlocksBasic2.default)(editor, {
            stylePrefix: '',
            flexGrid: 1
        });
    }, function (editor) {
        return (0, _grapesjsCustomCode2.default)(editor, {});
    }, function (editor) {
        return (0, _grapesjsPluginToolbox2.default)(editor, {
            traitsInSm: false
        });
    }, function (editor) {
        return (0, _grapesjsStyleFilter2.default)(editor, {});
    }, function (editor) {
        return (0, _grapesjsRteExtensions2.default)(editor, {
            fonts: {
                fontSize: true,
                fontColor: true,
                hilite: false
            },
            list: true,
            align: true,
            actions: true
        });
    }, function (editor) {
        return (0, _grapesjsTabs2.default)(editor, {
            tabsBlock: { category: 'Extra' }
        });
    }];
};

/***/ }),

/***/ 44520:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var selectorManager = exports.selectorManager = function selectorManager() {

    return {
        appendTo: '.gjs-styles-container'
    };
};

/***/ }),

/***/ 39298:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var storageManager = exports.storageManager = function storageManager(belongsTo, template, find, metaFieldId) {

    var globals = document.globals;
    var urlStore = '' + globals.site_url + globals.rest_route_url + '/template/store';
    var urlLoad = '' + globals.site_url + globals.rest_route_url + '/template/load/' + belongsTo + '/' + template + (find ? '/' + find : '') + (metaFieldId ? '/' + metaFieldId : '');

    return {
        autosave: false,
        type: 'remote',
        stepsBeforeSave: 3,
        options: {
            remote: {
                urlLoad: urlLoad,
                urlStore: urlStore,
                contentTypeJson: true,
                onStore: function onStore(data, editor) {

                    var pagesHtml = editor.Pages.getAll().map(function (page) {
                        var component = page.getMainComponent();
                        return {
                            html: editor.getHtml({ component: component }),
                            css: editor.getCss({ component: component })
                        };
                    });

                    return { data: data, pagesHtml: pagesHtml, template: template, belongsTo: belongsTo, find: find, metaFieldId: metaFieldId };
                },
                onLoad: function onLoad(result) {
                    return result.data;
                }
            }
        }
    };
};

/***/ }),

/***/ 92624:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var styleManager = exports.styleManager = function styleManager() {

    return {
        appendTo: '.gjs-styles-container',
        sectors: [{
            name: 'General',
            properties: [{
                extend: 'float',
                type: 'radio',
                default: 'none',
                options: [{ value: 'none', className: 'fa fa-times' }, { value: 'left', className: 'fa fa-align-left' }, { value: 'right', className: 'fa fa-align-right' }]
            }, 'display', { extend: 'position', type: 'select' }, 'top', 'right', 'left', 'bottom']
        }, {
            name: 'Dimension',
            open: false,
            properties: ['width', {
                id: 'flex-width',
                type: 'integer',
                name: 'Width',
                units: ['px', '%'],
                property: 'flex-basis',
                toRequire: 1
            }, 'height', 'max-width', 'min-height', 'margin', 'padding']
        }, {
            name: 'Typography',
            open: false,
            properties: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', {
                extend: 'text-align',
                options: [{ id: 'left', label: 'Left', className: 'fa fa-align-left' }, { id: 'center', label: 'Center', className: 'fa fa-align-center' }, { id: 'right', label: 'Right', className: 'fa fa-align-right' }, { id: 'justify', label: 'Justify', className: 'fa fa-align-justify' }]
            }, {
                property: 'text-decoration',
                type: 'radio',
                default: 'none',
                options: [{ id: 'none', label: 'None', className: 'fa fa-times' }, { id: 'underline', label: 'underline', className: 'fa fa-underline' }, { id: 'line-through', label: 'Line-through', className: 'fa fa-strikethrough' }]
            }, 'text-shadow']
        }, {
            name: 'Decorations',
            open: false,
            properties: ['opacity', 'border-radius', 'border', 'box-shadow',
            // 'background',
            {
                id: 'background-color',
                label: 'Background color',
                property: 'background-color',
                type: 'color'
            }]
        }, {
            name: 'Extra',
            open: false,
            buildProps: ['transition', 'perspective', 'transform']
        }, {
            name: 'Flex',
            open: false,
            properties: [{
                name: 'Flex Container',
                property: 'display',
                type: 'select',
                defaults: 'block',
                list: [{ value: 'block', name: 'Disable' }, { value: 'flex', name: 'Enable' }]
            }, {
                name: 'Flex Parent',
                property: 'label-parent-flex',
                type: 'integer'
            }, {
                name: 'Direction',
                property: 'flex-direction',
                type: 'radio',
                defaults: 'row',
                list: [{
                    value: 'row',
                    name: 'Row',
                    className: 'icons-flex icon-dir-row',
                    title: 'Row'
                }, {
                    value: 'row-reverse',
                    name: 'Row reverse',
                    className: 'icons-flex icon-dir-row-rev',
                    title: 'Row reverse'
                }, {
                    value: 'column',
                    name: 'Column',
                    title: 'Column',
                    className: 'icons-flex icon-dir-col'
                }, {
                    value: 'column-reverse',
                    name: 'Column reverse',
                    title: 'Column reverse',
                    className: 'icons-flex icon-dir-col-rev'
                }]
            }, {
                name: 'Justify',
                property: 'justify-content',
                type: 'radio',
                defaults: 'flex-start',
                list: [{
                    value: 'flex-start',
                    className: 'icons-flex icon-just-start',
                    title: 'Start'
                }, {
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-just-end'
                }, {
                    value: 'space-between',
                    title: 'Space between',
                    className: 'icons-flex icon-just-sp-bet'
                }, {
                    value: 'space-around',
                    title: 'Space around',
                    className: 'icons-flex icon-just-sp-ar'
                }, {
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-just-sp-cent'
                }]
            }, {
                name: 'Align',
                property: 'align-items',
                type: 'radio',
                defaults: 'center',
                list: [{
                    value: 'flex-start',
                    title: 'Start',
                    className: 'icons-flex icon-al-start'
                }, {
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-al-end'
                }, {
                    value: 'stretch',
                    title: 'Stretch',
                    className: 'icons-flex icon-al-str'
                }, {
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-al-center'
                }]
            }, {
                name: 'Flex Children',
                property: 'label-parent-flex',
                type: 'integer'
            }, {
                name: 'Order',
                property: 'order',
                type: 'integer',
                defaults: 0,
                min: 0
            }, {
                name: 'Flex',
                property: 'flex',
                type: 'composite',
                properties: [{
                    name: 'Grow',
                    property: 'flex-grow',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                }, {
                    name: 'Shrink',
                    property: 'flex-shrink',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                }, {
                    name: 'Basis',
                    property: 'flex-basis',
                    type: 'integer',
                    units: ['px', '%', ''],
                    unit: '',
                    defaults: 'auto'
                }]
            }, {
                name: 'Align',
                property: 'align-self',
                type: 'radio',
                defaults: 'auto',
                list: [{
                    value: 'auto',
                    name: 'Auto'
                }, {
                    value: 'flex-start',
                    title: 'Start',
                    className: 'icons-flex icon-al-start'
                }, {
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-al-end'
                }, {
                    value: 'stretch',
                    title: 'Stretch',
                    className: 'icons-flex icon-al-str'
                }, {
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-al-center'
                }]
            }]
        }]
    };
};

/***/ }),

/***/ 96859:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var styles = exports.styles = function styles() {

    var googleFontsUrl = 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Montserrat:ital,wght@0,400;0,800;1,400;1,800&family=Open+Sans:ital,wght@0,400;0,800;1,400;1,800&family=Oswald:wght@400;700&family=Poppins:ital,wght@0,400;0,800;1,400;1,800&family=Raleway:ital,wght@0,400;0,800;1,400;1,800&family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap';

    return {
        styles: [googleFontsUrl, '/wp-content/plugins/advanced-custom-post-type/assets/static/css/gjs_canvas.css']
    };
};

/***/ }),

/***/ 99901:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var traitManager = exports.traitManager = function traitManager() {

    return {
        appendTo: '.gjs-traits-container'
    };
};

/***/ }),

/***/ 56232:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchMetaFieldsFromBelongsTo = exports.fetchFindFromBelongsTo = undefined;

var _ajax = __webpack_require__(47569);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchFindFromBelongsTo = exports.fetchFindFromBelongsTo = function () {
    var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(belongsTo) {
        return regeneratorRuntime.wrap(function _callee$(_context) {
            while (1) {
                switch (_context.prev = _context.next) {
                    case 0:
                        _context.next = 2;
                        return (0, _ajax.wpAjaxRequest)("fetchFindFromBelongsToAction", { belongsTo: belongsTo });

                    case 2:
                        return _context.abrupt("return", _context.sent);

                    case 3:
                    case "end":
                        return _context.stop();
                }
            }
        }, _callee, undefined);
    }));

    return function fetchFindFromBelongsTo(_x) {
        return _ref.apply(this, arguments);
    };
}();

var fetchMetaFieldsFromBelongsTo = exports.fetchMetaFieldsFromBelongsTo = function () {
    var _ref2 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee2(belongsTo, find) {
        return regeneratorRuntime.wrap(function _callee2$(_context2) {
            while (1) {
                switch (_context2.prev = _context2.next) {
                    case 0:
                        _context2.next = 2;
                        return (0, _ajax.wpAjaxRequest)("fetchMetaFieldsFromBelongsToAction", { belongsTo: belongsTo, find: find });

                    case 2:
                        return _context2.abrupt("return", _context2.sent);

                    case 3:
                    case "end":
                        return _context2.stop();
                }
            }
        }, _callee2, undefined);
    }));

    return function fetchMetaFieldsFromBelongsTo(_x2, _x3) {
        return _ref2.apply(this, arguments);
    };
}();

/***/ }),

/***/ 51666:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();
// Styles

// GrapesJS


var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRedux = __webpack_require__(28216);

var _fetchPostData = __webpack_require__(71986);

__webpack_require__(33070);

__webpack_require__(92065);

var _grapesjs = __webpack_require__(58531);

var _grapesjs2 = _interopRequireDefault(_grapesjs);

var _deviceManager = __webpack_require__(14894);

var _blockManager = __webpack_require__(6573);

var _layerManager = __webpack_require__(89327);

var _styleManager = __webpack_require__(92624);

var _traitManager = __webpack_require__(99901);

var _selectorManager = __webpack_require__(44520);

var _panels = __webpack_require__(61428);

var _plugins = __webpack_require__(82489);

var _styles = __webpack_require__(96859);

var _storageManager = __webpack_require__(39298);

var _addCommands = __webpack_require__(44317);

var _addGoogleFonts = __webpack_require__(66811);

var _addPanels = __webpack_require__(5582);

var _addBlocks = __webpack_require__(49809);

var _addStyles = __webpack_require__(15823);

var _addTraits = __webpack_require__(664);

var _Canvas = __webpack_require__(61374);

var _Canvas2 = _interopRequireDefault(_Canvas);

var _Sidebar = __webpack_require__(25170);

var _Sidebar2 = _interopRequireDefault(_Sidebar);

var _Top = __webpack_require__(36903);

var _Top2 = _interopRequireDefault(_Top);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _objects = __webpack_require__(54040);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var VisualBuilder = function VisualBuilder(_ref) {
    var belongsTo = _ref.belongsTo,
        find = _ref.find,
        metaFieldId = _ref.metaFieldId,
        postList = _ref.postList,
        taxonomyList = _ref.taxonomyList,
        template = _ref.template,
        showPreviewLink = _ref.showPreviewLink,
        exitLink = _ref.exitLink;


    // Visual Builder cannot be used under 960px resolution
    window.addEventListener("resize", function () {
        setBrowserWidth(document.body.clientWidth);
    });

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchSettingsReducer;
    }),
        settingsLoading = _useSelector.loading,
        settings = _useSelector.fetched;

    // manage local state


    var _useState = (0, _react.useState)(document.body.clientWidth),
        _useState2 = _slicedToArray(_useState, 2),
        browserWidth = _useState2[0],
        setBrowserWidth = _useState2[1];

    var _useState3 = (0, _react.useState)(null),
        _useState4 = _slicedToArray(_useState3, 2),
        editor = _useState4[0],
        setEditor = _useState4[1];

    var _useState5 = (0, _react.useState)(null),
        _useState6 = _slicedToArray(_useState5, 2),
        blocks = _useState6[0],
        setBlocks = _useState6[1];

    var _useState7 = (0, _react.useState)(false),
        _useState8 = _slicedToArray(_useState7, 2),
        loadingBlocks = _useState8[0],
        setLoadingBlocks = _useState8[1];

    var _useState9 = (0, _react.useState)(postList.length > 0 ? postList[0].id : null),
        _useState10 = _slicedToArray(_useState9, 2),
        leadingPostId = _useState10[0],
        setLeadingPostId = _useState10[1];

    var _useState11 = (0, _react.useState)(taxonomyList.length > 0 ? taxonomyList[0].term_id : null),
        _useState12 = _slicedToArray(_useState11, 2),
        leadingTermId = _useState12[0],
        setLeadingTermId = _useState12[1];

    var enableVisualEditor = settings.length > 0 && (0, _objects.filterByLabel)(settings, 'key', 'enable_visual_editor') !== '' ? (0, _objects.filterByLabel)(settings, 'key', 'enable_visual_editor').value : false;

    // Fetching blocks
    (0, _react.useEffect)(function () {

        dispatch((0, _fetchPostData.fetchPostData)(leadingPostId));

        var fetchBlocks = function () {
            var _ref2 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
                var globals, blocksApiUrl, response;
                return regeneratorRuntime.wrap(function _callee$(_context) {
                    while (1) {
                        switch (_context.prev = _context.next) {
                            case 0:
                                globals = document.globals;
                                blocksApiUrl = "" + globals.site_url + globals.rest_route_url + "/template/block/" + belongsTo + "/" + template + (find ? '/' + find : '') + (metaFieldId ? '/' + metaFieldId : '');


                                setLoadingBlocks(true);
                                _context.next = 5;
                                return fetch(blocksApiUrl);

                            case 5:
                                response = _context.sent;
                                _context.next = 8;
                                return response.json();

                            case 8:
                                response = _context.sent;

                                setLoadingBlocks(false);
                                setBlocks(response);

                            case 11:
                            case "end":
                                return _context.stop();
                        }
                    }
                }, _callee, undefined);
            }));

            return function fetchBlocks() {
                return _ref2.apply(this, arguments);
            };
        }();

        fetchBlocks();
    }, []);

    // init GrapesJS
    (0, _react.useEffect)(function () {

        if (blocks !== null) {

            // Disable body scroll
            document.body.style.overflow = 'hidden';

            // Init editor
            var _editor = _grapesjs2.default.init({
                container: '.gjs-editor',
                fromElement: true,
                canvas: (0, _styles.styles)(),
                plugins: (0, _plugins.plugins)(),
                blockManager: (0, _blockManager.blockManager)(),
                deviceManager: (0, _deviceManager.deviceManager)(),
                layerManager: (0, _layerManager.layerManager)(),
                styleManager: (0, _styleManager.styleManager)(),
                selectorManager: (0, _selectorManager.selectorManager)(),
                traitManager: (0, _traitManager.traitManager)(),
                storageManager: (0, _storageManager.storageManager)(belongsTo, template, find, metaFieldId),
                panels: (0, _panels.panels)()
            });

            // Blocks
            (0, _addBlocks.addBlocks)(_editor, blocks);

            // Commands
            (0, _addCommands.addCommands)(_editor, exitLink);

            // Panels
            (0, _addPanels.addPanels)(_editor);

            // StyleManager
            (0, _addStyles.addStyles)(_editor);

            // Traits
            (0, _addTraits.addTraits)(_editor);

            // Google fonts
            (0, _addGoogleFonts.addGoogleFonts)(_editor);

            setEditor(_editor);
        }
    }, [blocks]);

    // Dynamically fetch post data on leading post ID changes
    (0, _react.useEffect)(function () {
        dispatch((0, _fetchPostData.fetchPostData)(leadingPostId));
    }, [leadingPostId]);

    // Query blocks
    var blockQueryHandle = function blockQueryHandle(query) {
        var bm = editor.Blocks;
        var all = bm.getAll();
        var filter = void 0;
        query && (filter = all.filter(function (block) {
            return block.get('label').toLowerCase().indexOf(query.toLowerCase()) > -1;
        })) || (filter = all.filter(function (block) {
            return true;
        }));
        bm.render(filter);
    };

    if (loadingBlocks && settingsLoading) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    if (enableVisualEditor !== "1") {
        return wp.element.createElement(
            _react2.default.Fragment,
            null,
            wp.element.createElement(
                "div",
                { style: { marginLeft: "22px" }, className: "update-nag notice notice-warning" },
                (0, _useTranslation2.default)("ALERT: The visual builder is disabled. Go to Settings page and enable it.")
            )
        );
    }

    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            "div",
            { style: { marginLeft: "22px" }, className: "update-nag notice notice-warning " + (browserWidth >= 960 ? 'hidden' : '') },
            (0, _useTranslation2.default)("ALERT: The visual builder can be used with a minimum resolution of 960 px wide")
        ),
        wp.element.createElement(
            "div",
            { className: "gjs-wrapper " + (browserWidth < 960 ? 'hidden' : '') },
            wp.element.createElement(_Top2.default, null),
            wp.element.createElement(
                "div",
                { className: "gjs-main" },
                wp.element.createElement(_Sidebar2.default, {
                    blockQueryHandle: blockQueryHandle
                }),
                wp.element.createElement(_Canvas2.default, {
                    template: template,
                    belongsTo: belongsTo,
                    find: find,
                    leadingPostId: leadingPostId,
                    setLeadingPostId: setLeadingPostId,
                    postList: postList,
                    leadingTermId: leadingTermId,
                    setLeadingTermId: setLeadingTermId,
                    taxonomyList: taxonomyList,
                    showPreviewLink: showPreviewLink
                })
            )
        )
    );
};

exports["default"] = VisualBuilder;

/***/ }),

/***/ 91512:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.useSearchParams = useSearchParams;

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _queryString = __webpack_require__(17563);

var _queryString2 = _interopRequireDefault(_queryString);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function useSearchParams() {
    var _useLocation = (0, _reactRouterDom.useLocation)(),
        search = _useLocation.search;

    return _queryString2.default.parse(search);
}

/***/ }),

/***/ 71986:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchPostData = undefined;

var _ajax = __webpack_require__(47569);

var _fetchPostDataActions = __webpack_require__(56822);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchPostData = exports.fetchPostData = function fetchPostData(id) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchPostDataActions.fetchPostDataInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchPostDataAction', { id: id });

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchPostDataActions.fetchPostDataSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchPostDataActions.fetchPostDataFailure)(_context.t0));

                        case 11:
                        case "end":
                            return _context.stop();
                    }
                }
            }, _callee, undefined, [[0, 8]]);
        }));

        return function (_x, _x2) {
            return _ref.apply(this, arguments);
        };
    }();
};

/***/ }),

/***/ 64574:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchPosts = undefined;

var _ajax = __webpack_require__(47569);

var _fetchPostsActions = __webpack_require__(26325);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchPosts = exports.fetchPosts = function fetchPosts(postType, perPage, sortBy, sortOrder) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchPostsActions.fetchPostsInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchPostsAction', { postType: postType, perPage: perPage, sortBy: sortBy, sortOrder: sortOrder });

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchPostsActions.fetchPostsSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchPostsActions.fetchPostsFailure)(_context.t0));

                        case 11:
                        case "end":
                            return _context.stop();
                    }
                }
            }, _callee, undefined, [[0, 8]]);
        }));

        return function (_x, _x2) {
            return _ref.apply(this, arguments);
        };
    }();
};

/***/ }),

/***/ 84938:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchPreviewLink = undefined;

var _ajax = __webpack_require__(47569);

var _fetchPreviewLinkActions = __webpack_require__(90873);

var _objects = __webpack_require__(54040);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchPreviewLink = exports.fetchPreviewLink = function fetchPreviewLink(belongsTo, find, template, id) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched, previewLink;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchPreviewLinkActions.fetchPreviewLinkInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchPreviewLinkAction', { id: id, belongsTo: belongsTo, find: find, template: template });

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchPreviewLinkActions.fetchPreviewLinkSuccess)(fetched));

                            if (!(0, _objects.isEmpty)(fetched) && fetched.success === true) {
                                previewLink = template === 'archive' ? fetched.data.archive_link : fetched.data.single_link;

                                window.open(previewLink, '_blank');
                            }

                            _context.next = 12;
                            break;

                        case 9:
                            _context.prev = 9;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchPreviewLinkActions.fetchPreviewLinkFailure)(_context.t0));

                        case 12:
                        case "end":
                            return _context.stop();
                    }
                }
            }, _callee, undefined, [[0, 9]]);
        }));

        return function (_x, _x2) {
            return _ref.apply(this, arguments);
        };
    }();
};

/***/ }),

/***/ 57348:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchTerms = undefined;

var _ajax = __webpack_require__(47569);

var _fetchTermsActions = __webpack_require__(41607);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchTerms = exports.fetchTerms = function fetchTerms(taxonomy, perPage, sortBy, sortOrder) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchTermsActions.fetchTermsInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchTermsAction', { taxonomy: taxonomy, perPage: perPage, sortBy: sortBy, sortOrder: sortOrder });

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchTermsActions.fetchTermsSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchTermsActions.fetchTermsFailure)(_context.t0));

                        case 11:
                        case "end":
                            return _context.stop();
                    }
                }
            }, _callee, undefined, [[0, 8]]);
        }));

        return function (_x, _x2) {
            return _ref.apply(this, arguments);
        };
    }();
};

/***/ }),

/***/ 92065:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=8300.js.map