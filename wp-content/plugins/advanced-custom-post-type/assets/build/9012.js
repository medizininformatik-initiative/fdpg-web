"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[9012],{

/***/ 56595:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



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

var _react3 = __webpack_require__(44226);

var _Modal = __webpack_require__(22651);

var _Modal2 = _interopRequireDefault(_Modal);

var _TaxonomiesMiniTable = __webpack_require__(19738);

var _TaxonomiesMiniTable2 = _interopRequireDefault(_TaxonomiesMiniTable);

var _WoocommerceMiniTable = __webpack_require__(75200);

var _WoocommerceMiniTable2 = _interopRequireDefault(_WoocommerceMiniTable);

var _CustomPostTypeListElementButtons = __webpack_require__(60636);

var _CustomPostTypeListElementButtons2 = _interopRequireDefault(_CustomPostTypeListElementButtons);

var _MetaBoxMiniTable = __webpack_require__(70400);

var _MetaBoxMiniTable2 = _interopRequireDefault(_MetaBoxMiniTable);

var _CustomPostTypeLabel = __webpack_require__(26054);

var _CustomPostTypeLabel2 = _interopRequireDefault(_CustomPostTypeLabel);

var _ExportCodeModal = __webpack_require__(35733);

var _ExportCodeModal2 = _interopRequireDefault(_ExportCodeModal);

var _metaTypes = __webpack_require__(81895);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

var _CustomPostTypeIcon = __webpack_require__(79794);

