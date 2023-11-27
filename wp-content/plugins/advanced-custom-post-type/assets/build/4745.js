"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[4745],{

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

/***/ 56824:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _react3 = __webpack_require__(44226);

var _scroll = __webpack_require__(82727);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var MiniNavMapElement = function MiniNavMapElement(_ref) {
    var value = _ref.value,
        isActiveId = _ref.isActiveId,
        setActiveId = _ref.setActiveId;

    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        isClosed = _useState2[0],
        setClosed = _useState2[1];

    var itemTitle = function itemTitle(val) {

        if (val.title) {
            return val.title;
        }

        if (val.name) {
            return val.name;
        }

        if (val.pageTitle) {
            return val.pageTitle;
        }

        return null;
    };

    var renderChildrenPages = function renderChildrenPages(children) {
        return wp.element.createElement(
            "ul",
            { className: isClosed ? 'closed' : 'open' },
            children.map(function (child, index) {
                return wp.element.createElement(
                    _react2.default.Fragment,
                    null,
                    wp.element.createElement(
                        "li",
                        { className: child.parentId ? 'is-child' : '', key: index },
                        wp.element.createElement(
                            "a",
                            {
                                href: "#",
                                className: isActiveId === child.id ? 'active' : '',
                                onClick: function onClick(e) {
                                    e.preventDefault();
                                    (0, _scroll.scrollToId)(child.id);
                                    setActiveId(child.id);
                                }
                            },
                            wp.element.createElement(
                                "span",
                                { className: "label field" },
                                "C"
                            ),
                            " ",
                            itemTitle(child)
                        )
                    )
                );
            })
        );
    };

    var renderChildrenElements = function renderChildrenElements(children) {
        return wp.element.createElement(
            "ul",
            { className: isClosed ? 'closed' : 'open' },
            children.map(function (child, index) {
                if (typeof child.parentId === 'undefined' || child.parentId === null) {
                    return wp.element.createElement(
                        _react2.default.Fragment,
                        null,
                        wp.element.createElement(
                            "li",
                            { className: child.parentId ? 'is-child' : '', key: index },
                            wp.element.createElement(
                                "a",
                                {
                                    href: "#",
                                    className: isActiveId === child.id ? 'active' : '',
                                    onClick: function onClick(e) {
                                        e.preventDefault();
                                        (0, _scroll.scrollToId)(child.id);
                                        setActiveId(child.id);
                                    }
                                },
                                wp.element.createElement(
                                    "span",
                                    { className: "label field" },
                                    "F"
                                ),
                                " ",
                                itemTitle(child)
                            )
                        ),
                        child.children && child.children.length > 0 && child.children.map(function (ch, chIndex) {
                            return wp.element.createElement(
                                "li",
                                { className: "is-child", key: chIndex },
                                wp.element.createElement(
                                    "a",
                                    {
                                        href: "#",
                                        className: isActiveId === ch.id ? 'active' : '',
                                        onClick: function onClick(e) {
                                            e.preventDefault();
                                            (0, _scroll.scrollToId)(ch.id);
                                            setActiveId(ch.id);
                                        }
                                    },
                                    wp.element.createElement(
                                        "span",
                                        { className: "label child" },
                                        "C"
                                    ),
                                    " ",
                                    itemTitle(ch)
                                )
                            );
                        }),
                        child.blocks && child.blocks.length > 0 && renderBlocksElements(child.blocks)
                    );
                }
            })
        );
    };

    var renderBlocksElements = function renderBlocksElements(blocks) {

        return wp.element.createElement(
            _react2.default.Fragment,
            null,
            blocks.map(function (block) {
                return wp.element.createElement(
                    _react2.default.Fragment,
                    null,
                    wp.element.createElement(
                        "li",
                        { className: "is-child" },
                        wp.element.createElement(
                            "a",
                            {
                                href: "#",
                                className: isActiveId === block.id ? 'active' : '',
                                onClick: function onClick(e) {
                                    e.preventDefault();
                                    (0, _scroll.scrollToId)(block.id);
                                    setActiveId(block.id);
                                }
                            },
                            wp.element.createElement(
                                "span",
                                { className: "label block" },
                                "B"
                            ),
                            " ",
                            block.name
                        )
                    ),
                    block.fields && block.fields.map(function (field) {
                        return wp.element.createElement(
                            "li",
                            { className: "is-grandchild" },
                            wp.element.createElement(
                                "a",
                                {
                                    href: "#",
                                    className: isActiveId === field.id ? 'active' : '',
                                    onClick: function onClick(e) {
                                        e.preventDefault();
                                        (0, _scroll.scrollToId)(field.id);
                                        setActiveId(field.id);
                                    }
                                },
                                wp.element.createElement(
                                    "span",
                                    { className: "label child" },
                                    "C"
                                ),
                                " ",
                                field.name
                            )
                        );
                    })
                );
            })
        );
    };

    return wp.element.createElement(
        "div",
        { className: "mini-map-element" },
        wp.element.createElement(
            "div",
            { className: "box" },
            wp.element.createElement(
                "a",
                { href: "#",
                    onClick: function onClick(e) {
                        e.preventDefault();
                        setClosed(!isClosed);
                    }
                },
                wp.element.createElement(_react3.Icon, { icon: "bx:bx-chevron-" + (isClosed ? 'up' : 'down'), width: "18px" })
            ),
            wp.element.createElement(
                "a",
                {
                    href: "#",
                    className: "title " + (isActiveId === value.id ? 'active' : ''),
                    onClick: function onClick(e) {
                        e.preventDefault();
                        (0, _scroll.scrollToId)(value.id);
                        setActiveId(value.id);
                    } },
                itemTitle(value)
            )
        ),
        value.fields && value.fields.length > 0 && renderChildrenElements(value.fields),
        value.children && value.children.length > 0 && renderChildrenPages(value.children)
    );
};

