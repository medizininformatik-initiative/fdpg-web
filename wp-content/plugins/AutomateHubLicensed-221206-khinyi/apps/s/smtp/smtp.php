<?php

use PHPMailer\PHPMailer\PHPMailer;

class AWP_SMTP extends Appfactory
{
    public function init_actions()
    {
        add_action('admin_post_awp_smtp_save_account_details', [$this, 'save_account_details'], 10, 0);
    }

    public function init_filters()
    {
    }

    public function settings_tab($tabs)
    {
        $tabs['smtp'] = array('name' => esc_html__('SMTP', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-smtp-script', AWP_URL . '/apps/s/smtp/smtp.js', array('awp-vuejs'), '', 1);
    }

    public function save_account_details()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }

        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_smtp_save_account_details')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $account_details = json_encode(
            [
                'awp_smtp_encryption' => sanitize_text_field($_POST["awp_smtp_encryption"]),
                "awp_smtp_host" => sanitize_text_field($_POST["awp_smtp_host"]),
                "awp_smtp_port" => (int) sanitize_text_field($_POST["awp_smtp_port"]),
                "awp_smtp_auth" => sanitize_text_field($_POST["awp_smtp_auth"]) == "on" ? true : false,
                "awp_smtp_username" => sanitize_text_field($_POST["awp_smtp_username"]),
                "awp_smtp_password" => sanitize_text_field($_POST["awp_smtp_password"]),
                "awp_smtp_display_name" => sanitize_text_field($_POST["awp_smtp_display_name"]),
                "awp_smtp_from_email" => sanitize_text_field($_POST["awp_smtp_from_email"]),
            ],
            true
        );

