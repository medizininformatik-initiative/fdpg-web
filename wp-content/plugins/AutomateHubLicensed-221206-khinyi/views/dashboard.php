<?php include AWP_INCLUDES.'/header.php'; ?>
<main>
    <section class="main-section">
		<div class="pages-background"></div>
        <div class="container">
            <div class="main-section__columns">
<!-- 				row -->
                <div class="main-section__left">
                    <div><h1 class="main-title"><?php esc_html_e('Quick and easy integrations with major ESPs, CRMs and popular cloud apps!','automate_hub'); ?></h1></div>
                    <div class="desc-sperse-app ">
                        <p><?php esc_html_e("Seamlessly connect your Wordpress forms with Sperse or other CRM platforms, Marketing Automation apps, Email Service Providers or Webinar services with drag-and-drop field-mapping and zero code! Watch the video below to learn how to:",'automate_hub'); ?></p>
                    </div>
                    <div><ul class="steps-list">
                        <li><?php esc_html_e('Activate your Automate Hub Plugin License.','automate_hub'); ?></li>
                        <li><?php esc_html_e('Browse the directory of available apps.','automate_hub'); ?> </li>
                        <li><?php esc_html_e('Activate your apps with your account credentials.','automate_hub'); ?></li>
                        <li><?php esc_html_e('Map your form-fields to create an integration.','automate_hub'); ?></li>
                        <li><?php esc_html_e('See it in action! View Activity Log for details.','automate_hub'); ?></li>
                        </ul>
                    </div>
                    <div class="create-account">
                        <table width="100%">
                        <tr><td align="center" nowrap  width="100%">
                            <?php if(get_option('sperse_license_key')){ ?>
                                <button class="upgrade" ><a href="<?php echo esc_url(admin_url('admin.php?page=awp_app_directory')); ?>"><?php esc_html_e('Get Started','automate_hub'); ?></a></button>
                            <?php } else{ ?>
                                <button class="activate-license"><a href="<?php echo esc_url(admin_url('admin.php?page=automate_license')); ?>"><?php esc_html_e('Activate Plugin','automate_hub'); ?></a></button>
                            <?php } ?>
                             </td>
                        </tr>
                        </table>
                    </div>
                    <div class="main-section__video">
						<object data="https://sperse.io/introvideo.php" type="text/html" id="video" 
			   			standby="Video Loading, Please Wait..." title="Video is loading, please wait..." 
			   			width="560" height="315" scrolling="no" marginwidth="0" marginheight="0" >
			   			<p> <?php esc_html_e('Sperse Video Cannot Load Here.','automate_hub'); ?><a href="https://sperse.io/introvideo.php" target="_blank"><?php esc_html_e('Click Here to View.','automate_hub'); ?></a></p>
						</object>
		            </div>
                </div>

                <div class="main-section__right">
                    <div class="cv-scroll">
                        <div class="cv-scroll-container">
                            <div class="cv-vertical">
                                <div class="cv-icon-list">
                                    <div class="cv-icon"><a href="#"                                                   ><i class="sprite sprite-acoustic"         ></i></a></div> 
                                    <div class="cv-icon"><a href="https://sperse.io/go/activecampaign"  target="_blank"><i class="sprite sprite-acton"            ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/activecampaign"  target="_blank"><i class="sprite sprite-activecampaign"   ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/amazon"          target="_blank"><i class="sprite sprite-amazon"           ></i></a></div>
                                    <div class="cv-icon"><a href="#"                                                   ><i class="sprite sprite-amphtml"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/aweber"          target="_blank"><i class="sprite sprite-aweber"           ></i></a></div>
                                    <div class="cv-icon"><a href="#"                                                   ><i class="sprite sprite-braze"            ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/campaignmonitor" target="_blank"><i class="sprite sprite-campaignmonitor"  ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/close"           target="_blank"><i class="sprite sprite-close"            ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/constantcontact" target="_blank"><i class="sprite sprite-constantcontact"  ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/marketo"         target="_blank"><i class="sprite sprite-marketo"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/moonmail"        target="_blank"><i class="sprite sprite-moonmail"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/pipedrive"       target="_blank"><i class="sprite sprite-pipedrive"        ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/postmarkapp"     target="_blank"><i class="sprite sprite-postmarkapp"      ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/salesforce"      target="_blank"><i class="sprite sprite-salesforce"       ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/sendfox"         target="_blank"><i class="sprite sprite-sendfox"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/copper"          target="_blank"><i class="sprite sprite-copper"           ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/dotdigital"      target="_blank"><i class="sprite sprite-dotdigital"       ></i></a></div>
                                </div>
                            </div>
                            <div class="cv-vertical">
                                <div class="cv-icon-list">
                                    <div class="cv-icon"><a href="#"                                   target="_blank"><i class="sprite sprite-group336"         ></i></a></div>
                                    <div class="cv-icon"><a href="#"                                   target="_blank"><i class="sprite sprite-group7"           ></i></a></div>
                                    <div class="cv-icon"><a href="#"                                   target="_blank"><i class="sprite sprite-html"             ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/hubspot"        target="_blank"><i class="sprite sprite-hubspot"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/emailoctopus"   target="_blank"><i class="sprite sprite-emailoctopus"     ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/esputnik"       target="_blank"><i class="sprite sprite-esputnik"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/everwebinar"    target="_blank"><i class="sprite sprite-everwebinar"      ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/freshworks"     target="_blank"><i class="sprite sprite-freshworks"       ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/getresponse"    target="_blank"><i class="sprite sprite-getresponse"      ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/gmail"          target="_blank"><i class="sprite sprite-gmail"            ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/googlesheets"   target="_blank"><i class="sprite sprite-googlesheets"     ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/moosend"        target="_blank"><i class="sprite sprite-moosend"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/omnisend"       target="_blank"><i class="sprite sprite-omnisend"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/sendgrid"       target="_blank"><i class="sprite sprite-sendgrid"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/sendinblue"     target="_blank"><i class="sprite sprite-sendinblue"       ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/sendloop"       target="_blank"><i class="sprite sprite-sendloop"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/sendy"          target="_blank"><i class="sprite sprite-sendy"            ></i></a></div>
                                </div>
                            </div>
                            <div class="cv-vertical">
                                <div class="cv-icon-list">
                                    <div class="cv-icon"><a href="https://sperse.io/go/kartra"         target="_blank"><i class="sprite sprite-kartra"           ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/keap"           target="_blank"><i class="sprite sprite-keap"             ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/klaviyo"        target="_blank"><i class="sprite sprite-klaviyo"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/liondesk"       target="_blank"><i class="sprite sprite-liondesk"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/mailchimp"      target="_blank"><i class="sprite sprite-mailchimp"        ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/mailclick"      target="_blank"><i class="sprite sprite-mailclickconvert" ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/zapier"         target="_blank"><i class="sprite sprite-zapier"           ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/jumplead"       target="_blank"><i class="sprite sprite-jumplead"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/icontact"       target="_blank"><i class="sprite sprite-icontact"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/insightly"      target="_blank"><i class="sprite sprite-insightly"        ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/iterable"       target="_blank"><i class="sprite sprite-iterable"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/ongage"         target="_blank"><i class="sprite sprite-ongage"           ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/outlook"        target="_blank"><i class="sprite sprite-outlook"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/sharpspring"    target="_blank"><i class="sprite sprite-sharpspring"      ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/sparkpost"      target="_blank"><i class="sprite sprite-sparkpost"        ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/tripolis"       target="_blank"><i class="sprite sprite-tripolis"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/unisender"      target="_blank"><i class="sprite sprite-unisender"        ></i></a></div>
                                </div>
                            </div>
                            <div class="cv-vertical">
                                <div class="cv-icon-list">
                                    <div class="cv-icon"><a href="https://sperse.io/go/mailerlite"     target="_blank"><i class="sprite sprite-mailerliteicon"   ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/mailgun"        target="_blank"><i class="sprite sprite-mailgun"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/mailify"        target="_blank"><i class="sprite sprite-mailify"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/mailjet"        target="_blank"><i class="sprite sprite-mailjet"          ></i></a></div>
                                    <div class="cv-icon"><a href="#"                                   target="_blank"><i class="sprite sprite-pdf"              ></i></a></div>
                                    <div class="cv-icon"><a href="#"                                   target="_blank"><i class="sprite sprite-pepipost"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/webhookout"     target="_blank"><i class="sprite sprite-webhook"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/webinarjam"     target="_blank"><i class="sprite sprite-webinarjam"       ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/woocommerce"    target="_blank"><i class="sprite sprite-woocommerce"      ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/woodpecker"     target="_blank"><i class="sprite sprite-woodpecker"       ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/zoom"           target="_blank"><i class="sprite sprite-zoom"             ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/airtable"       target="_blank"><i class="sprite sprite-airtable"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/twilio"         target="_blank"><i class="sprite sprite-twilio"           ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/drip"           target="_blank"><i class="sprite sprite-drip"             ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/slack"          target="_blank"><i class="sprite sprite-slack"            ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/calendly"       target="_blank"><i class="sprite sprite-calendly"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/zendesk"        target="_blank"><i class="sprite sprite-zendesk"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/directiq"       target="_blank"><i class="sprite sprite-directiq"         ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/lemlist"        target="_blank"><i class="sprite sprite-lemlist"          ></i></a></div>
                                    <div class="cv-icon"><a href="https://sperse.io/go/clinchpad"      target="_blank"><i class="sprite sprite-clinchpad"        ></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
