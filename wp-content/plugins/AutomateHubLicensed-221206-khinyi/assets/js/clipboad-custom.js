// new ClipboardJS('.btn');

// Tooltip

// jQuery('.spci_btn').tooltip({
//   trigger: 'click',
//   placement: 'bottom'
// });

function setTooltip(btn, message) {
  jQuery(btn).tooltip('hide')
    .attr('data-original-title', message)
    .tooltip('show');
}

function hideTooltip(btn) {
  setTimeout(function() {
    jQuery(btn).tooltip('hide');
  }, 1000);
}

// Clipboard

var clipboard = new ClipboardJS('.spci_btn');

clipboard.on('success', function(e) {
  setTooltip(e.trigger, 'Copied!');
  hideTooltip(e.trigger);
});

clipboard.on('error', function(e) {
  setTooltip(e.trigger, 'Failed!');
  hideTooltip(e.trigger);
});
