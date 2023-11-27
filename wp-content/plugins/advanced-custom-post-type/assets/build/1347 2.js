"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[1347,4929],{

/***/ 94929:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



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

/***/ 73375:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _reactRedux = __webpack_require__(28216);

var _react3 = __webpack_require__(44226);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

var _CustomPostTypeIcon = __webpack_require__(79794);

var _CustomPostTypeIcon2 = _interopRequireDefault(_CustomPostTypeIcon);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var BasicElement = function BasicElement() {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetched = _useSelector.fetched;

    var data = {
        name: fetched[0].name,
        singular: fetched[0].singular,
        plural: fetched[0].plural,
        icon: fetched[0].icon,
        supports: fetched[0].supports
    };

    // manage local state

    var _useParams = (0, _reactRouterDom.useParams)(),
        postType = _useParams.postType;

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
                    (0, _useTranslation2.default)("Name")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.name
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
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Icon")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_CustomPostTypeIcon2.default, { value: data.icon })
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Supports")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.supports && data.supports.map(function (s) {
                        return s !== '' && wp.element.createElement(
                            "div",
                            { className: "acpt-badge mr-1" },
                            wp.element.createElement(
                                "span",
                                { className: "label" },
                                s
                            )
                        );
                    })
                )
            )
        )
    );
};

exports["default"] = BasicElement;

/***/ }),

/***/ 50500:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _label = __webpack_require__(4384);

var _reactRedux = __webpack_require__(28216);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var LabelsElement = function LabelsElement() {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetched = _useSelector.fetched;

    var data = fetched[0].labels;

    // manage local state

    var _useParams = (0, _reactRouterDom.useParams)(),
        postType = _useParams.postType;

    return wp.element.createElement(
        "div",
        null,
        wp.element.createElement(
            "table",
            { className: "acpt-table acpt-table-secondary" },
            _label.postLabelsList.map(function (item) {
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
        ),
        wp.element.createElement(
            "div",
            null,
            wp.element.createElement(
                _reactRouterDom.Link,
                {
                    className: "acpt-btn acpt-btn-primary",
                    to: "/edit/" + postType + "/2"
                },
                (0, _useTranslation2.default)("Edit")
            ),
            "\xA0",
            wp.element.createElement(
                _reactRouterDom.Link,
                {
                    className: "acpt-btn acpt-btn-primary-o",
                    to: "/assoc-taxonomy-post/" + postType
                },
                (0, _useTranslation2.default)("Taxonomies association")
            )
        )
    );
};

exports["default"] = LabelsElement;

/***/ }),

/***/ 41268:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



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

