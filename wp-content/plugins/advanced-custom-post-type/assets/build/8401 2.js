"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[8401],{

/***/ 7485:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _CustomPostTypeLabel = __webpack_require__(26054);

var _CustomPostTypeLabel2 = _interopRequireDefault(_CustomPostTypeLabel);

var _metaTypes = __webpack_require__(81895);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ExportElement = function ExportElement(_ref) {
    var element = _ref.element,
        type = _ref.type,
        _onChange = _ref.onChange;


    return wp.element.createElement(
        "div",
        { className: "export-element" },
        wp.element.createElement(
            "div",
            { className: "name" },
            type === _metaTypes.metaTypes.CUSTOM_POST_TYPE && element.name,
            type === _metaTypes.metaTypes.TAXONOMY && element.slug,
            type === _metaTypes.metaTypes.OPTION_PAGE && element.pageTitle,
            type === _metaTypes.metaTypes.USER && element.name
        ),
        wp.element.createElement(
            "div",
            { className: "type" },
            wp.element.createElement(_CustomPostTypeLabel2.default, { element: element })
        ),
        wp.element.createElement(
            "div",
            { className: "check" },
            wp.element.createElement(
                "label",
                { className: "switch" },
                wp.element.createElement("input", {
                    id: element.id,
                    type: "checkbox",
                    defaultChecked: true,
                    onChange: function onChange(e) {
                        return _onChange({
                            id: element.id,
                            type: type,
                            checked: e.target.checked
                        });
                    }
                }),
                wp.element.createElement("span", { className: "slider round" })
            )
        )
    );
};

exports["default"] = ExportElement;

/***/ }),

/***/ 15169:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _ExportElement = __webpack_require__(7485);

var _ExportElement2 = _interopRequireDefault(_ExportElement);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ExportWrapper = function ExportWrapper(_ref) {
    var name = _ref.name,
        elements = _ref.elements,
        type = _ref.type,
        handleChange = _ref.handleChange;


    return wp.element.createElement(
        "div",
        { className: "acpt-card" },
        wp.element.createElement(
            "div",
            { className: "acpt-card__header" },
            wp.element.createElement(
                "div",
                { className: "acpt-card__inner" },
                wp.element.createElement(
                    "h3",
                    null,
                    name
                )
            )
        ),
        wp.element.createElement(
            "div",
            { className: "acpt-card__body" },
            wp.element.createElement(
                "div",
                { className: "export-wrapper" },
                elements.map(function (element, index) {
                    return wp.element.createElement(_ExportElement2.default, {
                        key: index,
                        type: type,
                        element: element,
                        onChange: handleChange
                    });
                })
            )
        )
    );
};

exports["default"] = ExportWrapper;

/***/ }),

/***/ 98401:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _misc = __webpack_require__(53154);

var _reactRedux = __webpack_require__(28216);

var _fetchPostTypes = __webpack_require__(14825);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _exportFile = __webpack_require__(18210);

var _files = __webpack_require__(44194);

var _json = __webpack_require__(28845);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _metaTypes = __webpack_require__(81895);

var _fetchTaxonomies = __webpack_require__(91141);

var _ExportWrapper = __webpack_require__(15169);

var _ExportWrapper2 = _interopRequireDefault(_ExportWrapper);