var _CustomPostTypeIcon2 = _interopRequireDefault(_CustomPostTypeIcon);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var CustomPostTypeListElement = function CustomPostTypeListElement(_ref) {
    var id = _ref.id,
        isBBThemeBuilderActive = _ref.isBBThemeBuilderActive,
        isOxygenActive = _ref.isOxygenActive,
        thereIsWooCommerce = _ref.thereIsWooCommerce,
        element = _ref.element,
        handleDeleteTemplate = _ref.handleDeleteTemplate,
        enableVisualEditor = _ref.enableVisualEditor;

    // manage local state
    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        modalVisible = _useState2[0],
        setModalVisible = _useState2[1];

    var _useState3 = (0, _react.useState)(false),
        _useState4 = _slicedToArray(_useState3, 2),
        exportCodeModalVisible = _useState4[0],
        setExportCodeModalVisible = _useState4[1];

    var _useState5 = (0, _react.useState)(false),
        _useState6 = _slicedToArray(_useState5, 2),
        modalTemplateType = _useState6[0],
        setModalTemplateType = _useState6[1];

    // manage redirect


    var openDeleteModal = function openDeleteModal(templateType) {
        setModalTemplateType(templateType);
        setModalVisible(!modalVisible);
    };

    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(_ExportCodeModal2.default, { setModalVisible: setExportCodeModalVisible, modalVisible: exportCodeModalVisible, belongsTo: _metaTypes.metaTypes.CUSTOM_POST_TYPE, find: element.name }),
        wp.element.createElement(
            _Modal2.default,
            { title: (0, _useTranslation2.default)("Confirm deleting this template?"), visible: modalVisible },
            wp.element.createElement(
                'p',
                null,
                (0, _useTranslation2.default)("Are you sure?")
            ),
            wp.element.createElement(
                'p',
                null,
                wp.element.createElement(
                    'a',
                    {
                        href: '#',
                        className: 'acpt-btn acpt-btn-primary',
                        onClick: function onClick(e) {
                            e.preventDefault();
                            setModalVisible(!modalVisible);
                            handleDeleteTemplate(element.name, modalTemplateType);
                        }
                    },
                    (0, _useTranslation2.default)("Yes")
                ),
                '\xA0',
                wp.element.createElement(
                    'a',
                    {
                        href: '#',
                        className: 'acpt-btn acpt-btn-primary-o',
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
            'tr',
            null,
            wp.element.createElement(
                'td',
                { className: 'backend' },
                wp.element.createElement(_CustomPostTypeIcon2.default, { value: element.icon })
            ),
            wp.element.createElement(
                'td',
                { className: 'backend' },
                wp.element.createElement(
                    'div',
                    { className: 'm-0 mb-1' },
                    wp.element.createElement(
                        'strong',
                        null,
                        element.name
                    ),
                    !element.isNative && wp.element.createElement(
                        'div',
                        { className: 'element-buttons' },
                        wp.element.createElement(
                            'a',
                            { href: '#/view/' + element.name },
                            (0, _useTranslation2.default)("View")
                        ),
                        wp.element.createElement(
                            'a',
                            { href: '#/edit/' + element.name },
                            (0, _useTranslation2.default)("Edit")
                        ),
                        wp.element.createElement(
                            'a',
                            { href: '#/delete/' + element.name },
                            (0, _useTranslation2.default)("Delete")
                        ),
                        wp.element.createElement(
                            'a',
                            {
                                href: '#',
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
                'td',
                null,
                wp.element.createElement(_CustomPostTypeLabel2.default, { element: element })
            ),
            wp.element.createElement(
                'td',
                { className: 'backend' },
                (0, _objects.isset)(element, "meta") && element.meta.length > 0 ? wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: 'meta/' + element.name,
                        className: 'acpt-btn no-border acpt-btn-sm acpt-btn-info-o'
                    },
                    (0, _useTranslation2.default)("Manage")
                ) : wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: '/meta/' + element.name,
                        className: 'acpt-btn no-border acpt-btn-sm acpt-btn-primary-o'
                    },
                    (0, _useTranslation2.default)("Create")
                )
            ),
            thereIsWooCommerce === true && wp.element.createElement(
                'td',
                { className: 'backend' },
                element.isWooCommerce === true && wp.element.createElement(
                    _react2.default.Fragment,
                    null,
                    (0, _objects.isset)(element, "woocommerceProductData") && element.woocommerceProductData.length > 0 ? wp.element.createElement(
                        _reactRouterDom.Link,
                        {
                            to: '/product-data/' + element.name,
                            className: 'acpt-btn no-border acpt-btn-sm acpt-btn-info-o'
                        },
                        (0, _useTranslation2.default)("Manage")
                    ) : wp.element.createElement(
                        _reactRouterDom.Link,
                        {
                            to: '/product-data/' + element.name,
                            className: 'acpt-btn no-border acpt-btn-sm acpt-btn-primary-o'
                        },
                        (0, _useTranslation2.default)("Create")
                    )
                )
            ),
            wp.element.createElement(
                'td',
                null,
                (0, _objects.isset)(element, "taxonomies") && element.taxonomies.length > 0 ? wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: '/assoc-taxonomy-post/' + element.name,
                        className: 'acpt-btn no-border acpt-btn-sm acpt-btn-info-o'
                    },
                    (0, _useTranslation2.default)("Manage")
                ) : wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: '/assoc-taxonomy-post/' + element.name,
                        className: 'acpt-btn no-border acpt-btn-sm acpt-btn-primary-o'
                    },
                    (0, _useTranslation2.default)("Associate")
                )
            ),
            wp.element.createElement(
                'td',
                { className: 'backend with-border' },
                wp.element.createElement(
                    'span',
                    { className: 'acpt-badge' },
                    wp.element.createElement(
                        'span',
                        { className: 'label' },
                        element.postCount
                    )
                )
            ),
            wp.element.createElement(_CustomPostTypeListElementButtons2.default, {
                element: element,
                openDeleteModal: openDeleteModal,
                isOxygenActive: isOxygenActive,
                isBBThemeBuilderActive: isBBThemeBuilderActive,
                enableVisualEditor: enableVisualEditor
            })
        )
    );
};

exports["default"] = CustomPostTypeListElement;

/***/ }),

/***/ 60636:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _Tippy = __webpack_require__(85825);

