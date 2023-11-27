"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[4938],{

/***/ 54938:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



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

var _Spinner = __webpack_require__(17410);

var _Spinner2 = _interopRequireDefault(_Spinner);

var _Layout = __webpack_require__(73067);

var _Layout2 = _interopRequireDefault(_Layout);

var _ActionsBar = __webpack_require__(43700);

var _ActionsBar2 = _interopRequireDefault(_ActionsBar);

var _reactToastify = __webpack_require__(39249);

var _objects = __webpack_require__(54040);

var _lists = __webpack_require__(93021);

var _languages = __webpack_require__(69504);

var _reactHookForm = __webpack_require__(40930);

var _saveSettings = __webpack_require__(59210);

var _ReactSelect = __webpack_require__(92762);

var _ReactSelect2 = _interopRequireDefault(_ReactSelect);

var _InputNumber = __webpack_require__(51759);

var _InputNumber2 = _interopRequireDefault(_InputNumber);

var _InputText = __webpack_require__(27388);

var _InputText2 = _interopRequireDefault(_InputText);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Settings = function Settings() {

    // manage global state
    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.saveSettingsReducer;
    }),
        saveErrors = _useSelector.errors,
        saveSuccess = _useSelector.success,
        saveLoading = _useSelector.loading;

    var _useSelector2 = (0, _reactRedux.useSelector)(function (state) {
        return state.fetchSettingsReducer;
    }),
        loading = _useSelector2.loading,
        fetched = _useSelector2.fetched;

    var dispatch = (0, _reactRedux.useDispatch)();

    var _useState = (0, _react.useState)(false),
        _useState2 = _slicedToArray(_useState, 2),
        isFormSubmitted = _useState2[0],
        setFormSubmitted = _useState2[1];

    // form & handle form functions

    /**
     *
     * @param key
     * @return {string|boolean}
     */


    var validateGoogleMapsApiKey = function validateGoogleMapsApiKey(key) {

        if (typeof key === 'undefined' || key === null || key === '' || key.length === 0) {
            return true;
        }

        if (key.length !== 39) {
            return (0, _useTranslation2.default)('Key length must be 39.');
        }

        var regx = new RegExp("^[A-Za-z0-9-_]+$");

        if (!regx.test(key)) {
            return (0, _useTranslation2.default)('Not valid format.');
        }

        return true;
    };

    var onSubmit = function onSubmit(data) {
        dispatch((0, _saveSettings.saveSettings)({
            records_per_page: data.records_per_page,
            enable_visual_editor: data.enable_visual_editor ? data.enable_visual_editor.value : null,
            delete_tables_when_deactivate: data.delete_tables_when_deactivate ? data.delete_tables_when_deactivate.value : null,
            delete_posts: data.delete_posts ? data.delete_posts.value : null,
            delete_metadata: data.delete_metadata ? data.delete_metadata.value : null,
            google_maps_api_key: data.google_maps_api_key,
            language: data.language ? data.language.value : null
        }));
        setFormSubmitted(true);
    };

    (0, _react.useEffect)(function () {
        if (isFormSubmitted && saveSuccess) {
            _reactToastify.toast.success((0, _useTranslation2.default)("Settings saved. The browser will refresh after 5 seconds."));
            (0, _misc.refreshPage)(5000);
        }

        if (isFormSubmitted && saveErrors.length > 0) {
            saveErrors.map(function (error) {
                return _reactToastify.toast.error(error);
            });
        }

        if (isFormSubmitted) {
            setFormSubmitted(false);
        }
    }, [saveLoading]);

    var enableVisualEditorDefaultValue = fetched.length > 0 && typeof (0, _objects.filterByLabel)(fetched, 'key', 'enable_visual_editor') !== 'undefined' ? (0, _objects.filterByValue)(_lists.yesOrNoList, (0, _objects.filterByLabel)(fetched, 'key', 'enable_visual_editor').value) : { label: (0, _useTranslation2.default)("Select"), value: null };
    var deletePostsDefaultValue = fetched.length > 0 && typeof (0, _objects.filterByLabel)(fetched, 'key', 'delete_posts') !== 'undefined' ? (0, _objects.filterByValue)(_lists.yesOrNoList, (0, _objects.filterByLabel)(fetched, 'key', 'delete_posts').value) : { label: (0, _useTranslation2.default)("Select"), value: null };
    var deleteMetadataDefaultValue = fetched.length > 0 && typeof (0, _objects.filterByLabel)(fetched, 'key', 'delete_metadata') !== 'undefined' ? (0, _objects.filterByValue)(_lists.yesOrNoList, (0, _objects.filterByLabel)(fetched, 'key', 'delete_metadata').value) : { label: (0, _useTranslation2.default)("Select"), value: null };
    var deleteDefinitionsDefaultValue = fetched.length > 0 && typeof (0, _objects.filterByLabel)(fetched, 'key', 'delete_tables_when_deactivate') !== 'undefined' ? (0, _objects.filterByValue)(_lists.yesOrNoList, (0, _objects.filterByLabel)(fetched, 'key', 'delete_tables_when_deactivate').value) : { label: (0, _useTranslation2.default)("Select"), value: null };
    var perPageDefaultValue = fetched.length > 0 && (0, _objects.filterByLabel)(fetched, 'key', 'records_per_page') !== '' ? (0, _objects.filterByLabel)(fetched, 'key', 'records_per_page').value : 20;
    var googleMapsApiKeyDefaultValue = fetched.length > 0 && (0, _objects.filterByLabel)(fetched, 'key', 'google_maps_api_key').value !== '' ? (0, _objects.filterByLabel)(fetched, 'key', 'google_maps_api_key').value : null;
    var languageDefaultValue = fetched.length > 0 && typeof (0, _objects.filterByLabel)(fetched, 'key', 'language').value !== 'undefined' ? (0, _objects.filterByValue)(_languages.lanuagesList, (0, _objects.filterByLabel)(fetched, 'key', 'language').value) : { value: 'en', label: "English" };

    var _useForm = (0, _reactHookForm.useForm)({
        mode: 'all',
        defaultValues: {
            enable_visual_editor: enableVisualEditorDefaultValue,
            delete_metadata: deleteMetadataDefaultValue,
            delete_posts: deletePostsDefaultValue,
            delete_tables_when_deactivate: deleteDefinitionsDefaultValue,
            records_per_page: perPageDefaultValue,
            google_maps_api_key: googleMapsApiKeyDefaultValue,
            language: languageDefaultValue
        }
    }),
        control = _useForm.control,
        register = _useForm.register,
        handleSubmit = _useForm.handleSubmit,
        _useForm$formState = _useForm.formState,
        errors = _useForm$formState.errors,
        isValid = _useForm$formState.isValid;

    (0, _react.useEffect)(function () {
        (0, _misc.metaTitle)((0, _useTranslation2.default)("Settings page"));
        (0, _misc.changeCurrentAdminMenuLink)('#/settings');
    }, []);

    if (loading) {
        return wp.element.createElement(_Spinner2.default, null);
    }

    var submitButton = wp.element.createElement(
        "button",
        {
            className: "acpt-btn acpt-btn-primary",
            disabled: !isValid || loading ? 'disabled' : ''
        },
        loading ? (0, _useTranslation2.default)('Loading...') : (0, _useTranslation2.default)('Save')
    );

    return wp.element.createElement(
        _Layout2.default,
        null,
        wp.element.createElement(
            "form",
            { onSubmit: handleSubmit(onSubmit) },
            wp.element.createElement(_ActionsBar2.default, {
                title: "Settings",
                actions: submitButton
            }),
            wp.element.createElement(
                "main",
                null,
                wp.element.createElement(_Breadcrumbs2.default, { crumbs: [{
                        label: (0, _useTranslation2.default)("Registered Custom Post Types"),
                        link: "/"
                    }, {
                        label: (0, _useTranslation2.default)("Settings")
                    }] }),
                wp.element.createElement(
                    "div",
                    { className: "acpt-card" },
                    wp.element.createElement(
                        "div",
                        { className: "acpt-card__header" },
                        wp.element.createElement(
                            "div",
                            { className: "acpt-card__inner" },
                            (0, _useTranslation2.default)("Your favourite ACPT settings")
                        )
                    ),
                    wp.element.createElement(
                        "div",
                        { className: "acpt-card__inner" },
                        wp.element.createElement(
                            "fieldset",
                            null,
                            wp.element.createElement(
                                "legend",
                                null,
                                (0, _useTranslation2.default)("General settings")
                            ),
                            wp.element.createElement(_ReactSelect2.default, {
                                id: "language",
                                label: (0, _useTranslation2.default)("Language"),
                                placeholder: (0, _useTranslation2.default)("Set the language of the plug-in"),
                                description: (0, _useTranslation2.default)("Set the language of the plug-in"),
                                control: control,
                                defaultValue: languageDefaultValue,
                                values: _languages.lanuagesList,
                                isRequired: true,
                                validate: {
                                    required: "This field is mandatory"
                                }
                            }),
                            wp.element.createElement(_InputNumber2.default, {
                                id: "records_per_page",
                                label: (0, _useTranslation2.default)("Records per page"),
                                placeholder: (0, _useTranslation2.default)("Records per page in ACPT dashboards"),
                                defaultValue: perPageDefaultValue,
                                description: (0, _useTranslation2.default)("Set the number of records per page in your ACPT dashboards."),
                                register: register,
                                errors: errors,
                                isRequired: true,
                                min: 1,
                                max: 200
                            }),
                            wp.element.createElement(_InputText2.default, {
                                id: "google_maps_api_key",
                                label: (0, _useTranslation2.default)("Google Maps API key"),
                                placeholder: (0, _useTranslation2.default)("Google Maps API key"),
                                defaultValue: googleMapsApiKeyDefaultValue,
                                description: (0, _useTranslation2.default)("Paste here your Google Maps API key. Needed to use the Address meta field."),
                                register: register,
                                errors: errors,
                                isRequired: false,
                                validate: {
                                    validate: validateGoogleMapsApiKey
                                }
                            })
                        ),
                        wp.element.createElement(
                            "fieldset",
                            null,
                            wp.element.createElement(
                                "legend",
                                null,
                                (0, _useTranslation2.default)("Content settings")
                            ),
                            wp.element.createElement(_ReactSelect2.default, {
                                id: "enable_visual_editor",
                                label: (0, _useTranslation2.default)("Enable ACPT visual builder"),
                                placeholder: (0, _useTranslation2.default)("Enable the ACPT visual builder to manage your templates."),
                                description: (0, _useTranslation2.default)("Enable the ACPT visual builder. This option allows you to use the integrated visual builder to create and manage templates for single CPTs, archive pages, taxonomy terms and relations."),
                                control: control,
                                defaultValue: enableVisualEditorDefaultValue,
                                values: _lists.yesOrNoList,
                                isRequired: false
                            })
                        ),
                        wp.element.createElement(
                            "fieldset",
                            null,
                            wp.element.createElement(
                                "legend",
                                null,
                                (0, _useTranslation2.default)("Danger zone")
                            ),
                            wp.element.createElement(_ReactSelect2.default, {
                                id: "delete_tables_when_deactivate",
                                label: (0, _useTranslation2.default)("Delete ACPT definitions when deactivate the plug-in"),
                                placeholder: (0, _useTranslation2.default)("Delete ACPT definitions when deactivate the plug-in"),
                                description: (0, _useTranslation2.default)("Delete all saved ACPT definitions when you deactivate the plug-in. This means that if you select NO when you deactivate and then reactivate the plug-in you will find all the previously saved ACPT definitions (meta fields, options etc.)"),
                                control: control,
                                defaultValue: deleteDefinitionsDefaultValue,
                                values: _lists.yesOrNoList,
                                isRequired: false
                            }),
                            wp.element.createElement(_ReactSelect2.default, {
                                id: "delete_posts",
                                label: (0, _useTranslation2.default)("Delete posts when delete an ACPT post definition"),
                                placeholder: (0, _useTranslation2.default)("Delete posts when delete an ACPT post definition"),
                                description: (0, _useTranslation2.default)("Delete posts when delete an ACPT post type definition. This means that if you select YES, when you delete an ACPT post type definition all the saved posts will be deleted"),
                                control: control,
                                defaultValue: deletePostsDefaultValue,
                                values: _lists.yesOrNoList,
                                isRequired: false
                            }),
                            wp.element.createElement(_ReactSelect2.default, {
                                id: "delete_metadata",
                                label: (0, _useTranslation2.default)("Delete metadata when delete an ACPT field"),
                                placeholder: (0, _useTranslation2.default)("Delete post metadata when deleting ACPT fields"),
                                description: (0, _useTranslation2.default)("Delete post metadata when deleting an ACPT field. This means that if you select YES, when you delete an ACPT meta field all the saved metadata will be deleted"),
                                control: control,
                                defaultValue: deleteMetadataDefaultValue,
                                values: _lists.yesOrNoList,
                                isRequired: false
                            })
                        )
                    )
                )
            )
        )
    );
};

