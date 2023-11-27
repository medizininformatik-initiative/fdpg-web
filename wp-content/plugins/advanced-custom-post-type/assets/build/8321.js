(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[8321],{

/***/ 8230:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

__webpack_require__(10159);

var _ApiKeysListElement = __webpack_require__(66729);

var _ApiKeysListElement2 = _interopRequireDefault(_ApiKeysListElement);

var _Pagination = __webpack_require__(41222);

var _Pagination2 = _interopRequireDefault(_Pagination);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ApiKeysList = function ApiKeysList(_ref) {
    var count = _ref.count,
        elements = _ref.elements,
        page = _ref.page,
        perPage = _ref.perPage,
        refreshApiKeys = _ref.refreshApiKeys;


    var totalPages = Math.ceil(count / perPage);

    if (elements.length === 0) {
        return wp.element.createElement(
            "div",
            { className: "acpt-alert acpt-alert-secondary" },
            (0, _useTranslation2.default)('No API keys found.')
        );
    }

    return wp.element.createElement(
        "div",
        { className: "acpt-card" },
        wp.element.createElement(
            "div",
            { className: "acpt-card__header borderless" },
            wp.element.createElement(
                "div",
                { className: "acpt-card__inner" },
                wp.element.createElement(
                    "h3",
                    null,
                    (0, _useTranslation2.default)('Your API keys')
                ),
                count,
                " ",
                (0, _useTranslation2.default)('record(s)'),
                " ",
                (0, _useTranslation2.default)('found')
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
                    { className: "acpt-table " + (document.globals.is_rtl === true ? "rtl" : "") },
                    wp.element.createElement(
                        "thead",
                        null,
                        wp.element.createElement(
                            "tr",
                            null,
                            wp.element.createElement(
                                "th",
                                null,
                                (0, _useTranslation2.default)('Key')
                            ),
                            wp.element.createElement(
                                "th",
                                null,
                                (0, _useTranslation2.default)('Secret')
                            ),
                            wp.element.createElement(
                                "th",
                                null,
                                (0, _useTranslation2.default)('Generated at')
                            ),
                            wp.element.createElement(
                                "th",
                                null,
                                (0, _useTranslation2.default)('Actions')
                            )
                        )
                    ),
                    wp.element.createElement(
                        "tbody",
                        null,
                        elements.map(function (element) {
                            return wp.element.createElement(_ApiKeysListElement2.default, { refreshApiKeys: refreshApiKeys, element: element });
                        })
                    )
                )
            )
        ),
        totalPages > 1 && wp.element.createElement(
            "div",
            { className: "acpt-card__footer", style: { border: "none" } },
            wp.element.createElement(
                "div",
                { className: "acpt-card__inner" },
                wp.element.createElement(_Pagination2.default, { currentPage: page ? page : 1, perPage: perPage, records: count })
            )
        )
    );
};

exports["default"] = ApiKeysList;

/***/ }),

/***/ 66729:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

__webpack_require__(10159);

var _reactModal = __webpack_require__(83253);

var _reactModal2 = _interopRequireDefault(_reactModal);

var _deleteApiKey = __webpack_require__(47169);

var _reactRedux = __webpack_require__(28216);

var _reactToastify = __webpack_require__(39249);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var _require = __webpack_require__(67294),
    useState = _require.useState;

