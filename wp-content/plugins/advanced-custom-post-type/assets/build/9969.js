"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[9969],{

/***/ 2653:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Modal = __webpack_require__(22651);

var _Modal2 = _interopRequireDefault(_Modal);

var _reactHookForm = __webpack_require__(40930);

var _ReactSelect = __webpack_require__(92762);

var _ReactSelect2 = _interopRequireDefault(_ReactSelect);

var _metaTypes = __webpack_require__(81895);

var _ajax = __webpack_require__(47569);

var _reactRouterDom = __webpack_require__(73727);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var CreateTemplateModal = function CreateTemplateModal(_ref) {
    var modalVisible = _ref.modalVisible,
        setModalVisible = _ref.setModalVisible,
        savedTemplates = _ref.savedTemplates;


    // manage redirect
    var history = (0, _reactRouterDom.useHistory)();

    // manage local state

    var _useState = (0, _react.useState)([]),
        _useState2 = _slicedToArray(_useState, 2),
        findOptions = _useState2[0],
        setFindOptions = _useState2[1];

    var _useState3 = (0, _react.useState)([]),
        _useState4 = _slicedToArray(_useState3, 2),
        templateTypeOptions = _useState4[0],
        setTemplateTypeOptions = _useState4[1];

    // handle form


    var _useForm = (0, _reactHookForm.useForm)({
        mode: 'all'
    }),
        control = _useForm.control,
        register = _useForm.register,
        handleSubmit = _useForm.handleSubmit,
        _useForm$formState = _useForm.formState,
        errors = _useForm$formState.errors,
        isValid = _useForm$formState.isValid,
        watch = _useForm.watch;

    var belongsTo = watch('belongsTo');
    var find = watch('find');

    (0, _react.useEffect)(function () {
        if (modalVisible === true) {
            (0, _ajax.wpAjaxRequest)("fetchElementsAction", { belongsTo: belongsTo.value }).then(function (res) {
                setFindOptions(res);
            }).catch(function (err) {
                console.error(err.message);
            });
        }
    }, [belongsTo]);

    (0, _react.useEffect)(function () {

        if (savedTemplates && find && belongsTo) {
            var templatesAlreadyCreated = savedTemplates.filter(function (template) {
                return template.belongsTo === belongsTo.value && template.find === find.value;
            });

            var newTemplateTypeOptions = [];

            if (belongsTo.value === _metaTypes.metaTypes.CUSTOM_POST_TYPE) {
                var checkArchiveTemplate = templatesAlreadyCreated.filter(function (t) {
                    return t.templateType === 'archive';
                });
                var checkSingleTemplate = templatesAlreadyCreated.filter(function (t) {
                    return t.templateType === 'single';
                });
                var checkRelatedTemplate = templatesAlreadyCreated.filter(function (t) {
                    return t.templateType === 'related';
                });
                var isNative = find.value === 'post' || find.value === 'page';

                if (!isNative && checkArchiveTemplate.length === 0) {
                    newTemplateTypeOptions.push({ label: 'Archive', value: 'archive' });
                }
                if (!isNative && checkSingleTemplate.length === 0) {
                    newTemplateTypeOptions.push({ label: 'Single', value: 'single' });
                }
                if (checkRelatedTemplate.length === 0) {
                    newTemplateTypeOptions.push({ label: 'Related', value: 'related' });
                }
            }

            if (belongsTo.value === _metaTypes.metaTypes.TAXONOMY) {
                var _checkSingleTemplate = templatesAlreadyCreated.filter(function (t) {
                    return t.templateType === 'single';
                });

                if (_checkSingleTemplate.length === 0) {
                    newTemplateTypeOptions.push({ label: 'Single', value: 'single' });
                }
            }

            setTemplateTypeOptions(newTemplateTypeOptions);
        }
    }, [find]);

    var onSubmit = function onSubmit(data) {
        history.push("/template/" + data.belongsTo.value + "/" + data.templateType.value + "/" + data.find.value + "/?refer=templates");
    };

    return wp.element.createElement(
        _Modal2.default,
        { title: (0, _useTranslation2.default)("Create template"), visible: modalVisible },
        wp.element.createElement(
            "form",
            { onSubmit: handleSubmit(onSubmit) },
            wp.element.createElement(
                "div",
                { className: "text-left" },
                wp.element.createElement(
                    "p",
                    null,
                    (0, _useTranslation2.default)("Which does this template belong to?")
                ),
                wp.element.createElement(_ReactSelect2.default, {
                    id: "belongsTo",
                    placeholder: (0, _useTranslation2.default)("Which does this template belong to?"),
                    control: control,
                    defaultValue: null,
                    values: [{ label: (0, _useTranslation2.default)('Custom post type'), value: _metaTypes.metaTypes.CUSTOM_POST_TYPE }, { label: (0, _useTranslation2.default)('Taxonomy'), value: _metaTypes.metaTypes.TAXONOMY }],
                    isRequired: true,
                    validate: {
                        required: (0, _useTranslation2.default)("This field is mandatory")
                    }
                }),
                wp.element.createElement(
                    "p",
                    null,
                    (0, _useTranslation2.default)("Select the element from the list:")
                ),
                wp.element.createElement(_ReactSelect2.default, {
                    id: "find",
                    placeholder: (0, _useTranslation2.default)("Choose from the list"),
                    control: control,
                    defaultValue: null,
                    values: findOptions,
                    isRequired: true,
                    disabled: true,
                    validate: {
                        required: (0, _useTranslation2.default)("This field is mandatory")
                    }
                }),
                wp.element.createElement(
                    "p",
                    null,
                    (0, _useTranslation2.default)("Select the template type:")
                ),
                wp.element.createElement(_ReactSelect2.default, {
                    id: "templateType",
                    placeholder: (0, _useTranslation2.default)("Choose from the list"),
                    control: control,
                    defaultValue: null,
                    values: templateTypeOptions,
                    isRequired: true,
                    disabled: true,
                    validate: {
                        required: (0, _useTranslation2.default)("This field is mandatory")
                    }
                }),
                wp.element.createElement(
                    "p",
                    { className: "acpt-buttons" },
                    wp.element.createElement(
                        "button",
                        {
                            type: "submit",
                            className: "acpt-btn acpt-btn-primary",
                            disabled: !isValid
                        },
                        (0, _useTranslation2.default)("Create template")
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
            )
        )
    );
};

exports["default"] = CreateTemplateModal;

/***/ }),

