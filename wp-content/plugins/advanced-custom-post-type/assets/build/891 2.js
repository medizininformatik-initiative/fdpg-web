"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[891],{

/***/ 97035:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _assocTaxonomyToPostType = __webpack_require__(24061);

var _reactRedux = __webpack_require__(28216);

var _CustomPostTypeLabel = __webpack_require__(26054);

var _CustomPostTypeLabel2 = _interopRequireDefault(_CustomPostTypeLabel);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var AssocTaxonomyElement = function AssocTaxonomyElement(_ref) {
    var id = _ref.id,
        element = _ref.element,
        postType = _ref.postType,
        defaultChecked = _ref.defaultChecked;


    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var handleOnChange = function handleOnChange(element) {
        dispatch((0, _assocTaxonomyToPostType.assocTaxonomyToPostType)(postType, [element]));
    };

    return wp.element.createElement(
        "tr",
        null,
        wp.element.createElement(
            "td",
            null,
            wp.element.createElement(
                "strong",
                null,
                element.slug
            )
        ),
        wp.element.createElement(
            "td",
            null,
            wp.element.createElement(_CustomPostTypeLabel2.default, { element: element })
        ),
        wp.element.createElement(
            "td",
            null,
            element.singular
        ),
        wp.element.createElement(
            "td",
            null,
            element.plural
        ),
        wp.element.createElement(
            "td",
            { className: "with-border" },
            wp.element.createElement(
                "span",
                { className: "acpt-badge" },
                wp.element.createElement(
                    "span",
                    { className: "label" },
                    element.postCount
                )
            )
        ),
        wp.element.createElement(
            "td",
            null,
            wp.element.createElement(
                "label",
                { className: "switch" },
                wp.element.createElement("input", {
                    id: element.id,
                    type: "checkbox",
                    defaultChecked: defaultChecked,
                    onChange: function onChange(e) {
                        return handleOnChange({
                            id: element.id,
                            checked: e.target.checked
                        });
                    }
                }),
                wp.element.createElement("span", { className: "slider round" })
            )
        )
    );
};

exports["default"] = AssocTaxonomyElement;

/***/ }),

/***/ 50891:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _reactRedux = __webpack_require__(28216);

var _misc = __webpack_require__(53154);

var _fetchTaxonomies = __webpack_require__(91141);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _AssocTaxonomyElement = __webpack_require__(97035);

var _AssocTaxonomyElement2 = _interopRequireDefault(_AssocTaxonomyElement);

var _fetchPostTypes = __webpack_require__(14825);

var _objects = __webpack_require__(54040);

var _reactToastify = __webpack_require__(39249);

var _react3 = __webpack_require__(44226);

var _Tippy = __webpack_require__(85825);