var _fetchOptionPages = __webpack_require__(60068);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Export = function Export() {

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetchedPostTypes = _useSelector.fetched,
        loadingPostTypes = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTaxonomiesReducer;
    }),
        fetchedTaxonomies = _useSelector2.fetched,
        loadingTaxonomies = _useSelector2.loading;

    var _useSelector3 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchOptionPagesReducer;
    }),
        fetchedOptionPages = _useSelector3.fetchedNormalized,
        loadingOptionPages = _useSelector3.loading;

    var _useSelector4 = (0, _reactRedux.useSelector)(function (state) {
        return state.exportFileReducer;
    }),
        contentExport = _useSelector4.content,
        successExport = _useSelector4.success,
        loadingExport = _useSelector4.loading,
        errorsExport = _useSelector4.errors;

    // manage local state


    var didMountRef = (0, _react.useRef)(false);

    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        fetchedSuccess = _useState2[0],
        setFetchedSuccess = _useState2[1];

    var _useState3 = (0, _react.useState)([]),
        _useState4 = _slicedToArray(_useState3, 2),
        checked = _useState4[0],
        setChecked = _useState4[1];

    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)((0, _useTranslation2.default)("Export custom post types"));
        (0, _misc.changeCurrentAdminMenuLink)('#/export');
        dispatch((0, _fetchPostTypes.fetchPostTypes)());
        dispatch((0, _fetchTaxonomies.fetchTaxonomies)());
        dispatch((0, _fetchOptionPages.fetchOptionPages)());
    }, []);

    // handle fetch outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loadingPostTypes && !loadingTaxonomies && !loadingOptionPages) {
                setFetchedSuccess(true);

                var f = [];
                fetchedPostTypes.map(function (item) {
                    f.push({
                        id: item.id,
                        checked: true,
                        type: _metaTypes.metaTypes.CUSTOM_POST_TYPE
                    });
                });

                fetchedTaxonomies.map(function (item) {
                    f.push({
                        id: item.id,
                        checked: true,
                        type: _metaTypes.metaTypes.TAXONOMY
                    });
                });

                fetchedOptionPages.map(function (item) {
                    f.push({
                        id: item.id,
                        checked: true,
                        type: _metaTypes.metaTypes.OPTION_PAGE
                    });
                });

                f.push({
                    id: 'user_meta',
                    checked: true,
                    type: _metaTypes.metaTypes.USER
                });

                setChecked(f);
            }
        } else {
            didMountRef.current = true;
        }
    }, [loadingPostTypes, loadingTaxonomies, loadingOptionPages]);

    // download file
    (0, _react.useEffect)(function () {
        if (contentExport !== null) {
            var filename = "acpt_export_" + new Date().toJSON().slice(0, 19) + ".acpt";
            var compressedContent = (0, _json.compressJson)(JSON.stringify(contentExport));

            (0, _files.download)(filename, compressedContent);
        }
    }, [contentExport]);

    // handle data change and form submit
    var handleChange = function handleChange(data) {

        var newState = checked.map(function (state) {
            if (state.id === data.id) {
                return {
                    id: data.id,
                    checked: data.checked,
                    type: data.type
                };
            } else {
                return state;
            }
        });

        setChecked(newState);
    };

    var handleSubmit = function handleSubmit() {
        dispatch((0, _exportFile.exportFile)(checked));
    };

    if (!fetchedSuccess) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    var button = wp.element.createElement(
        "button",
        {
            onClick: function onClick(e) {
                return handleSubmit();
            },
            className: "acpt-btn acpt-btn-primary"
        },
        (0, _useTranslation2.default)("Export selected elements")
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: (0, _useTranslation2.default)("Export data"),
            actions: button
        }),
        wp.element.createElement(
            "main",
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                    link: "/"
                }, {
                    label: (0, _useTranslation2.default)("ACPT Tools"),
                    link: "/tools"
                }, {
                    label: (0, _useTranslation2.default)("Export data")
                }]
            }),
            wp.element.createElement(
                "div",
                null,
                (0, _useTranslation2.default)("Export settings and meta fields for selected elements:")
            ),
            wp.element.createElement(_ExportWrapper2.default, {
                name: (0, _useTranslation2.default)("Custom post types"),
                elements: fetchedPostTypes,
                type: _metaTypes.metaTypes.CUSTOM_POST_TYPE,
                handleChange: handleChange
            }),
            wp.element.createElement(_ExportWrapper2.default, {
                name: (0, _useTranslation2.default)("Taxonomies"),
                elements: fetchedTaxonomies,
                type: _metaTypes.metaTypes.TAXONOMY,
                handleChange: handleChange
            }),
            wp.element.createElement(_ExportWrapper2.default, {
                name: (0, _useTranslation2.default)("Option pages"),
                elements: fetchedOptionPages,
                type: _metaTypes.metaTypes.OPTION_PAGE,
                handleChange: handleChange
            }),
            wp.element.createElement(_ExportWrapper2.default, {
                name: (0, _useTranslation2.default)("User"),
                elements: [{
                    id: "user_meta",
                    name: "User meta"
                }],
                type: _metaTypes.metaTypes.USER,
                handleChange: handleChange
            })
        )
    );
};

exports["default"] = Export;

/***/ }),

/***/ 26054:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var CustomPostTypeLabel = function CustomPostTypeLabel(_ref) {
    var element = _ref.element;


    if (typeof element.isNative === 'undefined') {
        return null;
    }

    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        element.isNative ? wp.element.createElement(
            'span',
            { className: 'acpt-badge acpt-badge-native' },
            wp.element.createElement(
                'span',
                { className: 'label' },
                (0, _useTranslation2.default)("Native")
            )
        ) : wp.element.createElement(
            'span',
            { className: 'acpt-badge acpt-badge-' + (element.isWooCommerce === true ? 'woocommerce' : 'custom') },
            wp.element.createElement(
                'span',
                { className: 'label' },
                element.isWooCommerce === true ? 'WooCommerce' : (0, _useTranslation2.default)('Custom')
            )
        )
    );
};

