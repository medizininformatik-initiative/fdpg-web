/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 332:
/***/ (function() {

/**
 * Copyright (C) 2014-2020 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */
jQuery(document).ready(function ($) {
  'use strict'; // Idea

  $('#ai1wm-feedback-type-link-1').on('click', function () {
    var radio = $('#ai1wm-feedback-type-1');

    if (radio.is(':checked')) {
      radio.attr('checked', false);
    } else {
      radio.attr('checked', true);
    }
  }); // Help

  $('#ai1wm-feedback-type-2').on('click', function () {
    // Hide other options
    $('#ai1wm-feedback-type-1').closest('li').hide(); // Change placeholder message

    $('.ai1wm-feedback-form').find('.ai1wm-feedback-message').attr('placeholder', ai1wm_locale.how_may_we_help_you); // Show feedback form

    $('.ai1wm-feedback-form').fadeIn();
  }); // Cancel feedback form

  $('#ai1wm-feedback-cancel').on('click', function (e) {
    $('.ai1wm-feedback-form').fadeOut(function () {
      $('.ai1wm-feedback-type').attr('checked', false).closest('li').show();
    });
    e.preventDefault();
  }); // Send feedback form

  $('#ai1wm-feedback-submit').on('click', function (e) {
    var self = $(this);
    var spinner = self.next();
    var type = $('.ai1wm-feedback-type:checked').val();
    var email = $('.ai1wm-feedback-email').val();
    var message = $('.ai1wm-feedback-message').val();
    var terms = $('.ai1wm-feedback-terms').is(':checked');
    self.attr('disabled', true);
    spinner.css('visibility', 'visible');
    $.ajax({
      url: ai1wm_feedback.ajax.url,
      type: 'POST',
      dataType: 'json',
      async: true,
      data: {
        secret_key: ai1wm_feedback.secret_key,
        ai1wm_type: type,
        ai1wm_email: email,
        ai1wm_message: message,
        ai1wm_terms: +terms
      },
      dataFilter: function dataFilter(data) {
        return Ai1wm.Util.json(data);
      }
    }).done(function (data) {
      self.attr('disabled', false);
      spinner.css('visibility', 'hidden');

      if (data.errors.length > 0) {
        $('.ai1wm-feedback .ai1wm-message').remove();
        var errorMessage = $('<div />').addClass('ai1wm-message ai1wm-error-message');
        $.each(data.errors, function (key, value) {
          errorMessage.append($('<p />').text(value));
        });
        $('.ai1wm-feedback').prepend(errorMessage);
      } else {
        var successMessage = $('<div />').addClass('ai1wm-message ai1wm-success-message');
        successMessage.append($('<p />').text(ai1wm_locale.thanks_for_submitting_your_feedback));
        $('.ai1wm-feedback').html(successMessage);
      }
    });
    e.preventDefault();
  });
});

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/global */
/******/ 	!function() {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/**
 * Copyright (C) 2014-2020 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */
var Feedback = __webpack_require__(332);

jQuery(document).ready(function () {
  'use strict';
});
__webpack_require__.g.Ai1wm = jQuery.extend({}, __webpack_require__.g.Ai1wm, {
  Feedback: Feedback
});
}();
/******/ })()
;