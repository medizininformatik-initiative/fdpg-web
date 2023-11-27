/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 12:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

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
var Modal = __webpack_require__(326),
    $ = jQuery;

var Export = function Export() {
  var self = this; // Set params

  this.params = []; // Set modal

  this.modal = new Modal(); // Set stop listener

  this.modal.onStop = function (options) {
    self.onStop(options);
  };
};

Export.prototype.setParams = function (params) {
  this.params = Ai1wm.Util.list(params);
};

Export.prototype.start = function (options, retries) {
  var self = this;
  retries = retries || 0; // Reset stop flag

  if (retries === 0) {
    this.stopExport(false);
  } // Stop running export


  if (this.isExportStopped()) {
    return;
  } // Initializing beforeunload event


  $(window).bind('beforeunload', function () {
    return ai1wm_locale.stop_exporting_your_website;
  }); // Set initial status

  this.setStatus({
    type: 'info',
    message: ai1wm_locale.preparing_to_export
  }); // Set params

  var params = this.params.concat({
    name: 'secret_key',
    value: ai1wm_export.secret_key
  }); // Set additional params

  if (options) {
    params = params.concat(Ai1wm.Util.list(options));
  } // Export


  $.ajax({
    url: ai1wm_export.ajax.url,
    type: 'POST',
    dataType: 'json',
    data: params,
    dataFilter: function dataFilter(data) {
      return Ai1wm.Util.json(data);
    }
  }).done(function () {
    self.getStatus();
  }).done(function (result) {
    if (result) {
      self.run(result);
    }
  }).fail(function (xhr) {
    var timeout = retries * 1000;

    try {
      var json = Ai1wm.Util.json(xhr.responseText);

      if (json) {
        var result = JSON.parse(json);
        var error = result.errors.pop();

        if (error.message) {
          self.stopExport(true);
          self.setStatus({
            type: 'error',
            title: ai1wm_locale.unable_to_export,
            message: error.message
          });
          return;
        }
      }
    } catch (e) {}

    if (retries >= 5) {
      self.stopExport(true);
      self.setStatus({
        type: 'error',
        title: ai1wm_locale.unable_to_export,
        message: ai1wm_locale.unable_to_start_the_export
      });
      return;
    }

    retries++;
    setTimeout(self.start.bind(self, options, retries), timeout);
  });
};

Export.prototype.run = function (params, retries) {
  var self = this;
  retries = retries || 0; // Stop running export

  if (this.isExportStopped()) {
    return;
  } // Export


  $.ajax({
    url: ai1wm_export.ajax.url,
    type: 'POST',
    dataType: 'json',
    data: params,
    dataFilter: function dataFilter(data) {
      return Ai1wm.Util.json(data);
    }
  }).done(function (result) {
    if (result) {
      self.run(result);
    }
  }).fail(function (xhr) {
    var timeout = retries * 1000;

    try {
      var json = Ai1wm.Util.json(xhr.responseText);

      if (json) {
        var result = JSON.parse(json);
        var error = result.errors.pop();

        if (error.message) {
          self.stopExport(true);
          self.setStatus({
            type: 'error',
            title: ai1wm_locale.unable_to_export,
            message: error.message
          });
          return;
        }
      }
    } catch (e) {}

    if (retries >= 5) {
      self.stopExport(true);
      self.setStatus({
        type: 'error',
        title: ai1wm_locale.unable_to_export,
        message: ai1wm_locale.unable_to_run_the_export
      });
      return;
    }

    retries++;
    setTimeout(self.run.bind(self, params, retries), timeout);
  });
};

