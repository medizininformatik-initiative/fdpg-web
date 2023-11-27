(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[5786],{

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

/***/ 29633:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports["default"] = Step;

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function Step(_ref) {
    var isActive = _ref.isActive,
        headings = _ref.headings,
        component = _ref.component;


    var ClonedElementWithMoreProps = _react2.default.cloneElement(component, { headings: headings });

    return wp.element.createElement(
        'div',
        { className: 'acpt-step ' + (isActive ? 'active' : 'hidden') },
        ClonedElementWithMoreProps
    );
}

/***/ }),

/***/ 2873:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports["default"] = StepsButtons;

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRedux = __webpack_require__(28216);

var _stepsActions = __webpack_require__(14576);

var _scroll = __webpack_require__(82727);

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function StepsButtons(_ref) {
    var isValid = _ref.isValid,
        next = _ref.next,
        prev = _ref.prev;


    var dispatch = (0, _reactRedux.useDispatch)();

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.stepsReducer;
    }),
        loading = _useSelector.loading;

    return wp.element.createElement(
        "div",
        { className: "acpt-step-buttons" },
        prev && wp.element.createElement(
            "a",
            { className: "acpt-btn acpt-btn-primary-o",
                onClick: function onClick(e) {
                    dispatch((0, _stepsActions.stepBack)());
                    (0, _scroll.scrollToTop)();
                }
            },
            (0, _useTranslation2.default)("Previous Step")
        ),
        next && wp.element.createElement(
            "button",
            {
                className: "acpt-btn acpt-btn-primary-o",
                disabled: isValid ? '' : 'disabled',
                onClick: function onClick() {
                    (0, _scroll.scrollToTop)();
                }
            },
            (0, _useTranslation2.default)("Next Step")
        ),
        !next && wp.element.createElement(
            "button",
            {
                className: "acpt-btn acpt-btn-primary",
                disabled: !isValid || loading ? 'disabled' : ''
            },
            loading ? (0, _useTranslation2.default)('Loading...') : (0, _useTranslation2.default)('Save')
        )
    );
}

/***/ }),

/***/ 60069:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports["default"] = StepsContainer;

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRedux = __webpack_require__(28216);

var _Step = __webpack_require__(29633);

var _Step2 = _interopRequireDefault(_Step);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function StepsContainer(_ref) {
    var headings = _ref.headings,
        steps = _ref.steps;

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.stepsReducer;
    }),
        activeStep = _useSelector.activeStep;

    return wp.element.createElement(
        "div",
        { className: "acpt-steps-wrapper" },
        steps.map(function (step, index) {
            return wp.element.createElement(_Step2.default, {
                headings: headings,
                component: step,
                isActive: activeStep === index + 1
            });
        })
    );
}

/***/ }),

/***/ 43119:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports["default"] = StepsHeader;

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _StepsHeaderElement = __webpack_require__(33319);

var _StepsHeaderElement2 = _interopRequireDefault(_StepsHeaderElement);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function StepsHeader(_ref) {
    var headings = _ref.headings;

    return wp.element.createElement(
        "div",
        { className: "acpt-steps__headings" },
        headings.map(function (heading) {
            return wp.element.createElement(_StepsHeaderElement2.default, { heading: heading });
        })
    );
}

/***/ }),

/***/ 33319:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports["default"] = StepsHeaderElement;

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _reactRedux = __webpack_require__(28216);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function StepsHeaderElement(_ref) {
    var heading = _ref.heading;

    var _useSelector = (0, _reactRedux.useSelector)(function (state) {
        return state.stepsReducer;
    }),
        activeStep = _useSelector.activeStep;

    var getClassName = function getClassName() {
        var className = 'acpt-steps__heading';

        if (activeStep == heading.number) {
            className += ' active';
        } else if (activeStep > heading.number) {
            className += ' done';
        } else {
            className += ' undone';
        }

        return className;
    };

    return wp.element.createElement(
        'div',
        { className: getClassName() },
        wp.element.createElement(
            'div',
            { className: 'number' },
            heading.number
        ),
        wp.element.createElement(
            'h3',
            { className: 'title' },
            heading.title
        ),
        heading.description && wp.element.createElement(
            'div',
            { className: 'description' },
            heading.description
        )
    );
}

