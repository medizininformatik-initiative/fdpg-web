"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[8443],{

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

/***/ 88443:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _reactRedux = __webpack_require__(28216);

var _useUnsavedChangesWarning = __webpack_require__(49755);

var _useUnsavedChangesWarning2 = _interopRequireDefault(_useUnsavedChangesWarning);

var _misc = __webpack_require__(53154);

var _fetchWooCommerceProductDataFields = __webpack_require__(33647);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _reactToastify = __webpack_require__(39249);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _reactSortableHoc = __webpack_require__(3659);

var _WooCommerceFieldsStateAction = __webpack_require__(29413);

var _WooCommerceProductDataFieldsSubmit = __webpack_require__(53765);

var _deleteAllWooCommerceProductDataFields = __webpack_require__(38574);

var _fetchWooCommerceProductData = __webpack_require__(87338);

var _ = __webpack_require__(94929);

var _2 = _interopRequireDefault(_);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _MiniNavMap = __webpack_require__(42632);

var _MiniNavMap2 = _interopRequireDefault(_MiniNavMap);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

var _VerticalSortableList = __webpack_require__(19569);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var WooCommerceProductDataFields = function WooCommerceProductDataFields() {

    // manage global state
    var _useParams = (0, _reactRouterDom.useParams)(),
        id = _useParams.id;

    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchWooCommerceProductDataReducer;
    }),
        productData = _useSelector.fetched,
        productDataLoading = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.WooCommerceFieldsStateReducer;
    }),
        fields = _useSelector2.fields,
        values = _useSelector2.values,
        isSaved = _useSelector2.isSaved,
        isValid = _useSelector2.isValid,
        saveLoading = _useSelector2.loading,
        saveErrors = _useSelector2.errors,
        success = _useSelector2.success;

    var _useSelector3 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchWooCommerceProductDataFieldsReducer;
    }),
        loading = _useSelector3.loading,
        fetched = _useSelector3.fetched;

    // manage local state


    var didMountRef = (0, _react.useRef)(false);

    var _useUnsavedChangesWar = (0, _useUnsavedChangesWarning2.default)(),
        _useUnsavedChangesWar2 = _slicedToArray(_useUnsavedChangesWar, 3),
        Prompt = _useUnsavedChangesWar2[0],
        setDirty = _useUnsavedChangesWar2[1],
        setPristine = _useUnsavedChangesWar2[2];

    // manage redirect


    var history = (0, _reactRouterDom.useHistory)();

    // set page meta title
    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)("ACPT - " + (0, _useTranslation2.default)("WooCommerce product data") + (isSaved ? '' : '*'));
        if (!isSaved) {
            setDirty();
        }
    }, [isSaved]);

    // fetching data and
    // populate the UI
    (0, _react.useEffect)(function () {
        dispatch((0, _fetchWooCommerceProductData.fetchWooCommerceProductData)({
            id: id
        }));
        dispatch((0, _fetchWooCommerceProductDataFields.fetchWooCommerceProductDataFields)(id));
    }, [saveLoading]);

    // sortable
    var onSortEnd = function onSortEnd(_ref) {
        var oldIndex = _ref.oldIndex,
            newIndex = _ref.newIndex;

        dispatch((0, _WooCommerceFieldsStateAction.setWooCommerceProductDataFields)((0, _reactSortableHoc.arrayMove)(fields, oldIndex, newIndex)));
    };

    // handle data submit
    var handleSubmit = function handleSubmit() {
        dispatch((0, _WooCommerceProductDataFieldsSubmit.WooCommerceProductDataFieldsSubmit)(values));
        dispatch((0, _WooCommerceFieldsStateAction.setWooCommerceProductDataStatusSaved)());
        setPristine();
    };

    var handleDeleteAll = function handleDeleteAll() {
        dispatch((0, _deleteAllWooCommerceProductDataFields.deleteAllWooCommerceProductDataFields)(id));
        dispatch((0, _WooCommerceFieldsStateAction.setWooCommerceProductDataStatusSaved)());
        setPristine();
    };

    // handle form submission outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!saveLoading) {
                if (success) {
                    setPristine();
                    _reactToastify.toast.success((0, _useTranslation2.default)("WooCommerce product data fields successfully saved"));
                }

                if (saveErrors.length > 0) {
                    saveErrors.map(function (error) {
                        _reactToastify.toast.error(error);
                    });
                }
            }
        } else {
            didMountRef.current = true;
        }
    }, [saveLoading]);

    if (loading || productDataLoading) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    if (!productData[0]) {
        return wp.element.createElement(_2.default, null);
    }

    var actions = wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            "a",
            {
                href: "#",
                onClick: function onClick(e) {
                    e.preventDefault();
                    dispatch((0, _WooCommerceFieldsStateAction.createWooCommerceProductDataField)(id));
                },
                className: "acpt-btn acpt-btn-primary-o"
            },
            (0, _useTranslation2.default)("Add field box")
        ),
        fields.length > 0 && wp.element.createElement(
            _react2.default.Fragment,
            null,
            wp.element.createElement(
                "button",
                {
                    disabled: !isValid,
                    onClick: function onClick(e) {
                        e.preventDefault();
                        handleSubmit();
                    },
                    type: "submit",
                    className: "acpt-btn acpt-btn-primary"
                },
                (0, _useTranslation2.default)("Save")
            ),
            wp.element.createElement(
                "button",
                {
                    onClick: function onClick(e) {
                        e.preventDefault();
                        handleDeleteAll();
                    },
                    type: "submit",
                    className: "acpt-btn acpt-btn-danger"
                },
                (0, _useTranslation2.default)("Delete all")
            )
        )
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        Prompt,
        wp.element.createElement(_ActionsBar2.default, {
            title: productData[0].name + " product data fields",
            actions: actions
        }),
        wp.element.createElement(
            "main",
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                    link: "/"
                }, {
                    label: (0, _useTranslation2.default)("WooCommerce product data"),
                    link: "/product-data/product"
                }, {
                    label: productData[0].name + ": " + (0, _useTranslation2.default)("product data fields")
                }]
            }),
            fields.length > 0 ? wp.element.createElement(
                _react2.default.Fragment,
                null,
                wp.element.createElement(
                    "div",
                    { className: "acpt-meta-wrapper" },
                    wp.element.createElement(
                        "div",
                        { className: "acpt-meta-list-wrapper" },
                        wp.element.createElement(
                            "div",
                            { className: "acpt-card" },
                            wp.element.createElement(
                                "div",
                                { className: "acpt-card__inner" },
                                wp.element.createElement(_VerticalSortableList.VerticalSortableList, {
                                    items: fields,
                                    onSortEnd: onSortEnd,
                                    useDragHandle: true,
                                    lockAxis: "y",
                                    helperClass: "dragging-helper-class",
                                    disableAutoscroll: false,
                                    useWindowAsScrollContainer: true
                                })
                            )
                        )
                    ),
                    wp.element.createElement(_MiniNavMap2.default, { values: values })
                )
            ) : wp.element.createElement(
                _react2.default.Fragment,
                null,
                wp.element.createElement(
                    "div",
                    { className: "acpt-alert acpt-alert-warning" },
                    (0, _useTranslation2.default)('No field box already created. Create the first one now by clicking the button "Add field box"!')
                )
            )
        )
    );
};