        $platform = new AWP_Platform_Shell_Table('smtp');
        $platform->save_platform(
            [
                'api_key' => $account_details, "account_name" => sanitize_text_field($_POST["awp_smtp_identifier"]),
            ]
        );
        AWP_redirect("admin.php?page=automate_hub&tab=smtp");
    }

    public function action_provider($providers)
    {
        $providers['smtp'] = [
            'title' => __('SMTP', 'automate_hub'),
            'tasks' => array(
                'send_email' => __('Send Email', 'automate_hub'),
            ),
        ];

        return $providers;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'smtp') {
            return;
        }
        $nonce = wp_create_nonce("awp_smtp_save_account_details");
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        // $account_details = isset($_GET['api_key']) ? json_decode($_GET['api_key']) : "";
        $account_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="no-platformheader">
            <a href="https://sperse.io/go/smtp" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/smtpmail.png" width="192" height="30" alt="smtp"></a><br /><br />
            <div id="introbox">
                See the instructions below to setup SMTP:
                <br />
            </div>
            <br />
            <form action='admin-post.php' method="post">
                <input type="hidden" name="action" value="awp_smtp_save_account_details">
                <input type="hidden" name="_nonce" value="<?php echo $nonce ?>">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Account Identifier', 'automate_hub'); ?></th>
                        <td>
                            <input type="text" name="awp_smtp_identifier" id="awp_smtp_identifier" value="<?php echo $account_name ?>" required placeholder="<?php esc_html_e('Enter Account Identifier', 'automate_hub'); ?>" class="basic-text" />
                            <span class="spci_btn" body-clipboard-action="copy" data-clipboard-target="#awp_smtp_identifier"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>
                        </td>
                    </tr>
                    <h3><?php esc_html_e('SMTP Account Details', 'automate_hub'); ?></h3>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Encryption', 'automate_hub'); ?></th>
                        <td>
                            <div class="awp_smtp_radios">
                                <label for="awp_smtp_none">
                                    <input name="awp_smtp_encryption" type="radio" id="awp_smtp_none" value="" checked="checked">
                                    None
                                </label>

                                <label for="awp_smtp_ssl">
                                    <input type="radio" id="awp_smtp_ssl" name="awp_smtp_encryption" value="ssl">
                                    SSL
                                </label>

                                <label for="awp_smtp_ttl">
                                    <input type="radio" id="awp_smtp_ttl" name="awp_smtp_encryption" value="tls">
                                    TLS
                                </label>

                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('SMTP Host', 'automate_hub'); ?></th>
                        <td>
                            <input type="text" name="awp_smtp_host" id="awp_smtp_host" value="" required placeholder="<?php esc_html_e('Enter SMTP Host', 'automate_hub'); ?>" class="basic-text" />
                            <span class="spci_btn" body-clipboard-action="copy" data-clipboard-target="#awp_smtp_host"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('SMTP Port', 'automate_hub'); ?></th>
                        <td>
                            <input type="text" name="awp_smtp_port" id="awp_smtp_port" value="" required placeholder="<?php esc_html_e('Enter SMTP Port', 'automate_hub'); ?>" class="basic-text" />
                            <span class="spci_btn" body-clipboard-action="copy" data-clipboard-target="#awp_smtp_port"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('SMTP Auth', 'automate_hub'); ?></th>
                        <td>
                            <input onclick="checkAuth()" type="checkbox" name="awp_smtp_auth" id="awp_smtp_auth" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('SMTP Email/Username', 'automate_hub'); ?></th>
                        <td>
                            <input type="text" name="awp_smtp_username" id="awp_smtp_username" value="" placeholder="<?php esc_html_e('Enter SMTP Username', 'automate_hub'); ?>" class="basic-text" />
                            <span class="spci_btn" body-clipboard-action="copy" data-clipboard-target="#awp_smtp_username"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('SMTP Password', 'automate_hub'); ?></th>
                        <td>
                            <input type="text" name="awp_smtp_password" id="awp_smtp_password" value="" placeholder="<?php esc_html_e('Enter SMTP Password', 'automate_hub'); ?>" class="basic-text" />
                            <span class="spci_btn" body-clipboard-action="copy" data-clipboard-target="#awp_smtp_password"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>
                        </td>
                    </tr>
                </table>
                <div class="submit-button-plugin"><?php submit_button(); ?></div>
            </form>
        </div>
        <div class="wrap">
            <form id="form-list" method="post">
                <input type="hidden" name="page" value="automate_hub" />
                <?php
                $data = [
                    'table-cols' => ['account_name' => 'Account Identifier', 'api_key' => 'Details', 'active_status' => 'Active'],
                ];
                $platform_obj = new AWP_Platform_Shell_Table('smtp');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();
                ?>
            </form>
        </div>
        <?php
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="smtp-action-template">
            <div class="forms-setting-wrapper">
                <div class="form_fields sperse_reverse_draggable">
                <div class="dynamic-form-logo"> <img v-if="trigger.formProviderId" :src="'<?php echo AWP_ASSETS; ?>/images/icons/' + trigger.formProviderId + '.png'" v-bind:alt="trigger.formProviderId" class="logo-form">
                        <span>Drag your form field to map it to a destination field.</span>
                    </div>
                    <ul>
                        <li class="form_fields_name"  v-for="(nfield, nindex) in trigger.formFields" :data-name="nindex"  :data-fname="nfield" v-if="CheckinDatabase(nindex,nfield)" >
                            <div class="field-actions hide">
                                <a type="remove" v-bind:id=nindex v-bind:data-name=nindex v-bind:data-field=nfield v-on:click="say($event)"  class="del-button btn formbuilder-icon-cancel delete-confirm" title="Remove Element"></a>
                            </div>
                            <span class="input-group-addon fx-dragdrop-handle">{{nfield}}</span>
                        </li>
                    </ul>
                </div>

                <div class="form_placeholder_wrap">
                    <div class="dynamic-platform-logo" ><img  style="width: 100px" class="logo-platform" src="<?php echo AWP_ASSETS; ?>/images/logos/smtp.png" alt="SMTP Logo"><span class="label-title">
                        <strong>SMTP</strong> Available Fields To Map Your Form Values:</span>
                    </div>

                

                    <div v-if="action.paltformConnected == true">
                        <div v-if="action.paltformConnected == true" v-if="action.task == 'send_email'">
                            <div style="display: block;clear: both;">
                                <span class="label-select" id="account-select-label"><?php esc_html_e('Select SMTP Account ', 'automate_hub'); ?></span>
                                <select style="margin-left: 18px;" name="activePlatformId" v-model="fielddata.activePlatformId" required="required"  class="global-two-form">
                                    <option value=""> <?php esc_html_e('Select Account...', 'automate_hub'); ?> </option>
                                    <option v-for="(item, index) in action.accountList" :value="index" > {{item}}  </option>
                                </select>
                                <div style="margin-top: 15px;"></div>
                            </div>
                            <table class="form-table form-fields-table">
                                <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
                            </table> 
                        </div>
                    </div>
                    <div v-if="action.paltformConnected == false">
                        <div class="submit-button-plugin" style="width: 100%;display: flex;">
                            <a style="margin: 0 auto;" href="<?php echo admin_url('admin.php?page=automate_hub&tab=smtp') ?>">
                                <div  class="button button-primary" style="padding: 8px;font-size: 14px;">Provide SMTP Account Details</div>
                            </a>
                        </div>
                    </div>
            </div>   
        </script>
        <?php
    }
}

