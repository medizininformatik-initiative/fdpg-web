<?php

use ACPT\Core\Repository\SettingsRepository;

$language = SettingsRepository::getSingle('language');
?>

<div class="acpt-container is-wrapper">
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
            language: "<?php echo ($language !== null) ? $language->getValue() : 'en_GB'; ?>",
            translations: translations
        };
    })
    .catch((err) => {
        console.error("Something went wrong!", err);
    });
</script>