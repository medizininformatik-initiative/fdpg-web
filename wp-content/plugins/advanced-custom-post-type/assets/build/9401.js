"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[9401],{

/***/ 5983:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Modal = __webpack_require__(22651);

var _Modal2 = _interopRequireDefault(_Modal);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var DeleteAllModal = function DeleteAllModal(_ref) {
    var modalVisible = _ref.modalVisible,
        setModalVisible = _ref.setModalVisible,
        handleDeleteAll = _ref.handleDeleteAll;


    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            _Modal2.default,
            { title: (0, _useTranslation2.default)("Confirm deleting all"), visible: modalVisible },
            wp.element.createElement(
                "p",
                null,
                (0, _useTranslation2.default)("Are you sure?")
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
                            handleDeleteAll();
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
        )
    );
};

exports["default"] = DeleteAllModal;

/***/ }),

/***/ 69401:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _misc = __webpack_require__(53154);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _reactRedux = __webpack_require__(28216);

var _fetchOptionPages = __webpack_require__(60068);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _optionPagesManageAction = __webpack_require__(73352);

var _arrayMove = __webpack_require__(80454);

var _arrayMove2 = _interopRequireDefault(_arrayMove);

var _MiniNavMap = __webpack_require__(42632);

var _MiniNavMap2 = _interopRequireDefault(_MiniNavMap);

var _DeleteAllModal = __webpack_require__(5983);

var _DeleteAllModal2 = _interopRequireDefault(_DeleteAllModal);

var _useUnsavedChangesWarning = __webpack_require__(49755);

var _useUnsavedChangesWarning2 = _interopRequireDefault(_useUnsavedChangesWarning);

var _reactToastify = __webpack_require__(39249);

var _deleteAllOptionPages = __webpack_require__(88838);

var _optionPagesSubmit = __webpack_require__(34636);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

var _Tippy = __webpack_require__(85825);

var _Tippy2 = _interopRequireDefault(_Tippy);

var _react3 = __webpack_require__(44226);

var _VerticalSortableList = __webpack_require__(19569);

var _HorizontalSortableList = __webpack_require__(47667);

var _AddOptionPageTabButton = __webpack_require__(20069);

var _AddOptionPageTabButton2 = _interopRequireDefault(_AddOptionPageTabButton);

var _OptionPageItem = __webpack_require__(29989);