$Awp_SMTP = AWP_SMTP::get_instance();

function awp_smtp_save_integration()
{
    AWP_SMTP::save_integration();
}

function awp_smtp_send_data($record, $posted_data)
{
    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('smtp');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $account_details = json_decode($temp->api_key);

    $decoded_data = AWP_SMTP::decode_data($record, $posted_data);

    $task = $decoded_data["task"];
    $data = $decoded_data["data"];

    if ($task == "send_email") {
        $fields =  [];
        foreach ($data as $key => $value) {
            if (strpos($key, 'dis') !== false || $key === "activePlatformId") {
                continue;
            }
            $fields[$key] = awp_get_parsed_values($data[$key], $posted_data);
        }

        try {
            $mailer = new PHPMailer();

            $mailer->IsHTML(true);
            $mailer->isSMTP();
            $mailer->setFrom($account_details->awp_smtp_username ? $account_details->awp_smtp_username : get_bloginfo('admin_email'), "Automate Hub Ultimate");
            $mailer->AddReplyTo($fields["email"], "Wordpress User");
            $mailer->addAddress(get_bloginfo('admin_email'), "Wordpress");
            $mailer->Subject = $fields["subject"];
            $mailer->Body = $fields["body"];

            /* SMTP parameters. */
            $mailer->Host = $account_details->awp_smtp_host;
            $mailer->Port = (int) $account_details->awp_smtp_port;
            $mailer->SMTPSecure = $account_details->awp_smtp_encryption;

            if ($account_details->awp_smtp_auth == 1) {
                $mailer->SMTPAuth =  true;
                $mailer->Username = $account_details->awp_smtp_username;
                $mailer->Password = $account_details->awp_smtp_password;
            } else {
                $mailer->SMTPAuth = false;
            }

            /* Disable some SSL checks. */
            $mailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];

            // $mailer->SMTPDebug = 4;

            if (!$mailer->send()) {
                // pprint('Message could not be sent.');
                // pprint('Mailer Error: ' . $mailer->ErrorInfo);
                awp_add_to_log('Mailer Error: ' . $mailer->ErrorInfo, "", array(), $record);
            } else {
                // pprint('Message has been sent');
                awp_add_to_log('Mail sent successfully', "", array(), $record);
            }
            awp_add_to_log("Successful", "", array(), $record);
        } catch (\Exception $e) {
            awp_add_to_log("Failed", "", array(), $record);
        }
    }

    return;
}
