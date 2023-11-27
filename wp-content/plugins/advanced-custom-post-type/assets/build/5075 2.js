"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[5075],{

/***/ 49908:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactHookForm = __webpack_require__(40930);

var _InputText = __webpack_require__(27388);

var _InputText2 = _interopRequireDefault(_InputText);

var _reactRedux = __webpack_require__(28216);

var _stepsActions = __webpack_require__(14576);

var _StepsButtons = __webpack_require__(2873);

var _StepsButtons2 = _interopRequireDefault(_StepsButtons);

var _Checkboxes = __webpack_require__(82184);

var _Checkboxes2 = _interopRequireDefault(_Checkboxes);

var _ReactSelect = __webpack_require__(92762);

var _ReactSelect2 = _interopRequireDefault(_ReactSelect);

var _dashicons = __webpack_require__(96921);

var _objects = __webpack_require__(54040);

var _strings = __webpack_require__(38029);

var _validation = __webpack_require__(39593);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _StepsHeader = __webpack_require__(43119);

var _StepsHeader2 = _interopRequireDefault(_StepsHeader);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

var _PostTypeIcon = __webpack_require__(73305);

var _PostTypeIcon2 = _interopRequireDefault(_PostTypeIcon);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var BasicStep = function BasicStep(_ref) {
    var postType = _ref.postType,
        headings = _ref.headings,
        edit = _ref.edit;

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetched = _useSelector.fetched;

    var dispatch = (0, _reactRedux.useDispatch)();

    // handle form
    var supports = [];
    if (fetched.length > 0) {
        supports = fetched[0].supports;
    }

    var _useForm = (0, _reactHookForm.useForm)({
        mode: 'all',
        defaultValues: {
            post_name: fetched.length > 0 ? fetched[0].name : null,
            singular_label: fetched.length > 0 ? fetched[0].singular : null,
            plural_label: fetched.length > 0 ? fetched[0].plural : null,
            icon: fetched.length > 0 ? fetched[0].icon : null
        }
    }),
        control = _useForm.control,
        register = _useForm.register,
        handleSubmit = _useForm.handleSubmit,
        setValue = _useForm.setValue,
        _useForm$formState = _useForm.formState,
        errors = _useForm$formState.errors,
        isValid = _useForm$formState.isValid;

    var handlePostNameChange = function handlePostNameChange(post_name) {
        setValue('post_name', (0, _strings.sluggifyString)(post_name, 20));
    };

    var onSubmit = function onSubmit(data) {
        dispatch((0, _stepsActions.stepForward)(data));
    };

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(
            "form",
            { onSubmit: handleSubmit(onSubmit) },
            wp.element.createElement(_ActionsBar2.default, {
                title: postType ? (0, _useTranslation2.default)("Edit Custom Post Type") : (0, _useTranslation2.default)("Create new Custom Post Type"),
                actions: wp.element.createElement(_StepsButtons2.default, { isValid: isValid, next: 2 })
            }),
            wp.element.createElement(
                "main",
                null,
                wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                        label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                        link: "/"
                    }, {
                        label: postType ? (0, _useTranslation2.default)("Edit Custom Post Type") : (0, _useTranslation2.default)("Create new Custom Post Type")
                    }] }),
                wp.element.createElement(
                    "div",
                    { className: "acpt-card" },
                    wp.element.createElement(_StepsHeader2.default, { headings: headings }),
                    wp.element.createElement(
                        "div",
                        { className: "acpt-card__inner" },
                        wp.element.createElement(_InputText2.default, {
                            id: "post_name",
                            label: (0, _useTranslation2.default)("Post name"),
                            placeholder: (0, _useTranslation2.default)("Post name"),
                            readOnly: fetched.length > 0,
                            defaultValue: fetched.length > 0 ? fetched[0].name : null,
                            description: (0, _useTranslation2.default)("The post name/slug. Used for various queries."),
                            register: register,
                            errors: errors,
                            isRequired: true,
                            onChangeCapture: function onChangeCapture(e) {
                                return handlePostNameChange(e.currentTarget.value);
                            },
                            validate: {
                                validate: edit ? _validation.isPostTypeNameValid : _validation.asyncIsPostTypeNameValid,
                                required: (0, _useTranslation2.default)("This field is mandatory")
                            } }),
                        wp.element.createElement(_InputText2.default, {
                            id: "singular_label",
                            label: (0, _useTranslation2.default)("Singular label"),
                            placeholder: (0, _useTranslation2.default)("(e.g. Movie)"),
                            defaultValue: fetched.length > 0 ? fetched[0].singular : null,
                            description: (0, _useTranslation2.default)("Used when a singular label is needed"),
                            register: register,
                            errors: errors,
                            isRequired: true,
                            validate: {
                                required: (0, _useTranslation2.default)("This field is mandatory")
                            } }),
                        wp.element.createElement(_InputText2.default, {
                            id: "plural_label",
                            label: (0, _useTranslation2.default)("Plural label"),
                            placeholder: (0, _useTranslation2.default)("(e.g. Movies)"),
                            defaultValue: fetched.length > 0 ? fetched[0].plural : null,
                            description: (0, _useTranslation2.default)("Used for the post type admin menu item"),
                            register: register,
                            errors: errors,
                            isRequired: true,
                            validate: {
                                required: (0, _useTranslation2.default)("This field is mandatory")
                            } }),
                        wp.element.createElement(_PostTypeIcon2.default, {
                            id: "icon",
                            label: (0, _useTranslation2.default)("Icon"),
                            placeholder: (0, _useTranslation2.default)("Associated icon"),
                            description: (0, _useTranslation2.default)("Displayed on the sidebar of the admin panel"),
                            register: register,
                            errors: errors,
                            defaultValue: fetched.length > 0 ? fetched[0].icon : null,
                            setValue: setValue,
                            isRequired: true,
                            validate: {
                                required: (0, _useTranslation2.default)("This field is mandatory")
                            }
                        }),
                        wp.element.createElement(_Checkboxes2.default, {
                            id: "support",
                            label: (0, _useTranslation2.default)("Support"),
                            wizard: (0, _useTranslation2.default)("Add support for various available post edit features. For more info") + " <a target='_blank' href='https://developer.wordpress.org/reference/functions/register_post_type/#supports'>" + (0, _useTranslation2.default)("see here") + "<a/>.",
                            values: {
                                "title": {
                                    "value": "title",
                                    "checked": fetched.length > 0 ? supports.includes('title') : true
                                },
                                "editor": {
                                    "value": "editor",
                                    "checked": fetched.length > 0 ? supports.includes('editor') : true
                                },
                                "thumbnail": {
                                    "value": "thumbnail",
                                    "checked": fetched.length > 0 ? supports.includes('thumbnail') : true
                                },
                                "excerpt": {
                                    "value": "excerpt",
                                    "checked": fetched.length > 0 ? supports.includes('excerpt') : true
                                },
                                "author": {
                                    "value": "author",
                                    "checked": fetched.length > 0 ? supports.includes('author') : false
                                },
                                "trackbacks": {
                                    "value": "trackbacks",
                                    "checked": fetched.length > 0 ? supports.includes('trackbacks') : false
                                },
                                "custom-fields": {
                                    "value": "custom-fields",
                                    "checked": fetched.length > 0 ? supports.includes('custom-fields') : false
                                },
                                "comments": {
                                    "value": "comments",
                                    "checked": fetched.length > 0 ? supports.includes('comments') : false
                                },
                                "revisions": {
                                    "value": "revisions",
                                    "checked": fetched.length > 0 ? supports.includes('revisions') : false
                                },
                                "page-attributes": {
                                    "value": "page-attributes",
                                    "checked": fetched.length > 0 ? supports.includes('page-attributes') : false
                                },
                                "post-formats": {
                                    "value": "post-formats",
                                    "checked": fetched.length > 0 ? supports.includes('post-formats') : false
                                }
                            },
                            register: register,
                            errors: errors
                        })
                    )
                )
            )
        )
    );
};

