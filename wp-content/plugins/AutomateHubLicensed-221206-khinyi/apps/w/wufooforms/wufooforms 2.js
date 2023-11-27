jQuery.noConflict();

window.addEventListener(
    'message',
    function (e) {
        if (e.origin.includes('wufoo.com')) {
            const formId = e.data;
            const strippedFormId = formId.substr(4, 14);
            if (e.data == "formSubmitted") {
                // console.log(strippedFormId);
                let baseUrl = window.location.origin;
                jQuery.ajax({
                    url: `${baseUrl}/wp-json/automatehub/wufooforms/?form_id=${strippedFormId}`,
                }).done(function (res) { }).fail(function (err) { });
            }
        }
    }
);
