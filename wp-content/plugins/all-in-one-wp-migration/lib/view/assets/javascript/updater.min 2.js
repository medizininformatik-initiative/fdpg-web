/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
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
  'use strict';

  $('.ai1wm-purchase-add').on('click', function (e) {
    var self = $(this);
    self.attr('disabled', true);
    var dialog = self.closest('.ai1wm-modal-dialog');
    var error = dialog.find('.ai1wm-modal-error');
    var index = dialog.attr('id').split('-').pop();
    var purchaseId = dialog.find('.ai1wm-purchase-id').val();
    var updateLink = dialog.find('.ai1wm-update-link').val(); // Check Purchase ID

    $.ajax({
      url: 'https://servmask.com/purchase/' + purchaseId + '/check',
      type: 'GET',
      dataType: 'json',
      dataFilter: function dataFilter(data) {
        return Ai1wm.Util.json(data);
      }
    }).done(function (product) {
      // Update Purchase ID
      $.ajax({
        url: ai1wm_updater.ajax.url,
        type: 'POST',
        dataType: 'json',
        data: {
          ai1wm_uuid: product.uuid,
          ai1wm_extension: product.extension
        },
        dataFilter: function dataFilter(data) {
          return Ai1wm.Util.json(data);
        }
      }).done(function () {
        window.location.hash = ''; // Update plugin row

        $('#ai1wm-update-section-' + index).html($('<a />').attr('href', updateLink).text(ai1wm_locale.check_for_updates));
        self.attr('disabled', false);
      });
    }).fail(function () {
      self.attr('disabled', false);
      error.html(ai1wm_locale.invalid_purchase_id);
    });
    e.preventDefault();
  });
  $('.ai1wm-purchase-discard').on('click', function (e) {
    window.location.hash = '';
    e.preventDefault();
  });
});
/******/ })()
;