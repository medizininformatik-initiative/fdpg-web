jQuery.noConflict();

window.addEventListener('PaperformSubmission', function (e) {
    let formData = e.detail;
    let formId = formData.form_id;
    let submissionId = formData.submission_id;

    let baseUrl = window.location.origin;
    jQuery.ajax({
        url: `${baseUrl}/wp-json/automatehub/paperform/?form_id=${formId}&submission_id=${submissionId}`,
    }).done(function (res) { }).fail(function (err) { });

})