exports["default"] = BasicStep;

/***/ }),

/***/ 69099:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactHookForm = __webpack_require__(40930);

var _InputText = __webpack_require__(27388);

var _InputText2 = _interopRequireDefault(_InputText);

var _StepsButtons = __webpack_require__(2873);

var _StepsButtons2 = _interopRequireDefault(_StepsButtons);

var _reactRedux = __webpack_require__(28216);

var _stepsActions = __webpack_require__(14576);

var _label = __webpack_require__(4384);

var _localization = __webpack_require__(48525);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _StepsHeader = __webpack_require__(43119);

var _StepsHeader2 = _interopRequireDefault(_StepsHeader);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var AdditionalLabelsStep = function AdditionalLabelsStep(_ref) {
    var postType = _ref.postType,
        headings = _ref.headings;

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.stepsReducer;
    }),
        stepsData = _useSelector.data,
        activeStep = _useSelector.activeStep;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetched = _useSelector2.fetched;

    var dispatch = (0, _reactRedux.useDispatch)();

    // handle form
    var labels = {};
    if (fetched.length > 0) {
        labels = fetched[0].labels;
    }

    var _useForm = (0, _reactHookForm.useForm)({
        mode: 'all'
    }),
        register = _useForm.register,
        handleSubmit = _useForm.handleSubmit,
        _useForm$formState = _useForm.formState,
        errors = _useForm$formState.errors,
        isValid = _useForm$formState.isValid,
        setValue = _useForm.setValue;

    // form default values


    (0, _react.useEffect)(function () {
        if (stepsData[1]) {
            setValue('menu_name', fetched.length > 0 ? labels.menu_name : stepsData[1].singular_label);
            setValue('all_items', fetched.length > 0 ? labels.all_items : "" + (0, _localization.translate)("general.labels.all_items", { r: stepsData[1].plural_label }));
            setValue('add_new', fetched.length > 0 ? labels.add_new : (0, _localization.translate)("general.labels.add") + " " + stepsData[1].singular_label);
            setValue('add_new_item', fetched.length > 0 ? labels.add_new_item : (0, _localization.translate)("general.labels.add") + " " + stepsData[1].singular_label);
            setValue('edit_item', fetched.length > 0 ? labels.edit_item : (0, _localization.translate)("general.labels.edit") + " " + stepsData[1].singular_label);
            setValue('new_item', fetched.length > 0 ? labels.new_item : (0, _localization.translate)("general.labels.new") + " " + stepsData[1].singular_label);
            setValue('view_item', fetched.length > 0 ? labels.view_item : (0, _localization.translate)("general.labels.view") + " " + stepsData[1].singular_label);
            setValue('view_items', fetched.length > 0 ? labels.view_items : (0, _localization.translate)("general.labels.view") + " " + stepsData[1].plural_label);
            setValue('search_item', fetched.length > 0 ? labels.search_item : (0, _localization.translate)("general.labels.search") + " " + stepsData[1].plural_label);
            setValue('not_found', fetched.length > 0 ? labels.not_found : (0, _localization.translate)("general.labels.not_found", { r: stepsData[1].singular_label }));
            setValue('not_found_in_trash', fetched.length > 0 ? labels.not_found_in_trash : (0, _localization.translate)("general.labels.not_found", { r: stepsData[1].singular_label }));
            setValue('parent_item_colon', fetched.length > 0 ? labels.parent_item_colon : (0, _localization.translate)("general.labels.parent_item_colon"));
            setValue('featured_image', fetched.length > 0 ? labels.featured_image : (0, _localization.translate)("general.labels.featured_image"));
            setValue('set_featured_image', fetched.length > 0 ? labels.set_featured_image : (0, _localization.translate)("general.labels.set_featured_image"));
            setValue('remove_featured_image', fetched.length > 0 ? labels.remove_featured_image : (0, _localization.translate)("general.labels.remove_featured_image"));
            setValue('use_featured_image', fetched.length > 0 ? labels.use_featured_image : (0, _localization.translate)("general.labels.use_featured_image"));
            setValue('archives', fetched.length > 0 ? labels.archives : (0, _localization.translate)("general.labels.archives"));
            setValue('insert_into_item', fetched.length > 0 ? labels.insert_into_item : (0, _localization.translate)("general.labels.insert_into_item"));
            setValue('uploaded_to_this_item', fetched.length > 0 ? labels.uploaded_to_this_item : (0, _localization.translate)("general.labels.uploaded_to_this_item"));
            setValue('filter_items_list', fetched.length > 0 ? labels.filter_items_list : (0, _localization.translate)("general.labels.filter_items_list", { r: stepsData[1].plural_label }));
            setValue('items_list_navigation', fetched.length > 0 ? labels.items_list_navigation : (0, _localization.translate)("general.labels.items_list_navigation", { r: stepsData[1].plural_label }));
            setValue('items_list', fetched.length > 0 ? labels.items_list : (0, _localization.translate)("general.labels.items_list", { r: stepsData[1].plural_label }));
            setValue('filter_by_date', fetched.length > 0 ? labels.filter_by_date : (0, _localization.translate)("general.labels.filter_by_date"));
            setValue('item_published', fetched.length > 0 ? labels.item_published : (0, _localization.translate)("general.labels.item_published", { r: stepsData[1].singular_label }));
            setValue('item_published_privately', fetched.length > 0 ? labels.item_published_privately : (0, _localization.translate)("general.labels.item_published_privately", { r: stepsData[1].singular_label }));
            setValue('item_reverted_to_draft', fetched.length > 0 ? labels.item_reverted_to_draft : (0, _localization.translate)("general.labels.item_reverted_to_draft", { r: stepsData[1].singular_label }));
            setValue('item_scheduled', fetched.length > 0 ? labels.item_scheduled : (0, _localization.translate)("general.labels.item_scheduled", { r: stepsData[1].singular_label }));
            setValue('item_updated', fetched.length > 0 ? labels.item_updated : (0, _localization.translate)("general.labels.item_updated", { r: stepsData[1].singular_label }));
        }
    }, [activeStep]);

    var onSubmit = function onSubmit(data) {
        dispatch((0, _stepsActions.stepForward)(data));
    };

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(
            "form",
            { onSubmit: handleSubmit(onSubmit) },
            wp.element.createElement(_ActionsBar2.default, {
                title: postType ? (0, _useTranslation2.default)("Edit Custom Post Type") : (0, _useTranslation2.default)("Create new Custom Post Type"),
                actions: wp.element.createElement(_StepsButtons2.default, { isValid: isValid, next: 3, prev: 1 })
            }),
            wp.element.createElement(
                "main",
                null,
                wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                        label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                        link: "/"
                    }, {
                        label: postType ? (0, _useTranslation2.default)("Edit Custom Post Type") : (0, _useTranslation2.default)("Create new Custom Post Type")
                    }] }),
                wp.element.createElement(
                    "div",
                    { className: "acpt-card" },
                    wp.element.createElement(_StepsHeader2.default, { headings: headings }),
                    wp.element.createElement(
                        "div",
                        { className: "acpt-card__inner" },
                        _label.postLabelsList.map(function (item) {
                            return wp.element.createElement(_InputText2.default, {
                                id: item.id,
                                label: (0, _useTranslation2.default)(item.label),
                                placeholder: (0, _useTranslation2.default)(item.label),
                                register: register,
                                errors: errors,
                                description: (0, _useTranslation2.default)(item.description),
                                validate: {
                                    maxLength: {
                                        value: 255,
                                        message: (0, _useTranslation2.default)("min length is 255")
                                    }
                                }
                            });
                        })
                    )
                )
            )
        )
    );
};