var _Tippy2 = _interopRequireDefault(_Tippy);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var CustomPostTypeListElementButtons = function CustomPostTypeListElementButtons(_ref) {
    var element = _ref.element,
        openDeleteModal = _ref.openDeleteModal,
        isOxygenActive = _ref.isOxygenActive,
        isBBThemeBuilderActive = _ref.isBBThemeBuilderActive,
        enableVisualEditor = _ref.enableVisualEditor;


    var hasArchive = function hasArchive() {
        return element.settings.has_archive;
    };

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

    var renderButton = function renderButton(type) {

        if (enableVisualEditor !== "1") {
            return wp.element.createElement(
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
            );
        }

        if (isOxygenActive) {
            return wp.element.createElement(
                _Tippy2.default,
                {
                    placement: "end",
                    html: wp.element.createElement(
                        "div",
                        null,
                        (0, _useTranslation2.default)("When Oxygen Builder is active, the visual builder is disabled.")
                    )
                },
                wp.element.createElement(
                    "span",
                    {
                        className: "acpt-btn no-border acpt-btn-sm acpt-btn-secondary-o cursor-not-allowed"
                    },
                    (0, _useTranslation2.default)("Disabled")
                )
            );
        }

        if (isBBThemeBuilderActive) {
            return wp.element.createElement(
                _Tippy2.default,
                {
                    placement: "end",
                    html: wp.element.createElement(
                        "div",
                        null,
                        (0, _useTranslation2.default)("When BB Theme builder is active, the visual builder is disabled.")
                    )
                },
                wp.element.createElement(
                    "span",
                    {
                        className: "acpt-btn no-border acpt-btn-sm acpt-btn-secondary-o cursor-not-allowed"
                    },
                    (0, _useTranslation2.default)("Disabled")
                )
            );
        }

        if (type === 'archive') {
            if (!hasArchive()) {
                return wp.element.createElement(
                    _Tippy2.default,
                    {
                        placement: "end",
                        html: wp.element.createElement(
                            "div",
                            null,
                            (0, _useTranslation2.default)("`has_archive` set to false")
                        )
                    },
                    wp.element.createElement(
                        "span",
                        {
                            className: "acpt-btn no-border acpt-btn-sm acpt-btn-secondary-o cursor-not-allowed"
                        },
                        (0, _useTranslation2.default)("Not allowed")
                    )
                );
            }

            if (element.existsArchivePageInTheme === true) {
                return wp.element.createElement(
                    _Tippy2.default,
                    {
                        placement: "end",
                        html: wp.element.createElement(
                            "div",
                            null,
                            (0, _useTranslation2.default)("Your theme already has an archive template for"),
                            " ",
                            element.name
                        )
                    },
                    wp.element.createElement(
                        "span",
                        {
                            className: "acpt-btn no-border acpt-btn-sm acpt-btn-secondary-o cursor-not-allowed"
                        },
                        (0, _useTranslation2.default)("Not allowed")
                    )
                );
            }

            if (parseInt(element.postCount) === 0) {
                return wp.element.createElement(
                    _Tippy2.default,
                    {
                        placement: "end",
                        html: wp.element.createElement(
                            "div",
                            null,
                            (0, _useTranslation2.default)("First, create a post for this post type")
                        )
                    },
                    wp.element.createElement(
                        "span",
                        {
                            className: "acpt-btn no-border acpt-btn-sm acpt-btn-warning-o cursor-not-allowed"
                        },
                        (0, _useTranslation2.default)("Action required")
                    )
                );
            }

            return wp.element.createElement(
                _react2.default.Fragment,
                null,
                wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: "template/customPostType/archive/" + element.name,
                        className: "acpt-btn no-border acpt-btn-sm " + (hasTemplate('archive') ? 'acpt-btn-info-o' : 'acpt-btn-primary-o')
                    },
                    hasTemplate('archive') ? (0, _useTranslation2.default)('Manage') : (0, _useTranslation2.default)('Create')
                ),
                hasTemplate('archive') && wp.element.createElement(
                    "a",
                    {
                        onClick: function onClick(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            openDeleteModal('archive');
                        },
                        className: "ml-1 acpt-btn no-border acpt-btn-sm acpt-btn-danger-o",
                        href: "#"
                    },
                    (0, _useTranslation2.default)("Delete")
                )
            );
        }

        if (type === 'single') {
            if (element.existsSinglePageInTheme === true) {
                return wp.element.createElement(
                    _Tippy2.default,
                    {
                        placement: "end",
                        html: wp.element.createElement(
                            "div",
                            null,
                            (0, _useTranslation2.default)("Your theme already has a single template for"),
                            " ",
                            element.name
                        )
                    },
                    wp.element.createElement(
                        "span",
                        {
                            className: "acpt-btn no-border acpt-btn-sm acpt-btn-secondary-o cursor-not-allowed"
                        },
                        (0, _useTranslation2.default)("Not allowed")
                    )
                );
            }

            if (parseInt(element.postCount) === 0) {
                return wp.element.createElement(
                    _Tippy2.default,
                    {
                        placement: "end",
                        html: wp.element.createElement(
                            "div",
                            null,
                            (0, _useTranslation2.default)("First, create a post for this post type")
                        )
                    },
                    wp.element.createElement(
                        "span",
                        {
                            className: "acpt-btn no-border acpt-btn-sm acpt-btn-warning-o cursor-not-allowed"
                        },
                        (0, _useTranslation2.default)("Action required")
                    )
                );
            }

            return wp.element.createElement(
                _react2.default.Fragment,
                null,
                wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: "/template/customPostType/single/" + element.name,
                        className: "acpt-btn no-border acpt-btn-sm " + (hasTemplate('single') ? 'acpt-btn-info-o' : 'acpt-btn-primary-o')
                    },
                    hasTemplate('single') ? (0, _useTranslation2.default)('Manage') : (0, _useTranslation2.default)('Create')
                ),
                hasTemplate('single') && wp.element.createElement(
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
            );
        }

        if (type === 'related') {

            if (parseInt(element.postCount) === 0) {
                return wp.element.createElement(
                    _Tippy2.default,
                    {
                        placement: "end",
                        html: wp.element.createElement(
                            "div",
                            null,
                            (0, _useTranslation2.default)("First, create a post for this post type")
                        )
                    },
                    wp.element.createElement(
                        "span",
                        {
                            className: "acpt-btn no-border acpt-btn-sm acpt-btn-warning-o cursor-not-allowed"
                        },
                        (0, _useTranslation2.default)("Action required")
                    )
                );
            }

            return wp.element.createElement(
                _react2.default.Fragment,
                null,
                wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: "/template/customPostType/related/" + element.name,
                        className: "acpt-btn no-border acpt-btn-sm " + (hasTemplate('related') ? 'acpt-btn-info-o' : 'acpt-btn-primary-o')
                    },
                    hasTemplate('related') ? (0, _useTranslation2.default)('Manage') : (0, _useTranslation2.default)('Create')
                ),
                hasTemplate('related') && wp.element.createElement(
                    "a",
                    {
                        onClick: function onClick(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            openDeleteModal('related');
                        },
                        className: "ml-1 acpt-btn no-border acpt-btn-sm acpt-btn-danger-o",
                        href: "#"
                    },
                    (0, _useTranslation2.default)("Delete")
                )
            );
        }
    };

    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            "td",
            { className: "frontend" },
            renderButton("archive")
        ),
        wp.element.createElement(
            "td",
            { className: "frontend" },
            renderButton("single")
        ),
        wp.element.createElement(
            "td",
            { className: "frontend" },
            renderButton("related")
        )
    );
};

