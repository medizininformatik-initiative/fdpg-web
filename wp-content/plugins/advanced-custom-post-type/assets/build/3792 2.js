(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[3792,4929],{

/***/ 48053:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var map = {
	"./en.js": 63167
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = 48053;

/***/ }),

/***/ 94929:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _reactRouterDom = __webpack_require__(73727);

var _react3 = __webpack_require__(44226);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var NotFound404 = function NotFound404() {

    // manage local state
    return wp.element.createElement(
        "div",
        null,
        wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                link: "/"
            }, {
                label: (0, _useTranslation2.default)("Page not found")
            }] }),
        wp.element.createElement(
            "h1",
            { className: "acpt-title" },
            (0, _useTranslation2.default)('Page not found')
        ),
        wp.element.createElement(
            "p",
            { className: "acpt-alert acpt-alert-warning" },
            (0, _useTranslation2.default)('The requested page was not found, was deleted or was moved!')
        ),
        wp.element.createElement(
            _reactRouterDom.Link,
            {
                className: "acpt-btn acpt-btn-primary-o",
                to: "/" },
            wp.element.createElement(_react3.Icon, { icon: "bx:bx-category-alt" }),
            (0, _useTranslation2.default)('Return back to list')
        )
    );
};

exports["default"] = NotFound404;

/***/ }),

/***/ 74347:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _reactRedux = __webpack_require__(28216);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var BasicElement = function BasicElement() {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTaxonomiesReducer;
    }),
        fetched = _useSelector.fetched;

    var data = {
        slug: fetched[0].slug,
        singular: fetched[0].singular,
        plural: fetched[0].plural
    };

    // manage local state

    var _useParams = (0, _reactRouterDom.useParams)(),
        taxonomy = _useParams.taxonomy;

    return wp.element.createElement(
        "div",
        null,
        wp.element.createElement(
            "table",
            { className: "acpt-table acpt-table-secondary" },
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Slug")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.slug
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Singular")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.singular
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Plural")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.plural
                )
            )
        )
    );
};

exports["default"] = BasicElement;

/***/ }),

/***/ 94350:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _reactRedux = __webpack_require__(28216);

var _taxonomy_label = __webpack_require__(14644);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var LabelsElement = function LabelsElement() {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTaxonomiesReducer;
    }),
        fetched = _useSelector.fetched;

    var data = fetched[0].labels;

    // manage local state

    var _useParams = (0, _reactRouterDom.useParams)(),
        taxonomy = _useParams.taxonomy;

    return wp.element.createElement(
        "div",
        null,
        wp.element.createElement(
            "table",
            { className: "acpt-table acpt-table-secondary" },
            _taxonomy_label.taxonomyLabelsList.map(function (item) {
                return wp.element.createElement(
                    "tr",
                    null,
                    wp.element.createElement(
                        "th",
                        { style: { width: '180px' } },
                        item.label
                    ),
                    wp.element.createElement(
                        "td",
                        null,
                        data[item.id]
                    )
                );
            })
        )
    );
};

exports["default"] = LabelsElement;

/***/ }),

/***/ 93111:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRedux = __webpack_require__(28216);

var _reactRouterDom = __webpack_require__(73727);

var _Boolean = __webpack_require__(19904);

var _Boolean2 = _interopRequireDefault(_Boolean);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var SettingsElement = function SettingsElement() {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTaxonomiesReducer;
    }),
        fetched = _useSelector.fetched;

    var data = fetched[0].settings;

    // manage local state

    var _useParams = (0, _reactRouterDom.useParams)(),
        taxonomy = _useParams.taxonomy;

    return wp.element.createElement(
        "div",
        null,
        wp.element.createElement(
            "table",
            { className: "acpt-table acpt-table-secondary" },
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Is Public")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.public })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Publicly queryable")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.publicly_queryable })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Hierarchical")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.hierarchical })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Show in menu")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.show_in_menu })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Show in nav menus")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.show_in_nav_menus })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Show in REST API")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.show_in_rest })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("REST API base slug")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.rest_base
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("REST Controller class")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.rest_controller_class
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Show Tagcloud")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.show_tagcloud })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Show in quick edit")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.show_in_quick_edit })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Show admin column")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.show_admin_column })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Capabilities")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.capabilities && data.capabilities.map(function (s) {
                        return wp.element.createElement(
                            "div",
                            null,
                            s
                        );
                    })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Rewrite")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.rewrite })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Custom rewrite")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.custom_rewrite
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Query var")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.query_var })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Custom query var")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.custom_query_var
                )
            )
        )
    );
};

exports["default"] = SettingsElement;

/***/ }),

/***/ 83792:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _reactRedux = __webpack_require__(28216);

var _reactRouterDom = __webpack_require__(73727);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _misc = __webpack_require__(53154);

var _Accordion = __webpack_require__(81989);

var _Accordion2 = _interopRequireDefault(_Accordion);

var _Labels = __webpack_require__(94350);

var _Labels2 = _interopRequireDefault(_Labels);

var _Basic = __webpack_require__(74347);