exports["default"] = AdditionalLabelsStep;

/***/ }),

/***/ 99814:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactHookForm = __webpack_require__(40930);

var _InputText = __webpack_require__(27388);

var _InputText2 = _interopRequireDefault(_InputText);

var _reactRedux = __webpack_require__(28216);

var _StepsButtons = __webpack_require__(2873);

var _StepsButtons2 = _interopRequireDefault(_StepsButtons);

var _stepsSubmit = __webpack_require__(19218);

var _InputSwitch = __webpack_require__(28195);

var _InputSwitch2 = _interopRequireDefault(_InputSwitch);

var _InputNumber = __webpack_require__(51759);

var _InputNumber2 = _interopRequireDefault(_InputNumber);

var _reactToastify = __webpack_require__(39249);

var _reactRouterDom = __webpack_require__(73727);

var _misc = __webpack_require__(53154);

var _validation = __webpack_require__(39593);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _Breadcrumbs = __webpack_require__(95827);

var _Breadcrumbs2 = _interopRequireDefault(_Breadcrumbs);

var _StepsHeader = __webpack_require__(43119);

var _StepsHeader2 = _interopRequireDefault(_StepsHeader);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var OtherSettingsStep = function OtherSettingsStep(_ref) {
    var postType = _ref.postType,
        headings = _ref.headings,
        isWPGraphQLActive = _ref.isWPGraphQLActive,
        setPristineHandler = _ref.setPristineHandler;


    // manage redux state
    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.stepsReducer;
    }),
        stepsData = _useSelector.data,
        activeStep = _useSelector.activeStep,
        stepsErrors = _useSelector.errors,
        success = _useSelector.success,
        loading = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchPostTypesReducer;
    }),
        fetched = _useSelector2.fetched;

    // manage local state


    var didMountRef = (0, _react.useRef)(false);

    // manage redirect
    var history = (0, _reactRouterDom.useHistory)();

    // handle form
    var settings = {};
    if (fetched.length > 0) {
        settings = fetched[0].settings;
    }

    var _useForm = (0, _reactHookForm.useForm)({
        mode: 'all',
        defaultValues: {
            public: fetched.length > 0 ? settings.public : null,
            publicly_queryable: fetched.length > 0 ? settings.publicly_queryable : null,
            show_ui: fetched.length > 0 ? settings.show_ui : null,
            show_in_menu: fetched.length > 0 ? settings.show_in_menu : null,
            show_in_nav_menus: fetched.length > 0 ? settings.show_in_nav_menus : null,
            show_in_admin_bar: fetched.length > 0 ? settings.show_in_admin_bar : null,
            show_in_rest: fetched.length > 0 ? settings.show_in_rest : null,
            rest_base: fetched.length > 0 ? settings.rest_base : null,
            menu_position: fetched.length > 0 ? settings.menu_position : null,
            capability_type: fetched.length > 0 ? settings.capability_type : 'post',
            has_archive: fetched.length > 0 ? settings.has_archive : null,
            rewrite: fetched.length > 0 ? settings.rewrite : null,
            custom_rewrite: fetched.length > 0 ? settings.custom_rewrite : null,
            query_var: fetched.length > 0 ? settings.query_var : null,
            custom_query_var: fetched.length > 0 ? settings.custom_query_var : null
        }
    }),
        register = _useForm.register,
        handleSubmit = _useForm.handleSubmit,
        setValue = _useForm.setValue,
        setError = _useForm.setError,
        _useForm$formState = _useForm.formState,
        errors = _useForm$formState.errors,
        isValid = _useForm$formState.isValid,
        watch = _useForm.watch;

    // GRAPHQL Integration


    (0, _react.useEffect)(function () {
        if (stepsData[1]) {
            setValue("show_in_graphql", fetched.length > 0 ? settings.show_in_graphql : true);
            setValue("graphql_single_name", fetched.length > 0 ? settings.graphql_single_name : stepsData[1].singular_label);
            setValue("graphql_plural_name", fetched.length > 0 ? settings.graphql_plural_name : stepsData[1].plural_label);
        }
    }, [activeStep]);

    var showInGraphql = watch('show_in_graphql');
    var graphqlSingleName = watch('graphql_single_name');
    var graphqlPluralName = watch('graphql_plural_name');

    var handleGraphQLSingleNameChange = function handleGraphQLSingleNameChange(single_name) {
        if (single_name === graphqlPluralName) {
            return (0, _useTranslation2.default)('Single name MUST be different from plural name');
        }
    };

    var handleGraphQLPluralNameChange = function handleGraphQLPluralNameChange(plural_name) {
        if (plural_name === graphqlSingleName) {
            return (0, _useTranslation2.default)('Different name MUST be different from single name');
        }
    };

    // submit data
    var onSubmit = function () {
        var _ref2 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(data) {
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            setPristineHandler();
                            _context.next = 3;
                            return dispatch((0, _stepsSubmit.stepsSubmit)('saveCustomPostTypeAction', data));

                        case 3:
                        case "end":
                            return _context.stop();
                    }
                }
            }, _callee, undefined);
        }));

        return function onSubmit(_x) {
            return _ref2.apply(this, arguments);
        };
    }();

    // handle form submission outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loading) {
                if (success) {
                    history.push('/');
                    _reactToastify.toast.success((0, _useTranslation2.default)("Custom post type successfully saved. The browser will refresh after 5 seconds."));
                    (0, _misc.refreshPage)(5000);
                }

                if (stepsErrors.length > 0) {
                    stepsErrors.map(function (error) {
                        _reactToastify.toast.error(error);
                    });
                }
            }
        } else {
            didMountRef.current = true;
        }
    }, [loading]);

    var rewrite = watch("rewrite");
    var query_var = watch("query_var");

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(
            "form",
            { onSubmit: handleSubmit(onSubmit) },
            wp.element.createElement(_ActionsBar2.default, {
                title: postType ? (0, _useTranslation2.default)("Edit Custom Post Type") : (0, _useTranslation2.default)("Create new Custom Post Type"),
                actions: wp.element.createElement(_StepsButtons2.default, { isValid: isValid, prev: 2 })
            }),
            wp.element.createElement(
                "main",
                null,
                wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                        label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                        link: "/"
                    }, {
                        label: postType ? (0, _useTranslation2.default)("Edit Custom Post Type") : (0, _useTranslation2.default)("Create new Custom Post Type")
                    }] }),
                wp.element.createElement(
                    "div",
                    { className: "acpt-card" },
                    wp.element.createElement(_StepsHeader2.default, { headings: headings }),
                    isWPGraphQLActive && wp.element.createElement(
                        "div",
                        { className: "wpgraphql-wrapper" },
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "show_in_graphql",
                            label: (0, _useTranslation2.default)("Show in GraphQL"),
                            isRequired: true,
                            description: (0, _useTranslation2.default)("Show the custom post type in WPGraphQL."),
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputText2.default, {
                            id: "graphql_single_name",
                            label: (0, _useTranslation2.default)("GraphQL single name"),
                            placeholder: (0, _useTranslation2.default)("Ex. movie"),
                            register: register,
                            errors: errors,
                            isRequired: showInGraphql,
                            description: (0, _useTranslation2.default)("Camel case string with no punctuation or spaces. Needs to start with a letter (not a number). Important to be different than the plural name."),
                            validate: {
                                validate: {
                                    validWPGraphQLName: _validation.validWPGraphQLName,
                                    handleGraphQLSingleNameChange: handleGraphQLSingleNameChange
                                }
                            }
                        }),
                        wp.element.createElement(_InputText2.default, {
                            id: "graphql_plural_name",
                            label: (0, _useTranslation2.default)("GraphQL plural name"),
                            placeholder: (0, _useTranslation2.default)("Ex. movies"),
                            register: register,
                            errors: errors,
                            isRequired: showInGraphql,
                            description: (0, _useTranslation2.default)("Camel case string with no punctuation or spaces. Needs to start with a letter (not a number). Important to be different than the single name."),
                            validate: {
                                validate: {
                                    validWPGraphQLName: _validation.validWPGraphQLName,
                                    handleGraphQLPluralNameChange: handleGraphQLPluralNameChange
                                }
                            }
                        })
                    ),
                    wp.element.createElement(
                        "div",
                        { className: "acpt-card__inner" },
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "public",
                            label: (0, _useTranslation2.default)("Is Public"),
                            description: (0, _useTranslation2.default)("Whether a post type is intended for use publicly either via the admin interface or by front-end users."),
                            defaultValue: fetched.length > 0 ? settings.public : true,
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "publicly_queryable",
                            label: (0, _useTranslation2.default)("Publicly queryable"),
                            description: (0, _useTranslation2.default)("Whether queries can be performed on the front end for the post type as part of parse_request()."),
                            defaultValue: fetched.length > 0 ? settings.publicly_queryable : true,
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "show_ui",
                            label: "Show in UI",
                            description: "Whether to generate and allow a UI for managing this post type in the admin. Default is value of $public.",
                            defaultValue: fetched.length > 0 ? settings.show_ui : true,
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "show_in_menu",
                            label: (0, _useTranslation2.default)("Show in menu"),
                            description: (0, _useTranslation2.default)("Where to show the post type in the admin menu. To work, $show_ui must be true. If true, the post type is shown in its own top level menu. If false, no menu is shown."),
                            defaultValue: fetched.length > 0 ? settings.show_in_menu : true,
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "show_in_nav_menus",
                            label: (0, _useTranslation2.default)("Show in nav menus"),
                            description: (0, _useTranslation2.default)("Makes this post type available for selection in navigation menus. Default is value of $public."),
                            defaultValue: fetched.length > 0 ? settings.show_in_nav_menus : true,
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "show_in_admin_bar",
                            label: (0, _useTranslation2.default)("Show in admin bar"),
                            description: (0, _useTranslation2.default)("Makes this post type available via the admin bar. Default is value of $show_in_menu."),
                            defaultValue: fetched.length > 0 ? settings.show_in_admin_bar : true,
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "show_in_rest",
                            label: (0, _useTranslation2.default)("Show in REST API"),
                            description: (0, _useTranslation2.default)("Whether to include the post type in the REST API. Set this to true for the post type to be available in the block editor. SET TRUE TO ENABLE GUTEMBERG EDITOR."),
                            defaultValue: fetched.length > 0 ? settings.show_in_rest : true,
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputText2.default, {
                            id: "rest_base",
                            label: (0, _useTranslation2.default)("REST API base slug"),
                            placeholder: (0, _useTranslation2.default)("REST API base slug"),
                            register: register,
                            errors: errors,
                            description: (0, _useTranslation2.default)("To change the base url of REST API route. Default is $post_type."),
                            validate: {
                                maxLength: {
                                    value: 255,
                                    message: (0, _useTranslation2.default)("min length is 255")
                                }
                            }
                        }),
                        wp.element.createElement(_InputNumber2.default, {
                            id: "menu_position",
                            min: "1",
                            max: "100",
                            label: (0, _useTranslation2.default)("Menu position"),
                            placeholder: (0, _useTranslation2.default)("Menu position"),
                            register: register,
                            errors: errors,
                            description: (0, _useTranslation2.default)("The position in the menu order the post type should appear. To work, $show_in_menu must be true. Default null (at the bottom)."),
                            validate: {
                                min: {
                                    value: 1,
                                    message: (0, _useTranslation2.default)("min length is 1")
                                },
                                max: {
                                    value: 100,
                                    message: (0, _useTranslation2.default)("max length is 100")
                                }
                            }
                        }),
                        wp.element.createElement(_InputText2.default, {
                            id: "capability_type",
                            label: (0, _useTranslation2.default)("Capability type"),
                            placeholder: (0, _useTranslation2.default)("Capability type"),
                            register: register,
                            errors: errors,
                            defaultValue: "post",
                            description: (0, _useTranslation2.default)("The string to use to build the read, edit, and delete capabilities. May be passed as an array to allow for alternative plurals when using this argument as a base to construct the capabilities, e.g. array('story', 'stories'). Default 'post'."),
                            validate: {
                                maxLength: {
                                    value: 255,
                                    message: "min length is 255"
                                }
                            }
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "has_archive",
                            label: (0, _useTranslation2.default)("Has archive"),
                            description: (0, _useTranslation2.default)("Whether there should be post type archives, or if a string, the archive slug to use. Will generate the proper rewrite rules if $rewrite is enabled."),
                            defaultValue: fetched.length > 0 ? settings.has_archive : true,
                            register: register,
                            errors: errors
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "rewrite",
                            label: (0, _useTranslation2.default)("Rewrite"),
                            description: (0, _useTranslation2.default)("Whether there should be post type archives, or if a string, the archive slug to use. Will generate the proper rewrite rules if $rewrite is enabled."),
                            defaultValue: fetched.length > 0 ? settings.rewrite : false,
                            register: register,
                            errors: errors
                        }),
                        rewrite && wp.element.createElement(_InputText2.default, {
                            id: "custom_rewrite",
                            label: (0, _useTranslation2.default)("Custom rewrite rules"),
                            placeholder: (0, _useTranslation2.default)("Custom rewrite rules"),
                            register: register,
                            errors: errors,
                            description: (0, _useTranslation2.default)("Custom post type slug to use instead of default."),
                            validate: {
                                maxLength: {
                                    value: 255,
                                    message: (0, _useTranslation2.default)("min length is 255")
                                }
                            }
                        }),
                        wp.element.createElement(_InputSwitch2.default, {
                            id: "query_var",
                            label: (0, _useTranslation2.default)("Query var"),
                            description: (0, _useTranslation2.default)("Sets the query_var key for this post type. Defaults to $post_type key. If false, a post type cannot be loaded at ?{query_var}={post_slug}. If specified as a string, the query ?{query_var_string}={post_slug} will be valid."),
                            defaultValue: fetched.length > 0 ? settings.query_var : false,
                            register: register,
                            errors: errors
                        }),
                        query_var && wp.element.createElement(_InputText2.default, {
                            id: "custom_query_var",
                            label: (0, _useTranslation2.default)("Custom query var"),
                            placeholder: (0, _useTranslation2.default)("Custom query var"),
                            register: register,
                            errors: errors,
                            description: (0, _useTranslation2.default)("Custom query var slug to use instead of default."),
                            validate: {
                                maxLength: {
                                    value: 255,
                                    message: (0, _useTranslation2.default)("min length is 255")
                                }
                            }
                        })
                    )
                )
            )
        )
    );
};