exports["default"] = CustomPostTypeLabel;

/***/ }),

/***/ 43700:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ActionsBar = function ActionsBar(_ref) {
    var title = _ref.title,
        actions = _ref.actions,
        secondaryActions = _ref.secondaryActions;


    return wp.element.createElement(
        "div",
        { className: "actions-bar" },
        wp.element.createElement(
            "div",
            { className: "title" },
            wp.element.createElement(
                "h1",
                null,
                title
            )
        ),
        secondaryActions && wp.element.createElement(
            "div",
            { className: "secondary-actions" },
            secondaryActions
        ),
        wp.element.createElement(
            "div",
            { className: "actions" },
            actions
        )
    );
};

exports["default"] = ActionsBar;

/***/ }),

/***/ 76512:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _react3 = __webpack_require__(44226);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Breadcrumb = function Breadcrumb(_ref) {
    var label = _ref.label,
        link = _ref.link,
        isLast = _ref.isLast;


    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            "li",
            { className: isLast ? 'current' : '' },
            link ? wp.element.createElement(
                _reactRouterDom.Link,
                { to: link },
                label
            ) : label
        ),
        !isLast && wp.element.createElement(
            "span",
            { className: "separator" },
            wp.element.createElement(_react3.Icon, { icon: "bx:bx-chevron-right", color: "#aaa", width: "18px" })
        )
    );
};

exports["default"] = Breadcrumb;

/***/ }),

/***/ 95827:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Breadcrumb = __webpack_require__(76512);

var _Breadcrumb2 = _interopRequireDefault(_Breadcrumb);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Breadcrumbs = function Breadcrumbs(_ref) {
    var crumbs = _ref.crumbs;

    return wp.element.createElement(
        "div",
        { className: "acpt-breadcrumbs" },
        crumbs.length > 0 && wp.element.createElement(
            "ul",
            null,
            crumbs.map(function (crumb, index) {
                return wp.element.createElement(_Breadcrumb2.default, {
                    label: crumb.label,
                    link: crumb.link,
                    isLast: index + 1 === crumbs.length,
                    key: index
                });
            })
        )
    );
};

exports["default"] = Breadcrumbs;

/***/ }),

/***/ 31494:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Copyright = function Copyright() {
    return wp.element.createElement(
        "div",
        { className: "acpt-copyright" },
        wp.element.createElement(
            "span",
            null,
            (0, _useTranslation2.default)("Copyright"),
            " \xA9 2021 - ",
            new Date().getFullYear(),
            " \xA0",
            wp.element.createElement(
                "a",
                { href: "https://acpt.io", target: "_blank" },
                "ACPT"
            )
        )
    );
};

exports["default"] = Copyright;

/***/ }),

/***/ 35347:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var HeaderMenu = function HeaderMenu(_ref) {
    var isVisible = _ref.isVisible,
        setIsVisible = _ref.setIsVisible;


    var node = (0, _react.useRef)();
    var handleOutsideTitleBoxClick = function handleOutsideTitleBoxClick(e) {
        if (node && !node.current.contains(e.target)) {
            setIsVisible(false);
        }
    };

    (0, _react.useEffect)(function () {
        document.addEventListener("mousedown", handleOutsideTitleBoxClick);

        return function () {
            document.removeEventListener("mousedown", handleOutsideTitleBoxClick);
        };
    }, []);

    return wp.element.createElement(
        "nav",
        {
            ref: node,
            className: "nav " + (isVisible ? 'visible' : '')
        },
        wp.element.createElement(
            "a",
            { href: "https://acpt.io/documentation", target: "_blank" },
            (0, _useTranslation2.default)("Documentation")
        ),
        wp.element.createElement(
            "a",
            { href: "https://acpt.io/acpt-dashboard/", target: "_blank" },
            (0, _useTranslation2.default)("My account")
        ),
        wp.element.createElement(
            _reactRouterDom.Link,
            { to: "/license" },
            (0, _useTranslation2.default)("My license")
        ),
        wp.element.createElement(
            "a",
            { href: "https://acpt.io/changelog/", target: "_blank" },
            (0, _useTranslation2.default)("Changelog")
        ),
        wp.element.createElement(
            "a",
            { href: "mailto:info@acpt.io", target: "_blank" },
            (0, _useTranslation2.default)("Support")
        ),
        wp.element.createElement(
            "a",
            { className: "facebook", href: "https://www.facebook.com/groups/880817719861018", target: "_blank" },
            (0, _useTranslation2.default)("Facebook group")
        )
    );
};