/***/ }),

/***/ 95832:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports["default"] = Steps;

var _react = __webpack_require__(67294);

var _react2 = _interopRequireDefault(_react);

var _StepsContainer = __webpack_require__(60069);

var _StepsContainer2 = _interopRequireDefault(_StepsContainer);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function Steps(_ref) {
    var headings = _ref.headings,
        steps = _ref.steps;


    return wp.element.createElement(
        "div",
        { className: "acpt-steps" },
        wp.element.createElement(_StepsContainer2.default, { headings: headings, steps: steps })
    );
}

/***/ }),

/***/ 99500:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.saveCustomPostTypeHeadings = undefined;

var _useTranslation = __webpack_require__(1422);

var _useTranslation2 = _interopRequireDefault(_useTranslation);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var saveCustomPostTypeHeadings = exports.saveCustomPostTypeHeadings = [{
    "id": 1,
    "number": 1,
    "title": (0, _useTranslation2.default)("Basic"),
    "description": (0, _useTranslation2.default)("Minimum configuration")
}, {
    "id": 2,
    "number": 2,
    "title": (0, _useTranslation2.default)("Labels"),
    "description": (0, _useTranslation2.default)("Additional labels")
}, {
    "id": 3,
    "number": 3,
    "title": (0, _useTranslation2.default)("Settings"),
    "description": (0, _useTranslation2.default)("Other settings")
}];

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

/***/ 19218:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
exports.stepsSubmit = undefined;

var _stepsActions = __webpack_require__(14576);