exports["default"] = OtherSettingsStep;

/***/ }),

/***/ 25075:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _Settings = __webpack_require__(99814);

var _Settings2 = _interopRequireDefault(_Settings);

var _Basic = __webpack_require__(49908);

var _Basic2 = _interopRequireDefault(_Basic);

var _Labels = __webpack_require__(69099);

var _Labels2 = _interopRequireDefault(_Labels);

var _Steps = __webpack_require__(95832);

var _Steps2 = _interopRequireDefault(_Steps);

var _steps = __webpack_require__(99500);

var _misc = __webpack_require__(53154);

var _reactRouterDom = __webpack_require__(73727);

var _reactRedux = __webpack_require__(28216);

var _fetchPostTypes = __webpack_require__(14825);

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _resetPostTypes = __webpack_require__(13648);

var _useUnsavedChangesWarning = __webpack_require__(49755);

var _useUnsavedChangesWarning2 = _interopRequireDefault(_useUnsavedChangesWarning);

var _stepsActions = __webpack_require__(14576);

var _forms = __webpack_require__(39207);

var _ajax = __webpack_require__(47569);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var SaveCustomPostType = function SaveCustomPostType() {

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

    var _useParams2 = (0, _reactRouterDom.useParams)(),
        step = _useParams2.step;

    var _useUnsavedChangesWar = (0, _useUnsavedChangesWarning2.default)(),
        _useUnsavedChangesWar2 = _slicedToArray(_useUnsavedChangesWar, 3),
        Prompt = _useUnsavedChangesWar2[0],
        setDirty = _useUnsavedChangesWar2[1],
        setPristine = _useUnsavedChangesWar2[2];

    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        edit = _useState2[0],
        isEdit = _useState2[1];

    var didMountRef = (0, _react.useRef)(false);

    var _useState3 = (0, _react.useState)(null),
        _useState4 = _slicedToArray(_useState3, 2),
        fetchedSuccess = _useState4[0],
        setFetchedSuccess = _useState4[1];

    var _useState5 = (0, _react.useState)(false),
        _useState6 = _slicedToArray(_useState5, 2),
        isWPGraphQLActive = _useState6[0],
        setIsWPGraphQLActive = _useState6[1];

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

    // handle fetch outcome
    (0, _react.useEffect)(function () {
        if (didMountRef.current) {
            if (!loading) {
                setFetchedSuccess(true);
            }
        } else {
            didMountRef.current = true;
        }
    }, [loading]);

    (0, _react.useEffect)(function () {
        if (postType) {
            (0, _misc.metaTitle)((0, _useTranslation2.default)("Edit Custom Post Type"));
            dispatch((0, _fetchPostTypes.fetchPostTypes)({
                postType: postType
            }));

            isEdit(true);

            if (step) {
                if (fetchedSuccess) {
                    var stepInt = parseInt(step);
                    dispatch((0, _stepsActions.startFromStep)(stepInt, (0, _forms.hydratePostTypeFormFromStep)(stepInt, fetched[0])));
                }
            } else {
                dispatch((0, _stepsActions.stepReset)());
            }
        } else {
            (0, _misc.metaTitle)((0, _useTranslation2.default)("Register new Post Type"));
            (0, _misc.changeCurrentAdminMenuLink)('#/register');
            dispatch((0, _resetPostTypes.resetPostTypes)());
            dispatch((0, _stepsActions.stepReset)());
        }
        setDirty();
    }, [fetchedSuccess]);

    var setPristineHandler = function setPristineHandler() {
        setPristine();
    };

    var steps = [wp.element.createElement(_Basic2.default, { postType: postType, edit: edit }), wp.element.createElement(_Labels2.default, { postType: postType }), wp.element.createElement(_Settings2.default, { postType: postType, isWPGraphQLActive: isWPGraphQLActive, setPristineHandler: setPristineHandler })];

    if (!fetchedSuccess) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    return wp.element.createElement(
        _react2.default.Fragment,
        null,
        Prompt,
        wp.element.createElement(_Steps2.default, {
            headings: _steps.saveCustomPostTypeHeadings,
            steps: steps
        })
    );
};