exports["default"] = CustomPostTypeListElementButtons;

/***/ }),

/***/ 19738:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var TaxonomiesMiniTable = function TaxonomiesMiniTable(_ref) {
    var postType = _ref.postType,
        elements = _ref.elements;


    return wp.element.createElement(
        "div",
        { className: "acpt-table-responsive" },
        wp.element.createElement(
            "table",
            { className: "acpt-minitable " + (document.globals.is_rtl === true ? "rtl" : "") },
            wp.element.createElement(
                "thead",
                null,
                wp.element.createElement(
                    "tr",
                    null,
                    wp.element.createElement(
                        "th",
                        null,
                        (0, _useTranslation2.default)("Taxonomy")
                    ),
                    wp.element.createElement(
                        "th",
                        null,
                        (0, _useTranslation2.default)("Sing. label")
                    ),
                    wp.element.createElement(
                        "th",
                        null,
                        (0, _useTranslation2.default)("Plural label")
                    ),
                    wp.element.createElement(
                        "th",
                        null,
                        (0, _useTranslation2.default)("Post count")
                    )
                )
            ),
            wp.element.createElement(
                "tbody",
                null,
                elements.map(function (element) {
                    return wp.element.createElement(
                        "tr",
                        null,
                        wp.element.createElement(
                            "td",
                            null,
                            element.slug
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
                            null,
                            wp.element.createElement(
                                "span",
                                { className: "acpt-badge" },
                                wp.element.createElement(
                                    "span",
                                    { className: "label" },
                                    element.postCount ? element.postCount : 0
                                )
                            )
                        )
                    );
                })
            )
        ),
        wp.element.createElement(
            "div",
            { className: "minitable-buttons" },
            wp.element.createElement(
                "a",
                { href: "#/assoc-taxonomy-post/" + postType },
                (0, _useTranslation2.default)("Manage")
            )
        )
    );
};

exports["default"] = TaxonomiesMiniTable;

/***/ }),

/***/ 75200:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

__webpack_require__(72107);

var _Boolean = __webpack_require__(19904);