var _ajax = __webpack_require__(47569);

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var stepsSubmit = exports.stepsSubmit = function stepsSubmit(action, data) {
    return function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(dispatch, getState) {
            var res;
            return regeneratorRuntime.wrap(function _callee$(_context) {
                while (1) {
                    switch (_context.prev = _context.next) {
                        case 0:
                            _context.prev = 0;

                            dispatch((0, _stepsActions.stepsSubmitInProgress)(data));
                            _context.next = 4;
                            return (0, _ajax.wpAjaxRequest)(action, getState().stepsReducer.data);

                        case 4:
                            res = _context.sent;

                            res.success === true ? dispatch((0, _stepsActions.stepsSubmitSuccess)()) : dispatch((0, _stepsActions.stepsSubmitFailure)(res.error));
                            _context.next = 12;
                            break;

                        case 8:
                            _context.prev = 8;
                            _context.t0 = _context["catch"](0);

                            console.log(_context.t0);
                            dispatch((0, _stepsActions.stepsSubmitFailure)(_context.t0));

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

/***/ 39207:
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
    value: true
}));
var hydratePostTypeFormFromStep = exports.hydratePostTypeFormFromStep = function hydratePostTypeFormFromStep(step, data) {

    if (step === 2) {
        return {
            1: {
                post_name: data.name,
                singular_label: data.singular,
                plural_label: data.plural,
                icon: data.icon,
                support_0: data.supports[0] ? data.supports[0] : false,
                support_1: data.supports[1] ? data.supports[1] : false,
                support_2: data.supports[2] ? data.supports[2] : false,
                support_3: data.supports[3] ? data.supports[3] : false,
                support_4: data.supports[4] ? data.supports[4] : false,
                support_5: data.supports[5] ? data.supports[5] : false,
                support_6: data.supports[6] ? data.supports[6] : false,
                support_7: data.supports[7] ? data.supports[7] : false,
                support_8: data.supports[8] ? data.supports[8] : false,
                support_9: data.supports[9] ? data.supports[9] : false,
                support_10: data.supports[10] ? data.supports[10] : false
            }
        };
    }

    if (step === 3) {
        return {
            1: {
                post_name: data.name,
                singular_label: data.singular,
                plural_label: data.plural,
                icon: data.icon,
                support_0: data.supports[0] ? data.supports[0] : false,
                support_1: data.supports[1] ? data.supports[1] : false,
                support_2: data.supports[2] ? data.supports[2] : false,
                support_3: data.supports[3] ? data.supports[3] : false,
                support_4: data.supports[4] ? data.supports[4] : false,
                support_5: data.supports[5] ? data.supports[5] : false,
                support_6: data.supports[6] ? data.supports[6] : false,
                support_7: data.supports[7] ? data.supports[7] : false,
                support_8: data.supports[8] ? data.supports[8] : false,
                support_9: data.supports[9] ? data.supports[9] : false,
                support_10: data.supports[10] ? data.supports[10] : false
            },
            2: {
                menu_name: data.labels.menu_name,
                all_items: data.labels.all_items,
                add_new: data.labels.add_new,
                add_new_item: data.labels.add_new_item,
                edit_item: data.labels.edit_item,
                new_item: data.labels.new_item,
                view_item: data.labels.view_item,
                view_items: data.labels.view_items,
                search_item: data.labels.search_item,
                not_found: data.labels.not_found,
                not_found_in_trash: data.labels.not_found_in_trash,
                parent_item_colon: data.labels.parent_item_colon,
                featured_image: data.labels.featured_image,
                set_featured_image: data.labels.set_featured_image,
                remove_featured_image: data.labels.remove_featured_image,
                use_featured_image: data.labels.use_featured_image,
                archives: data.labels.archives,
                insert_into_item: data.labels.insert_into_item,
                uploaded_to_this_item: data.labels.uploaded_to_this_item,
                filter_items_list: data.labels.filter_items_list,
                items_list_navigation: data.labels.items_list_navigation,
                items_list: data.labels.items_list,
                filter_by_date: data.labels.filter_by_date,
                item_published: data.labels.item_published,
                item_published_privately: data.labels.item_published_privately,
                item_reverted_to_draft: data.labels.item_reverted_to_draft,
                item_scheduled: data.labels.item_scheduled,
                item_updated: data.labels.item_updated
            }
        };
    }

    return {};
};

var hydrateTaxonomyFormFromStep = exports.hydrateTaxonomyFormFromStep = function hydrateTaxonomyFormFromStep(step, data) {

    console.log(data);

    if (step === 2) {
        return {
            1: {
                slug: data.slug,
                singular_label: data.singular,
                plural_label: data.plural
            }
        };
    }

    if (step === 3) {
        return {
            1: {
                slug: data.slug,
                singular_label: data.singular,
                plural_label: data.plural
            },
            2: {
                name: data.labels.name,
                singular_name: data.labels.singular_name,
                search_items: data.labels.search_items,
                popular_items: data.labels.popular_items,
                all_items: data.labels.all_items,
                parent_item: data.labels.parent_item,
                parent_item_colon: data.labels.parent_item_colon,
                edit_item: data.labels.edit_item,
                view_item: data.labels.view_item,
                update_item: data.labels.update_item,
                add_new_item: data.labels.add_new_item,
                new_item_name: data.labels.new_item_name,
                separate_items_with_commas: data.labels.separate_items_with_commas,
                add_or_remove_items: data.labels.add_or_remove_items,
                choose_from_most_used: data.labels.choose_from_most_used,
                not_found: data.labels.not_found,
                no_terms: data.labels.no_terms,
                filter_by_item: data.labels.filter_by_item,
                items_list_navigation: data.labels.items_list_navigation,
                items_list: data.labels.items_list,
                most_used: data.labels.most_used,
                back_to_items: data.labels.back_to_items
            }
        };
    }

    return {};
};

/***/ }),

/***/ 82727:
/***/ ((__unused_webpack_module, exports) => {

"use strict";


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
    var y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;

    window.scrollTo({ top: y, behavior: 'smooth' });
};

/***/ })

}]);
//# sourceMappingURL=5786.js.map