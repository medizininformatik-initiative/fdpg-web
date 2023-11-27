"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[1441],{

/***/ 91441:
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

var _useUnsavedChangesWarning = __webpack_require__(49755);

var _useUnsavedChangesWarning2 = _interopRequireDefault(_useUnsavedChangesWarning);

var _misc = __webpack_require__(53154);

var _reactSortableHoc = __webpack_require__(3659);

var _reactToastify = __webpack_require__(39249);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _fetchMeta = __webpack_require__(4553);

var _metaTypes = __webpack_require__(81895);

var _metaSubmit = __webpack_require__(94717);

var _deleteAllMeta = __webpack_require__(67209);

var _metaStateActions = __webpack_require__(38527);

var _Meta = __webpack_require__(47975);

var _Meta2 = _interopRequireDefault(_Meta);

var _Modal = __webpack_require__(22651);

var _Modal2 = _interopRequireDefault(_Modal);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var UserMeta = function UserMeta() {

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.metaStateReducer;
    }),
        boxes = _useSelector.boxes,
        fields = _useSelector.fields,
        values = _useSelector.values,
        blocks = _useSelector.blocks,
        isSaved = _useSelector.isSaved,
        isValid = _useSelector.isValid,
        saveLoading = _useSelector.loading,
        saveErrors = _useSelector.errors,
        success = _useSelector.success;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchMetaReducer;
    }),
        loading = _useSelector2.loading,
        fetched = _useSelector2.fetched;

    // manage local state


    var didMountRef = (0, _react.useRef)(false);

    var _useUnsavedChangesWar = (0, _useUnsavedChangesWarning2.default)(),
        _useUnsavedChangesWar2 = _slicedToArray(_useUnsavedChangesWar, 3),
        Prompt = _useUnsavedChangesWar2[0],
        setDirty = _useUnsavedChangesWar2[1],
        setPristine = _useUnsavedChangesWar2[2];

    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        modalVisible = _useState2[0],
        setModalVisible = _useState2[1];

    // set page meta title


    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)("ACPT - " + (0, _useTranslation2.default)("User meta") + (isSaved ? '' : '*'));
        (0, _misc.changeCurrentAdminMenuLink)('#/user-meta');
        if (!isSaved) {
            setDirty();
        }
    }, [isSaved]);

    // fetching data and
    // populate the UI
    (0, _react.useEffect)(function () {
        dispatch((0, _fetchMeta.fetchMeta)(_metaTypes.metaTypes.USER));
    }, [saveLoading]);

    // sortable
    var onSortEnd = function onSortEnd(_ref) {
        var oldIndex = _ref.oldIndex,
            newIndex = _ref.newIndex;

        dispatch((0, _metaStateActions.setBoxes)((0, _reactSortableHoc.arrayMove)(boxes, oldIndex, newIndex)));
    };

    // handle data submit
    var handleSubmit = function handleSubmit() {
        dispatch((0, _metaSubmit.metaSubmit)(values));
        dispatch((0, _metaStateActions.setStatusSaved)());
        setPristine();
    };

    var handleDeleteAll = function handleDeleteAll() {
        dispatch((0, _deleteAllMeta.deleteAllMeta)(_metaTypes.metaTypes.USER));
        dispatch(setUserMetaStatusSaved());
        setPristine();
    };

    // handle form submission outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!saveLoading) {
                if (success) {
                    setPristine();
                    _reactToastify.toast.success((0, _useTranslation2.default)("User meta successfully saved"));
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

    if (loading) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    var renderDeleteButton = wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            _Modal2.default,
            { title: "Confirm deleting all", visible: modalVisible },
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
        ),
        wp.element.createElement(
            "button",
            {
                onClick: function onClick(e) {
                    e.preventDefault();
                    setModalVisible(true);
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
                    dispatch((0, _metaStateActions.createBox)(_metaTypes.metaTypes.USER));
                },
                className: "acpt-btn acpt-btn-primary-o"
            },
            (0, _useTranslation2.default)("Add meta box")
        ),
        boxes.length > 0 ? wp.element.createElement(
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
            title: (0, _useTranslation2.default)("Manage User Meta"),
            actions: buttons
        }),
        wp.element.createElement(
            "main",
            { className: "" },
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                    link: "/"
                }, {
                    label: (0, _useTranslation2.default)("Manage User Meta")
                }] }),
            Prompt,
            wp.element.createElement(_Meta2.default, {
                id: "user_meta",
                belongsTo: _metaTypes.metaTypes.USER,
                boxes: boxes,
                fields: fields,
                blocks: blocks,
                onSortEnd: onSortEnd,
                values: values
            })
        )
    );
};

exports["default"] = UserMeta;

/***/ })

}]);
//# sourceMappingURL=1441.js.map