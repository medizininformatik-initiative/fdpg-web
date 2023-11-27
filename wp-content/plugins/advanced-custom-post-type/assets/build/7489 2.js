(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[7489],{

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

/***/ 50272:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _objects = __webpack_require__(54040);

var _Tippy = __webpack_require__(85825);

var _Tippy2 = _interopRequireDefault(_Tippy);

var _Modal = __webpack_require__(22651);

var _Modal2 = _interopRequireDefault(_Modal);

var _CustomPostTypeLabel = __webpack_require__(26054);

var _CustomPostTypeLabel2 = _interopRequireDefault(_CustomPostTypeLabel);

var _metaTypes = __webpack_require__(81895);

var _ExportCodeModal = __webpack_require__(35733);

var _ExportCodeModal2 = _interopRequireDefault(_ExportCodeModal);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var TaxonomyListElement = function TaxonomyListElement(_ref) {
    var id = _ref.id,
        element = _ref.element,
        handleDeleteTemplate = _ref.handleDeleteTemplate,
        enableVisualEditor = _ref.enableVisualEditor;

    // manage local state
    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        modalVisible = _useState2[0],
        setModalVisible = _useState2[1];

    var _useState3 = (0, _react.useState)(null),
        _useState4 = _slicedToArray(_useState3, 2),
        modalTemplateType = _useState4[0],
        setModalTemplateType = _useState4[1];

    var _useState5 = (0, _react.useState)(false),
        _useState6 = _slicedToArray(_useState5, 2),
        exportCodeModalVisible = _useState6[0],
        setExportCodeModalVisible = _useState6[1];

    var hasTemplate = function hasTemplate(templateType) {
        if (element.templates.length === 0) {
            return false;
        }

        var matches = 0;

        element.templates.map(function (el) {
            if (el.templateType === templateType) matches++;
        });

        return matches > 0;
    };

    // manage delete
    var openDeleteModal = function openDeleteModal(templateType) {
        setModalVisible(!modalVisible);
        setModalTemplateType(templateType);
    };

    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(_ExportCodeModal2.default, { setModalVisible: setExportCodeModalVisible, modalVisible: exportCodeModalVisible, belongsTo: _metaTypes.metaTypes.TAXONOMY, find: element.slug }),
        wp.element.createElement(
            _Modal2.default,
            { title: (0, _useTranslation2.default)("Confirm deleting this template?"), visible: modalVisible },
            wp.element.createElement(
                "p",
                null,
                "Are you sure?"
            ),
            wp.element.createElement(
                "p",
                null,
                wp.element.createElement(
                    "a",
                    {
                        href: "#",
                        className: "acpt-btn acpt-btn-primary",
                        onClick: function onClick(e) {
                            e.preventDefault();
                            setModalVisible(!modalVisible);
                            handleDeleteTemplate(element.slug, modalTemplateType);
                        }
                    },
                    (0, _useTranslation2.default)("Yes")
                ),
                "\xA0",
                wp.element.createElement(
                    "a",
                    {
                        href: "#",
                        className: "acpt-btn acpt-btn-primary-o",
                        onClick: function onClick(e) {
                            e.preventDefault();
                            setModalVisible(!modalVisible);
                        }
                    },
                    (0, _useTranslation2.default)("No")
                )
            )
        ),
        wp.element.createElement(
            "tr",
            null,
            wp.element.createElement(
                "td",
                { className: "backend" },
                wp.element.createElement(
                    "div",
                    { className: "m-0 mb-1" },
                    wp.element.createElement(
                        "strong",
                        null,
                        element.slug
                    ),
                    !element.isNative && wp.element.createElement(
                        "div",
                        { className: "element-buttons" },
                        wp.element.createElement(
                            "a",
                            { href: "#/view_taxonomy/" + element.slug },
                            (0, _useTranslation2.default)("View")
                        ),
                        wp.element.createElement(
                            "a",
                            { href: "#/edit_taxonomy/" + element.slug },
                            (0, _useTranslation2.default)("Edit")
                        ),
                        wp.element.createElement(
                            "a",
                            { href: "#/delete_taxonomy/" + element.slug },
                            (0, _useTranslation2.default)("Delete")
                        ),
                        wp.element.createElement(
                            "a",
                            {
                                href: "#",
                                onClick: function onClick(e) {
                                    e.preventDefault();
                                    setExportCodeModalVisible(!exportCodeModalVisible);
                                }
                            },
                            (0, _useTranslation2.default)("Export code")
                        )
                    )
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
                (0, _objects.isset)(element, "meta") && element.meta.length > 0 ? wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: "meta-taxonomy/" + element.slug,
                        className: "acpt-btn no-border acpt-btn-sm acpt-btn-info-o"
                    },
                    (0, _useTranslation2.default)("Manage")
                ) : wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: "/meta-taxonomy/" + element.slug,
                        className: "acpt-btn no-border acpt-btn-sm acpt-btn-primary-o"
                    },
                    (0, _useTranslation2.default)("Create")
                )
            ),
            wp.element.createElement(
                "td",
                null,
                (0, _objects.isset)(element, "customPostTypes") && element.customPostTypes.length > 0 ? wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: "/assoc-post-taxonomy/" + element.slug,
                        className: "acpt-btn no-border acpt-btn-sm acpt-btn-info-o"
                    },
                    (0, _useTranslation2.default)("Manage")
                ) : wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: "/assoc-post-taxonomy/" + element.slug,
                        className: "acpt-btn no-border acpt-btn-sm acpt-btn-primary-o"
                    },
                    (0, _useTranslation2.default)("Associate")
                )
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
                { className: "frontend" },
                enableVisualEditor !== "1" ? wp.element.createElement(
                    _react2.default.Fragment,
                    null,
                    wp.element.createElement(
                        _Tippy2.default,
                        {
                            placement: "end",
                            html: wp.element.createElement(
                                "div",
                                null,
                                (0, _useTranslation2.default)("The visual builder is disabled. Go to Settings page and enable it.")
                            )
                        },
                        wp.element.createElement(
                            "span",
                            {
                                className: "acpt-btn no-border acpt-btn-sm acpt-btn-secondary-o cursor-not-allowed"
                            },
                            (0, _useTranslation2.default)("Disabled")
                        )
                    )
                ) : wp.element.createElement(
                    _react2.default.Fragment,
                    null,
                    hasTemplate('single') ? wp.element.createElement(
                        _react2.default.Fragment,
                        null,
                        wp.element.createElement(
                            _reactRouterDom.Link,
                            {
                                to: "/template/taxonomy/single/" + element.slug,
                                className: "acpt-btn no-border acpt-btn-sm acpt-btn-info-o"
                            },
                            (0, _useTranslation2.default)("Manage")
                        ),
                        wp.element.createElement(
                            "a",
                            {
                                onClick: function onClick(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    openDeleteModal('single');
                                },
                                className: "ml-1 acpt-btn no-border acpt-btn-sm acpt-btn-danger-o",
                                href: "#"
                            },
                            (0, _useTranslation2.default)("Delete")
                        )
                    ) : wp.element.createElement(
                        _reactRouterDom.Link,
                        {
                            to: "/template/taxonomy/single/" + element.slug,
                            className: "acpt-btn no-border acpt-btn-sm acpt-btn-primary-o"
                        },
                        (0, _useTranslation2.default)("Create")
                    )
                )
            )
        )
    );
};