var _Boolean2 = _interopRequireDefault(_Boolean);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var WoocommerceMiniTable = function WoocommerceMiniTable(_ref) {
    var postType = _ref.postType,
        elements = _ref.elements;


    return wp.element.createElement(
        'div',
        { className: 'acpt-table-responsive' },
        wp.element.createElement(
            'table',
            { className: 'acpt-minitable ' + (document.globals.is_rtl === true ? 'rtl' : '') },
            wp.element.createElement(
                'thead',
                null,
                wp.element.createElement(
                    'tr',
                    null,
                    wp.element.createElement(
                        'th',
                        null,
                        (0, _useTranslation2.default)("Name"),
                        'Name'
                    ),
                    wp.element.createElement(
                        'th',
                        null,
                        (0, _useTranslation2.default)("Icon"),
                        'Icon'
                    ),
                    wp.element.createElement(
                        'th',
                        null,
                        (0, _useTranslation2.default)("Show on UI"),
                        'Show on UI'
                    ),
                    wp.element.createElement(
                        'th',
                        null,
                        (0, _useTranslation2.default)("Fields"),
                        'Fields'
                    )
                )
            ),
            wp.element.createElement(
                'tbody',
                null,
                elements.map(function (element) {
                    return wp.element.createElement(
                        'tr',
                        null,
                        wp.element.createElement(
                            'td',
                            null,
                            element.name
                        ),
                        wp.element.createElement(
                            'td',
                            null,
                            wp.element.createElement('span', { className: 'wcicon-' + element.icon.icon })
                        ),
                        wp.element.createElement(
                            'td',
                            null,
                            wp.element.createElement(_Boolean2.default, { status: element.showInUI })
                        ),
                        wp.element.createElement(
                            'td',
                            null,
                            wp.element.createElement(
                                'span',
                                { className: 'acpt-badge' },
                                wp.element.createElement(
                                    'span',
                                    { className: 'label' },
                                    element.fields.length
                                )
                            )
                        )
                    );
                })
            )
        ),
        wp.element.createElement(
            'div',
            { className: 'minitable-buttons' },
            wp.element.createElement(
                'a',
                { href: '#/product-data/' + postType },
                (0, _useTranslation2.default)("Manage")
            )
        )
    );
};

exports["default"] = WoocommerceMiniTable;

/***/ }),

/***/ 19012:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _CustomPostTypeListElement = __webpack_require__(56595);

var _CustomPostTypeListElement2 = _interopRequireDefault(_CustomPostTypeListElement);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _reactRedux = __webpack_require__(28216);

var _fetchPostTypes = __webpack_require__(14825);

var _fetchPostTypesCount = __webpack_require__(84953);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _misc = __webpack_require__(53154);

var _Pagination = __webpack_require__(41222);

var _Pagination2 = _interopRequireDefault(_Pagination);

var _Tippy = __webpack_require__(85825);

var _Tippy2 = _interopRequireDefault(_Tippy);

var _react3 = __webpack_require__(44226);

var _deleteTemplate = __webpack_require__(10495);

var _reactToastify = __webpack_require__(39249);

var _syncPosts = __webpack_require__(20682);

var _objects = __webpack_require__(54040);