exports["default"] = SaveCustomPostType;

/***/ }),

/***/ 51759:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var InputNumber = function InputNumber(_ref) {
    var id = _ref.id,
        label = _ref.label,
        placeholder = _ref.placeholder,
        validate = _ref.validate,
        register = _ref.register,
        errors = _ref.errors,
        min = _ref.min,
        max = _ref.max,
        step = _ref.step,
        isRequired = _ref.isRequired,
        description = _ref.description,
        wizard = _ref.wizard;


    var error = errors[id];

    return wp.element.createElement(
        "div",
        { className: "acpt-form-group" },
        wp.element.createElement(
            "div",
            { className: "acpt-form-label-wrapper" },
            wp.element.createElement(
                "label",
                { htmlFor: id },
                label,
                isRequired && wp.element.createElement(
                    "span",
                    { className: "required" },
                    "*"
                )
            ),
            wizard && wp.element.createElement("span", { className: "wizard", dangerouslySetInnerHTML: { __html: wizard } })
        ),
        wp.element.createElement(
            "div",
            { className: "acpt-form-control-wrapper" },
            wp.element.createElement("input", _extends({
                id: id,
                name: id,
                type: "number",
                min: min ? min : 0,
                max: max,
                step: step ? min : 1,
                placeholder: placeholder,
                required: isRequired,
                "aria-invalid": error ? "true" : "false",
                className: "acpt-form-control " + (error ? 'has-errors' : '')
            }, register(id, validate))),
            description && wp.element.createElement(
                "span",
                { className: "description" },
                description
            ),
            error && wp.element.createElement(
                "div",
                { className: "invalid-feedback" },
                error.message
            )
        )
    );
};