exports["default"] = HeaderMenu;

/***/ }),

/***/ 78301:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _react3 = __webpack_require__(44226);

var _languages = __webpack_require__(69504);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var LanguagePlaceholder = function LanguagePlaceholder() {

    var language = document.globals.language;
    var languageLabel = _languages.lanuagesList.filter(function (l) {
        return l.value === language;
    });

    if (languageLabel.length === 0) {
        return null;
    }

    return wp.element.createElement(
        "div",
        { className: "language-placeholder" },
        wp.element.createElement(_react3.Icon, { icon: "ion:language-outline", width: "18px", color: "#2271b1" }),
        wp.element.createElement(
            "span",
            { className: "language-label" },
            languageLabel[0].label
        )
    );
};

exports["default"] = LanguagePlaceholder;

/***/ }),

/***/ 64609:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Logo = function Logo() {

    var globals = document.globals;

    return wp.element.createElement(
        "div",
        { className: "logo" },
        wp.element.createElement(
            "svg",
            { width: "40", height: "40", viewBox: "0 0 634 572", fill: "none", xmlns: "http://www.w3.org/2000/svg" },
            wp.element.createElement("path", { fillRule: "evenodd", clipRule: "evenodd", d: "M465.039 115.5L339.505 42.9755C333.314 39.3991 325.686 39.3991 319.495 42.9755L193.961 115.5L319.495 188.024C325.686 191.601 333.314 191.601 339.505 188.024L465.039 115.5ZM359.515 8.34015C340.943 -2.3891 318.057 -2.3891 299.485 8.34015L114 115.5L299.485 222.66C318.057 233.389 340.943 233.389 359.515 222.66L545 115.5L359.515 8.34015Z", fill: "#02c39a" }),
            wp.element.createElement("path", { fillRule: "evenodd", clipRule: "evenodd", d: "M520.34 209.498L394.765 281.952C388.572 285.524 384.758 292.131 384.76 299.28L384.801 444.258L510.376 371.805C516.568 368.232 520.383 361.625 520.381 354.476L520.34 209.498ZM374.775 247.305C356.197 258.024 344.754 277.844 344.76 299.292L344.82 513.507L530.366 406.452C548.944 395.733 560.387 375.913 560.381 354.465L560.32 140.25L374.775 247.305Z", fill: "#02c39a" }),
            wp.element.createElement("path", { opacity: "0.5", fillRule: "evenodd", clipRule: "evenodd", d: "M275.34 444.259L275.381 299.281C275.383 292.131 271.568 285.525 265.376 281.952L139.801 209.498L139.76 354.476C139.758 361.625 143.572 368.232 149.765 371.805L275.34 444.259ZM315.381 299.292C315.387 277.844 303.944 258.024 285.366 247.305L99.8202 140.25L99.7599 354.465C99.7538 375.913 111.197 395.733 129.775 406.452L315.32 513.507L315.381 299.292Z", fill: "#02c39a" })
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
    );
};

exports["default"] = Logo;

/***/ }),

/***/ 57286:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _react3 = __webpack_require__(44226);

var _HeaderMenu = __webpack_require__(35347);

var _HeaderMenu2 = _interopRequireDefault(_HeaderMenu);

var _LanguagePlaceholder = __webpack_require__(78301);

var _LanguagePlaceholder2 = _interopRequireDefault(_LanguagePlaceholder);

var _Logo = __webpack_require__(64609);

var _Logo2 = _interopRequireDefault(_Logo);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Header = function Header() {
    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        isMenuVisible = _useState2[0],
        setIsMenuVisible = _useState2[1];

    return wp.element.createElement(
        "header",
        { className: "acpt-header" },
        wp.element.createElement(_Logo2.default, null),
        wp.element.createElement(
            "div",
            { className: "menu" },
            wp.element.createElement(_LanguagePlaceholder2.default, null),
            wp.element.createElement(
                "a",
                {
                    href: "#",
                    className: "menu-link",
                    onClick: function onClick(e) {
                        e.preventDefault();
                        setIsMenuVisible(!isMenuVisible);
                    }
                },
                wp.element.createElement(_react3.Icon, { icon: "bx:bx-menu", width: "24px" })
            ),
            wp.element.createElement(_HeaderMenu2.default, {
                isVisible: isMenuVisible,
                setIsVisible: setIsMenuVisible
            })
        )
    );
};

exports["default"] = Header;

