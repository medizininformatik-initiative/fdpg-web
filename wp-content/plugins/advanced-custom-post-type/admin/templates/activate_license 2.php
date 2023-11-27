<?php

use ACPT\Admin\ACPT_License_Manager;

$errors = [];

if(isset($_POST['activation'])){

    $siteName = get_bloginfo('name');
    $siteUrl = get_bloginfo('url');

    $data = $_POST;
    $data['siteName'] = $siteName;
    $data['siteUrl'] = $siteUrl;

    $activation = ACPT_License_Manager::activate($data);

    if($activation === true){
        wp_redirect(get_admin_url().'admin.php?page=advanced-custom-post-type');
        exit();
    }

    $errors[] = __('Error during the activation of the license.', ACPT_PLUGIN_NAME);
}
?>
<div class="acpt-container is-wrapper with-padding">
    <h1><?php echo __('Activate your license', ACPT_PLUGIN_NAME); ?></h1>
    <p><?php echo __('Activate ACPT by your license key to get professional support and automatic updates from your WordPress dashboard.', ACPT_PLUGIN_NAME); ?></p>
    <?php
    if(!empty($errors)){
        foreach ($errors as $error){
            echo '<div class="acpt-alert acpt-alert-warning mb-3">'.$error.'</div>';
        }
    }
    ?>
    <form action="" method="post">
        <div class="acpt-card">
            <div class="acpt-card__header">
                <div class="acpt-card__inner">
	                <?php echo __('Activate your license key here. Please note that you need also your ACPT user email', ACPT_PLUGIN_NAME); ?>
                </div>
            </div>
            <div class="acpt-card__body">
                <div class="acpt-card__inner">
                    <div class="mb-2">
                        <label class="acpt-form-label" for="email">
	                        <?php echo __('ACPT email account', ACPT_PLUGIN_NAME); ?>
                            <span class="required">*</span>
                        </label>
                        <input
                                id="email"
                                type="email"
                                name="email"
                                class="acpt-form-control"
                                placeholder="<?php echo __('Type here your ACPT email account. Ex: mauro@acpt.io', ACPT_PLUGIN_NAME); ?>"
                                required
                        >
                    </div>
                    <div class="mb-2">
                        <label class="acpt-form-label" for="code">
                            License
                            <span class="required">*</span>
                        </label>
                        <input
                                type="text"
                                id="code"
                                name="code"
                                class="acpt-form-control"
                                placeholder="<?php echo __('Type here your license. Ex: 123e4567-e89b-12d3-a456-426614174000', ACPT_PLUGIN_NAME); ?>"
                                required
                        >
                    </div>
                    <button type="submit" class="acpt-btn acpt-btn-primary">
	                    <?php echo __('Activate your license now', ACPT_PLUGIN_NAME); ?>
                    </button>
                    <?php echo wp_nonce_field( 'activation-nonce', 'activation' ); ?>
                </div>
            </div>
        </div>
    </form>
</div>