exports["default"] = InputNumber;

/***/ }),

/***/ 73305:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _IconPicker = __webpack_require__(69550);

var _IconPicker2 = _interopRequireDefault(_IconPicker);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var PostTypeIcon = function PostTypeIcon(_ref) {
    var id = _ref.id,
        label = _ref.label,
        setValue = _ref.setValue,
        validate = _ref.validate,
        register = _ref.register,
        errors = _ref.errors,
        isRequired = _ref.isRequired,
        defaultValue = _ref.defaultValue,
        description = _ref.description,
        wizard = _ref.wizard;


    var error = errors[id];

    /**
     * set icon value
     * @param value
     */
    var callbackIcon = function callbackIcon(value) {
        setValue(id, value);
    };

    return wp.element.createElement(
        "div",
        { className: "acpt-form-group" },
        wp.element.createElement(
            "div",
            { className: "acpt-form-label-wrapper" },
            wp.element.createElement(
                "label",
                { htmlFor: id },
                label,
                isRequired && wp.element.createElement(
                    "span",
                    { className: "required" },
                    "*"
                )
            ),
            wizard && wp.element.createElement("span", { className: "wizard", dangerouslySetInnerHTML: { __html: wizard } })
        ),
        wp.element.createElement(
            "div",
            { className: "acpt-form-control-wrapper" },
            wp.element.createElement(_IconPicker2.default, {
                error: error,
                id: id,
                defaultValue: defaultValue,
                register: register,
                callback: callbackIcon,
                validate: validate
            }),
            description && wp.element.createElement(
                "span",
                { className: "description" },
                description
            ),
            error && wp.element.createElement(
                "div",
                { className: "invalid-feedback" },
                error.message
            )
        )
    );
};

exports["default"] = PostTypeIcon;

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

/***/ 13648:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.resetPostTypes = undefined;

var _fetchCustomPostTypesActions = __webpack_require__(8912);

var _ajax = __webpack_require__(47569);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var resetPostTypes = exports.resetPostTypes = function resetPostTypes() {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var fetched;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            dispatch((0, _fetchCustomPostTypesActions.resetPostTypesInProgress)());
                            _context.next = 3;
                            return (0, _ajax.wpAjaxRequest)('resetCustomPostTypesAction');

                        case 3:
                            fetched = _context.sent;

                            dispatch((0, _fetchCustomPostTypesActions.resetPostTypesSuccess)());

                        case 5:
                        case "end":
                            return _context.stop();
                    }
                }
            }, _callee, undefined);
        }));

        return function (_x, _x2) {
            return _ref.apply(this, arguments);
        };
    }();
};

/***/ })

}]);
//# sourceMappingURL=5075.js.map