var _Basic2 = _interopRequireDefault(_Basic);

var _Settings = __webpack_require__(93111);

var _Settings2 = _interopRequireDefault(_Settings);

var _ = __webpack_require__(94929);

var _2 = _interopRequireDefault(_);

var _fetchTaxonomies = __webpack_require__(91141);

var _localization = __webpack_require__(48525);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ViewTaxonomy = function ViewTaxonomy() {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTaxonomiesReducer;
    }),
        fetched = _useSelector.fetched,
        loading = _useSelector.loading;

    var dispatch = (0, _reactRedux.useDispatch)();

    // manage local state

    var _useParams = (0, _reactRouterDom.useParams)(),
        taxonomy = _useParams.taxonomy;

    var didMountRef = (0, _react.useRef)(false);

    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        fetchedSuccess = _useState2[0],
        setFetchedSuccess = _useState2[1];

    var _useState3 = (0, _react.useState)(1),
        _useState4 = _slicedToArray(_useState3, 2),
        activeTab = _useState4[0],
        setActiveTab = _useState4[1];

    (0, _react.useEffect)(function () {
        dispatch((0, _fetchTaxonomies.fetchTaxonomies)({
            taxonomy: taxonomy
        }));
        (0, _misc.metaTitle)((0, _useTranslation2.default)((0, _localization.translate)("taxonomy_view.title")));
    }, [taxonomy]);

    // handle fetch outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loading) {
                if (fetched.length !== 0) {
                    setFetchedSuccess(true);
                } else {
                    setFetchedSuccess(false);
                }
            }
        } else {
            didMountRef.current = true;
        }
    }, [loading]);

    if (fetchedSuccess === null) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    if (!fetchedSuccess) {
        return wp.element.createElement(_2.default, null);
    }

    var handleAccordionClick = function handleAccordionClick(index) {
        setActiveTab(index + 1);
    };

    var buttons = wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            _reactRouterDom.Link,
            {
                className: "acpt-btn acpt-btn-primary",
                to: "/edit_taxonomy/" + taxonomy + "/" + activeTab
            },
            "Edit"
        )
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: taxonomy + ": " + (0, _useTranslation2.default)("global settings"),
            actions: buttons
        }),
        wp.element.createElement(
            "main",
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                    link: "/"
                }, {
                    label: (0, _useTranslation2.default)("Registered Taxonomies"),
                    link: "/taxonomies"
                }, {
                    label: (0, _useTranslation2.default)("Taxonomy global settings")
                }] }),
            wp.element.createElement(
                _Accordion2.default,
                { handleClick: handleAccordionClick },
                wp.element.createElement(_Basic2.default, { title: "Basic" }),
                wp.element.createElement(_Labels2.default, { title: "Labels" }),
                wp.element.createElement(_Settings2.default, { title: "Settings" })
            )
        )
    );
};

exports["default"] = ViewTaxonomy;

/***/ }),

/***/ 19904:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _react3 = __webpack_require__(44226);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Boolean = function Boolean(_ref) {
    var status = _ref.status;


    var icon = status ? 'bx:bx-check' : 'bx:bx-x';
    var color = status ? '#2271b1' : '#f94144';

    return wp.element.createElement(_react3.Icon, { icon: icon, color: color, width: '18px' });
};

exports["default"] = Boolean;

/***/ }),

/***/ 43700:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


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

"use strict";


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

"use strict";


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

"use strict";


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

"use strict";


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

"use strict";


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

"use strict";


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

"use strict";


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

"use strict";


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

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var lanuagesList = exports.lanuagesList = [{ value: 'en_GB', label: "English" }, { value: 'it_IT', label: "Italian" }];

/***/ }),

/***/ 14644:
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