var _ajax = __webpack_require__(47569);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _metaTypes = __webpack_require__(81895);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var CustomPostTypeList = function CustomPostTypeList() {

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetched = _useSelector.fetched,
        loading = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesCountReducer;
    }),
        fetchedCount = _useSelector2.fetched,
        loadingCount = _useSelector2.loading;

    var _useSelector3 = (0, _reactRedux.useSelector)(function (state) {
        return state.deleteTemplateReducer;
    }),
        deleteTemplateErrors = _useSelector3.errors,
        deleteTemplateSuccess = _useSelector3.success,
        deleteTemplateLoading = _useSelector3.loading;

    var _useSelector4 = (0, _reactRedux.useSelector)(function (state) {
        return state.syncPostsReducer;
    }),
        syncPostsErrors = _useSelector4.errors,
        syncPostsSuccess = _useSelector4.success,
        syncPostsLoading = _useSelector4.loading;

    var _useSelector5 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchSettingsReducer;
    }),
        settingsLoading = _useSelector5.loading,
        settings = _useSelector5.fetched;

    // manage local state


    var _useParams = (0, _reactRouterDom.useParams)(),
        page = _useParams.page;

    var didMountRef = (0, _react.useRef)(false);

    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        fetchedSuccess = _useState2[0],
        setFetchedSuccess = _useState2[1];

    var _useState3 = (0, _react.useState)(false),
        _useState4 = _slicedToArray(_useState3, 2),
        thereIsWooCommerce = _useState4[0],
        setThereIsWooCommerce = _useState4[1];

    var _useState5 = (0, _react.useState)(false),
        _useState6 = _slicedToArray(_useState5, 2),
        isOxygenActive = _useState6[0],
        setIsOxygenActive = _useState6[1];

    var _useState7 = (0, _react.useState)(false),
        _useState8 = _slicedToArray(_useState7, 2),
        isBBThemeBuilderActive = _useState8[0],
        setIsBBThemeBuilderActive = _useState8[1];

    var perPage = settings.length > 0 && (0, _objects.filterByLabel)(settings, 'key', 'records_per_page') !== '' ? (0, _objects.filterByLabel)(settings, 'key', 'records_per_page').value : 20;
    var enableVisualEditor = settings.length > 0 && (0, _objects.filterByLabel)(settings, 'key', 'enable_visual_editor') !== '' ? (0, _objects.filterByLabel)(settings, 'key', 'enable_visual_editor').value : false;
    var history = (0, _reactRouterDom.useHistory)();
    var totalPages = Math.ceil(fetchedCount / perPage);

    (0, _react.useEffect)(function () {

        // is Oxygen builder active?
        (0, _ajax.wpAjaxRequest)("isOxygenBuilderActiveAction", {}).then(function (res) {
            setIsOxygenActive(res.status);
        }).catch(function (err) {
            console.error(err.message);
        });

        // is BB Theme builder active?
        (0, _ajax.wpAjaxRequest)("isBBThemeBuilderActiveAction", {}).then(function (res) {
            setIsBBThemeBuilderActive(res.status);
        }).catch(function (err) {
            console.error(err.message);
        });
    }, []);

    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)((0, _useTranslation2.default)("Registered Custom Post Types"));
        (0, _misc.changeCurrentAdminMenuLink)('');
        dispatch((0, _fetchPostTypesCount.fetchPostTypesCount)());
        dispatch((0, _fetchPostTypes.fetchPostTypes)({
            page: page ? page : 1,
            perPage: perPage
        }));
    }, [page]);

    // handle fetch outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loading && !settingsLoading) {
                setFetchedSuccess(true);

                var isWooCommerce = 0;

                fetched.map(function (post) {
                    if (post.isWooCommerce) {
                        isWooCommerce++;
                    }
                });

                isWooCommerce > 0 && setThereIsWooCommerce(true);
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
                    history.push('/');
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

    // handle sync posts
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!syncPostsLoading) {
                if (syncPostsSuccess) {
                    history.push('/');
                    _reactToastify.toast.success((0, _useTranslation2.default)("Successfully post sync. The browser will refresh after 5 seconds."));
                    (0, _misc.refreshPage)(5000);
                }

                if (syncPostsErrors.length > 0) {
                    syncPostsErrors.map(function (error) {
                        _reactToastify.toast.error(error);
                    });
                }
            }
        } else {
            didMountRef.current = true;
        }
    }, [syncPostsLoading]);

    var handleDeleteTemplate = function handleDeleteTemplate(name, type) {
        dispatch((0, _deleteTemplate.deleteTemplate)(_metaTypes.metaTypes.CUSTOM_POST_TYPE, name, type));
    };

    var handleSyncPosts = function handleSyncPosts() {
        dispatch((0, _syncPosts.syncPosts)());
    };

    if (!fetchedSuccess) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    var actions = wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            _reactRouterDom.Link,
            {
                className: 'acpt-btn acpt-btn-primary',
                to: '/register' },
            (0, _useTranslation2.default)("Register new Post Type")
        ),
        wp.element.createElement(
            'a',
            {
                onClick: function onClick(e) {
                    e.preventDefault();
                    handleSyncPosts();
                },
                className: 'acpt-btn acpt-btn-primary-o',
                href: '#'
            },
            (0, _useTranslation2.default)("Sync with post types")
        )
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: (0, _useTranslation2.default)("Registered Custom Post Types"),
            actions: actions
        }),
        wp.element.createElement(
            'main',
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Custom Post Types")
                }] }),
            fetched.length > 0 ? wp.element.createElement(
                'div',
                { className: 'acpt-card' },
                wp.element.createElement(
                    'div',
                    { className: 'acpt-card__header borderless' },
                    wp.element.createElement(
                        'div',
                        { className: 'acpt-card__inner' },
                        fetchedCount,
                        ' ',
                        (0, _useTranslation2.default)("record(s) found")
                    )
                ),
                wp.element.createElement(
                    'div',
                    { className: 'acpt-card__body' },
                    wp.element.createElement(
                        'div',
                        { className: 'acpt-table-responsive' },
                        wp.element.createElement(
                            'table',
                            { className: 'acpt-table ' + (document.globals.is_rtl === true ? 'rtl' : '') },
                            wp.element.createElement(
                                'thead',
                                null,
                                wp.element.createElement(
                                    'tr',
                                    null,
                                    wp.element.createElement('th', null),
                                    wp.element.createElement(
                                        'th',
                                        null,
                                        (0, _useTranslation2.default)("Name")
                                    ),
                                    wp.element.createElement(
                                        'th',
                                        null,
                                        (0, _useTranslation2.default)("Type")
                                    ),
                                    wp.element.createElement(
                                        'th',
                                        null,
                                        (0, _useTranslation2.default)("Meta fields"),
                                        '\xA0',
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Associated meta fields") },
                                            wp.element.createElement(
                                                'span',
                                                { className: 'helper' },
                                                wp.element.createElement(_react3.Icon, { icon: 'bx:bx-help-circle', color: '#2271b1', width: '12px' })
                                            )
                                        )
                                    ),
                                    thereIsWooCommerce === true && wp.element.createElement(
                                        'th',
                                        null,
                                        (0, _useTranslation2.default)("Product data"),
                                        '\xA0',
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Associated WooCommerce product data") },
                                            wp.element.createElement(
                                                'span',
                                                { className: 'helper' },
                                                wp.element.createElement(_react3.Icon, { icon: 'bx:bx-help-circle', color: '#2271b1', width: '12px' })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        'th',
                                        null,
                                        (0, _useTranslation2.default)("Associated taxonomies"),
                                        '\xA0',
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Associated taxonomies with the post") },
                                            wp.element.createElement(
                                                'span',
                                                { className: 'helper' },
                                                wp.element.createElement(_react3.Icon, { icon: 'bx:bx-help-circle', color: '#2271b1', width: '12px' })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        'th',
                                        { className: 'with-border' },
                                        (0, _useTranslation2.default)("Post count"),
                                        '\xA0',
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Published posts count") },
                                            wp.element.createElement(
                                                'span',
                                                { className: 'helper' },
                                                wp.element.createElement(_react3.Icon, { icon: 'bx:bx-help-circle', color: '#2271b1', width: '12px' })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        'th',
                                        { className: 'text-center' },
                                        (0, _useTranslation2.default)("Archive template"),
                                        '\xA0',
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("The archive template for this custom post type") },
                                            wp.element.createElement(
                                                'span',
                                                { className: 'helper' },
                                                wp.element.createElement(_react3.Icon, { icon: 'bx:bx-help-circle', color: '#2271b1', width: '12px' })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        'th',
                                        { className: 'text-center' },
                                        (0, _useTranslation2.default)("Single template"),
                                        '\xA0',
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("The single template for this custom post type") },
                                            wp.element.createElement(
                                                'span',
                                                { className: 'helper' },
                                                wp.element.createElement(_react3.Icon, { icon: 'bx:bx-help-circle', color: '#2271b1', width: '12px' })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        'th',
                                        { className: 'text-center' },
                                        (0, _useTranslation2.default)("Related template"),
                                        '\xA0',
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { html: wp.element.createElement(
                                                    'div',
                                                    null,
                                                    (0, _useTranslation2.default)("The template used to display this custom post type when it is releated to another one. If no template is set, it will be displayed just the title of the post."),
                                                    wp.element.createElement('br', null),
                                                    ' '
                                                ) },
                                            wp.element.createElement(
                                                'span',
                                                { className: 'helper' },
                                                wp.element.createElement(_react3.Icon, { icon: 'bx:bx-help-circle', color: '#2271b1', width: '12px' })
                                            )
                                        )
                                    )
                                )
                            ),
                            wp.element.createElement(
                                'tbody',
                                null,
                                fetched.map(function (element) {
                                    return wp.element.createElement(_CustomPostTypeListElement2.default, {
                                        isBBThemeBuilderActive: isBBThemeBuilderActive,
                                        isOxygenActive: isOxygenActive,
                                        thereIsWooCommerce: thereIsWooCommerce,
                                        id: element.id,
                                        key: element.id,
                                        element: element,
                                        enableVisualEditor: enableVisualEditor,
                                        handleDeleteTemplate: handleDeleteTemplate
                                    });
                                })
                            )
                        )
                    )
                ),
                totalPages > 1 && wp.element.createElement(
                    'div',
                    { className: 'acpt-card__footer', style: { border: "none" } },
                    wp.element.createElement(
                        'div',
                        { className: 'acpt-card__inner' },
                        wp.element.createElement(_Pagination2.default, { currentPage: page ? page : 1, perPage: perPage, records: fetchedCount })
                    )
                )
            ) : wp.element.createElement(
                'div',
                { className: 'acpt-alert acpt-alert-secondary' },
                (0, _useTranslation2.default)("No custom post types found."),
                ' ',
                wp.element.createElement(
                    _reactRouterDom.Link,
                    { to: '/register' },
                    (0, _useTranslation2.default)("Register the first one")
                ),
                '!'
            )
        )
    );
};