Export.prototype.clean = function (options, retries) {
  var self = this;
  retries = retries || 0; // Reset stop flag

  if (retries === 0) {
    this.stopExport(true);
  } // Set initial status


  this.setStatus({
    type: 'info',
    message: ai1wm_locale.please_wait_stopping_the_export
  }); // Set params

  var params = this.params.concat({
    name: 'secret_key',
    value: ai1wm_export.secret_key
  }).concat({
    name: 'priority',
    value: 300
  }); // Set additional params

  if (options) {
    params = params.concat(Ai1wm.Util.list(options));
  } // Clean


  $.ajax({
    url: ai1wm_export.ajax.url,
    type: 'POST',
    dataType: 'json',
    data: params,
    dataFilter: function dataFilter(data) {
      return Ai1wm.Util.json(data);
    }
  }).done(function () {
    // Unbinding the beforeunload event when we stop exporting
    $(window).unbind('beforeunload'); // Destroy modal

    self.modal.destroy();
  }).fail(function (xhr) {
    var timeout = retries * 1000;

    try {
      var json = Ai1wm.Util.json(xhr.responseText);

      if (json) {
        var result = JSON.parse(json);
        var error = result.errors.pop();

        if (error.message) {
          self.stopExport(true);
          self.setStatus({
            type: 'error',
            title: ai1wm_locale.unable_to_export,
            message: error.message
          });
          return;
        }
      }
    } catch (e) {}

    if (retries >= 5) {
      self.stopExport(true);
      self.setStatus({
        type: 'error',
        title: ai1wm_locale.unable_to_export,
        message: ai1wm_locale.unable_to_stop_the_export
      });
      return;
    }

    retries++;
    setTimeout(self.clean.bind(self, options, retries), timeout);
  });
};

Export.prototype.getStatus = function () {
  var self = this; // Stop getting status

  if (this.isExportStopped()) {
    return;
  }

  this.statusXhr = $.ajax({
    url: ai1wm_export.status.url,
    type: 'GET',
    dataType: 'json',
    cache: false,
    dataFilter: function dataFilter(data) {
      return Ai1wm.Util.json(data);
    }
  }).done(function (params) {
    if (params) {
      self.setStatus(params); // Next status

      switch (params.type) {
        case 'done':
        case 'error':
        case 'download':
          // Unbinding beforeunload event when any case is performed
          $(window).unbind('beforeunload');
          return;
      }
    } // Export is not done yet, let's check status in 3 seconds


    setTimeout(self.getStatus.bind(self), 3000);
  }).fail(function () {
    // Export is not done yet, let's check status in 3 seconds
    setTimeout(self.getStatus.bind(self), 3000);
  });
};

Export.prototype.setStatus = function (params) {
  this.modal.render(params);
};

Export.prototype.onStop = function (options) {
  this.clean(options);
};

Export.prototype.stopExport = function (isStopped) {
  try {
    if (isStopped && this.statusXhr) {
      this.statusXhr.abort();
    }
  } finally {
    this.isStopped = isStopped;
  }
};

Export.prototype.isExportStopped = function () {
  return this.isStopped;
};

module.exports = Export;

/***/ }),