exports["default"] = WooCommerceProductDataFields;

/***/ }),

/***/ 53765:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.WooCommerceProductDataFieldsSubmit = undefined;

var _ajax = __webpack_require__(47569);

var _WooCommerceFieldsStateAction = __webpack_require__(29413);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var WooCommerceProductDataFieldsSubmit = exports.WooCommerceProductDataFieldsSubmit = function WooCommerceProductDataFieldsSubmit(data) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _WooCommerceFieldsStateAction.submitWooCommerceProductDataFieldsInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("saveWooCommerceProductDataFieldsAction", data);

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _WooCommerceFieldsStateAction.submitWooCommerceProductDataFieldsSuccess)()) : dispatch((0, _WooCommerceFieldsStateAction.submitWooCommerceProductDataFieldsFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _WooCommerceFieldsStateAction.submitWooCommerceProductDataFieldsFailure)(_context.t0));

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

/***/ 38574:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.deleteAllWooCommerceProductDataFields = undefined;

var _ajax = __webpack_require__(47569);

var _WooCommerceFieldsStateAction = __webpack_require__(29413);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var deleteAllWooCommerceProductDataFields = exports.deleteAllWooCommerceProductDataFields = function deleteAllWooCommerceProductDataFields(id) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _WooCommerceFieldsStateAction.deleteAllWooCommerceProductDataFieldsInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("deleteWooCommerceProductDataFieldsAction", { id: id });

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _WooCommerceFieldsStateAction.deleteAllWooCommerceProductDataFieldsSuccess)()) : dispatch((0, _WooCommerceFieldsStateAction.deleteAllWooCommerceProductDataFieldsFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _WooCommerceFieldsStateAction.deleteAllWooCommerceProductDataFieldsFailure)(_context.t0));

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

/***/ 87338:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchWooCommerceProductData = undefined;

var _ajax = __webpack_require__(47569);

var _fetchWooCommerceProductDataActions = __webpack_require__(16208);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchWooCommerceProductData = exports.fetchWooCommerceProductData = function fetchWooCommerceProductData(meta) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchWooCommerceProductDataActions.fetchWooCommerceProductDataInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchWooCommerceProductDataAction', meta);

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchWooCommerceProductDataActions.fetchWooCommerceProductDataSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchWooCommerceProductDataActions.fetchWooCommerceProductDataFailure)(_context.t0));

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

/***/ 33647:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchWooCommerceProductDataFields = undefined;

var _ajax = __webpack_require__(47569);

var _fetchWooCommerceProductDataFieldsActions = __webpack_require__(2357);

var _WooCommerceFieldsStateAction = __webpack_require__(29413);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchWooCommerceProductDataFields = exports.fetchWooCommerceProductDataFields = function fetchWooCommerceProductDataFields(id) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchWooCommerceProductDataFieldsActions.fetchWooCommerceProductDataFieldsInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchWooCommerceProductDataFieldsAction', { id: id });

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchWooCommerceProductDataFieldsActions.fetchWooCommerceProductDataFieldsSuccess)(fetched));
                            dispatch((0, _WooCommerceFieldsStateAction.hydrateWooCommerceProductDataValues)(fetched));
                            _context.next = 12;
                            break;

                        case 9:
                            _context.prev = 9;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchWooCommerceProductDataFieldsActions.fetchWooCommerceProductDataFieldsFailure)(_context.t0));

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

/***/ })

}]);
//# sourceMappingURL=8443.js.map