var _OptionPageItem2 = _interopRequireDefault(_OptionPageItem);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var OptionPageManage = function OptionPageManage() {

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchOptionPagesReducer;
    }),
        fetched = _useSelector.fetched,
        loading = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.optionPagesManageReducer;
    }),
        pages = _useSelector2.pages,
        pageTabs = _useSelector2.pageTabs,
        currentPageTab = _useSelector2.currentPageTab,
        isSaved = _useSelector2.isSaved,
        isValid = _useSelector2.isValid,
        saveLoading = _useSelector2.loading,
        saveErrors = _useSelector2.errors,
        values = _useSelector2.values,
        success = _useSelector2.success,
        view = _useSelector2.view;

    var pagesBlocks = pages.filter(function (page) {
        return typeof page.props.parentId === 'undefined';
    });

    var pageTabsBlocks = pageTabs.filter(function (pageTab) {

        if (typeof pageTab === 'undefined') {
            return false;
        }

        return typeof pageTab.props.parentId === 'undefined' || !pageTab.props.parentId;
    });

    // manage local state
    var didMountRef = (0, _react.useRef)(false);

    var _useUnsavedChangesWar = (0, _useUnsavedChangesWarning2.default)(),
        _useUnsavedChangesWar2 = _slicedToArray(_useUnsavedChangesWar, 3),
        Prompt = _useUnsavedChangesWar2[0],
        setDirty = _useUnsavedChangesWar2[1],
        setPristine = _useUnsavedChangesWar2[2];

    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        deleteAllModalVisible = _useState2[0],
        setDeleteAllModalVisible = _useState2[1];

    // show hide all fields handlers


    var showHideAllPages = function showHideAllPages() {
        dispatch((0, _optionPagesManageAction.showHideAll)());
    };

    // switch view handler
    var handleSwitchView = function handleSwitchView(view) {
        localStorage.setItem("META_SWITCH_VIEW_OPTION_PAGES", view);
        dispatch((0, _optionPagesManageAction.switchView)(view));
    };

    // set saved switch view
    (0, _react.useEffect)(function () {

        // get the default view
        var getDefaultView = function getDefaultView() {
            if (localStorage.getItem("META_SWITCH_VIEW_OPTION_PAGES")) {
                return localStorage.getItem("META_SWITCH_VIEW_OPTION_PAGES");
            }

            return 'list';
        };

        dispatch((0, _optionPagesManageAction.switchView)(getDefaultView()));
    }, []);

    /**
     *
     * @return {null|*}
     */
    var currentPage = function currentPage() {

        var currentTab = [];

        pages.map(function (page) {
            currentPageTab.map(function (tab) {
                if (page.props.id === tab.id && typeof page.props.parentId === 'undefined') {
                    currentTab.push(page);
                }
            });
        });

        if (currentTab.length === 1) {
            var props = currentTab[0].props;

            return wp.element.createElement(_OptionPageItem2.default, {
                forTabView: true,
                id: props.id,
                isClosed: props.isClosed,
                isSaved: props.isSaved,
                position: props.position
            });
        }

        return null;
    };

    // set page meta title
    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)("ACPT - " + (0, _useTranslation2.default)("Manage option pages") + (isSaved ? '' : '*'));
        if (!isSaved) {
            setDirty();
        }
    }, [isSaved]);

    (0, _react.useEffect)(function () {
        dispatch((0, _fetchOptionPages.fetchOptionPages)({}));
    }, [saveLoading]);

    // handle fetch outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!saveLoading) {
                if (success) {
                    setPristine();
                    _reactToastify.toast.success((0, _useTranslation2.default)("Option pages successfully saved"));
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

    // sortable
    var onSortEnd = function onSortEnd(_ref) {
        var oldIndex = _ref.oldIndex,
            newIndex = _ref.newIndex;


        var sortedBlocks = (0, _arrayMove2.default)(pages.filter(function (page) {
            return typeof page.props.parentId === 'undefined';
        }), oldIndex, newIndex);

        sortedBlocks.map(function (sortedBlock) {
            var childrenBlocks = pages.filter(function (page) {
                return page.props.parentId === sortedBlock.props.id;
            });

            childrenBlocks.map(function (childrenBlock) {
                sortedBlocks.push(childrenBlock);
            });
        });

        dispatch((0, _optionPagesManageAction.setPages)(sortedBlocks));
    };

    // handle data submit
    var handleSubmit = function handleSubmit() {
        dispatch((0, _optionPagesSubmit.optionPagesSubmit)(values));
        dispatch((0, _optionPagesManageAction.setStatusSaved)());
        setPristine();
    };

    // handle delete all
    var handleDeleteAll = function handleDeleteAll() {
        dispatch((0, _deleteAllOptionPages.deleteAllOptionPages)());
        dispatch((0, _optionPagesManageAction.setStatusSaved)());
        setPristine();
    };

    if (loading) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    var renderDeleteButton = wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(_DeleteAllModal2.default, {
            modalVisible: deleteAllModalVisible,
            setModalVisible: setDeleteAllModalVisible,
            handleDeleteAll: handleDeleteAll
        }),
        wp.element.createElement(
            "button",
            {
                onClick: function onClick(e) {
                    e.preventDefault();
                    setDeleteAllModalVisible(!deleteAllModalVisible);
                },
                type: "submit",
                className: "acpt-btn acpt-btn-danger"
            },
            (0, _useTranslation2.default)("Delete all")
        )
    );

    var buttons = wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            "button",
            {
                onClick: function onClick(e) {
                    e.preventDefault();
                    dispatch((0, _optionPagesManageAction.createPage)());
                },
                className: "acpt-btn acpt-btn-primary-o"
            },
            (0, _useTranslation2.default)("Add page")
        ),
        pagesBlocks.length > 0 ? wp.element.createElement(
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
            renderDeleteButton
        ) : wp.element.createElement(
            _react2.default.Fragment,
            null,
            fetched.length > 0 && renderDeleteButton
        )
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: (0, _useTranslation2.default)("Option pages"),
            actions: buttons
        }),
        wp.element.createElement(
            "main",
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Option pages"),
                    link: "/option-pages"
                }, {
                    label: (0, _useTranslation2.default)("Manage option pages")
                }] }),
            Prompt,
            pagesBlocks.length > 0 ? wp.element.createElement(
                _react2.default.Fragment,
                null,
                wp.element.createElement(
                    "div",
                    { className: "acpt-meta-wrapper" },
                    wp.element.createElement(_MiniNavMap2.default, { values: values }),
                    wp.element.createElement(
                        "div",
                        { className: "acpt-meta-list-wrapper" },
                        wp.element.createElement(
                            "div",
                            { className: "space-between mb-3 flex-center" },
                            wp.element.createElement(
                                "div",
                                { className: "acpt-meta-view-switcher" },
                                wp.element.createElement(
                                    _Tippy2.default,
                                    { title: (0, _useTranslation2.default)("List view") },
                                    wp.element.createElement(
                                        "a",
                                        {
                                            href: "#",
                                            onClick: function onClick(e) {
                                                e.preventDefault();
                                                handleSwitchView("list");
                                            },
                                            className: view === 'list' ? 'active' : ''
                                        },
                                        wp.element.createElement(_react3.Icon, { icon: "bx:list-ul", width: "18px" })
                                    )
                                ),
                                wp.element.createElement(
                                    _Tippy2.default,
                                    { title: (0, _useTranslation2.default)("Tabular view") },
                                    wp.element.createElement(
                                        "a",
                                        {
                                            href: "#",
                                            onClick: function onClick(e) {
                                                e.preventDefault();
                                                handleSwitchView("tabular");
                                            },
                                            className: view === 'tabular' ? 'active' : '',
                                            title: (0, _useTranslation2.default)("Tabular view")
                                        },
                                        wp.element.createElement(_react3.Icon, { icon: "bx:table", width: "18px" })
                                    )
                                )
                            ),
                            view === 'list' && wp.element.createElement(
                                "a",
                                {
                                    href: "#",
                                    onClick: function onClick(e) {
                                        e.preventDefault();
                                        showHideAllPages();
                                    }
                                },
                                (0, _useTranslation2.default)("Show/hide all pages")
                            )
                        ),
                        view === 'tabular' ? wp.element.createElement(
                            "div",
                            { className: "acpt-op-horizontal-wrapper" },
                            wp.element.createElement(_HorizontalSortableList.HorizontalSortableList, {
                                items: pageTabsBlocks,
                                externalComponent: wp.element.createElement(_AddOptionPageTabButton2.default, null),
                                onSortEnd: onSortEnd,
                                useDragHandle: true,
                                axis: "x",
                                helperClass: "dragging-helper-class",
                                disableAutoscroll: false,
                                useWindowAsScrollContainer: true
                            }),
                            wp.element.createElement(
                                "div",
                                { className: "mt-2" },
                                currentPage()
                            )
                        ) : wp.element.createElement(_VerticalSortableList.VerticalSortableList, {
                            items: pagesBlocks,
                            onSortEnd: onSortEnd,
                            useDragHandle: true,
                            lockAxis: "y",
                            helperClass: "dragging-helper-class",
                            disableAutoscroll: false,
                            useWindowAsScrollContainer: true
                        })
                    )
                )
            ) : wp.element.createElement(
                _react2.default.Fragment,
                null,
                wp.element.createElement(
                    "div",
                    { className: "acpt-alert acpt-alert-warning" },
                    (0, _useTranslation2.default)('No page already created. Create the first one now by clicking the button "Add page"!')
                )
            )
        )
    );
};

exports["default"] = OptionPageManage;

/***/ }),

/***/ 88838:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.deleteAllOptionPages = undefined;

var _ajax = __webpack_require__(47569);

var _optionPagesManageAction = __webpack_require__(73352);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var deleteAllOptionPages = exports.deleteAllOptionPages = function deleteAllOptionPages() {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _optionPagesManageAction.deleteAllInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("deleteOptionPagesAction", {});

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _optionPagesManageAction.deleteAllSuccess)()) : dispatch((0, _optionPagesManageAction.deleteAllFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.error(_context.t0);
                            dispatch((0, _optionPagesManageAction.deleteAllFailure)(_context.t0));

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

/***/ 34636:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.optionPagesSubmit = undefined;

var _ajax = __webpack_require__(47569);

var _optionPagesManageAction = __webpack_require__(73352);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var optionPagesSubmit = exports.optionPagesSubmit = function optionPagesSubmit(data) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _optionPagesManageAction.submitInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("saveOptionPagesAction", data);

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _optionPagesManageAction.submitSuccess)()) : dispatch((0, _optionPagesManageAction.submitFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _optionPagesManageAction.submitFailure)(_context.t0));

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

/***/ })

}]);
//# sourceMappingURL=9401.js.map