/***/ 326:
/***/ (function(module) {

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
var $ = jQuery;

var Modal = function Modal() {
  var self = this; // Error Modal

  this.error = function (params) {
    // Create the modal container
    var container = $('<div></div>'); // Create section to hold title, message and action

    var section = $('<section></section>'); // Create header to hold title

    var header = $('<h1></h1>'); // Create paragraph to hold mesage

    var message = $('<p></p>').html(params.message); // Create action section

    var action = $('<div></div>'); // Create title

    var title = $('<span></span>').addClass('ai1wm-title-red').text(params.title); // Create close button

    var closeButton = $('<button type="button" class="ai1wm-button-red"></button>').on('click', function () {
      self.destroy();
    }); // Append text to close button

    closeButton.append(ai1wm_locale.close_export); // Append close button to action

    action.append(closeButton); // Append title to section

    header.append(title); // Append header and message to section

    section.append(header).append(message); // Append section and action to container

    container.append(section).append(action); // Render modal

    self.modal.html(container).show();
    self.modal.trigger('focus');
    self.overlay.show();
  }; // Info Modal


  this.info = function (params) {
    // Create the modal container
    var container = $('<div></div>'); // Create section to hold title, message and action

    var section = $('<section></section>'); // Create header to hold loader

    var header = $('<h1></h1>'); // Create paragraph to hold mesage

    var message = $('<p></p>').html(params.message); // Create action section

    var action = $('<div></div>'); // Create loader

    var loader = $('<span class="ai1wm-loader"></span>'); // Create stop export

    var stopButton = $('<button type="button" class="ai1wm-button-red"></button>').on('click', function () {
      stopButton.attr('disabled', 'disabled');
      self.onStop();
    }); // Append text to stop button

    stopButton.append('<i class="ai1wm-icon-notification"></i> ' + ai1wm_locale.stop_export); // Append stop button to action

    action.append(stopButton); // Append loader to header

    header.append(loader); // Append header and message to section

    section.append(header).append(message); // Append section and action to container

    container.append(section).append(action); // Render modal

    self.modal.html(container).show();
    self.modal.trigger('focus');
    self.overlay.show();
  }; // Done Modal


  this.done = function (params) {
    // Create the modal container
    var container = $('<div></div>'); // Create section to hold title, message and action

    var section = $('<section></section>'); // Create header to hold title

    var header = $('<h1></h1>'); // Create paragraph to hold mesage

    var message = $('<p></p>').html(params.message); // Create action section

    var action = $('<div></div>'); // Create title

    var title = $('<span></span>').addClass('ai1wm-title-green').text(params.title); // Create close button

    var closeButton = $('<button type="button" class="ai1wm-button-red"></button>').on('click', function () {
      self.destroy();
    }); // Append text to close button

    closeButton.append(ai1wm_locale.close_export); // Append close button to action

    action.append(closeButton); // Append title to section

    header.append(title); // Append header and message to section

    section.append(header).append(message); // Append section and action to container

    container.append(section).append(action); // Render modal

    self.modal.html(container).show();
    self.modal.trigger('focus');
    self.overlay.show();
  }; // Download Modal


  this.download = function (params) {
    // Create the modal container
    var container = $('<div></div>'); // Create section to hold title, message and action

    var section = $('<section></section>'); // Create paragraph to hold mesage

    var message = $('<p></p>').html(params.message); // Create action section

    var action = $('<div></div>'); // Create close button

    var closeButton = $('<button type="button" class="ai1wm-button-red"></button>').on('click', function () {
      self.destroy();
    });
    var counter = $('.ai1wm-menu-count'); // Update counter text

    counter.text(+counter.text() + 1);

    if (counter.text() > 1) {
      counter.prop('title', ai1wm_locale.backups_count_plural.replace('%d', counter.text()));
    } else {
      counter.removeClass('ai1wm-menu-hide');
      counter.prop('title', ai1wm_locale.backups_count_singular.replace('%d', counter.text()));
    } // Append text to close button


    closeButton.append(ai1wm_locale.close_export); // Append close button to action

    action.append(closeButton); // Append message to section

    section.append(message); // Append section and action to container

    container.append(section).append(action); // Render modal

    self.modal.html(container).show();
    self.modal.trigger('focus');
    self.overlay.show();
  }; // Create the overlay


  this.overlay = $('<div class="ai1wm-overlay"></div>'); // Create the modal container

  this.modal = $('<div class="ai1wm-modal-container" role="dialog" tabindex="-1"></div>');
  $('body').append(this.overlay) // Append overlay to body
  .append(this.modal); // Append modal to body
};

Modal.prototype.render = function (params) {
  $(document).trigger('ai1wm-export-status', params); // Show modal

  switch (params.type) {
    case 'error':
      this.error(params);
      break;

    case 'info':
      this.info(params);
      break;

    case 'done':
      this.done(params);
      break;

    case 'download':
      this.download(params);
      break;
  }
};

Modal.prototype.destroy = function () {
  this.modal.hide();
  this.overlay.hide();
};

module.exports = Modal;

/***/ }),

/***/ 813:
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
(function ($) {
  $.fn.ai1wm_find_replace = function () {
    $(this).on('click', function (e) {
      e.preventDefault();
      var row = $('#ai1wm-queries > li:first').clone(); // Reset input values

      row.find('input').val(''); // Reset ai1wm-query-find-text

      row.find('.ai1wm-query-find-text').html('&lt;text&gt;'); // Reset ai1wm-query-replace-text

      row.find('.ai1wm-query-replace-text').html('&lt;another-text&gt;');
      $('#ai1wm-queries > li').removeClass('ai1wm-open');
      $(row).addClass('ai1wm-open'); // Add new replace fields

      $('#ai1wm-queries').append(row);
      $(row).ai1wm_query();
      $(row).find('p:first').on('click', function () {
        $(this).parent().toggleClass('ai1wm-open');
      });
    });
    return this;
  };
})(jQuery);

/***/ }),