exports["default"] = TaxonomyListElement;

/***/ }),

/***/ 97489:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _misc = __webpack_require__(53154);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _reactRedux = __webpack_require__(28216);

var _reactRouterDom = __webpack_require__(73727);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _fetchTaxonomiesCount = __webpack_require__(82081);

var _fetchTaxonomies = __webpack_require__(91141);

var _Pagination = __webpack_require__(41222);

var _Pagination2 = _interopRequireDefault(_Pagination);

var _TaxonomyListElement = __webpack_require__(50272);

var _TaxonomyListElement2 = _interopRequireDefault(_TaxonomyListElement);

var _react3 = __webpack_require__(44226);

var _Tippy = __webpack_require__(85825);

var _Tippy2 = _interopRequireDefault(_Tippy);

var _localization = __webpack_require__(48525);

var _objects = __webpack_require__(54040);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _deleteTemplate = __webpack_require__(10495);

var _metaTypes = __webpack_require__(81895);

var _reactToastify = __webpack_require__(39249);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var TaxonomyList = function TaxonomyList() {

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTaxonomiesReducer;
    }),
        fetched = _useSelector.fetched,
        loading = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTaxonomiesCountReducer;
    }),
        fetchedCount = _useSelector2.fetched,
        loadingCount = _useSelector2.loading;

    var _useSelector3 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchSettingsReducer;
    }),
        settingsLoading = _useSelector3.loading,
        settings = _useSelector3.fetched;

    var _useSelector4 = (0, _reactRedux.useSelector)(function (state) {
        return state.deleteTemplateReducer;
    }),
        deleteTemplateErrors = _useSelector4.errors,
        deleteTemplateSuccess = _useSelector4.success,
        deleteTemplateLoading = _useSelector4.loading;

    // manage local state


    var _useParams = (0, _reactRouterDom.useParams)(),
        page = _useParams.page;

    var didMountRef = (0, _react.useRef)(false);

    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        fetchedSuccess = _useState2[0],
        setFetchedSuccess = _useState2[1];

    var perPage = settings.length > 0 && (0, _objects.filterByLabel)(settings, 'key', 'records_per_page') !== '' ? (0, _objects.filterByLabel)(settings, 'key', 'records_per_page').value : 20;
    var enableVisualEditor = settings.length > 0 && (0, _objects.filterByLabel)(settings, 'key', 'enable_visual_editor') !== '' ? (0, _objects.filterByLabel)(settings, 'key', 'enable_visual_editor').value : false;
    var totalPages = Math.ceil(fetchedCount / perPage);
    var history = (0, _reactRouterDom.useHistory)();

    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)((0, _useTranslation2.default)((0, _localization.translate)("taxonomy_list.title")));
        (0, _misc.changeCurrentAdminMenuLink)('#/taxonomies');
        dispatch((0, _fetchTaxonomiesCount.fetchTaxonomiesCount)());
        dispatch((0, _fetchTaxonomies.fetchTaxonomies)({
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

    // handle delete template outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!deleteTemplateLoading) {
                if (deleteTemplateSuccess) {
                    history.push('/taxonomies');
                    _reactToastify.toast.success((0, _useTranslation2.default)("Template was successfully deleted. The browser will refresh after 5 seconds."));
                    (0, _misc.refreshPage)(5000);
                }

                if (deleteTemplateErrors.length > 0) {
                    deleteTemplateErrors.map(function (error) {
                        _reactToastify.toast.error(error);
                    });
                }
            }
        } else {
            didMountRef.current = true;
        }
    }, [deleteTemplateLoading]);

    var handleDeleteTemplate = function handleDeleteTemplate(slug, type) {
        dispatch((0, _deleteTemplate.deleteTemplate)(_metaTypes.metaTypes.TAXONOMY, slug, type));
    };

    if (!fetchedSuccess) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    var button = wp.element.createElement(
        _reactRouterDom.Link,
        {
            className: "acpt-btn acpt-btn-primary",
            to: "/register_taxonomy"
        },
        (0, _useTranslation2.default)("Register new Taxonomy")
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: (0, _useTranslation2.default)("Registered Taxonomies"),
            actions: button
        }),
        wp.element.createElement(
            "main",
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Taxonomies")
                }] }),
            fetched.length > 0 ? wp.element.createElement(
                "div",
                { className: "acpt-card" },
                wp.element.createElement(
                    "div",
                    { className: "acpt-card__header" },
                    wp.element.createElement(
                        "div",
                        { className: "acpt-card__inner" },
                        fetchedCount,
                        " ",
                        (0, _useTranslation2.default)("record(s) found")
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
                                        null,
                                        "Slug \xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Taxonomy slug. The post name/slug. Used for various queries for taxonomy content.") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", color: "#2271b1", width: "12px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        (0, _useTranslation2.default)("Type")
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        (0, _useTranslation2.default)("Meta fields"),
                                        "\xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Associated meta fields") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", color: "#2271b1", width: "12px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        (0, _useTranslation2.default)("Associated post types"),
                                        "\xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Associate custom post types here") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", color: "#2271b1", width: "12px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        { className: "with-border" },
                                        (0, _useTranslation2.default)("Post count"),
                                        "\xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Published posts count associated with the taxonomy") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", color: "#2271b1", width: "18px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        { className: "text-center" },
                                        (0, _useTranslation2.default)("Single template"),
                                        "\xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("The single template for taxonomy term") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", color: "#2271b1", width: "12px" })
                                            )
                                        )
                                    )
                                )
                            ),
                            wp.element.createElement(
                                "tbody",
                                null,
                                fetched.map(function (element) {
                                    return wp.element.createElement(_TaxonomyListElement2.default, {
                                        id: element.id,
                                        key: element.id,
                                        element: element,
                                        handleDeleteTemplate: handleDeleteTemplate,
                                        enableVisualEditor: enableVisualEditor
                                    });
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
                        wp.element.createElement(_Pagination2.default, { currentPage: page ? page : 1, perPage: perPage, records: fetchedCount })
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

exports["default"] = TaxonomyList;

/***/ }),

/***/ 26054:
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

/***/ 41076:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactCodeBlocks = __webpack_require__(18879);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ShowCode = function ShowCode(_ref) {
    var codeString = _ref.codeString;


    return wp.element.createElement(
        "div",
        {
            style: {
                maxHeight: '450px',
                overflow: 'auto'
            }
        },
        wp.element.createElement(_reactCodeBlocks.CopyBlock, {
            text: codeString,
            language: "php",
            showLineNumbers: false,
            theme: _reactCodeBlocks.monoBlue,
            wrapLines: true,
            highlight: true
        })
    );
};

exports["default"] = ShowCode;

/***/ }),