/***/ 68403:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _Modal = __webpack_require__(22651);

var _Modal2 = _interopRequireDefault(_Modal);

var _metaTypes = __webpack_require__(81895);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var TemplateListElement = function TemplateListElement(_ref) {
    var id = _ref.id,
        element = _ref.element,
        handleDeleteTemplate = _ref.handleDeleteTemplate;

    // manage local state
    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        modalVisible = _useState2[0],
        setModalVisible = _useState2[1];

    // manage redirect


    var openDeleteModal = function openDeleteModal() {
        setModalVisible(!modalVisible);
    };

    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(
            _Modal2.default,
            { title: (0, _useTranslation2.default)("Confirm deleting this template?"), visible: modalVisible },
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
                            handleDeleteTemplate(element.belongsTo, element.find, element.templateType);
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
                null,
                element.belongsTo === _metaTypes.metaTypes.CUSTOM_POST_TYPE ? 'custom post type' : element.belongsTo
            ),
            wp.element.createElement(
                "td",
                null,
                element.find
            ),
            wp.element.createElement(
                "td",
                null,
                element.templateType
            ),
            wp.element.createElement(
                "td",
                null,
                wp.element.createElement(
                    _reactRouterDom.Link,
                    {
                        to: element.link + "?refer=templates",
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
                            openDeleteModal(element.templateType);
                        },
                        className: "ml-1 acpt-btn no-border acpt-btn-sm acpt-btn-danger-o",
                        href: "#"
                    },
                    (0, _useTranslation2.default)("Delete")
                )
            )
        )
    );
};

exports["default"] = TemplateListElement;

/***/ }),

/***/ 99969:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRouterDom = __webpack_require__(73727);

var _objects = __webpack_require__(54040);

var _misc = __webpack_require__(53154);

var _reactToastify = __webpack_require__(39249);

var _fetchTemplatesCount = __webpack_require__(72756);

var _fetchTemplates = __webpack_require__(95393);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _reactRedux = __webpack_require__(28216);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _react3 = __webpack_require__(44226);

var _Tippy = __webpack_require__(85825);

var _Tippy2 = _interopRequireDefault(_Tippy);

var _TemplateListElement = __webpack_require__(68403);

var _TemplateListElement2 = _interopRequireDefault(_TemplateListElement);

var _deleteTemplate = __webpack_require__(10495);

var _Pagination = __webpack_require__(41222);

var _Pagination2 = _interopRequireDefault(_Pagination);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _CreateTemplateModal = __webpack_require__(2653);