var _Tippy2 = _interopRequireDefault(_Tippy);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var AssocTaxonomyToCustomPostType = function AssocTaxonomyToCustomPostType() {

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.assocTaxonomyToPostReducer;
    }),
        successAssocTaxonomyToPost = _useSelector.success,
        loadingAssocTaxonomyToPost = _useSelector.loading,
        assocTaxonomyToPostErrors = _useSelector.errors;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTaxonomiesReducer;
    }),
        fetchedTaxonomies = _useSelector2.fetched,
        loadingTaxonomies = _useSelector2.loading;

    var _useSelector3 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetchedPosts = _useSelector3.fetched,
        loadingPosts = _useSelector3.loading;

    // manage local state


    var _useParams = (0, _reactRouterDom.useParams)(),
        postType = _useParams.postType;

    var didMountRef = (0, _react.useRef)(false);

    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        fetchedSuccess = _useState2[0],
        setFetchedSuccess = _useState2[1];

    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)((0, _useTranslation2.default)("Registered Taxonomies"));
        dispatch((0, _fetchTaxonomies.fetchTaxonomies)());
        dispatch((0, _fetchPostTypes.fetchPostTypes)({
            postType: postType
        }));
    }, []);

    // handle fetch outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loadingTaxonomies && !loadingPosts) {
                setFetchedSuccess(true);

                var f = [];
                fetchedTaxonomies.map(function (item) {
                    f.push({
                        id: item.id,
                        checked: !(0, _objects.isEmpty)((0, _objects.filterById)(fetchedPosts[0].taxonomies, item.id))
                    });
                });
            }
        } else {
            didMountRef.current = true;
        }
    }, [loadingTaxonomies, loadingPosts]);

    // handle assoc outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loadingAssocTaxonomyToPost) {
                if (successAssocTaxonomyToPost) {
                    _reactToastify.toast.success((0, _useTranslation2.default)("Custom post type was associated to selected taxonomies"));
                }

                if (assocTaxonomyToPostErrors.length > 0) {
                    assocTaxonomyToPostErrors.map(function (error) {
                        _reactToastify.toast.error(error);
                    });
                }
            }
        } else {
            didMountRef.current = true;
        }
    }, [loadingAssocTaxonomyToPost]);

    if (!fetchedSuccess) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: (0, _useTranslation2.default)("Associate taxonomies to") + " " + postType
        }),
        wp.element.createElement(
            "main",
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                    link: "/"
                }, {
                    label: postType,
                    link: "/view/" + postType
                }, {
                    label: (0, _useTranslation2.default)("Associate taxonomies to") + " " + postType
                }] }),
            fetchedTaxonomies.length > 0 ? wp.element.createElement(
                "div",
                { className: "acpt-card" },
                wp.element.createElement(
                    "div",
                    { className: "acpt-card__header" },
                    wp.element.createElement(
                        "div",
                        { className: "acpt-card__inner" },
                        fetchedTaxonomies.length,
                        " $",
                        (0, _useTranslation2.default)("taxonomies registered")
                    )
                ),
                wp.element.createElement(
                    "div",
                    { className: "acpt-card__body" },
                    wp.element.createElement(
                        "div",
                        { className: "acpt-table-responsive" },
                        wp.element.createElement(
                            "table",
                            { className: "acpt-table" },
                            wp.element.createElement(
                                "thead",
                                null,
                                wp.element.createElement(
                                    "tr",
                                    null,
                                    wp.element.createElement(
                                        "th",
                                        { className: "grey backend with-border", colSpan: 5 },
                                        (0, _useTranslation2.default)("Taxonomy data")
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        { className: "grey frontend", colSpan: 1 },
                                        (0, _useTranslation2.default)("Actions")
                                    )
                                ),
                                wp.element.createElement(
                                    "tr",
                                    null,
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        "Slug \xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Taxonomy slug. The post name/slug. Used for various queries for taxonomy content.") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement("th", null),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        "Singular \xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Singular label. Used when a singular label is needed") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        "Plural \xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Plural label. Used for the taxonomy admin menu item") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        { className: "with-border" },
                                        "Post count \xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Published posts count associated with the taxonomy") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        "Associate \xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Associate custom post types here") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" })
                                            )
                                        )
                                    )
                                )
                            ),
                            wp.element.createElement(
                                "tbody",
                                null,
                                fetchedTaxonomies.map(function (element) {
                                    return wp.element.createElement(_AssocTaxonomyElement2.default, {
                                        postType: postType,
                                        id: element.id,
                                        key: element.id,
                                        element: element,
                                        defaultChecked: !(0, _objects.isEmpty)((0, _objects.filterById)(fetchedPosts[0].taxonomies, element.id))
                                    });
                                })
                            )
                        )
                    )
                )
            ) : wp.element.createElement(
                "div",
                { className: "acpt-alert acpt-alert-secondary" },
                (0, _useTranslation2.default)("No taxonomies found."),
                " ",
                wp.element.createElement(
                    _reactRouterDom.Link,
                    { to: "/register_taxonomy" },
                    (0, _useTranslation2.default)("Register the first one")
                ),
                "!"
            )
        )
    );
};

exports["default"] = AssocTaxonomyToCustomPostType;

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

/***/ 24061:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.assocTaxonomyToPostType = undefined;

var _ajax = __webpack_require__(47569);

var _assocTaxonomyToPostActions = __webpack_require__(64627);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var assocTaxonomyToPostType = exports.assocTaxonomyToPostType = function assocTaxonomyToPostType(postType, taxonomies) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _assocTaxonomyToPostActions.assocTaxonomyToPostInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("assocTaxonomyToPostTypeAction", { postType: postType, taxonomies: taxonomies });

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _assocTaxonomyToPostActions.assocTaxonomyToPostSuccess)()) : dispatch((0, _assocTaxonomyToPostActions.assocTaxonomyToPostFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _assocTaxonomyToPostActions.assocTaxonomyToPostFailure)(_context.t0));

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

/***/ })

}]);
//# sourceMappingURL=891.js.map