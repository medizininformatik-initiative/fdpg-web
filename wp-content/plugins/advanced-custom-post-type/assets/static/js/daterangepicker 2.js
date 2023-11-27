var $ = jQuery.noConflict();

$(document).ready(function(){

    const daterangepickerElement = $('.acpt-daterangepicker');
    const maxDate = daterangepickerElement.data('max-date');
    const minDate = daterangepickerElement.data('min-date');

    // https://www.daterangepicker.com/#options
    daterangepickerElement.daterangepicker({
            opens: 'top',
            startDate: maxDate,
            endDate: minDate,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }
    );
});