var ApiKeysListElement = function ApiKeysListElement(_ref) {
    var refreshApiKeys = _ref.refreshApiKeys,
        element = _ref.element;


    _reactModal2.default.setAppElement('#acpt-admin-app');

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.deleteApiKeyReducer;
    }),
        errors = _useSelector.errors,
        success = _useSelector.success,
        loading = _useSelector.loading;

    // manage local state


    var didMountRef = (0, _react.useRef)(false);

    var _useState = useState(false),
        _useState2 = _slicedToArray(_useState, 2),
        modalIsOpen = _useState2[0],
        setIsOpen = _useState2[1];

    // modal


    var openModal = function openModal() {
        setIsOpen(true);
    };

    var afterOpenModal = function afterOpenModal() {};

    var closeModal = function closeModal() {
        setIsOpen(false);
    };

    // handle form submission outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loading) {
                if (success) {
                    refreshApiKeys();
                    _reactToastify.toast.success((0, _useTranslation2.default)("The API key was successfully deleted"));
                    closeModal();
                }

                if (errors.length > 0) {
                    errors.map(function (error) {
                        _reactToastify.toast.error(error);
                    });
                }
            }
        } else {
            didMountRef.current = true;
        }
    }, [loading]);

    return wp.element.createElement(
        'tr',
        null,
        wp.element.createElement(
            'td',
            null,
            element.key
        ),
        wp.element.createElement(
            'td',
            null,
            element.secret
        ),
        wp.element.createElement(
            'td',
            null,
            element.createdAt
        ),
        wp.element.createElement(
            'td',
            null,
            wp.element.createElement(
                _reactModal2.default,
                {
                    isOpen: modalIsOpen,
                    onAfterOpen: afterOpenModal,
                    onRequestClose: closeModal,
                    className: 'acpt-modal text-center',
                    contentLabel: 'Delete API key'
                },
                wp.element.createElement(
                    'h2',
                    { className: 'title' },
                    (0, _useTranslation2.default)("Delete API key")
                ),
                wp.element.createElement(
                    'p',
                    null,
                    (0, _useTranslation2.default)("You are going to delete this API key. Are you sure?")
                ),
                wp.element.createElement(
                    'div',
                    { className: 'acpt-buttons' },
                    wp.element.createElement(
                        'button',
                        {
                            className: 'acpt-btn acpt-btn-danger',
                            onClick: function onClick(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                dispatch((0, _deleteApiKey.deleteApiKey)(element.id));
                            }
                        },
                        (0, _useTranslation2.default)("Yes, delete it")
                    ),
                    wp.element.createElement(
                        'button',
                        {
                            className: 'acpt-btn acpt-btn-primary-o',
                            onClick: closeModal
                        },
                        (0, _useTranslation2.default)("Close")
                    )
                )
            ),
            wp.element.createElement(
                'a',
                {
                    href: '#',
                    onClick: function onClick(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        openModal();
                    }
                },
                (0, _useTranslation2.default)("Delete")
            )
        )
    );
};

exports["default"] = ApiKeysListElement;

/***/ }),

/***/ 86144:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRedux = __webpack_require__(28216);

var _generateApiKey = __webpack_require__(26765);

var _reactToastify = __webpack_require__(39249);

var _reactModal = __webpack_require__(83253);

var _reactModal2 = _interopRequireDefault(_reactModal);

var _copy = __webpack_require__(60927);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var _require = __webpack_require__(67294),
    useState = _require.useState;

var GenerateApiKeys = function GenerateApiKeys(_ref) {
    var count = _ref.count,
        refreshApiKeys = _ref.refreshApiKeys;


    _reactModal2.default.setAppElement('#acpt-admin-app');

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.generateApiKeyReducer;
    }),
        fetched = _useSelector.fetched,
        success = _useSelector.success,
        loading = _useSelector.loading,
        errors = _useSelector.errors;

    // manage local state


    var didMountRef = (0, _react.useRef)(false);

    var _useState = useState(false),
        _useState2 = _slicedToArray(_useState, 2),
        modalIsOpen = _useState2[0],
        setIsOpen = _useState2[1];

    var _useState3 = useState((0, _useTranslation2.default)("Copy")),
        _useState4 = _slicedToArray(_useState3, 2),
        copyText = _useState4[0],
        setCopyText = _useState4[1];

    // modal


    var openModal = function openModal() {
        setIsOpen(true);
    };

    var afterOpenModal = function afterOpenModal() {};

    var closeModal = function closeModal() {
        setIsOpen(false);
    };

    var handleGenerateApiKey = function handleGenerateApiKey() {
        dispatch((0, _generateApiKey.generateApiKey)());
    };

    // handle form submission outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loading) {
                if (success) {
                    refreshApiKeys();
                    setCopyText("Copy");
                    openModal();
                }

                if (errors.length > 0) {
                    errors.map(function (error) {
                        _reactToastify.toast.error(error);
                    });
                }
            }
        } else {
            didMountRef.current = true;
        }
    }, [loading]);

    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            _reactModal2.default,
            {
                isOpen: modalIsOpen,
                onAfterOpen: afterOpenModal,
                onRequestClose: closeModal,
                className: "acpt-modal text-center",
                contentLabel: "Example Modal"
            },
            wp.element.createElement(
                "h2",
                { className: "title" },
                (0, _useTranslation2.default)("Your new generated API key")
            ),
            wp.element.createElement(
                "p",
                null,
                (0, _useTranslation2.default)("This is the only time that the secret access key can be viewed or copied. You cannot recover it later. However, you can delete and create or regenerate new API key at any time.")
            ),
            wp.element.createElement(
                "div",
                { className: "api-key" },
                fetched.key,
                "-",
                fetched.secret
            ),
            wp.element.createElement(
                "div",
                { className: "acpt-buttons" },
                wp.element.createElement(
                    "button",
                    {
                        className: "acpt-btn acpt-btn-primary",
                        onClick: function onClick(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            (0, _copy.copyToClipboard)(fetched.key + "-" + fetched.secret);
                            setCopyText((0, _useTranslation2.default)("Copied"));
                        }
                    },
                    copyText
                ),
                wp.element.createElement(
                    "button",
                    {
                        className: "acpt-btn acpt-btn-primary-o",
                        onClick: closeModal
                    },
                    (0, _useTranslation2.default)("Close")
                )
            )
        ),
        wp.element.createElement(
            "a",
            {
                className: "acpt-btn acpt-btn-primary",
                href: "#",
                onClick: function onClick(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleGenerateApiKey();
                }
            },
            count > 0 ? (0, _useTranslation2.default)('Regenerate') : (0, _useTranslation2.default)('Generate'),
            " API key"
        )
    );
};