/***/ 35733:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _Modal = __webpack_require__(22651);

var _Modal2 = _interopRequireDefault(_Modal);

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Accordion = __webpack_require__(81989);

var _Accordion2 = _interopRequireDefault(_Accordion);

var _ShowCode = __webpack_require__(41076);

var _ShowCode2 = _interopRequireDefault(_ShowCode);

var _ajax = __webpack_require__(47569);

var _objects = __webpack_require__(54040);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var ExportCodeModal = function ExportCodeModal(_ref) {
    var modalVisible = _ref.modalVisible,
        setModalVisible = _ref.setModalVisible,
        belongsTo = _ref.belongsTo,
        find = _ref.find;

    // manage local state
    var _useState = (0, _react.useState)({}),
        _useState2 = _slicedToArray(_useState, 2),
        codeStrings = _useState2[0],
        setCodeStrings = _useState2[1];

    (0, _react.useEffect)(function () {
        if (modalVisible === true) {
            (0, _ajax.wpAjaxRequest)("exportCodeAction", { belongsTo: belongsTo, find: find }).then(function (res) {
                setCodeStrings(res);
            }).catch(function (err) {
                console.error(err.message);
            });
        }
    }, [modalVisible]);

    return wp.element.createElement(
        _Modal2.default,
        { title: (0, _useTranslation2.default)("Export code"), visible: modalVisible, textAlign: "left" },
        wp.element.createElement(
            "div",
            { className: "mb-3" },
            !(0, _objects.isEmpty)(codeStrings) && (0, _objects.isset)(codeStrings, 'wordpress') && (0, _objects.isset)(codeStrings, 'acpt') ? wp.element.createElement(
                _Accordion2.default,
                { cssClass: "bordered w-full" },
                wp.element.createElement(_ShowCode2.default, { codeString: codeStrings.wordpress, title: "WORDPRESS" }),
                wp.element.createElement(_ShowCode2.default, { codeString: codeStrings.acpt, title: "ACPT" })
            ) : wp.element.createElement(
                _react2.default.Fragment,
                null,
                (0, _useTranslation2.default)("Generating code...")
            )
        ),
        wp.element.createElement(
            "div",
            { className: "acpt-buttons text-left" },
            wp.element.createElement(
                "a",
                {
                    href: "#",
                    className: "acpt-btn acpt-btn-primary-o",
                    onClick: function onClick(e) {
                        e.preventDefault();
                        setModalVisible(false);
                    }
                },
                (0, _useTranslation2.default)("Close")
            )
        )
    );
};