var SettingsElement = function SettingsElement(isWPGraphQLActive) {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetched = _useSelector.fetched;

    var data = fetched[0].settings;

    // manage local state

    var _useParams = (0, _reactRouterDom.useParams)(),
        postType = _useParams.postType;

    return wp.element.createElement(
        "div",
        null,
        wp.element.createElement(
            "table",
            { className: "acpt-table acpt-table-secondary" },
            isWPGraphQLActive && wp.element.createElement(
                _react2.default.Fragment,
                null,
                wp.element.createElement(
                    "tr",
                    null,
                    wp.element.createElement(
                        "th",
                        { style: { width: '180px' } },
                        (0, _useTranslation2.default)("Show the custom post type in WPGraphQL")
                    ),
                    wp.element.createElement(
                        "td",
                        null,
                        wp.element.createElement(_Boolean2.default, { status: data.show_in_graphql })
                    )
                ),
                wp.element.createElement(
                    "tr",
                    null,
                    wp.element.createElement(
                        "th",
                        { style: { width: '180px' } },
                        (0, _useTranslation2.default)("GraphQL single name")
                    ),
                    wp.element.createElement(
                        "td",
                        null,
                        data.graphql_single_name
                    )
                ),
                wp.element.createElement(
                    "tr",
                    null,
                    wp.element.createElement(
                        "th",
                        { style: { width: '180px' } },
                        (0, _useTranslation2.default)("GraphQL plural name")
                    ),
                    wp.element.createElement(
                        "td",
                        null,
                        data.graphql_plural_name
                    )
                )
            ),
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
                    (0, _useTranslation2.default)("Show in UI")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.show_ui })
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
                    (0, _useTranslation2.default)("Show in admin bar")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.show_in_admin_bar })
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
                    (0, _useTranslation2.default)("Menu position")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.menu_position
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Capability Type")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    data.capability_type
                )
            ),
            wp.element.createElement(
                "tr",
                null,
                wp.element.createElement(
                    "th",
                    { style: { width: '180px' } },
                    (0, _useTranslation2.default)("Has archive")
                ),
                wp.element.createElement(
                    "td",
                    null,
                    wp.element.createElement(_Boolean2.default, { status: data.has_archive })
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
                    (0, _useTranslation2.default)("Custom rewrite rules")
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

/***/ 81347:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



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

var _fetchPostTypes = __webpack_require__(14825);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _misc = __webpack_require__(53154);

var _Accordion = __webpack_require__(81989);

var _Accordion2 = _interopRequireDefault(_Accordion);

var _Labels = __webpack_require__(50500);

var _Labels2 = _interopRequireDefault(_Labels);

var _Basic = __webpack_require__(73375);

var _Basic2 = _interopRequireDefault(_Basic);

var _Settings = __webpack_require__(41268);

var _Settings2 = _interopRequireDefault(_Settings);

var _ = __webpack_require__(94929);

var _2 = _interopRequireDefault(_);

var _ajax = __webpack_require__(47569);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ViewCustomPostType = function ViewCustomPostType() {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetched = _useSelector.fetched,
        loading = _useSelector.loading;

    var dispatch = (0, _reactRedux.useDispatch)();

    // manage local state

    var _useParams = (0, _reactRouterDom.useParams)(),
        postType = _useParams.postType;

    var didMountRef = (0, _react.useRef)(false);

    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        fetchedSuccess = _useState2[0],
        setFetchedSuccess = _useState2[1];

    var _useState3 = (0, _react.useState)(false),
        _useState4 = _slicedToArray(_useState3, 2),
        isWPGraphQLActive = _useState4[0],
        setIsWPGraphQLActive = _useState4[1];

    var _useState5 = (0, _react.useState)(1),
        _useState6 = _slicedToArray(_useState5, 2),
        activeTab = _useState6[0],
        setActiveTab = _useState6[1];

    var handleAccordionClick = function handleAccordionClick(index) {
        setActiveTab(index + 1);
    };

    // manage redirect
    var history = (0, _reactRouterDom.useHistory)();

    if (postType === 'page' || postType === 'post') {
        history.push('/');
    }

    // is WPGraphQL active?
    (0, _react.useEffect)(function () {
        (0, _ajax.wpAjaxRequest)("isWPGraphQLActiveAction", {}).then(function (res) {
            setIsWPGraphQLActive(res.status);
        }).catch(function (err) {
            console.error(err.message);
        });
    }, []);

    // fetch post
    (0, _react.useEffect)(function () {
        dispatch((0, _fetchPostTypes.fetchPostTypes)({
            postType: postType
        }));
        (0, _misc.metaTitle)((0, _useTranslation2.default)("Custom Post Type global settings"));
    }, [postType]);

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

    var buttons = wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            _reactRouterDom.Link,
            {
                className: "acpt-btn acpt-btn-primary",
                to: "/edit/" + postType + "/" + activeTab
            },
            (0, _useTranslation2.default)("Edit")
        ),
        wp.element.createElement(
            _reactRouterDom.Link,
            {
                className: "acpt-btn acpt-btn-primary-o",
                to: "/assoc-taxonomy-post/" + postType
            },
            (0, _useTranslation2.default)("Taxonomies association")
        )
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: postType + ": " + (0, _useTranslation2.default)("global settings"),
            actions: buttons
        }),
        wp.element.createElement(
            "main",
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                    link: "/"
                }, {
                    label: postType + ": " + (0, _useTranslation2.default)("global settings")
                }] }),
            wp.element.createElement(
                _Accordion2.default,
                { handleClick: handleAccordionClick },
                wp.element.createElement(_Basic2.default, { title: "Basic" }),
                wp.element.createElement(_Labels2.default, { title: "Labels" }),
                wp.element.createElement(_Settings2.default, { title: "Settings", isWPGraphQLActive: isWPGraphQLActive })
            )
        )
    );
};

exports["default"] = ViewCustomPostType;

/***/ }),

/***/ 19904:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



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

/***/ 4384:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