var _CreateTemplateModal2 = _interopRequireDefault(_CreateTemplateModal);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var TemplateList = function TemplateList() {

    // manage global state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTemplatesReducer;
    }),
        fetched = _useSelector.fetched,
        loading = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchTemplatesCountReducer;
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

    var _useState3 = (0, _react.useState)(false),
        _useState4 = _slicedToArray(_useState3, 2),
        modalTemplateVisible = _useState4[0],
        setModalTemplateVisible = _useState4[1];

    var perPage = settings.length > 0 && (0, _objects.filterByLabel)(settings, 'key', 'records_per_page') !== '' ? (0, _objects.filterByLabel)(settings, 'key', 'records_per_page').value : 20;
    var totalPages = Math.ceil(fetchedCount / perPage);

    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)((0, _useTranslation2.default)("Template list"));
        (0, _misc.changeCurrentAdminMenuLink)('#/templates');
        dispatch((0, _fetchTemplatesCount.fetchTemplatesCount)());
        dispatch((0, _fetchTemplates.fetchTemplates)({
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
                    _reactToastify.toast.success((0, _useTranslation2.default)("Template was successfully deleted."));
                    dispatch((0, _fetchTemplates.fetchTemplates)({
                        page: page ? page : 1,
                        perPage: perPage
                    }));
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

    var handleDeleteTemplate = function handleDeleteTemplate(name, type, templateType) {
        dispatch((0, _deleteTemplate.deleteTemplate)(name, type, templateType));
    };

    if (!fetchedSuccess) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    var button = wp.element.createElement(
        _react2.default.Fragment,
        null,
        wp.element.createElement(_CreateTemplateModal2.default, {
            setModalVisible: setModalTemplateVisible,
            modalVisible: modalTemplateVisible,
            savedTemplates: fetched
        }),
        wp.element.createElement(
            "a",
            {
                href: "#",
                className: "acpt-btn acpt-btn-primary",
                onClick: function onClick(e) {
                    e.preventDefault();
                    setModalTemplateVisible(!modalTemplateVisible);
                }
            },
            (0, _useTranslation2.default)("Create template")
        )
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(_ActionsBar2.default, {
            title: (0, _useTranslation2.default)("Template list"),
            actions: button
        }),
        wp.element.createElement(
            "main",
            null,
            wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                    label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                    link: "/"
                }, {
                    label: (0, _useTranslation2.default)("Template list")
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
                                        (0, _useTranslation2.default)("Belongs to"),
                                        "\xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("Possible values: customPostType, taxonomy.") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        (0, _useTranslation2.default)("Element"),
                                        "\xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("The custom post type or taxonomy element.") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        (0, _useTranslation2.default)("Template"),
                                        "\xA0",
                                        wp.element.createElement(
                                            _Tippy2.default,
                                            { title: (0, _useTranslation2.default)("The template type. Possible values: archive, single, related, metaField.") },
                                            wp.element.createElement(
                                                "span",
                                                { className: "helper" },
                                                wp.element.createElement(_react3.Icon, { icon: "bx:bx-help-circle", width: "18px" })
                                            )
                                        )
                                    ),
                                    wp.element.createElement(
                                        "th",
                                        null,
                                        (0, _useTranslation2.default)("Actions")
                                    )
                                )
                            ),
                            wp.element.createElement(
                                "tbody",
                                null,
                                fetched.map(function (element) {
                                    return wp.element.createElement(_TemplateListElement2.default, { id: element.id, key: element.id, element: element, handleDeleteTemplate: handleDeleteTemplate });
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
                { className: "acpt-alert acpt-alert-warning" },
                (0, _useTranslation2.default)("No templates found.")
            )
        )
    );
};

exports["default"] = TemplateList;

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

/***/ 95393:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchTemplates = undefined;

var _ajax = __webpack_require__(47569);

var _fetchTemplatesActions = __webpack_require__(64904);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchTemplates = exports.fetchTemplates = function fetchTemplates(meta) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchTemplatesActions.fetchTemplatesInProgress)(meta));
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchTemplatesAction', meta ? meta : {});

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchTemplatesActions.fetchTemplatesSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchTemplatesActions.fetchTemplatesFailure)(_context.t0));

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

/***/ 72756:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.fetchTemplatesCount = undefined;

var _ajax = __webpack_require__(47569);

var _fetchTemplatesCountActions = __webpack_require__(16522);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var fetchTemplatesCount = exports.fetchTemplatesCount = function fetchTemplatesCount() {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _fetchTemplatesCountActions.fetchTemplatesCountInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('fetchTemplatesCountAction');

                        case 4:
                            fetched = _context.sent;

                            dispatch((0, _fetchTemplatesCountActions.fetchTemplatesCountSuccess)(fetched));
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _fetchTemplatesCountActions.fetchTemplatesCountFailure)(_context.t0));

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
//# sourceMappingURL=9969.js.map