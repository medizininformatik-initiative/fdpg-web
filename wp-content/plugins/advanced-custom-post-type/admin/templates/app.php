<?php

use ACPT\Core\Repository\SettingsRepository;

try {
	$language = SettingsRepository::getSingle('language') ? SettingsRepository::getSingle('language')->getValue() : 'en_US';
} catch (\Exception $exception){
	$language = 'en_US';
}
?>

<div class="acpt-container is-wrapper <?php echo (is_rtl() === true) ? "rtl" : ""; ?>">
    <div id="acpt-admin-app"></div>
</div>
<script>

    /**
     * Fetch all translations before the App starts
     *
     * @returns {Promise<Response>}
     */
    const fetchLanguages = () => {

        const baseAjaxUrl = (typeof ajaxurl === 'string') ? ajaxurl : '/wp-admin/admin-ajax.php';

        let formData;
        formData = new FormData();
        formData.append('action', 'languagesAction');

        return fetch(baseAjaxUrl, {
            method: 'POST',
            body: formData
        });
    };

    fetchLanguages()
    .then((response) => response.json())
    .then((translations) => {
        document.globals = {
            plugin_version: "<?php echo ACPT_PLUGIN_VERSION; ?>",
            site_url: "<?php echo site_url(); ?>",
            admin_url: "<?php echo admin_url(); ?>",
            ajax_url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
            rest_route_url: "<?php echo "/?rest_route=/acpt/v1"; ?>",
            http_referer: "<?php echo (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : ''; ?>",
            language: "<?php echo $language; ?>",
            locale: "<?php echo get_locale(); ?>",
            is_rtl: <?php echo (is_rtl() === true) ? "true" : "false"; ?>,
            available_languages: translations.languages,
            translations: translations.translations,
        };
    })
    .catch((err) => {
        console.error("Something went wrong!", err);
    });
</script>