// please refer to
// https://developer.wordpress.org/reference/functions/get_post_type_labels/
var postLabelsList = exports.postLabelsList = [{
    id: "menu_name",
    label: "Menu Name",
    description: "Label for the menu name. Default is the same as name."
}, {
    id: "all_items",
    label: "All items",
    description: "Label to signify all items in a submenu link. Default is ‘All Posts’ / ‘All Pages’."
}, {
    id: "add_new",
    label: "Add New",
    description: "Default is ‘Add New’ for both hierarchical and non-hierarchical types."
}, {
    id: "add_new_item",
    label: "Add New Item",
    description: "Label for adding a new singular item. Default is ‘Add New Post’ / ‘Add New Page’."
}, {
    id: "edit_item",
    label: "Edit Item",
    description: "Label for editing a singular item. Default is ‘Edit Post’ / ‘Edit Page’."
}, {
    id: "new_item",
    label: "New Item",
    description: "Label for the new item page title. Default is ‘New Post’ / ‘New Page’."
}, {
    id: "view_item",
    label: "View Item",
    description: "Label for viewing a singular item. Default is ‘View Post’ / ‘View Page’."
}, {
    id: "view_items",
    label: "View Items",
    description: "Label for viewing post type archives. Default is ‘View Posts’ / ‘View Pages’."
}, {
    id: "search_item",
    label: "Search Item",
    description: "Label for searching plural items. Default is ‘Search Posts’ / ‘Search Pages’."
}, {
    id: "not_found",
    label: "Not Found",
    description: "Label used when no items are found. Default is ‘No posts found’ / ‘No pages found’."
}, {
    id: "not_found_in_trash",
    label: "Not Found in Trash",
    description: "Label used when no items are in the Trash. Default is ‘No posts found in Trash’ / ‘No pages found in Trash’."
}, {
    id: "parent_item_colon",
    label: "Parent",
    description: "Label used to prefix parents of hierarchical items. Not used on non-hierarchical post types. Default is ‘Parent Page:’."
}, {
    id: "featured_image",
    label: "Featured Image",
    description: "Label for the featured image meta box title. Default is ‘Featured image’."
}, {
    id: "set_featured_image",
    label: "Set Featured Image",
    description: "Label for setting the featured image. Default is ‘Set featured image’."
}, {
    id: "remove_featured_image",
    label: "Remove Featured Image",
    description: "Label for removing the featured image. Default is ‘Remove featured image’."
}, {
    id: "use_featured_image",
    label: "Use Featured Image",
    description: "Label in the media frame for using a featured image. Default is ‘Use as featured image’."
}, {
    id: "archives",
    label: "Archives",
    description: "Label for archives in nav menus. Default is ‘Post Archives’ / ‘Page Archives’."
}, {
    id: "insert_into_item",
    label: "Insert into item",
    description: "Label for the media frame button. Default is ‘Insert into post’ / ‘Insert into page’."
}, {
    id: "uploaded_to_this_item",
    label: "Uploaded to this Item",
    description: "Label for the media frame filter. Default is ‘Uploaded to this post’ / ‘Uploaded to this page’."
}, {
    id: "filter_items_list",
    label: "Filter Items List",
    description: "Label for the table views hidden heading. Default is ‘Filter posts list’ / ‘Filter pages list’."
}, {
    id: "items_list_navigation",
    label: "Items List Navigation",
    description: "Label for the table pagination hidden heading. Default is ‘Posts list navigation’ / ‘Pages list navigation’."
}, {
    id: "items_list",
    label: "Items List",
    description: "Label for the table hidden heading. Default is ‘Posts list’ / ‘Pages list’."
}, {
    id: "filter_by_date",
    label: "Filter by date",
    description: "Label for the date filter in list tables. Default is ‘Filter by date’."
}, {
    id: "item_published",
    label: "Item published",
    description: "Label used when an item is published. Default is ‘Post published.’ / ‘Page published.’"
}, {
    id: "item_published_privately",
    label: "Item published privately",
    description: "Label used when an item is published with private visibility. Default is ‘Post published privately.’ / ‘Page published privately.’"
}, {
    id: "item_reverted_to_draft",
    label: "Item reverted to draft",
    description: "Label used when an item is switched to a draft. Default is ‘Post reverted to draft.’ / ‘Page reverted to draft.’"
}, {
    id: "item_scheduled",
    label: "Item scheduled",
    description: "Label used when an item is scheduled for publishing. Default is ‘Post scheduled.’ / ‘Page scheduled.’"
}, {
    id: "item_updated",
    label: "Item updated",
    description: "Label used when an item is updated. Default is ‘Post updated.’ / ‘Page updated.’"
}];

/***/ }),

/***/ 69504:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var lanuagesList = exports.lanuagesList = [{ value: 'en_GB', label: "English" }, { value: 'it_IT', label: "Italian" }];

/***/ })

}]);
//# sourceMappingURL=1347.js.map