exports["default"] = GenerateApiKeys;

/***/ }),

/***/ 38110:
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

var _misc = __webpack_require__(53154);

var _swaggerUiReact = __webpack_require__(99527);

var _swaggerUiReact2 = _interopRequireDefault(_swaggerUiReact);

__webpack_require__(10159);

var _GenerateApiKeys = __webpack_require__(86144);

var _GenerateApiKeys2 = _interopRequireDefault(_GenerateApiKeys);

var _ApiKeysList = __webpack_require__(8230);

var _ApiKeysList2 = _interopRequireDefault(_ApiKeysList);

var _reactRouterDom = __webpack_require__(73727);

var _reactRedux = __webpack_require__(28216);

var _objects = __webpack_require__(54040);

var _fetchApiKeys = __webpack_require__(98632);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _fetchApiKeysCount = __webpack_require__(64190);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ApiDashboard = function ApiDashboard() {

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchApiKeysReducer;
    }),
        fetched = _useSelector.fetched,
        loading = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchApiKeysCountReducer;
    }),
        fetchedCount = _useSelector2.fetched,
        loadingCount = _useSelector2.loading;

    var _useSelector3 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchSettingsReducer;
    }),
        settingsLoading = _useSelector3.loading,
        settings = _useSelector3.fetched;

    // manage local state


    var _useParams = (0, _reactRouterDom.useParams)(),
        page = _useParams.page;

    var didMountRef = (0, _react.useRef)(false);

    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        fetchedSuccess = _useState2[0],
        setFetchedSuccess = _useState2[1];

    var perPage = settings.length > 0 && (0, _objects.filterByLabel)(settings, 'key', 'records_per_page') !== '' ? (0, _objects.filterByLabel)(settings, 'key', 'records_per_page').value : 20;

    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)((0, _useTranslation2.default)("APIs dashboard panel"));
        (0, _misc.changeCurrentAdminMenuLink)('#/api');
        dispatch((0, _fetchApiKeysCount.fetchApiKeysCount)());
        dispatch((0, _fetchApiKeys.fetchApiKeys)({
            page: page ? page : 1,
            perPage: perPage
        }));
    }, [page]);

    // handle fetch outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loading && !settingsLoading) {
                setFetchedSuccess(true);
            }
        } else {
            didMountRef.current = true;
        }
    }, [loading]);

    // refresh records
    var handleRefreshApiKeys = function handleRefreshApiKeys() {
        dispatch((0, _fetchApiKeysCount.fetchApiKeysCount)());
        dispatch((0, _fetchApiKeys.fetchApiKeys)({
            page: page ? page : 1,
            perPage: perPage
        }));
    };

    if (!fetchedSuccess) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: (0, _useTranslation2.default)("APIs dashboard panel"),
            actions: wp.element.createElement(_GenerateApiKeys2.default, { count: fetchedCount, refreshApiKeys: handleRefreshApiKeys })
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
                    label: (0, _useTranslation2.default)("APIs")
                }] }),
            wp.element.createElement(
                "div",
                null,
                wp.element.createElement(_ApiKeysList2.default, { refreshApiKeys: handleRefreshApiKeys, count: fetchedCount, elements: fetched, page: page, perPage: perPage })
            ),
            wp.element.createElement(
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
                            (0, _useTranslation2.default)("APIs endpoints")
                        )
                    )
                ),
                wp.element.createElement(
                    "div",
                    { className: "acpt-card__body" },
                    wp.element.createElement(_swaggerUiReact2.default, { url: "/?rest_route=/acpt/v1/schema" })
                )
            )
        )
    );
};