/***/ }),

/***/ 73067:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Header = __webpack_require__(57286);

var _Header2 = _interopRequireDefault(_Header);

var _Copyright = __webpack_require__(31494);

var _Copyright2 = _interopRequireDefault(_Copyright);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Layout = function Layout(_ref) {
    var children = _ref.children;

    return wp.element.createElement(
        "div",
        null,
        wp.element.createElement(_Header2.default, null),
        children,
        wp.element.createElement(_Copyright2.default, null)
    );
};

exports["default"] = Layout;

/***/ }),

/***/ 69504:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var lanuagesList = exports.lanuagesList = [{ value: 'en_GB', label: "English" }, { value: 'it_IT', label: "Italian" }];

/***/ }),

/***/ 18210:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.exportFile = undefined;

var _ajax = __webpack_require__(47569);

var _exportFileActions = __webpack_require__(45648);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var exportFile = exports.exportFile = function exportFile(data) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _exportFileActions.exportFileInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("exportFileAction", data);

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _exportFileActions.exportFileSuccess)(res.data)) : dispatch((0, _exportFileActions.exportFileFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _exportFileActions.exportFileFailure)(_context.t0));

                        case 12:
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

/***/ 60068:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchOptionPages = undefined;

var _ajax = __webpack_require__(47569);

var _fetchOptionPagesActions = __webpack_require__(98243);

var _optionPagesManageAction = __webpack_require__(73352);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchOptionPages = exports.fetchOptionPages = function fetchOptionPages(data) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var postData, fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;
                            postData = data ? { page: data.page, perPage: data.perPage } : {};

                            dispatch((0, _fetchOptionPagesActions.fetchOptionPagesInProgress)());
                            _context.next = 5;
                            return (0, _ajax.wpAjaxRequest)('fetchOptionPagesAction', postData);

                        case 5:
                            fetched = _context.sent;

                            dispatch((0, _fetchOptionPagesActions.fetchOptionPagesSuccess)(fetched));
                            dispatch((0, _optionPagesManageAction.hydrateValues)(fetched));
                            _context.next = 13;
                            break;

                        case 10:
                            _context.prev = 10;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchOptionPagesActions.fetchOptionPagesFailure)(_context.t0));

                        case 13:
                        case "end":
                            return _context.stop();
                    }
                }
            }, _callee, undefined, [[0, 10]]);
        }));

        return function (_x, _x2) {
            return _ref.apply(this, arguments);
        };
    }();
};

/***/ }),

/***/ 91141:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchTaxonomies = undefined;

var _ajax = __webpack_require__(47569);

var _fetchTaxonomiesActions = __webpack_require__(77783);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchTaxonomies = exports.fetchTaxonomies = function fetchTaxonomies(meta) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchTaxonomiesActions.fetchTaxonomiesInProgress)(meta));
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchTaxonomiesAction', meta ? meta : {});

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchTaxonomiesActions.fetchTaxonomiesSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchTaxonomiesActions.fetchTaxonomiesFailure)(_context.t0));

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

/***/ 44194:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));

/**
 * Nicely format bytes to a human readable string
 *
 * please see:
 * https://stackoverflow.com/questions/15900485/correct-way-to-convert-size-in-bytes-to-kb-mb-gb-in-javascript
 *
 * @param bytes
 * @param decimals
 * @return {string}
 */
var formatBytes = exports.formatBytes = function formatBytes(bytes) {
  var decimals = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 2;

  if (bytes === 0) return '0 Bytes';

  var k = 1024;
  var dm = decimals < 0 ? 0 : decimals;
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

  var i = Math.floor(Math.log(bytes) / Math.log(k));

  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

/**
 * Download file
 * @param filename
 * @param text
 */
var download = exports.download = function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);

  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
};

/***/ }),

/***/ 28845:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.decompressJson = exports.compressJson = undefined;

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _lzutf = __webpack_require__(4037);

var _lzutf2 = _interopRequireDefault(_lzutf);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * compress a json
 *
 * @param json
 * @return {string}
 */
var compressJson = exports.compressJson = function compressJson(json) {
  return _lzutf2.default.encodeBase64(_lzutf2.default.compress(json));
};

/**
 * decompress a json
 *
 * @param compressedJson
 * @return {string | Uint8Array | LZUTF8.Buffer}
 */
var decompressJson = exports.decompressJson = function decompressJson(compressedJson) {
  return _lzutf2.default.decompress(_lzutf2.default.decodeBase64(compressedJson));
};

/***/ })

}]);
//# sourceMappingURL=8401.js.map