exports["default"] = ExportCodeModal;

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

/***/ 69504:
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var lanuagesList = exports.lanuagesList = [{ value: 'en_GB', label: "English" }, { value: 'it_IT', label: "Italian" }];

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

/***/ 10495:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.deleteTemplate = undefined;

var _ajax = __webpack_require__(47569);

var _deleteTemplateActions = __webpack_require__(23912);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var deleteTemplate = exports.deleteTemplate = function deleteTemplate(belongsTo, find, templateType) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _deleteTemplateActions.deleteTemplateInProgress)(belongsTo, find, templateType));
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('deleteTemplateAction', { belongsTo: belongsTo, find: find, templateType: templateType });

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _deleteTemplateActions.deleteTemplateSuccess)()) : dispatch((0, _deleteTemplateActions.deleteTemplateFailure)(res.error));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _deleteTemplateActions.deleteTemplateFailure)(_context.t0));

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

/***/ }),

/***/ 82081:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchTaxonomiesCount = undefined;

var _ajax = __webpack_require__(47569);

var _fetchTaxonomiesCountActions = __webpack_require__(15034);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchTaxonomiesCount = exports.fetchTaxonomiesCount = function fetchTaxonomiesCount() {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchTaxonomiesCountActions.fetchTaxonomiesCountInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchTaxonomiesCountAction');

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchTaxonomiesCountActions.fetchTaxonomiesCountSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchTaxonomiesCountActions.fetchTaxonomiesCountFailure)(_context.t0));

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
//# sourceMappingURL=7489.js.map