exports["default"] = CustomPostTypeList;

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

/***/ 41076:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactCodeBlocks = __webpack_require__(75431);

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

/***/ 70400:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var MetaBoxMiniTable = function MetaBoxMiniTable(_ref) {
    var postType = _ref.postType,
        taxonomy = _ref.taxonomy,
        menuSlug = _ref.menuSlug,
        elements = _ref.elements;


    var manageLink = function manageLink() {

        if (menuSlug) {
            return "#/option-page-meta/" + menuSlug;
        }

        return postType ? "#/meta/" + postType : "#/meta-taxonomy/" + taxonomy;
    };

    return wp.element.createElement(
        "div",
        { className: "acpt-table-responsive" },
        wp.element.createElement(
            "table",
            { className: "acpt-minitable " + (document.globals.is_rtl === true ? "rtl" : "") },
            wp.element.createElement(
                "thead",
                null,
                wp.element.createElement(
                    "tr",
                    null,
                    wp.element.createElement(
                        "th",
                        null,
                        (0, _useTranslation2.default)("Meta box")
                    ),
                    wp.element.createElement(
                        "th",
                        null,
                        (0, _useTranslation2.default)("Fields count")
                    )
                )
            ),
            wp.element.createElement(
                "tbody",
                null,
                elements.map(function (element) {
                    return wp.element.createElement(
                        "tr",
                        null,
                        wp.element.createElement(
                            "td",
                            null,
                            element.name
                        ),
                        wp.element.createElement(
                            "td",
                            null,
                            wp.element.createElement(
                                "span",
                                { className: "acpt-badge" },
                                wp.element.createElement(
                                    "span",
                                    { className: "label" },
                                    element.count
                                )
                            )
                        )
                    );
                })
            )
        ),
        wp.element.createElement(
            "div",
            { className: "minitable-buttons" },
            wp.element.createElement(
                "a",
                { href: manageLink() },
                (0, _useTranslation2.default)("Manage")
            )
        )
    );
};

exports["default"] = MetaBoxMiniTable;

/***/ }),

/***/ 41222:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



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

/***/ 10495:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



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

/***/ 84953:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchPostTypesCount = undefined;

var _ajax = __webpack_require__(47569);

var _fetchCustomPostTypesCountActions = __webpack_require__(38137);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchPostTypesCount = exports.fetchPostTypesCount = function fetchPostTypesCount() {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchCustomPostTypesCountActions.fetchPostTypesCountInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchCustomPostTypesCountAction');

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchCustomPostTypesCountActions.fetchPostTypesCountSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchCustomPostTypesCountActions.fetchPostTypesCountFailure)(_context.t0));

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

/***/ 20682:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.syncPosts = undefined;

var _ajax = __webpack_require__(47569);

var _syncPostsAction = __webpack_require__(81575);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var syncPosts = exports.syncPosts = function syncPosts() {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _syncPostsAction.syncPostsInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("syncPostsAction");

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _syncPostsAction.syncPostsSuccess)(res.data)) : dispatch((0, _syncPostsAction.syncPostsFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _syncPostsAction.syncPostsFailure)(_context.t0));

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

/***/ 72107:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);
//# sourceMappingURL=9012.js.map