// please refer to
// https://developer.wordpress.org/reference/functions/get_taxonomy_labels/
var taxonomyLabelsList = exports.taxonomyLabelsList = [{
    id: "name",
    label: "Menu Name",
    description: "General name for the taxonomy, usually plural. The same as and overridden by $tax->label. Default 'Tags'/'Categories'."
}, {
    id: "singular_name",
    label: "Singular name",
    description: "Name for one object of this taxonomy. Default 'Tag/Category'"
}, {
    id: "search_items",
    label: "Search items",
    description: "Search Tags'/'Search Categories"
}, {
    id: "popular_items",
    label: "Popular items",
    description: "This label is only used for non-hierarchical taxonomies. Default 'Popular Tags'."
}, {
    id: "all_items",
    label: "All items",
    description: "All Tags'/'All Categories"
}, {
    id: "parent_item",
    label: "Parent item",
    description: "This label is only used for hierarchical taxonomies. Default 'Parent Category'."
}, {
    id: "parent_item_colon",
    label: "Parent item colon",
    description: "The same as parent_item, but with colon : in the end."
}, {
    id: "edit_item",
    label: "Edit item",
    description: "Edit Tag'/'Edit Category."
}, {
    id: "view_item",
    label: "View item",
    description: "View Tag'/'View Category."
}, {
    id: "update_item",
    label: "Update item",
    description: "Update Tag'/'Update Category."
}, {
    id: "add_new_item",
    label: "Add new item",
    description: "Add New Tag'/'Add New Category."
}, {
    id: "new_item_name",
    label: "New item name",
    description: "New Tag Name'/'New Category Name."
}, {
    id: "separate_items_with_commas",
    label: "Separate items with commas",
    description: "This label is only used for non-hierarchical taxonomies. Default 'Separate tags with commas', used in the meta box."
}, {
    id: "add_or_remove_items",
    label: "Add or remove items",
    description: "This label is only used for non-hierarchical taxonomies. Default 'Add or remove tags', used in the meta box when JavaScript is disabled."
}, {
    id: "choose_from_most_used",
    label: "Choose from most used",
    description: "This label is only used on non-hierarchical taxonomies. Default 'Choose from the most used tags', used in the meta box."
}, {
    id: "not_found",
    label: "Not found",
    description: "No tags found'/'No categories found', used in the meta box and taxonomy list table."
}, {
    id: "no_terms",
    label: "No terms",
    description: "No tags'/'No categories', used in the posts and media list tables."
}, {
    id: "filter_by_item",
    label: "Filter by item",
    description: "This label is only used for hierarchical taxonomies. Default 'Filter by category', used in the posts list table."
}, {
    id: "items_list_navigation",
    label: "Items list navigation",
    description: "Label for the table pagination hidden heading."
}, {
    id: "items_list",
    label: "Items list",
    description: "Label for the table hidden heading."
}, {
    id: "most_used",
    label: "Most used",
    description: "Title for the Most Used tab. Default 'Most Used'."
}, {
    id: "back_to_items",
    label: "Back to items",
    description: "Label displayed after a term has been updated."
}];

/***/ }),

/***/ 48525:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.translate = undefined;

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * This function will be deleted in the v1.0.170
 *
 * Translate a string from locale files
 *
 * @param string
 * @return {*}
 */
var translate = exports.translate = function translate(string, args) {
    var lang = 'en';
    var json = __webpack_require__(48053)("./" + lang + ".js").translations;
    var strings = string.split(".");
    var translation = getTranslatedString(json, strings);

    if (args !== null && typeof args !== 'undefined') {
        for (var key in args) {
            translation = translation.replace("{{" + key + "}}", args[key]);
        }
    }

    return typeof translation !== 'undefined' ? (0, _useTranslation2.default)(translation) : string;
};

var getTranslatedString = function getTranslatedString(json, strings) {

    var object = json;

    strings.map(function (s) {
        object = object[s];
    });

    return object;
};

/***/ }),

/***/ 63167:
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var translations = exports.translations = {
    "404": {
        "title": "Page not found"
    },
    "create": {
        "title": "Register a new Custom Post Type"
    },
    "edit": {
        "title": "Edit a new Custom Post Type"
    },
    "list": {
        "title": "Registered Custom Post Types"
    },
    "view": {
        "title": "Custom Post Type global settings"
    },
    "taxonomy_list": {
        "title": "Registered Taxonomies"
    },
    "taxonomy_create": {
        "title": "Register a new Taxonomy"
    },
    "taxonomy_edit": {
        "title": "Edit a Taxonomy"
    },
    "taxonomy_view": {
        "title": "Taxonomy settings"
    },
    "general": {
        "labels": {
            "all_items": "All {{r}}",
            "add": "Add",
            "add_new_item": "Add new {{r}}",
            "back_to_items": "Back to {{r}}",
            "edit": "Edit",
            "new": "New",
            "not_found": "No {{r}} found",
            "search": "Search",
            "view": "View",
            "parent_item_colon": "Parent item",
            "featured_image": "Featured image",
            "set_featured_image": "Set featured image",
            "remove_featured_image": "Remove featured image",
            "use_featured_image": "Use featured image",
            "most_used": "Most used {{r}}",
            "archives": "Archives",
            "insert_into_item": "Insert",
            "uploaded_to_this_item": "Upload",
            "filter_items_list": "Filter {{r}} list",
            "items_list_navigation": "Navigation list {{r}}",
            "items_list": "List {{r}}",
            "filter_by_date": "Filter by date",
            "item_published": "{{r}} published",
            "item_published_privately": "{{r}} published privately",
            "item_reverted_to_draft": "{{r}} reverted to draft",
            "item_scheduled": "{{r}} scheduled",
            "item_updated": "{{r}} updated",
            "popular_items": "Popular {{r}}",
            "update_item": "Update {{r}}",
            "no_terms": "No {{r}}",
            "parent_item": "Parent {{r}}",
            "new_item_name": "New {{r}}",
            "filter_by_item": "Filter by {{r}}",
            "separate_items_with_commas": "Separate {{r}} with commas",
            "add_or_remove_items": "Add or remove {{r}}",
            "choose_from_most_used": "Choose from most used {{r}}",
            "search_items": "Search {{r}}"
        }
    }
};

/***/ }),

/***/ 91141:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


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
//# sourceMappingURL=3792.js.map