exports["default"] = Settings;

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

/***/ 27388:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var InputText = function InputText(_ref) {
    var id = _ref.id,
        label = _ref.label,
        placeholder = _ref.placeholder,
        disabled = _ref.disabled,
        readOnly = _ref.readOnly,
        validate = _ref.validate,
        register = _ref.register,
        errors = _ref.errors,
        isRequired = _ref.isRequired,
        defaultValue = _ref.defaultValue,
        description = _ref.description,
        wizard = _ref.wizard,
        onChangeCapture = _ref.onChangeCapture;


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
                type: "text",
                disabled: disabled,
                placeholder: placeholder,
                onChangeCapture: onChangeCapture,
                readOnly: readOnly && 'readonly',
                defaultValue: defaultValue,
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

exports["default"] = InputText;

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

/***/ 69504:
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var lanuagesList = exports.lanuagesList = [{ value: 'en_GB', label: "English" }, { value: 'it_IT', label: "Italian" }];

/***/ }),

/***/ 59210:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.saveSettings = undefined;

var _ajax = __webpack_require__(47569);

var _saveSettingsActions = __webpack_require__(20042);

var _fetchSettings = __webpack_require__(72111);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var saveSettings = exports.saveSettings = function saveSettings(data) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _saveSettingsActions.saveSettingsInProgress)());
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)('saveSettingsAction', data);

                        case 4:
                            res = _context.sent;

                            if (res.success === true) {
                                dispatch((0, _saveSettingsActions.saveSettingsSuccess)(res.data));
                                dispatch((0, _fetchSettings.fetchSettings)());
                            } else {
                                dispatch((0, _saveSettingsActions.saveSettingsFailure)(res.error));
                            }
                            _context.next = 11;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            dispatch((0, _saveSettingsActions.saveSettingsFailure)(_context.t0));

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
//# sourceMappingURL=4938.js.map