"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[6628],{

/***/ 63647:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var KeyboardLegendGroup = function KeyboardLegendGroup(_ref) {
    var group = _ref.group;


    return wp.element.createElement(
        "div",
        { className: "keys-group" },
        wp.element.createElement(
            "h3",
            null,
            (0, _useTranslation2.default)(group.title)
        ),
        wp.element.createElement(
            "ul",
            null,
            group.keys && group.keys.map(function (key) {
                return wp.element.createElement(
                    "li",
                    null,
                    wp.element.createElement(
                        "span",
                        { className: "keys" },
                        key.keys && key.keys.map(function (k) {
                            return wp.element.createElement(
                                "span",
                                { className: "" + (k !== '+' ? 'key' : '') },
                                (0, _useTranslation2.default)(k)
                            );
                        })
                    ),
                    wp.element.createElement(
                        "span",
                        { className: "legend" },
                        (0, _useTranslation2.default)(key.legend)
                    )
                );
            })
        )
    );
};

exports["default"] = KeyboardLegendGroup;

/***/ }),

/***/ 33544:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _Modal = __webpack_require__(22651);

var _Modal2 = _interopRequireDefault(_Modal);

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _KeyboardLegendGroup = __webpack_require__(63647);

var _KeyboardLegendGroup2 = _interopRequireDefault(_KeyboardLegendGroup);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var KeyboardLegendModal = function KeyboardLegendModal(_ref) {
    var modalVisible = _ref.modalVisible,
        setModalVisible = _ref.setModalVisible;


    var legends = [{
        title: "General",
        keys: [{
            keys: ["Control", "+", "S"],
            legend: (0, _useTranslation2.default)("Save action")
        }, {
            keys: ["Control", "+", "Shift", "+", "canc"],
            legend: (0, _useTranslation2.default)("Delete all action")
        }, {
            keys: ["Control", "+", "L"],
            legend: (0, _useTranslation2.default)("Show this legend modal")
        }, {
            keys: ["Esc"],
            legend: (0, _useTranslation2.default)("Hide all modals, deselect the current element")
        }]
    }, {
        title: "Navigation",
        keys: [{
            keys: ["▴"],
            legend: (0, _useTranslation2.default)("Navigate to previous element (box, field, option or condition)")
        }, {
            keys: ["▾"],
            legend: (0, _useTranslation2.default)("Navigate to next element (box, field, option or condition)")
        }, {
            keys: ["▸"],
            legend: (0, _useTranslation2.default)("Navigate the tabs of selected field (go to next tab)")
        }, {
            keys: ["◂"],
            legend: (0, _useTranslation2.default)("Navigate the tabs of selected field (go to previous tab)")
        }]
    }, {
        title: "Meta box",
        keys: [{
            keys: ["Control", "+", "B"],
            legend: (0, _useTranslation2.default)("Create new meta box")
        }, {
            keys: ["Control", "+", "C"],
            legend: (0, _useTranslation2.default)("Copy the selected meta box")
        }, {
            keys: ["Control", "+", "D"],
            legend: (0, _useTranslation2.default)("Duplicate the selected meta box")
        }, {
            keys: ["Control", "+", "F"],
            legend: (0, _useTranslation2.default)("Create a meta field in the selected meta box")
        }, {
            keys: ["Control", "+", "canc"],
            legend: (0, _useTranslation2.default)("Delete the selected meta box")
        }]
    }, {
        title: "Meta field",
        keys: [{
            keys: ["Tab"],
            legend: (0, _useTranslation2.default)("Navigate the input fields in the selected meta field")
        }, {
            keys: ["Control", "+", "C"],
            legend: (0, _useTranslation2.default)("Copy the selected meta field")
        }, {
            keys: ["Control", "+", "D"],
            legend: (0, _useTranslation2.default)("Duplicate the selected meta field")
        }, {
            keys: ["Control", "+", "O"],
            legend: (0, _useTranslation2.default)("Create a meta option (if applicable) in the selected meta field")
        }, {
            keys: ["Control", "+", "A"],
            legend: (0, _useTranslation2.default)("Check `show in archive` in the selected meta field")
        }, {
            keys: ["Control", "+", "Shift", "+", "A"],
            legend: (0, _useTranslation2.default)("Uncheck `show in archive` in the selected meta field")
        }, {
            keys: ["Control", "+", "Shift", "+", "B"],
            legend: (0, _useTranslation2.default)("Create a new block (only for Flexible field)")
        }, {
            keys: ["Control", "+", "R"],
            legend: (0, _useTranslation2.default)("Check `required` in the selected meta field")
        }, {
            keys: ["Control", "+", "Shift", "+", "R"],
            legend: (0, _useTranslation2.default)("Uncheck `required` in the selected meta field")
        }, {
            keys: ["Control", "+", "canc"],
            legend: (0, _useTranslation2.default)("Delete the selected meta field")
        }]
    }, {
        title: "Meta option",
        keys: [{
            keys: ["Tab"],
            legend: (0, _useTranslation2.default)("Navigate the input fields in the selected meta option")
        }, {
            keys: ["Control", "+", "O"],
            legend: (0, _useTranslation2.default)("Create new options after the selected meta option")
        }, {
            keys: ["Control", "+", "canc"],
            legend: (0, _useTranslation2.default)("Delete the selected meta option")
        }]
    }, {
        title: "Conditional rendering",
        keys: [{
            keys: ["Tab"],
            legend: (0, _useTranslation2.default)("Navigate the input fields in the selected meta option")
        }, {
            keys: ["Control", "+", "V"],
            legend: (0, _useTranslation2.default)("Create a new condition in the selected meta field")
        }, {
            keys: ["Control", "+", "canc"],
            legend: (0, _useTranslation2.default)("Delete the selected condition")
        }]
    }];

    return wp.element.createElement(
        _Modal2.default,
        { title: (0, _useTranslation2.default)("Keyboard interaction legend"), visible: modalVisible },
        wp.element.createElement(
            "div",
            { className: "text-left" },
            wp.element.createElement(
                "div",
                { className: "keys-legend" },
                legends.map(function (group) {
                    return wp.element.createElement(_KeyboardLegendGroup2.default, { group: group });
                })
            ),
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

exports["default"] = KeyboardLegendModal;

/***/ }),

/***/ 47975:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Sortable = __webpack_require__(60091);

var _MiniNavMap = __webpack_require__(42632);

var _MiniNavMap2 = _interopRequireDefault(_MiniNavMap);

var _reactHotKeys = __webpack_require__(4118);

var _reactHotKeys2 = _interopRequireDefault(_reactHotKeys);

var _reactRedux = __webpack_require__(28216);

var _metaStateActions = __webpack_require__(38527);

var _metaSubmit = __webpack_require__(94717);

var _deleteAllMeta = __webpack_require__(67209);

var _scroll = __webpack_require__(82727);

var _fields = __webpack_require__(80857);

var _CopyMetaBoxModal = __webpack_require__(2084);

var _CopyMetaBoxModal2 = _interopRequireDefault(_CopyMetaBoxModal);

var _CopyMetaFieldModal = __webpack_require__(42167);

var _CopyMetaFieldModal2 = _interopRequireDefault(_CopyMetaFieldModal);

var _KeyboardLegendModal = __webpack_require__(33544);

var _KeyboardLegendModal2 = _interopRequireDefault(_KeyboardLegendModal);

var _react3 = __webpack_require__(44226);

var _CopyMetaBlockModal = __webpack_require__(28440);

var _CopyMetaBlockModal2 = _interopRequireDefault(_CopyMetaBlockModal);

var _Tippy = __webpack_require__(85825);

var _Tippy2 = _interopRequireDefault(_Tippy);

var _localStorage = __webpack_require__(21500);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Meta = function Meta(_ref) {
    var belongsTo = _ref.belongsTo,
        find = _ref.find,
        boxes = _ref.boxes,
        fields = _ref.fields,
        blocks = _ref.blocks,
        onSortEnd = _ref.onSortEnd,
        values = _ref.values;


    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchMetaReducer;
    }),
        fetchedMeta = _useSelector.fetched;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.metaStateReducer;
    }),
        selectedElement = _useSelector2.selectedElement;

    // manage local state


    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        copyBoxModalVisible = _useState2[0],
        setCopyBoxModalVisible = _useState2[1];

    var _useState3 = (0, _react.useState)(false),
        _useState4 = _slicedToArray(_useState3, 2),
        copyFieldModalVisible = _useState4[0],
        setCopyFieldModalVisible = _useState4[1];

    var _useState5 = (0, _react.useState)(false),
        _useState6 = _slicedToArray(_useState5, 2),
        copyBlockModalVisible = _useState6[0],
        setCopyBlockModalVisible = _useState6[1];

    var _useState7 = (0, _react.useState)(false),
        _useState8 = _slicedToArray(_useState7, 2),
        legendModalVisible = _useState8[0],
        setLegendModalVisible = _useState8[1];

    var _useState9 = (0, _react.useState)(null),
        _useState10 = _slicedToArray(_useState9, 2),
        modalsContent = _useState10[0],
        setModalContent = _useState10[1];

    var showHideAllFields = function showHideAllFields() {
        dispatch((0, _metaStateActions.showHideAll)());
    };

    // manage key interactions
    var keyMaps = ['esc', 'escape', 'tab', 'up', 'down', 'left', 'right', 'control+A', 'command+A', 'control+B', 'command+B', 'control+C', 'command+C', 'control+D', 'command+D', 'control+F', 'command+F', 'control+L', 'command+L', 'control+O', 'command+O', 'control+R', 'command+R', 'control+S', 'command+S', 'control+del', 'command+del', 'control+shift+A', 'command+shift+A', 'control+shift+B', 'command+shift+B', 'control+shift+R', 'command+shift+R', 'control+V', 'command+V', 'control+shift+del', 'command+shift+del'];

    // keys navigation

    /**
     * Selected element object representation
     *
     * @returns {{canHaveOptions: boolean, isBlock: boolean, visibilityConditionId: null, hasChildren: boolean, isField: boolean, isOption: boolean, box: null, isVisibilityCondition: boolean, parentFieldId: null, blockId: null, field: null, isSaved: boolean, optionId: null, isBox: boolean, isChild: boolean, boxId: null, fieldId: null}}
     */
    var selectedElementDetails = function selectedElementDetails() {

        var object = {
            isBox: false,
            isField: false,
            hasChildren: false,
            isOption: false,
            isVisibilityCondition: false,
            isChild: false,
            isBlock: false,
            canHaveOptions: false,
            isSaved: false,
            box: null,
            boxId: null,
            parentFieldId: null,
            field: null,
            fieldId: null,
            optionId: null,
            block: null,
            blockId: null,
            visibilityConditionId: null
        };

        if (selectedElement) {
            values.forEach(function (box) {
                if (box.id === selectedElement) {

                    var selectedBox = boxes.filter(function (b) {
                        return b.props.id === selectedElement;
                    })[0];

                    object = {
                        isBox: true,
                        isField: false,
                        hasChildren: false,
                        isOption: false,
                        isVisibilityCondition: false,
                        isChild: false,
                        isBlock: false,
                        canHaveOptions: false,
                        isSaved: selectedBox.props.isSaved,
                        box: box,
                        boxId: box.id,
                        parentFieldId: null,
                        field: null,
                        fieldId: null,
                        optionId: null,
                        block: null,
                        blockId: null,
                        visibilityConditionId: null
                    };

                    return;
                }

                box.fields && box.fields.forEach(function (field) {

                    var canHaveOptions = field.type === _fields.SELECT || field.type === _fields.SELECT_MULTI || field.type === _fields.RADIO || field.type === _fields.CHECKBOX;
                    var hasChildren = field.type === _fields.REPEATER;

                    if (field.id === selectedElement) {

                        var selectedField = fields.filter(function (f) {
                            return f.props.id === selectedElement;
                        })[0];

                        object = {
                            isBox: false,
                            isField: true,
                            hasChildren: hasChildren,
                            isOption: false,
                            isVisibilityCondition: false,
                            isChild: false,
                            isBlock: false,
                            canHaveOptions: canHaveOptions,
                            isSaved: selectedField.props.isSaved,
                            box: box,
                            boxId: box.id,
                            parentFieldId: null,
                            field: field,
                            fieldId: field.id,
                            optionId: null,
                            block: null,
                            blockId: null,
                            visibilityConditionId: null
                        };

                        return;
                    }

                    field.visibilityConditions && field.visibilityConditions.forEach(function (condition) {
                        if (condition.id === selectedElement) {
                            object = {
                                isBox: false,
                                isField: false,
                                hasChildren: false,
                                isOption: false,
                                isVisibilityCondition: true,
                                isChild: false,
                                isBlock: false,
                                canHaveOptions: true,
                                isSaved: false,
                                box: box,
                                boxId: box.id,
                                parentFieldId: null,
                                field: field,
                                fieldId: field.id,
                                optionId: null,
                                block: null,
                                blockId: null,
                                visibilityConditionId: condition.id
                            };
                        }

                        return;
                    });

                    canHaveOptions && field.options && field.options.forEach(function (option) {
                        if (option.id === selectedElement) {
                            object = {
                                isBox: false,
                                isField: false,
                                hasChildren: false,
                                isOption: true,
                                isVisibilityCondition: false,
                                isChild: false,
                                isBlock: false,
                                canHaveOptions: true,
                                isSaved: false,
                                box: box,
                                boxId: box.id,
                                parentFieldId: null,
                                field: field,
                                fieldId: field.id,
                                optionId: option.id,
                                block: null,
                                blockId: null,
                                visibilityConditionId: null
                            };
                        }

                        return;
                    });

                    field.blocks && field.blocks.forEach(function (block) {

                        if (block.id === selectedElement) {

                            var selectedBlock = blocks.filter(function (f) {
                                return f.props.id === selectedElement;
                            })[0];

                            object = {
                                isBox: false,
                                isField: false,
                                hasChildren: false,
                                isOption: false,
                                isVisibilityCondition: false,
                                isChild: false,
                                isBlock: true,
                                canHaveOptions: false,
                                isSaved: selectedBlock.props.isSaved,
                                box: box,
                                boxId: box.id,
                                parentFieldId: null,
                                field: field,
                                fieldId: field.id,
                                optionId: null,
                                block: block,
                                blockId: block.id,
                                visibilityConditionId: null
                            };
                        }

                        block.fields && block.fields.forEach(function (nestedField) {

                            var canHaveOptions = nestedField.type === _fields.SELECT || nestedField.type === _fields.SELECT_MULTI || nestedField.type === _fields.RADIO || nestedField.type === _fields.CHECKBOX;

                            if (nestedField.id === selectedElement) {
                                var _selectedField = fields.filter(function (f) {
                                    return f.props.id === selectedElement;
                                })[0];

                                object = {
                                    isBox: false,
                                    isField: true,
                                    hasChildren: false,
                                    isOption: false,
                                    isVisibilityCondition: false,
                                    isChild: false,
                                    isBlock: false,
                                    canHaveOptions: canHaveOptions,
                                    isSaved: _selectedField.props.isSaved,
                                    box: box,
                                    boxId: box.id,
                                    parentFieldId: null,
                                    field: nestedField,
                                    fieldId: nestedField.id,
                                    optionId: null,
                                    block: null,
                                    blockId: null,
                                    visibilityConditionId: null
                                };

                                return;
                            }

                            nestedField.visibilityConditions && nestedField.visibilityConditions.forEach(function (condition) {
                                if (condition.id === selectedElement) {
                                    object = {
                                        isBox: false,
                                        isField: false,
                                        hasChildren: false,
                                        isOption: false,
                                        isVisibilityCondition: true,
                                        isChild: false,
                                        isBlock: false,
                                        canHaveOptions: false,
                                        isSaved: false,
                                        box: box,
                                        boxId: box.id,
                                        parentFieldId: field.id,
                                        field: nestedField,
                                        fieldId: nestedField.id,
                                        optionId: null,
                                        block: null,
                                        blockId: null,
                                        visibilityConditionId: condition.id
                                    };
                                }

                                return;
                            });

                            canHaveOptions && nestedField.options && nestedField.options.forEach(function (option) {
                                if (option.id === selectedElement) {
                                    object = {
                                        isBox: false,
                                        isField: false,
                                        hasChildren: false,
                                        isOption: true,
                                        isVisibilityCondition: false,
                                        isChild: false,
                                        isBlock: false,
                                        canHaveOptions: false,
                                        isSaved: false,
                                        box: box,
                                        boxId: box.id,
                                        parentFieldId: field.id,
                                        field: nestedField,
                                        fieldId: nestedField.id,
                                        optionId: option.id,
                                        block: null,
                                        blockId: null,
                                        visibilityConditionId: null
                                    };
                                }

                                return;
                            });
                        });

                        return;
                    });

                    field.children && field.children.forEach(function (child) {

                        var canHaveOptions = field.type === _fields.SELECT || field.type === _fields.SELECT_MULTI || field.type === _fields.RADIO || field.type === _fields.CHECKBOX;

                        if (child.id === selectedElement) {

                            var _selectedField2 = fields.filter(function (f) {
                                return f.props.id === selectedElement;
                            })[0];

                            object = {
                                isBox: false,
                                isField: true,
                                hasChildren: false,
                                isOption: false,
                                isVisibilityCondition: false,
                                isChild: true,
                                isBlock: false,
                                canHaveOptions: canHaveOptions,
                                isSaved: _selectedField2.props.isSaved,
                                box: box,
                                boxId: box.id,
                                parentFieldId: field.id,
                                field: child,
                                fieldId: child.id,
                                optionId: null,
                                block: null,
                                blockId: null,
                                visibilityConditionId: null
                            };

                            return;
                        }

                        child.visibilityConditions && child.visibilityConditions.forEach(function (condition) {
                            if (condition.id === selectedElement) {
                                object = {
                                    isBox: false,
                                    isField: false,
                                    hasChildren: false,
                                    isOption: false,
                                    isVisibilityCondition: true,
                                    isChild: false,
                                    isBlock: false,
                                    canHaveOptions: false,
                                    isSaved: false,
                                    box: box,
                                    boxId: box.id,
                                    parentFieldId: field.id,
                                    field: child,
                                    fieldId: child.id,
                                    optionId: null,
                                    block: null,
                                    blockId: null,
                                    visibilityConditionId: condition.id
                                };
                            }

                            return;
                        });

                        canHaveOptions && child.options && child.options.forEach(function (option) {
                            if (option.id === selectedElement) {
                                object = {
                                    isBox: false,
                                    isField: false,
                                    hasChildren: false,
                                    isOption: true,
                                    isVisibilityCondition: false,
                                    isChild: false,
                                    isBlock: false,
                                    canHaveOptions: false,
                                    isSaved: false,
                                    box: box,
                                    boxId: box.id,
                                    parentFieldId: field.id,
                                    field: child,
                                    fieldId: child.id,
                                    optionId: option.id,
                                    block: null,
                                    blockId: null,
                                    visibilityConditionId: null
                                };
                            }

                            return;
                        });
                    });
                });
            });
        }

        return object;
    };

    /**
     * Get next and prev ids
     *
     * @returns {[*, *]}
     */
    var prevNextElements = function prevNextElements() {

        var prev = void 0,
            next = void 0;
        var ids = [];

        values.forEach(function (box) {
            ids.push(box.id);

            box.fields && box.fields.forEach(function (field) {
                ids.push(field.id);

                field.options && field.options.forEach(function (option) {
                    ids.push(option.id);
                });

                field.visibilityConditions && field.visibilityConditions.forEach(function (condition) {
                    ids.push(condition.id);
                });

                field.blocks && field.blocks.forEach(function (block) {
                    ids.push(block.id);

                    block.fields && block.fields.forEach(function (nestedField) {
                        ids.push(nestedField.id);
                    });
                });
            });
        });

        var idsCount = ids.length;

        if (selectedElement === null) {
            prev = ids[0];
            next = ids[idsCount - 1];
        } else {
            var index = ids.indexOf(selectedElement);
            prev = ids[index - 1] ? ids[index - 1] : ids[idsCount - 1];
            next = ids[index + 1] ? ids[index + 1] : ids[0];
        }

        return [prev, next];
    };

    var _prevNextElements = prevNextElements(),
        _prevNextElements2 = _slicedToArray(_prevNextElements, 2),
        prev = _prevNextElements2[0],
        next = _prevNextElements2[1];

    /**
     * Handle key press event
     *
     * @param key
     * @param e
     * @param handle
     */


    var handleKeyPressEvent = function handleKeyPressEvent(key, e, handle) {
        e.preventDefault();

        var _selectedElementDetai = selectedElementDetails(),
            isBox = _selectedElementDetai.isBox,
            isField = _selectedElementDetai.isField,
            hasChildren = _selectedElementDetai.hasChildren,
            isOption = _selectedElementDetai.isOption,
            isBlock = _selectedElementDetai.isBlock,
            isVisibilityCondition = _selectedElementDetai.isVisibilityCondition,
            canHaveOptions = _selectedElementDetai.canHaveOptions,
            boxId = _selectedElementDetai.boxId,
            box = _selectedElementDetai.box,
            fieldId = _selectedElementDetai.fieldId,
            field = _selectedElementDetai.field,
            optionId = _selectedElementDetai.optionId,
            visibilityConditionId = _selectedElementDetai.visibilityConditionId,
            block = _selectedElementDetai.block,
            blockId = _selectedElementDetai.blockId,
            isSaved = _selectedElementDetai.isSaved;

        switch (key) {

            case "esc":
            case "escape":
                dispatch((0, _metaStateActions.deselectElements)());
                setCopyBoxModalVisible(false);
                setCopyFieldModalVisible(false);
                setLegendModalVisible(false);
                setModalContent(null);
                break;

            case "tab":
                if (isField || isOption) {
                    var firstElement = document.getElementById(selectedElement).querySelector('input[type=text]').id;
                    document.getElementById(firstElement).focus();
                }

                break;

            case "up":
                dispatch((0, _metaStateActions.selectElement)(prev));
                (0, _scroll.scrollToId)(prev, -230);
                break;

            case "down":
                dispatch((0, _metaStateActions.selectElement)(next));
                (0, _scroll.scrollToId)(next, -230);
                break;

            case 'left':
                if (isField) {
                    var activeTab = field.activeTab ? field.activeTab - 1 : 0;

                    if (activeTab < 0) {
                        activeTab = 0;
                    }

                    dispatch((0, _metaStateActions.changeAccordionFieldTab)(boxId, fieldId, activeTab));
                }
                break;

            case 'right':
                if (isField) {

                    var _activeTab = field.activeTab ? field.activeTab + 1 : 1;

                    if (_activeTab > 2) {
                        _activeTab = 2;
                    }

                    dispatch((0, _metaStateActions.changeAccordionFieldTab)(boxId, fieldId, _activeTab));
                }

                break;

            case "control+A":
            case "command+A":
                if (isField) {
                    dispatch((0, _metaStateActions.toggleFieldShowInArchive)(fieldId, boxId, true));
                }

                break;

            case "control+B":
            case "command+B":
                dispatch((0, _metaStateActions.createBox)(belongsTo, find));
                break;

            case "control+C":
            case "command+C":
                if (isBox && isSaved) {
                    setCopyBoxModalVisible(true);
                    setModalContent(wp.element.createElement(_CopyMetaBoxModal2.default, { box: box, modalVisible: true, setModalVisible: setCopyBoxModalVisible }));
                } else if (isField && isSaved) {
                    setCopyFieldModalVisible(true);
                    setModalContent(wp.element.createElement(_CopyMetaFieldModal2.default, { field: field, belongsTo: belongsTo, modalVisible: true, setModalVisible: setCopyFieldModalVisible }));
                } else if (isBlock && isSaved) {
                    setCopyBlockModalVisible(true);
                    setModalContent(wp.element.createElement(_CopyMetaBlockModal2.default, { box: box, field: field, block: block, modalVisible: true, setModalVisible: setCopyBlockModalVisible }));
                }

                break;

            case "control+D":
            case "command+D":
                if (isBox) {
                    dispatch((0, _metaStateActions.duplicateMetaBox)(boxId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                } else if (isField) {
                    dispatch((0, _metaStateActions.duplicateMetaField)(boxId, fieldId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                } else if (isBlock) {
                    dispatch((0, _metaStateActions.duplicateMetaFieldBlock)(boxId, fieldId, blockId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                }

                break;

            case "control+F":
            case "command+F":
                if (isBox) {
                    dispatch((0, _metaStateActions.createField)(boxId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                } else if (isField && hasChildren) {
                    dispatch((0, _metaStateActions.createField)(boxId, fieldId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                }

                break;

            case "control+L":
            case "command+L":
                setLegendModalVisible(true);
                break;

            case "control+O":
            case "command+O":
                if (isField && canHaveOptions || isOption) {
                    dispatch((0, _metaStateActions.createOption)(boxId, fieldId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                }

                break;

            case "control+R":
            case "command+R":
                if (isField) {
                    dispatch((0, _metaStateActions.toggleFieldIsRequired)(fieldId, boxId, true));
                }
                break;

            case "control+S":
            case "command+S":
                dispatch((0, _metaSubmit.metaSubmit)(values));
                dispatch((0, _metaStateActions.setStatusSaved)());
                break;

            case "control+del":
            case "command+del":
                if (isBox) {
                    dispatch((0, _metaStateActions.deleteBox)(boxId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                } else if (isField) {
                    dispatch((0, _metaStateActions.deleteField)(boxId, fieldId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                } else if (isOption) {
                    dispatch((0, _metaStateActions.deleteOption)(boxId, fieldId, optionId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                } else if (isVisibilityCondition) {
                    dispatch((0, _metaStateActions.deleteVisibilityCondition)(boxId, fieldId, visibilityConditionId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                } else if (isBlock) {
                    dispatch((0, _metaStateActions.deleteFieldBlock)(boxId, fieldId, blockId));
                    dispatch((0, _metaStateActions.setStatusSaved)());
                }

                dispatch((0, _metaStateActions.deselectElements)());

                break;

            case "control+shift+A":
            case "command+shift+A":
                if (isField) {
                    dispatch((0, _metaStateActions.toggleFieldShowInArchive)(fieldId, boxId, false));
                }
                break;

            case 'control+shift+B':
            case 'command+shift+B':
                if (isField && field.type === _fields.FLEXIBLE) {
                    dispatch((0, _metaStateActions.createFieldBlock)(boxId, fieldId));
                }
                break;

            case "control+shift+R":
            case "command+shift+R":
                if (isField) {
                    dispatch((0, _metaStateActions.toggleFieldIsRequired)(fieldId, boxId, false));
                }
                break;

            case 'control+V':
            case 'command+V':
                if (isField && field.activeTab && field.activeTab === 2) {
                    dispatch((0, _metaStateActions.createVisibilityCondition)(boxId, fieldId));
                }

                break;

            case "control+shift+del":
            case "command+shift+del":
                dispatch((0, _deleteAllMeta.deleteAllMeta)(belongsTo, find));
                dispatch((0, _metaStateActions.setStatusSaved)());
                dispatch((0, _metaStateActions.deselectElements)());
                break;
        }
    };

    return wp.element.createElement(
        _reactHotKeys2.default,
        {
            keyName: keyMaps.join(','),
            onKeyDown: handleKeyPressEvent
        },
        wp.element.createElement(_KeyboardLegendModal2.default, {
            modalVisible: legendModalVisible,
            setModalVisible: setLegendModalVisible
        }),
        wp.element.createElement(
            "a",
            {
                href: "#",
                className: "keyboard-legend-link",
                onClick: function onClick(e) {
                    e.preventDefault();
                    setLegendModalVisible(!legendModalVisible);
                }
            },
            wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" }),
            wp.element.createElement(
                "span",
                null,
                (0, _useTranslation2.default)("Keyboard legend")
            )
        ),
        boxes.length > 0 ? wp.element.createElement(
            _react2.default.Fragment,
            null,
            modalsContent,
            wp.element.createElement(
                "div",
                { className: "acpt-meta-wrapper" },
                wp.element.createElement(
                    "div",
                    { className: "acpt-meta-list-wrapper" },
                    wp.element.createElement(
                        "div",
                        { className: "space-between mb-3 flex-center" },
                        wp.element.createElement(
                            "div",
                            { className: "meta-totals" },
                            wp.element.createElement(
                                _Tippy2.default,
                                { title: (0, _useTranslation2.default)("Meta boxes total count") },
                                wp.element.createElement(
                                    "div",
                                    { className: "meta-totals-item with-border" },
                                    wp.element.createElement(_react3.Icon, { icon: "fluent:box-24-regular", width: "18px", color: "#2271b1" }),
                                    wp.element.createElement(
                                        "span",
                                        { className: "counter" },
                                        boxes.length
                                    )
                                )
                            ),
                            wp.element.createElement(
                                _Tippy2.default,
                                { title: (0, _useTranslation2.default)("Meta fields total count") },
                                wp.element.createElement(
                                    "div",
                                    { className: "meta-totals-item" },
                                    wp.element.createElement(_react3.Icon, { icon: "fluent:text-field-24-regular", width: "18px", color: "#2271b1" }),
                                    wp.element.createElement(
                                        "span",
                                        { className: "counter" },
                                        fields.length
                                    )
                                )
                            )
                        ),
                        wp.element.createElement(
                            "a",
                            {
                                href: "#",
                                onClick: function onClick(e) {
                                    e.preventDefault();
                                    showHideAllFields();
                                }
                            },
                            (0, _useTranslation2.default)("Show/hide all fields")
                        )
                    ),
                    wp.element.createElement(_Sortable.SortableList, {
                        items: boxes,
                        onSortEnd: onSortEnd,
                        useDragHandle: true,
                        lockAxis: "y",
                        helperClass: "dragging-helper-class",
                        disableAutoscroll: false,
                        useWindowAsScrollContainer: true
                    })
                ),
                wp.element.createElement(_MiniNavMap2.default, { values: values })
            )
        ) : wp.element.createElement(
            _react2.default.Fragment,
            null,
            wp.element.createElement(
                "div",
                { className: "acpt-alert acpt-alert-warning" },
                (0, _useTranslation2.default)('No meta box already created. Create the first one now by clicking the button "Add meta box"!')
            )
        )
    );
};

exports["default"] = Meta;

/***/ }),

/***/ 67209:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.deleteAllMeta = undefined;

var _ajax = __webpack_require__(47569);

var _metaStateActions = __webpack_require__(38527);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var deleteAllMeta = exports.deleteAllMeta = function deleteAllMeta(belongsTo, find) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _metaStateActions.deleteAllInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("deleteMetaAction", { belongsTo: belongsTo, find: find });

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _metaStateActions.deleteAllSuccess)()) : dispatch((0, _metaStateActions.deleteAllFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _metaStateActions.deleteAllFailure)(_context.t0));

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

/***/ 4553:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchMeta = undefined;

var _ajax = __webpack_require__(47569);

var _fetchMetaActions = __webpack_require__(86456);

var _metaStateActions = __webpack_require__(38527);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchMeta = exports.fetchMeta = function fetchMeta(belongsTo, find, metaFieldId) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchMetaActions.fetchMetaInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchMetaAction', { belongsTo: belongsTo, find: find, metaFieldId: metaFieldId });

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchMetaActions.fetchMetaSuccess)(fetched));
                            dispatch((0, _metaStateActions.hydrateValues)(fetched));
                            _context.next = 12;
                            break;

                        case 9:
                            _context.prev = 9;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchMetaActions.fetchMetaFailure)(_context.t0));

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

/***/ }),

/***/ 94717:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.metaSubmit = undefined;

var _ajax = __webpack_require__(47569);

var _metaStateActions = __webpack_require__(38527);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var metaSubmit = exports.metaSubmit = function metaSubmit(data) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _metaStateActions.submitInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)("saveMetaAction", data);

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _metaStateActions.submitSuccess)()) : dispatch((0, _metaStateActions.submitFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _metaStateActions.submitFailure)(_context.t0));

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

/***/ 4118:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "default": () => (/* binding */ ReactHotkeys)
});

// EXTERNAL MODULE: ./node_modules/react/index.js
var react = __webpack_require__(67294);
// EXTERNAL MODULE: ./node_modules/prop-types/index.js
var prop_types = __webpack_require__(45697);
;// CONCATENATED MODULE: ./node_modules/hotkeys-js/dist/hotkeys.esm.js
/**! 
 * hotkeys-js v3.10.1 
 * A simple micro-library for defining and dispatching keyboard shortcuts. It has no dependencies. 
 * 
 * Copyright (c) 2022 kenny wong <wowohoo@qq.com> 
 * http://jaywcjlove.github.io/hotkeys 
 * Licensed under the MIT license 
 */

var isff = typeof navigator !== 'undefined' ? navigator.userAgent.toLowerCase().indexOf('firefox') > 0 : false; // 绑定事件

function addEvent(object, event, method, useCapture) {
  if (object.addEventListener) {
    object.addEventListener(event, method, useCapture);
  } else if (object.attachEvent) {
    object.attachEvent("on".concat(event), function () {
      method(window.event);
    });
  }
} // 修饰键转换成对应的键码


function getMods(modifier, key) {
  var mods = key.slice(0, key.length - 1);

  for (var i = 0; i < mods.length; i++) {
    mods[i] = modifier[mods[i].toLowerCase()];
  }

  return mods;
} // 处理传的key字符串转换成数组


function getKeys(key) {
  if (typeof key !== 'string') key = '';
  key = key.replace(/\s/g, ''); // 匹配任何空白字符,包括空格、制表符、换页符等等

  var keys = key.split(','); // 同时设置多个快捷键，以','分割

  var index = keys.lastIndexOf(''); // 快捷键可能包含','，需特殊处理

  for (; index >= 0;) {
    keys[index - 1] += ',';
    keys.splice(index, 1);
    index = keys.lastIndexOf('');
  }

  return keys;
} // 比较修饰键的数组


function compareArray(a1, a2) {
  var arr1 = a1.length >= a2.length ? a1 : a2;
  var arr2 = a1.length >= a2.length ? a2 : a1;
  var isIndex = true;

  for (var i = 0; i < arr1.length; i++) {
    if (arr2.indexOf(arr1[i]) === -1) isIndex = false;
  }

  return isIndex;
}

var _keyMap = {
  backspace: 8,
  '⌫': 8,
  tab: 9,
  clear: 12,
  enter: 13,
  '↩': 13,
  return: 13,
  esc: 27,
  escape: 27,
  space: 32,
  left: 37,
  up: 38,
  right: 39,
  down: 40,
  del: 46,
  delete: 46,
  ins: 45,
  insert: 45,
  home: 36,
  end: 35,
  pageup: 33,
  pagedown: 34,
  capslock: 20,
  num_0: 96,
  num_1: 97,
  num_2: 98,
  num_3: 99,
  num_4: 100,
  num_5: 101,
  num_6: 102,
  num_7: 103,
  num_8: 104,
  num_9: 105,
  num_multiply: 106,
  num_add: 107,
  num_enter: 108,
  num_subtract: 109,
  num_decimal: 110,
  num_divide: 111,
  '⇪': 20,
  ',': 188,
  '.': 190,
  '/': 191,
  '`': 192,
  '-': isff ? 173 : 189,
  '=': isff ? 61 : 187,
  ';': isff ? 59 : 186,
  '\'': 222,
  '[': 219,
  ']': 221,
  '\\': 220
}; // Modifier Keys

var _modifier = {
  // shiftKey
  '⇧': 16,
  shift: 16,
  // altKey
  '⌥': 18,
  alt: 18,
  option: 18,
  // ctrlKey
  '⌃': 17,
  ctrl: 17,
  control: 17,
  // metaKey
  '⌘': 91,
  cmd: 91,
  command: 91
};
var modifierMap = {
  16: 'shiftKey',
  18: 'altKey',
  17: 'ctrlKey',
  91: 'metaKey',
  shiftKey: 16,
  ctrlKey: 17,
  altKey: 18,
  metaKey: 91
};
var _mods = {
  16: false,
  18: false,
  17: false,
  91: false
};
var _handlers = {}; // F1~F12 special key

for (var k = 1; k < 20; k++) {
  _keyMap["f".concat(k)] = 111 + k;
}

var _downKeys = []; // 记录摁下的绑定键

var winListendFocus = false; // window是否已经监听了focus事件

var _scope = 'all'; // 默认热键范围

var elementHasBindEvent = []; // 已绑定事件的节点记录
// 返回键码

var code = function code(x) {
  return _keyMap[x.toLowerCase()] || _modifier[x.toLowerCase()] || x.toUpperCase().charCodeAt(0);
};

var getKey = function getKey(x) {
  return Object.keys(_keyMap).find(function (k) {
    return _keyMap[k] === x;
  });
};

var getModifier = function getModifier(x) {
  return Object.keys(_modifier).find(function (k) {
    return _modifier[k] === x;
  });
}; // 设置获取当前范围（默认为'所有'）


function setScope(scope) {
  _scope = scope || 'all';
} // 获取当前范围


function getScope() {
  return _scope || 'all';
} // 获取摁下绑定键的键值


function getPressedKeyCodes() {
  return _downKeys.slice(0);
}

function getPressedKeyString() {
  return _downKeys.map(function (c) {
    return getKey(c) || getModifier(c) || String.fromCharCode(c);
  });
} // 表单控件控件判断 返回 Boolean
// hotkey is effective only when filter return true


function filter(event) {
  var target = event.target || event.srcElement;
  var tagName = target.tagName;
  var flag = true; // ignore: isContentEditable === 'true', <input> and <textarea> when readOnly state is false, <select>

  if (target.isContentEditable || (tagName === 'INPUT' || tagName === 'TEXTAREA' || tagName === 'SELECT') && !target.readOnly) {
    flag = false;
  }

  return flag;
} // 判断摁下的键是否为某个键，返回true或者false


function isPressed(keyCode) {
  if (typeof keyCode === 'string') {
    keyCode = code(keyCode); // 转换成键码
  }

  return _downKeys.indexOf(keyCode) !== -1;
} // 循环删除handlers中的所有 scope(范围)


function deleteScope(scope, newScope) {
  var handlers;
  var i; // 没有指定scope，获取scope

  if (!scope) scope = getScope();

  for (var key in _handlers) {
    if (Object.prototype.hasOwnProperty.call(_handlers, key)) {
      handlers = _handlers[key];

      for (i = 0; i < handlers.length;) {
        if (handlers[i].scope === scope) handlers.splice(i, 1);else i++;
      }
    }
  } // 如果scope被删除，将scope重置为all


  if (getScope() === scope) setScope(newScope || 'all');
} // 清除修饰键


function clearModifier(event) {
  var key = event.keyCode || event.which || event.charCode;

  var i = _downKeys.indexOf(key); // 从列表中清除按压过的键


  if (i >= 0) {
    _downKeys.splice(i, 1);
  } // 特殊处理 cmmand 键，在 cmmand 组合快捷键 keyup 只执行一次的问题


  if (event.key && event.key.toLowerCase() === 'meta') {
    _downKeys.splice(0, _downKeys.length);
  } // 修饰键 shiftKey altKey ctrlKey (command||metaKey) 清除


  if (key === 93 || key === 224) key = 91;

  if (key in _mods) {
    _mods[key] = false; // 将修饰键重置为false

    for (var k in _modifier) {
      if (_modifier[k] === key) hotkeys[k] = false;
    }
  }
}

function unbind(keysInfo) {
  // unbind(), unbind all keys
  if (typeof keysInfo === 'undefined') {
    Object.keys(_handlers).forEach(function (key) {
      return delete _handlers[key];
    });
  } else if (Array.isArray(keysInfo)) {
    // support like : unbind([{key: 'ctrl+a', scope: 's1'}, {key: 'ctrl-a', scope: 's2', splitKey: '-'}])
    keysInfo.forEach(function (info) {
      if (info.key) eachUnbind(info);
    });
  } else if (typeof keysInfo === 'object') {
    // support like unbind({key: 'ctrl+a, ctrl+b', scope:'abc'})
    if (keysInfo.key) eachUnbind(keysInfo);
  } else if (typeof keysInfo === 'string') {
    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }

    // support old method
    // eslint-disable-line
    var scope = args[0],
        method = args[1];

    if (typeof scope === 'function') {
      method = scope;
      scope = '';
    }

    eachUnbind({
      key: keysInfo,
      scope: scope,
      method: method,
      splitKey: '+'
    });
  }
} // 解除绑定某个范围的快捷键


var eachUnbind = function eachUnbind(_ref) {
  var key = _ref.key,
      scope = _ref.scope,
      method = _ref.method,
      _ref$splitKey = _ref.splitKey,
      splitKey = _ref$splitKey === void 0 ? '+' : _ref$splitKey;
  var multipleKeys = getKeys(key);
  multipleKeys.forEach(function (originKey) {
    var unbindKeys = originKey.split(splitKey);
    var len = unbindKeys.length;
    var lastKey = unbindKeys[len - 1];
    var keyCode = lastKey === '*' ? '*' : code(lastKey);
    if (!_handlers[keyCode]) return; // 判断是否传入范围，没有就获取范围

    if (!scope) scope = getScope();
    var mods = len > 1 ? getMods(_modifier, unbindKeys) : [];
    _handlers[keyCode] = _handlers[keyCode].filter(function (record) {
      // 通过函数判断，是否解除绑定，函数相等直接返回
      var isMatchingMethod = method ? record.method === method : true;
      return !(isMatchingMethod && record.scope === scope && compareArray(record.mods, mods));
    });
  });
}; // 对监听对应快捷键的回调函数进行处理


function eventHandler(event, handler, scope, element) {
  if (handler.element !== element) {
    return;
  }

  var modifiersMatch; // 看它是否在当前范围

  if (handler.scope === scope || handler.scope === 'all') {
    // 检查是否匹配修饰符（如果有返回true）
    modifiersMatch = handler.mods.length > 0;

    for (var y in _mods) {
      if (Object.prototype.hasOwnProperty.call(_mods, y)) {
        if (!_mods[y] && handler.mods.indexOf(+y) > -1 || _mods[y] && handler.mods.indexOf(+y) === -1) {
          modifiersMatch = false;
        }
      }
    } // 调用处理程序，如果是修饰键不做处理


    if (handler.mods.length === 0 && !_mods[16] && !_mods[18] && !_mods[17] && !_mods[91] || modifiersMatch || handler.shortcut === '*') {
      if (handler.method(event, handler) === false) {
        if (event.preventDefault) event.preventDefault();else event.returnValue = false;
        if (event.stopPropagation) event.stopPropagation();
        if (event.cancelBubble) event.cancelBubble = true;
      }
    }
  }
} // 处理keydown事件


function dispatch(event, element) {
  var asterisk = _handlers['*'];
  var key = event.keyCode || event.which || event.charCode; // 表单控件过滤 默认表单控件不触发快捷键

  if (!hotkeys.filter.call(this, event)) return; // Gecko(Firefox)的command键值224，在Webkit(Chrome)中保持一致
  // Webkit左右 command 键值不一样

  if (key === 93 || key === 224) key = 91;
  /**
   * Collect bound keys
   * If an Input Method Editor is processing key input and the event is keydown, return 229.
   * https://stackoverflow.com/questions/25043934/is-it-ok-to-ignore-keydown-events-with-keycode-229
   * http://lists.w3.org/Archives/Public/www-dom/2010JulSep/att-0182/keyCode-spec.html
   */

  if (_downKeys.indexOf(key) === -1 && key !== 229) _downKeys.push(key);
  /**
   * Jest test cases are required.
   * ===============================
   */

  ['ctrlKey', 'altKey', 'shiftKey', 'metaKey'].forEach(function (keyName) {
    var keyNum = modifierMap[keyName];

    if (event[keyName] && _downKeys.indexOf(keyNum) === -1) {
      _downKeys.push(keyNum);
    } else if (!event[keyName] && _downKeys.indexOf(keyNum) > -1) {
      _downKeys.splice(_downKeys.indexOf(keyNum), 1);
    } else if (keyName === 'metaKey' && event[keyName] && _downKeys.length === 3) {
      /**
       * Fix if Command is pressed:
       * ===============================
       */
      if (!(event.ctrlKey || event.shiftKey || event.altKey)) {
        _downKeys = _downKeys.slice(_downKeys.indexOf(keyNum));
      }
    }
  });
  /**
   * -------------------------------
   */

  if (key in _mods) {
    _mods[key] = true; // 将特殊字符的key注册到 hotkeys 上

    for (var k in _modifier) {
      if (_modifier[k] === key) hotkeys[k] = true;
    }

    if (!asterisk) return;
  } // 将 modifierMap 里面的修饰键绑定到 event 中


  for (var e in _mods) {
    if (Object.prototype.hasOwnProperty.call(_mods, e)) {
      _mods[e] = event[modifierMap[e]];
    }
  }
  /**
   * https://github.com/jaywcjlove/hotkeys/pull/129
   * This solves the issue in Firefox on Windows where hotkeys corresponding to special characters would not trigger.
   * An example of this is ctrl+alt+m on a Swedish keyboard which is used to type μ.
   * Browser support: https://caniuse.com/#feat=keyboardevent-getmodifierstate
   */


  if (event.getModifierState && !(event.altKey && !event.ctrlKey) && event.getModifierState('AltGraph')) {
    if (_downKeys.indexOf(17) === -1) {
      _downKeys.push(17);
    }

    if (_downKeys.indexOf(18) === -1) {
      _downKeys.push(18);
    }

    _mods[17] = true;
    _mods[18] = true;
  } // 获取范围 默认为 `all`


  var scope = getScope(); // 对任何快捷键都需要做的处理

  if (asterisk) {
    for (var i = 0; i < asterisk.length; i++) {
      if (asterisk[i].scope === scope && (event.type === 'keydown' && asterisk[i].keydown || event.type === 'keyup' && asterisk[i].keyup)) {
        eventHandler(event, asterisk[i], scope, element);
      }
    }
  } // key 不在 _handlers 中返回


  if (!(key in _handlers)) return;

  for (var _i = 0; _i < _handlers[key].length; _i++) {
    if (event.type === 'keydown' && _handlers[key][_i].keydown || event.type === 'keyup' && _handlers[key][_i].keyup) {
      if (_handlers[key][_i].key) {
        var record = _handlers[key][_i];
        var splitKey = record.splitKey;
        var keyShortcut = record.key.split(splitKey);
        var _downKeysCurrent = []; // 记录当前按键键值

        for (var a = 0; a < keyShortcut.length; a++) {
          _downKeysCurrent.push(code(keyShortcut[a]));
        }

        if (_downKeysCurrent.sort().join('') === _downKeys.sort().join('')) {
          // 找到处理内容
          eventHandler(event, record, scope, element);
        }
      }
    }
  }
} // 判断 element 是否已经绑定事件


function isElementBind(element) {
  return elementHasBindEvent.indexOf(element) > -1;
}

function hotkeys(key, option, method) {
  _downKeys = [];
  var keys = getKeys(key); // 需要处理的快捷键列表

  var mods = [];
  var scope = 'all'; // scope默认为all，所有范围都有效

  var element = document; // 快捷键事件绑定节点

  var i = 0;
  var keyup = false;
  var keydown = true;
  var splitKey = '+';
  var capture = false; // 对为设定范围的判断

  if (method === undefined && typeof option === 'function') {
    method = option;
  }

  if (Object.prototype.toString.call(option) === '[object Object]') {
    if (option.scope) scope = option.scope; // eslint-disable-line

    if (option.element) element = option.element; // eslint-disable-line

    if (option.keyup) keyup = option.keyup; // eslint-disable-line

    if (option.keydown !== undefined) keydown = option.keydown; // eslint-disable-line

    if (option.capture !== undefined) capture = option.capture; // eslint-disable-line

    if (typeof option.splitKey === 'string') splitKey = option.splitKey; // eslint-disable-line
  }

  if (typeof option === 'string') scope = option; // 对于每个快捷键进行处理

  for (; i < keys.length; i++) {
    key = keys[i].split(splitKey); // 按键列表

    mods = []; // 如果是组合快捷键取得组合快捷键

    if (key.length > 1) mods = getMods(_modifier, key); // 将非修饰键转化为键码

    key = key[key.length - 1];
    key = key === '*' ? '*' : code(key); // *表示匹配所有快捷键
    // 判断key是否在_handlers中，不在就赋一个空数组

    if (!(key in _handlers)) _handlers[key] = [];

    _handlers[key].push({
      keyup: keyup,
      keydown: keydown,
      scope: scope,
      mods: mods,
      shortcut: keys[i],
      method: method,
      key: keys[i],
      splitKey: splitKey,
      element: element
    });
  } // 在全局document上设置快捷键


  if (typeof element !== 'undefined' && !isElementBind(element) && window) {
    elementHasBindEvent.push(element);
    addEvent(element, 'keydown', function (e) {
      dispatch(e, element);
    }, capture);

    if (!winListendFocus) {
      winListendFocus = true;
      addEvent(window, 'focus', function () {
        _downKeys = [];
      }, capture);
    }

    addEvent(element, 'keyup', function (e) {
      dispatch(e, element);
      clearModifier(e);
    }, capture);
  }
}

function trigger(shortcut) {
  var scope = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'all';
  Object.keys(_handlers).forEach(function (key) {
    var dataList = _handlers[key].filter(function (item) {
      return item.scope === scope && item.shortcut === shortcut;
    });

    dataList.forEach(function (data) {
      if (data && data.method) {
        data.method();
      }
    });
  });
}

var _api = {
  getPressedKeyString: getPressedKeyString,
  setScope: setScope,
  getScope: getScope,
  deleteScope: deleteScope,
  getPressedKeyCodes: getPressedKeyCodes,
  isPressed: isPressed,
  filter: filter,
  trigger: trigger,
  unbind: unbind,
  keyMap: _keyMap,
  modifier: _modifier,
  modifierMap: modifierMap
};

for (var a in _api) {
  if (Object.prototype.hasOwnProperty.call(_api, a)) {
    hotkeys[a] = _api[a];
  }
}

if (typeof window !== 'undefined') {
  var _hotkeys = window.hotkeys;

  hotkeys.noConflict = function (deep) {
    if (deep && window.hotkeys === hotkeys) {
      window.hotkeys = _hotkeys;
    }

    return hotkeys;
  };

  window.hotkeys = hotkeys;
}



;// CONCATENATED MODULE: ./node_modules/react-hot-keys/esm/index.js



class ReactHotkeys extends react.Component {
  constructor(props) {
    super(props);
    this.isKeyDown = false;
    this.handle = void 0;
    this.onKeyDown = this.onKeyDown.bind(this);
    this.onKeyUp = this.onKeyUp.bind(this);
    this.handleKeyUpEvent = this.handleKeyUpEvent.bind(this);
    this.handle = {};
  }

  componentDidMount() {
    var {
      filter,
      splitKey
    } = this.props;

    if (filter) {
      hotkeys.filter = filter;
    }

    hotkeys.unbind(this.props.keyName);
    hotkeys(this.props.keyName, {
      splitKey
    }, this.onKeyDown);
    document && document.body.addEventListener('keyup', this.handleKeyUpEvent);
  }

  componentWillUnmount() {
    hotkeys.unbind(this.props.keyName);
    this.isKeyDown = true;
    this.handle = {};
    document && document.body.removeEventListener('keyup', this.handleKeyUpEvent);
  }

  onKeyUp(e, handle) {
    var {
      onKeyUp,
      disabled
    } = this.props;
    !disabled && onKeyUp && onKeyUp(handle.shortcut, e, handle);
  }

  onKeyDown(e, handle) {
    var {
      onKeyDown,
      allowRepeat,
      disabled
    } = this.props;
    if (this.isKeyDown && !allowRepeat) return;
    this.isKeyDown = true;
    this.handle = handle;
    !disabled && onKeyDown && onKeyDown(handle.shortcut, e, handle);
  }

  handleKeyUpEvent(e) {
    if (!this.isKeyDown) return;
    this.isKeyDown = false;
    if (this.props.keyName && this.props.keyName.indexOf(this.handle.shortcut) < 0) return;
    this.onKeyUp(e, this.handle);
    this.handle = {};
  }

  render() {
    return this.props.children || null;
  }

}
ReactHotkeys.defaultProps = {
  filter(event) {
    var target = event.target || event.srcElement;
    var tagName = target.tagName;
    return !(target.isContentEditable || tagName === 'INPUT' || tagName === 'SELECT' || tagName === 'TEXTAREA');
  }

};
ReactHotkeys.propTypes = {
  keyName: prop_types.string,
  filter: prop_types.func,
  onKeyDown: prop_types.func,
  onKeyUp: prop_types.func,
  disabled: prop_types.bool,
  splitKey: prop_types.string
};
//# sourceMappingURL=index.js.map

/***/ })

}]);
//# sourceMappingURL=6628.js.map