/***/ 88:
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
(function ($) {
  $.fn.ai1wm_query = function () {
    var findInput = $(this).find('input.ai1wm-query-find-input'),
        replaceInput = $(this).find('input.ai1wm-query-replace-input'),
        findText = $(this).find('small.ai1wm-query-find-text'),
        replaceText = $(this).find('small.ai1wm-query-replace-text');
    findInput.on('change paste input keypress keydown keyup', function () {
      var _inputValue = $(this).val().length > 0 ? $(this).val() : '<text>';

      findText.text(_inputValue);
    });
    replaceInput.on('change paste input keypress keydown keyup', function () {
      var _inputValue = $(this).val().length > 0 ? $(this).val() : '<another-text>';

      replaceText.text(_inputValue);
    });
    return this;
  };
})(jQuery);

/***/ }),

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
var Query = __webpack_require__(88),
    FindReplace = __webpack_require__(813),
    Feedback = __webpack_require__(332),
    Export = __webpack_require__(12);

jQuery(document).ready(function ($) {
  'use strict';

  var model = new Export(); // Export to file

  $('#ai1wm-export-file').on('click', function (e) {
    if ($('#ai1wm-encrypt-backups').is(':checked')) {
      var passwordInput = $('#ai1wm-backup-encrypt-password');
      var passwordConfirmationInput = $('#ai1wm-backup-encrypt-password-confirmation');

      if (!passwordInput.val().length) {
        passwordInput.parent().addClass('ai1wm-has-error');
        passwordInput.focus();
        return false;
      }

      if (passwordInput.val() !== passwordConfirmationInput.val()) {
        passwordConfirmationInput.parent().addClass('ai1wm-has-error');
        passwordConfirmationInput.focus();
        return false;
      }
    }

    var storage = Ai1wm.Util.random(12);
    var options = Ai1wm.Util.form('#ai1wm-export-form').concat({
      name: 'storage',
      value: storage
    }).concat({
      name: 'file',
      value: 1
    }); // Set global params

    model.setParams(options); // Start export

    model.start();
    e.preventDefault();
  });
  $('.ai1wm-accordion > .ai1wm-title').on('click', function () {
    $(this).parent().toggleClass('ai1wm-active');
  });
  $('#ai1wm-add-new-replace-button').ai1wm_find_replace();
  $('.ai1wm-expandable > p:first, .ai1wm-expandable > h4:first, .ai1wm-expandable > div.ai1wm-button-main').on('click', function () {
    $(this).parent().toggleClass('ai1wm-open');
  });
  $('.ai1wm-query').ai1wm_query();
  $('.ai1wm-toggle-password-visibility').on('click', function () {
    $(this).toggleClass('ai1wm-icon-eye ai1wm-icon-eye-blocked');
    $(this).prev().prop('type', function (index, oldPropertyValue) {
      return oldPropertyValue === 'text' ? 'password' : 'text';
    });
    return false;
  });
  $('#ai1wm-encrypt-backups').on('click', function () {
    $('.ai1wm-encrypt-backups-passwords-toggle').toggle();
  });
  $('#ai1wm-backup-encrypt-password').on('keyup', function () {
    var passwordInput = $(this);
    var passwordConfirmationInput = $('#ai1wm-backup-encrypt-password-confirmation');

    if (passwordInput.val() !== passwordConfirmationInput.val()) {
      passwordConfirmationInput.parent().addClass('ai1wm-has-error');
    }

    if (passwordInput.val().length) {
      passwordInput.parent().removeClass('ai1wm-has-error');
    }
  });
  $('#ai1wm-backup-encrypt-password-confirmation').on('keyup', function () {
    var passwordConfirmationInput = $(this);
    var passwordInput = $('#ai1wm-backup-encrypt-password');

    if (passwordInput.val() !== passwordConfirmationInput.val()) {
      passwordConfirmationInput.parent().addClass('ai1wm-has-error');
    } else {
      passwordConfirmationInput.parent().removeClass('ai1wm-has-error');
    }
  });
});
__webpack_require__.g.Ai1wm = jQuery.extend({}, __webpack_require__.g.Ai1wm, {
  Query: Query,
  FindReplace: FindReplace,
  Feedback: Feedback,
  Export: Export
});
}();
/******/ })()
;