exports["default"] = ApiDashboard;

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
            { className: "secondary-actions " + (document.globals.is_rtl === true ? "rtl" : "") },
            secondaryActions
        ),
        wp.element.createElement(
            "div",
            { className: "actions " + (document.globals.is_rtl === true ? "rtl" : "") },
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
        { className: "acpt-breadcrumbs " + (document.globals.is_rtl === true ? "rtl" : "") },
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

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var LanguagePlaceholder = function LanguagePlaceholder() {

    var language = document.globals.language;
    var availableLanguages = document.globals.available_languages;
    var languageLabel = availableLanguages.filter(function (l) {
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

/***/ 41222:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Pagination = function Pagination(_ref) {
    var currentPage = _ref.currentPage,
        perPage = _ref.perPage,
        records = _ref.records;


    var totalPages = Math.ceil(records / perPage);
    var rows = [];
    for (var i = 1; i <= totalPages; i++) {
        rows.push(i);
    }

    if (rows.length < 2) {
        return wp.element.createElement(_react2.default.Fragment, null);
    }

    return wp.element.createElement(
        "ul",
        { className: "acpt-pagination" },
        rows.map(function (row) {
            return wp.element.createElement(
                "li",
                null,
                row == currentPage ? wp.element.createElement(
                    "span",
                    null,
                    row
                ) : wp.element.createElement(
                    _reactRouterDom.Link,
                    { to: "/" + row },
                    row
                )
            );
        })
    );
};

exports["default"] = Pagination;

/***/ }),

/***/ 47169:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.deleteApiKey = undefined;

var _ajax = __webpack_require__(47569);

var _deleteApiKeyActions = __webpack_require__(34518);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var deleteApiKey = exports.deleteApiKey = function deleteApiKey(id) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _deleteApiKeyActions.deleteApiKeyInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("deleteApiKeyAction", { id: id });

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _deleteApiKeyActions.deleteApiKeySuccess)()) : dispatch((0, _deleteApiKeyActions.deleteApiKeyFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _deleteApiKeyActions.deleteApiKeyFailure)(_context.t0));

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

/***/ 98632:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchApiKeys = undefined;

var _ajax = __webpack_require__(47569);

var _fetchApiKeysActions = __webpack_require__(6812);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchApiKeys = exports.fetchApiKeys = function fetchApiKeys(meta) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchApiKeysActions.fetchApiKeysInProgress)(meta));
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchApiKeysAction', meta ? meta : {});

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchApiKeysActions.fetchApiKeysSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchApiKeysActions.fetchApiKeysFailure)(_context.t0));

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

/***/ 64190:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchApiKeysCount = undefined;

var _ajax = __webpack_require__(47569);

var _fetchApiKeysCountActions = __webpack_require__(12306);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchApiKeysCount = exports.fetchApiKeysCount = function fetchApiKeysCount() {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchApiKeysCountActions.fetchApiKeysCountInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchApiKeysCountAction');

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchApiKeysCountActions.fetchApiKeysCountSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchApiKeysCountActions.fetchApiKeysCountFailure)(_context.t0));

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

/***/ 26765:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.generateApiKey = undefined;

var _ajax = __webpack_require__(47569);

var _generateApiKeyActions = __webpack_require__(56673);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var generateApiKey = exports.generateApiKey = function generateApiKey() {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _generateApiKeyActions.generateApiKeyInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('generateApiKeyAction', {});

                        case 4:
                            res = _context.sent;

                            if (res.success === true) {
                                dispatch((0, _generateApiKeyActions.generateApiKeySuccess)(res.data));
                            } else {
                                dispatch((0, _generateApiKeyActions.generateApiKeyFailure)(res.error));
                            }
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _generateApiKeyActions.generateApiKeyFailure)(_context.t0));

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

/***/ 24654:
/***/ (() => {

/* (ignored) */

/***/ })

}]);
//# sourceMappingURL=8321.js.map