<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$message = '<div bgcolor="#e3e3e3" style="font-family:Arial;color:#707070;font-size:12px;background-color:#e3e3e3;margin:0;padding:0px">
<div align="center" style="font-family:Arial;width:600px;background-color:#ffffff;margin:0 auto;padding:0px">
    <div style="font-family:Arial;border-bottom-color:#cccccc;border-bottom-width:1px;border-bottom-style:solid;background-color:#eee;margin:0px;padding:4px">
       <a href="http://www.wpseeds.com"><img src="http://www.wpseeds.com/wp-content/uploads/2015/06/wpseedslogo.png" alt="WPSeeds-WprdPress Products" /></a>
    </div>

    <div align="left" style="font-family:Arial;text-align:left;margin:0px;padding:10px">
        <div>
    Dear <strong style="font-family:Arial;margin:0px;padding:0px">WP ALL Backup User</strong>, <br><br>

   Backup Created Successfully on ' . $site_url . '.      

    <br><br>   
     You can download the backup from the your site admin dashboard.
    <br><br>   
            <h3 style="font-family:Arial;font-size:14px;font-weight:bold;margin:0 0 5px 5px;padding:0px">Details as follow</h3>

                 
            <table width="100%" cellspacing="0" cellpadding="0" style="font-family:Arial;width:100%;border-collapse:collapse;border-spacing:0;margin:0px;padding:0px">
                <tbody><tr style="font-family:Arial;margin:0px;padding:0px">   
					<th bgcolor="#007bad" align="center" style="width:30px;font-family:Arial;text-align:center;color:#ffffff;font-size:11px;background-color:#007bad;margin:0px;padding:5px 2px;border:1px solid #007bad">#</th>
                    <th bgcolor="#007bad" align="center" style="width:250px;font-family:Arial;text-align:center;color:#ffffff;font-size:11px;background-color:#007bad;margin:0px;padding:5px 2px;border:1px solid #007bad">File Name</th>
                    <th bgcolor="#007bad" align="center" style="font-family:Arial;text-align:center;color:#ffffff;font-size:11px;background-color:#007bad;margin:0px;padding:5px 2px;border:1px solid #007bad">Size</th>
					<th bgcolor="#007bad" align="center" style="width:30px;font-family:Arial;text-align:center;color:#ffffff;font-size:11px;background-color:#007bad;margin:0px;padding:5px 2px;border:1px solid #007bad">Type</th>
                </tr>				
                    <tr style="font-family:Arial;margin:0px;padding:0px">                   
					<td style="font-family:Arial;margin:0px;padding:2px 5px;border:1px solid #007bad;text-align:right">1</td>
                    <td style="font-family:Arial;margin:0px;padding:2px 5px;border:1px solid #007bad">' . $filename . '</td>
                    <td style="font-family:Arial;margin:0px;padding:2px 5px;border:1px solid #007bad">' . WPALLBackupEmail::wp_all_backup_format_bytes($filesze) . '</td>
					<td style="font-family:Arial;margin:0px;padding:2px 5px;border:1px solid #007bad;text-align:right">' . $args[5] . '</td>
                </tr>
                             
                            </tbody></table><br>
                             <p>' . $args[2] . '</p>
    
    <br>
    Thank you for using WP ALL Backup Plugin.
</div>


    </div>

    <div style="font-family:Arial;border-top-style:solid;border-top-color:#cccccc;border-top-width:1px;color:#707070;font-size:12px;background-color:#efefef;margin:0px;padding:15px">
        <table width="100%" cellspacing="0" cellpadding="0" style="font-family:Arial;color:#707070;font-size:12px;margin:0px;padding:0px">
            <tbody><tr style="font-family:Arial;margin:0px;padding:0px">
                <td width="300" valign="top" align="center" style="font-family:Arial;margin:0px;padding:0px">

                    <h4 style="font-family:Arial;margin:0 0 5px;padding:0px">Contacts</h4>
                    <dl style="font-family:Arial;font-size:16px;font-weight:bold;text-align:left;margin:0px 10px 10px;padding:0px">
                        <dt style="font-family:Arial;font-size:13px;font-weight:bold;margin:0px;padding:0px">
                            Tech Support:
                        </dt>
                        <dd style="font-family:Arial;font-weight:normal;font-size:12px;margin:0 0 0 15px;padding:0px">
                           http://www.wpseeds.com/support/
                        </dd>

                        <dt style="font-family:Arial;font-size:13px;font-weight:bold;margin:0px;padding:0px">
                          Documentation
                        </dt>
                        <dd style="font-family:Arial;font-weight:normal;font-size:12px;margin:0 0 0 15px;padding:0px">
                           http://www.wpseeds.com/wp-all-backup/
                        </dd>						
                        <dt style="font-family:Arial;font-size:13px;font-weight:bold;margin:0px;padding:0px">
                            General Info/Inquiry:
                        </dt>
                        <dd style="font-family:Arial;font-weight:normal;font-size:12px;margin:0 0 0 15px;padding:0px">
                          <a target="_blank" style="font-family:Arial;color:#336699;font-weight:normal;text-decoration:underline;margin:0px;padding:0px" href="mailto:info@wpseeds.com">info@wpseeds.com</a>
                        </dd>                     

                       
                    </dl>
                </td>
               
            </tr>
        </tbody></table>

        <div style="font-family:Arial;margin:0px;padding:0px">Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/shop/">All WPSeeds other product.</a> Use Coupon code WPSEEDS25
        </div>
    </div>

    <div style="font-family:Arial;border-top-width:1px;border-top-color:#cccccc;border-top-style:solid;background-color:#eee;margin:0px;padding:10px">
        You\'re receiving this email because you have active Email Notification on your site(' . $site_url . ').
		<br>If you don\'t like to receieve a Email Notification then login to (' . $site_url . ') and goto (Dashboard=>WP ALL Backup=>Destination=>Email Notification) remove email address.
		<div class="yj6qo"></div><div class="adL">
    </div></div><div class="adL">
</div></div><div class="adL">
</div></div>';