exports["default"] = MiniNavMapElement;

/***/ }),

/***/ 42632:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _MiniNavMapElement = __webpack_require__(56824);

var _MiniNavMapElement2 = _interopRequireDefault(_MiniNavMapElement);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var MiniNavMap = function MiniNavMap(_ref) {
    var values = _ref.values;

    // manage local state
    var _useState = (0, _react.useState)(null),
        _useState2 = _slicedToArray(_useState, 2),
        isActiveId = _useState2[0],
        setActiveId = _useState2[1];

    if (values.length === 0) {
        return null;
    }

    // scroll handling
    (0, _react.useEffect)(function () {

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting === true) {
                    setActiveId(entry.target.id);
                }
            });
        }, { threshold: [1] });

        values.map(function (value) {
            if (document.getElementById(value.id)) {
                observer.observe(document.getElementById(value.id));

                value.fields && value.fields.map(function (field) {
                    if (document.getElementById(field.id)) {
                        observer.observe(document.getElementById(field.id));
                    }
                });

                value.children && value.children.map(function (child) {
                    if (document.getElementById(child.id)) {
                        observer.observe(document.getElementById(child.id));
                    }
                });

                value.blocks && value.blocks.map(function (block) {
                    if (document.getElementById(block.id)) {
                        observer.observe(document.getElementById(block.id));

                        block.fields && block.fields.map(function (field) {
                            if (document.getElementById(field.id)) {
                                observer.observe(document.getElementById(field.id));
                            }
                        });
                    }
                });
            }
        });
    }, []);

    return wp.element.createElement(
        "div",
        { className: "acpt-mini-map " + (document.globals.is_rtl === true ? "rtl" : "") },
        wp.element.createElement(
            "h4",
            null,
            (0, _useTranslation2.default)("Quick navigation")
        ),
        values.map(function (value, index) {
            return wp.element.createElement(_MiniNavMapElement2.default, {
                value: value,
                key: index,
                isActiveId: isActiveId,
                setActiveId: setActiveId
            });
        })
    );
};

exports["default"] = MiniNavMap;

/***/ }),

/***/ 49755:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var useUnsavedChangesWarning = function useUnsavedChangesWarning() {
    var message = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "Are you sure want to discard changes?";

    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        isDirty = _useState2[0],
        setDirty = _useState2[1];

    (0, _react.useEffect)(function () {
        //Detecting browser closing
        window.onbeforeunload = isDirty && function () {
            return message;
        };

        return function () {
            window.onbeforeunload = null;
        };
    }, [isDirty]);

    var routerPrompt = wp.element.createElement(_reactRouterDom.Prompt, { when: isDirty, message: message });

    return [routerPrompt, function () {
        return setDirty(true);
    }, function () {
        return setDirty(false);
    }, isDirty];
};

exports["default"] = useUnsavedChangesWarning;

/***/ }),

/***/ 82727:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var scrollToTop = exports.scrollToTop = function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

var scrollToBottom = exports.scrollToBottom = function scrollToBottom() {
    window.scrollTo({ top: document.body.scrollHeight + 120, behavior: 'smooth' });
};

var scrollToTargetId = exports.scrollToTargetId = function scrollToTargetId(id) {
    var element = document.getElementById(id);
    element.scrollIntoView({ behavior: 'smooth' }, true);
};

var scrollToId = exports.scrollToId = function scrollToId(id) {
    var yOffset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : -130;


    var element = document.getElementById(id);

    if (element) {
        var y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
        window.scrollTo({ top: y, behavior: 'smooth' });
    }
};

/***/ })

}]);
//# sourceMappingURL=4745.js.map