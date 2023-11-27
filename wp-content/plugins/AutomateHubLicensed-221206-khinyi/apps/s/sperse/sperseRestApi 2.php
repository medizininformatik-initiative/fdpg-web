<?php
class sperseRestApi
{

    public function __construct()
    {
        add_action("rest_api_init", array(
            $this,
            "create_webhook_route"
        ));
       
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/userCreatedOrUpdated', array(
            'methods' => array(
                'GET',
                'POST'
            ) ,
            'callback' => array(
                $this,
                'get_webhook_data'
            ) ,
            'permission_callback' => function ()
            {
                return '';
            }
        ));
    }

    public function get_webhook_data($request)
    {
        $params = $request->get_params();

        // $order            = wc_get_order(262426);
        // $order->update_meta_data('get_webhook_data_params', $params);
        // $order->save();
        unset($params['rest_route']);
        global $wpdb;
        $dataa = $params;
        $allResults = [];
        $allErrorResults = [];
        $chErrors = false;
        if (is_array($dataa) && !empty($dataa))
        {
            foreach ($dataa as $data)
            {
                if (isset($data['firstName']) && isset($data['lastName']) && isset($data['emailAddress']) && isset($data['userId']) && isset($data['contactId']) && !empty($data['firstName']) && !empty($data['lastName']) && !empty($data['emailAddress']) && !empty($data['userId']) && !empty($data['contactId']))
                {
                    if ($data['isDeleted'])
                    {
                        $chErrors = true;

                    }
                    else
                    {

                        $affiliateCode = "";
                        $userKey = "";
                        if (isset($data['affiliateCode']) && !empty($data['affiliateCode']))
                        {
                            if (is_null($data['affiliateCode']))
                            {
                                $affiliateCode = $data['firstName'] . "." . $data['lastName'] . substr(md5(microtime()) , rand(0, 26) , 5);
                            }
                            else
                            {
                                $affiliateCode = $data['affiliateCode'];
                            }
                        }
                        else
                        {
                            $affiliateCode = $data['firstName'] . "." . $data['lastName'] . substr(md5(microtime()) , rand(0, 26) , 5);
                        }
                        if (isset($data['userKey']) && !empty($data['userKey']))
                        {
                            if (is_null($data['userKey']))
                            {
                                $userKey = "";
                            }
                            else
                            {
                                $userKey = $data['userKey'];
                            }
                        }
                        else
                        {
                            $userKey = "";
                        }
                        if (isset($data['contactXref']) && !empty($data['contactXref']))
                        {
                            if (is_null($data['contactXref']))
                            {
                                $contactXref = "";
                            }
                            else
                            {
                                $contactXref = $data['contactXref'];
                            }
                        }
                        else
                        {
                            $contactXref = "";
                        }
                        global $wpdb;
                        $rs = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}usermeta WHERE (meta_key = '_userId' AND meta_value = %d )",$data['userId'] ));
                        if (empty($rs))
                        {
                            $resp = $this->wpAddUser($data['firstName'], $data['lastName'], $data['emailAddress'], $data['contactId'], $data['userId'], $userKey, $affiliateCode, $contactXref);
                            if ($resp['success'] === false)
                            {
                                if ($resp['message'] == "Email is already taken")
                                {
                                    $resp = $this->updateUserIDIfEmailExists($data['firstName'], $data['lastName'], $data['emailAddress'], $data['contactId'], $data['userId'], $userKey, $affiliateCode, $contactXref);
                                    if ($resp['success'] === false)
                                    {
                                        array_push($allErrorResults, $resp);
                                        $chErrors = true;
                                    }
                                    else
                                    {
                                        array_push($allErrorResults, $resp);
                                    }
                                }
                                else
                                {
                                    array_push($allErrorResults, $resp);
                                    $chErrors = true;
                                }
                            }
                            else
                            {
                                array_push($allErrorResults, $resp);
                            }
                        }
                        else
                        {
                            $resp = $this->updateAffiliateCode($data['firstName'], $data['lastName'], $data['emailAddress'], $data['contactId'], $data['userId'], $userKey, $affiliateCode, $contactXref);
                            if ($resp['success'] === false)
                            {
                                array_push($allErrorResults, $resp);
                                $chErrors = true;
                            }
                            else
                            {
                                array_push($allErrorResults, $resp);
                            }
                        }

                        $affiliateCode = $data['affiliateCode'];
                        if (is_null($data['affiliateRate']))
                        {
                            $affiliateRate = 0;
                        }
                        else
                        {
                            $affiliateRate = $data['affiliateRate'];
                        }
                        // if (is_null($data['affiliateContactAffiliateCode)) {
                        // $affiliateContactAffiliateCode = 'bankcode';
                        // } else {
                        $affiliateContactAffiliateCode = $data['affiliateContactAffiliateCode'];
                        // }
                        

                        $emailaddress = $data['emailAddress'];

                        $affiliateRateRound = $affiliateRate * 100;
                        $affiliateRateRound = round($affiliateRateRound, 2);

                        $create_customers_resp = $this->create_customers($emailaddress, $affiliateCode, $affiliateContactAffiliateCode, $affiliateRateRound);

                        if ($create_customers_resp['success'] === false)
                        {
                            array_push($allErrorResults, $create_customers_resp);
                            $chErrors = true;
                        }
                        else
                        {
                            array_push($allErrorResults, $create_customers_resp);
                        }

                    }

                }
                else
                {
                    $respo = array(
                        "success" => false,
                        "validationResults" => "firstName, lastName, emailAddress, contactId,  userId is Incorrect/Empty."
                    );
                    array_push($allErrorResults, $respo);
                    $chErrors = true;
                    // http_response_code(400);
                    // echo json_encode();
                    
                }
            }

            if ($chErrors)
            {
                http_response_code(400);
                echo json_encode(array(
                    "success" => false,
                    "validationResults" => $allErrorResults
                ));
                global $wpdb;
                if (!empty($_SERVER['HTTP_CLIENT_IP']))
                {
                     $ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);                //ip from share internet
                }
                elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                {
                $ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);          //ip pass from proxy
                }
                else
                {
                $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
                }
                $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) :''; 
                $request = json_encode($data);
                $wpdb->insert("{$wpdb->prefix}userCreatedOrUpdated", array(
                    "request" => $request,
                    "response" => json_encode($allErrorResults) ,
                    "success" => false,
                    "Ip" => $ip,
                    "userAgent" => $userAgent,
                ));
            }
            else
            {
                http_response_code(200);
                echo json_encode(array(
                    "success" => true
                ));
                global $wpdb;
                if (!empty($_SERVER['HTTP_CLIENT_IP']))
                {
                     $ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);                //ip from share internet
                }
                elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                {
                $ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);          //ip pass from proxy
                }
                else
                {
                $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
                }
                $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) :''; 

                $request = json_encode($data);
                $wpdb->insert("{$wpdb->prefix}userCreatedOrUpdated", array(
                    "request" => $request,
                    "response" => json_encode($allErrorResults) ,
                    "success" => true,
                    "Ip" => $ip,
                    "userAgent" => $userAgent,
                ));
                // $query = "INSERT INTO wp_userCreatedOrUpdated
                // SET request = :request,
                //     response = :response,
                //     success = :success";
                // $stmt = $conn->prepare($query);
                // $stmt->bindParam(':request', $request);
                // $stmt->bindParam(':response', "true");
                // $stmt->bindParam(':success', true);
                // if($stmt->execute()){}
                
            }
        }
        else
        {
            http_response_code(400);
            echo json_encode(array(
                "success" => false,
                "validationResults" => "Request is Invalid."
            ));
        }

    }

    public function updateUserIDIfEmailExists($firstName, $lastName, $emailAddress, $contactId, $userIdSperse, $userKey, $affiliateCode, $contactXref)
    {
        global $wpdb;
        $rs = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE (user_login = %s)",$affiliateCode ));
        if (!empty($rs))
        {
            $userId = email_exists($emailAddress);
            $user_info = get_userdata($userId);
            $user_login = $user_info->user_login;
            if (strtoupper($user_login) == strtoupper($affiliateCode))
            {
                $checkEmails = email_exists($emailAddress);
                if ($checkEmails)
                {
                    $userId = $checkEmails;
                    $this->create_affiliate_account($userId, $emailAddress);
                    update_user_meta($userId, "first_name", $firstName);
                    update_user_meta($userId, "last_name", $lastName);
                    update_user_meta($userId, "_userId", $userIdSperse);
                    update_user_meta($userId, "_userKey", $userKey);
                    update_user_meta($userId, "_contactId", $contactId);
                    update_user_meta($userId, "_contactXref", $contactXref);
                    update_user_meta($userId, "billing_email", $emailAddress);
                    update_user_meta($userId, "nickname", $affiliateCode);
                    $wpdb->update($wpdb->users, array(
                        'user_login' => $affiliateCode
                    ) , array(
                        'ID' => $userId
                    ));
                    return array(
                        "success" => true,
                        "message" => "User is Updated",
                        "affiliateCode" => $affiliateCode
                    );
                }
            }
            else
            {
                return array(
                    "success" => false,
                    "message" => "affiliateCode is already taken 1",
                    "userId" => $userIdSperse
                );
            }
        }
        else
        {
            $checkEmails = email_exists($emailAddress);
            if ($checkEmails)
            {
                $userId = $checkEmails;
                $this->create_affiliate_account($userId, $emailAddress);
                update_user_meta($userId, "first_name", $firstName);
                update_user_meta($userId, "last_name", $lastName);
                update_user_meta($userId, "_userId", $userIdSperse);
                update_user_meta($userId, "_userKey", $userKey);
                update_user_meta($userId, "_contactId", $contactId);
                update_user_meta($userId, "_contactXref", $contactXref);
                update_user_meta($userId, "billing_email", $emailAddress);
                update_user_meta($userId, "nickname", $affiliateCode);
                $wpdb->update($wpdb->users, array(
                    'user_login' => $affiliateCode
                ) , array(
                    'ID' => $userId
                ));
                return array(
                    "success" => true,
                    "message" => "User is Updated",
                    "affiliateCode" => $affiliateCode
                );
            }
        }
    }

    public function wpAddUser($firstName, $lastName, $emailAddress, $contactId, $userIdSperse, $userKey, $affiliateCode, $contactXref)
    {
        global $wpdb;
        $checkEmails = email_exists($emailAddress);
        $rs = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE (user_login = %s)", $affiliateCode));
        if ($checkEmails)
        {
            return array(
                "success" => false,
                "message" => "Email is already taken",
                "userId" => $userIdSperse
            );
            // http_response_code(200);
            // echo json_encode(array("status"=>false,"message" => "Email is already taken" ));
            
        }
        else if (!empty($rs))
        {
            return array(
                "success" => false,
                "message" => "affiliateCode is already taken 2",
                "userId" => $userIdSperse
            );
            // http_response_code(200);
            // echo json_encode(array("status"=>false,"message" => "affiliateCode is already taken" ));
            
        }
        else
        {
            $user_id = $this->wpInsertUser($firstName, $lastName, $emailAddress);
            if (is_wp_error($user_id))
            {
                // echo $user_id->get_error_message();
                if (isset($user_id->errors['existing_user_login'][0]))
                {
                    //user_login is already exists
                    $rand = substr(md5(microtime()) , rand(0, 26) , 5);
                    $lastName = $lastName . $rand;
                    $userId = $this->wpInsertUser($firstName, $lastName, $emailAddress);
                    // var_dump($userId);
                    if (is_wp_error($userId))
                    {
                        return array(
                            "success" => false,
                            "message" => "affiliateCode is already taken 3"
                        );
                        // http_response_code(200);
                        // echo json_encode(array("status"=>false,"message" =>  $userId->get_error_message()));
                        //    		http_response_code(200);
                        // echo json_encode(array("status"=>false,"message" => "affiliateCode is already taken" ));
                        
                    }
                    else
                    {
                        update_user_meta($userId, "first_name", $firstName);
                        update_user_meta($userId, "last_name", $lastName);
                        update_user_meta($userId, "_userKey", $userKey);
                        update_user_meta($userId, "_userId", $userIdSperse);
                        update_user_meta($userId, "_contactId", $contactId);
                        update_user_meta($userId, "_contactXref", $contactXref);
                        update_user_meta($userId, "billing_email", $emailAddress);
                        update_user_meta($userId, "nickname", $affiliateCode);
                        $affAcc = $this->create_affiliate_account($userId, $emailAddress);
                        $user_info = get_userdata($userId);
                        $user_nicename = $user_info->user_nicename;
                        $user_nicename = str_replace("-", ".", $user_nicename);
                        // $wpdb->update($wpdb->users, array('user_login' => $user_nicename), array('ID' => $userId));
                        if (empty($affiliateCode))
                        {
                            $affiliateCode = $user_nicename;
                        }
                        $this->checkAffCode($affiliateCode, $userId, $emailAddress);
                        // 		$res = update_user_meta( $userId, "nickname", $affiliateCode );
                        //      	if($res) {
                        //      		$wpdb->update($wpdb->users, array('user_login' => $affiliateCode), array('ID' => $userId));
                        // 	http_response_code(200);
                        // 	echo json_encode(array("status"=>true,"message" => "User is Created" ));
                        // } else {
                        // 	http_response_code(200);
                        // 	echo json_encode(array("status"=>false,"message" => "affiliateCode is already taken" ));
                        // }
                        
                    }
                }
                if (isset($user_id->errors['existing_user_email'][0]))
                {
                    // user email is already exits
                    // http_response_code(200);
                    //  		echo json_encode(array("status"=>false,"message" =>  $user_id->get_error_message()));
                    return array(
                        "success" => false,
                        "message" => "Email is already taken"
                    );
                    //  	http_response_code(200);
                    // echo json_encode(array("status"=>false,"message" => "Email is already taken" ));
                    
                }
            }
            else
            {
                // var_dump($user_id);
                update_user_meta($user_id, "first_name", $firstName);
                update_user_meta($user_id, "last_name", $lastName);
                update_user_meta($user_id, "_userKey", $userKey);
                update_user_meta($user_id, "_userId", $userIdSperse);
                update_user_meta($user_id, "_contactId", $contactId);
                update_user_meta($userId, "_contactXref", $contactXref);
                update_user_meta($user_id, "billing_email", $emailAddress);
                update_user_meta($user_id, "nickname", $affiliateCode);
                $affAcc = $this->create_affiliate_account($user_id, $emailAddress);
                $user_info = get_userdata($user_id);
                $user_nicename = $user_info->user_nicename;
                $user_nicename = str_replace("-", ".", $user_nicename);
                // $wpdb->update($wpdb->users, array('user_login' => $user_nicename), array('ID' => $user_id));
                if (empty($affiliateCode))
                {
                    $affiliateCode = $user_nicename;
                }
                $res = $this->checkAffCode($affiliateCode, $user_id, $emailAddress);
                if ($res['success'] === true)
                {
                    return array(
                        "success" => true,
                        "message" => "User is Created",
                        "affiliateCode" => $affiliateCode
                    );
                }
                else
                {
                    return array(
                        "success" => false,
                        "message" => "affiliateCode is already taken 4"
                    );
                }
                // 		$res = update_user_meta( $user_id, "nickname", $affiliateCode );
                // if($res) {
                // 	$wpdb->update($wpdb->users, array('user_login' => $affiliateCode), array('ID' => $user_id));
                // 	http_response_code(200);
                // 	echo json_encode(array("status"=>true,"message" => "User is Created" ));
                // } else {
                // 	http_response_code(200);
                // 	echo json_encode(array("status"=>false,"message" => "affiliateCode is already tsaken" ));
                // }
                
            }
        }
    }

    public function checkAffCode($affiliateCode, $userId, $emailAddress)
    {
        global $wpdb;
        $rs = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE (user_login = %s)", $affiliateCode));
        if (empty($rs))
        {
            update_user_meta($userId, "nickname", $affiliateCode);
            $wpdb->update($wpdb->users, array(
                'user_login' => $affiliateCode
            ) , array(
                'ID' => $userId
            ));

            $res = $this->checkIfUserExists($emailAddress);
            if ($res['success'] === true)
            {
                return array(
                    "success" => true,
                    "message" => "User is Created",
                    "affiliateCode" => $affiliateCode
                );
            }
            // http_response_code(200);
            // echo json_encode(array("success"=>true,"message" => "User is Created", "affiliateCode"=> $affiliateCode ));
            
        }
        else
        {
            return array(
                "success" => true,
                "message" => "affiliateCode exists 5"
            );
            // http_response_code(200);
            // echo json_encode(array("success"=>false,"message" => "affiliateCode is already taken" ));
            
        }
    }

    public function updateAffiliateCode($firstName, $lastName, $emailAddress, $contactId, $userIdSperse, $userKey, $affiliateCode, $contactXref)
    {
        global $wpdb;
        $rs = $wpdb->get_results($wpdb->prepare(  "SELECT {$wpdb->prefix}users.ID, {$wpdb->prefix}users.user_login FROM {$wpdb->prefix}usermeta INNER JOIN {$wpdb->prefix}users ON {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id WHERE ({$wpdb->prefix}usermeta.meta_key = '_userId' AND {$wpdb->prefix}usermeta.meta_value = %s) AND ({$wpdb->prefix}users.user_email = %s)", $userIdSperse,$emailAddress) );





        if (empty($rs))
        {
            $rs = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->prefix}usermeta WHERE (meta_key = '_userId' AND meta_value =%s )", $userIdSperse));
            $userId = $rs[0]->user_id;
            $wpdb->update($wpdb->users, array(
                'user_email' => $emailAddress
            ) , array(
                'ID' => $userId
            ));
            update_user_meta($userId, "first_name", $firstName);
            update_user_meta($userId, "last_name", $lastName);
            update_user_meta($userId, "_userId", $userIdSperse);
            update_user_meta($userId, "_userKey", $userKey);
            update_user_meta($userId, "_contactId", $contactId);
            update_user_meta($userId, "_contactXref", $contactXref);
            update_user_meta($userId, "billing_email", $emailAddress);
            update_user_meta($userId, "nickname", $affiliateCode);
            $this->create_affiliate_account($userId, $emailAddress);
            $user_info = get_userdata($userId);
            $user_login = $user_info->user_login;
            $user_nicename = $user_info->user_nicename;
            $user_nicename = str_replace("-", ".", $user_nicename);
            if ($user_login == $affiliateCode)
            {
                return array(
                    "success" => true,
                    "message" => "User is Updated",
                    "affiliateCode" => $affiliateCode
                );
                // 		http_response_code(200);
                // echo json_encode(array("status"=>true,"message" => "User is Updated", "affiliateCode"=> $affiliateCode ));
                
            }
            else
            {
                $user_info = get_userdata($userId);
                $user_login = $user_info->user_login;
                $user_nicename = $user_info->user_nicename;
                $user_nicename = str_replace("-", ".", $user_nicename);
                $affiliateCodee = $affiliateCode;
                if (empty($affiliateCode))
                {
                    $affiliateCode = $user_nicename;
                }
                $rs = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE (user_login = %s)",$affiliateCode ));
                if (empty($rs))
                {
                    $wpdb->update($wpdb->users, array(
                        'user_login' => $affiliateCode
                    ) , array(
                        'ID' => $userId
                    ));
                    return array(
                        "success" => true,
                        "message" => "User is Updated",
                        "affiliateCode" => $affiliateCode
                    );
                    // http_response_code(200);
                    // echo json_encode(array("success"=>true,"message" => "User is Created", "affiliateCode"=> $affiliateCode ));
                    
                }
                else
                {
                    // return array(
                    //     "success" => false,
                    //     "message" => "affiliateCode is already taken"
                    // );
                    if (empty($affiliateCodee))
                    {
                        //
                        return array(
                            "success" => true,
                            "message" => "User is Updated",
                            "affiliateCode" => $rs[0]->user_login,
                            "userId" => $userId
                        );
                    }
                    else
                    {
                        return array(
                            "success" => false,
                            "message" => "affiliateCode is already taken 6"
                        );
                    }
                    // http_response_code(200);
                    // echo json_encode(array("success"=>false,"message" => "affiliateCode is already taken" ));
                    
                }
            }
        }
        else
        {
            $userId = email_exists($emailAddress);
            if ($userId)
            {
                $user_info = get_userdata($userId);
                $user_login = $user_info->user_login;
                $user_nicename = $user_info->user_nicename;
                $user_nicename = str_replace("-", ".", $user_nicename);
                if ($userId == $rs[0]->ID)
                {
                    $wpdb->update($wpdb->users, array(
                        'user_email' => $emailAddress
                    ) , array(
                        'ID' => $userId
                    ));
                    $userId = email_exists($emailAddress);
                    update_user_meta($userId, "first_name", $firstName);
                    update_user_meta($userId, "last_name", $lastName);
                    update_user_meta($userId, "_userId", $userIdSperse);
                    update_user_meta($userId, "_userKey", $userKey);
                    update_user_meta($userId, "_contactId", $contactId);
                    update_user_meta($userId, "_contactXref", $contactXref);
                    update_user_meta($userId, "billing_email", $emailAddress);
                    update_user_meta($userId, "nickname", $affiliateCode);
                    $this->create_affiliate_account($userId, $emailAddress);
                    if (strtoupper($affiliateCode) == strtoupper($rs[0]->user_login))
                    {
                        return array(
                            "success" => true,
                            "message" => "User is Updated",
                            "affiliateCode" => $affiliateCode
                        );
                        // http_response_code(200);
                        // echo json_encode(array("status"=>true,"message" => "User is Updated", "affiliateCode"=>$affiliateCode ));
                        
                    }
                    else
                    {
                        $affiliateCodee = $affiliateCode;
                        if (empty($affiliateCode))
                        {
                            $affiliateCode = $user_nicename;
                        }
                        $rs = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE (user_login =%s )", $affiliateCode ));
                        if (empty($rs))
                        {
                            $wpdb->update($wpdb->users, array(
                                'user_login' => $affiliateCode
                            ) , array(
                                'ID' => $userId
                            ));
                            return array(
                                "success" => true,
                                "message" => "User is Updated",
                                "affiliateCode" => $affiliateCode
                            );
                            // http_response_code(200);
                            // echo json_encode(array("success"=>true,"message" => "User is Created", "affiliateCode"=> $affiliateCode ));
                            
                        }
                        else
                        {
                            // return array(
                            //     "success" => false,
                            //     "message" => "affiliateCode is already taken"
                            // );
                            if (empty($affiliateCodee))
                            {
                                //
                                return array(
                                    "success" => true,
                                    "message" => "User is Updated",
                                    "affiliateCode" => $rs[0]->user_login,
                                    "userId" => $userId
                                );
                            }
                            else
                            {
                                return array(
                                    "success" => false,
                                    "message" => "affiliateCode is already taken 7"
                                );
                            }
                            // http_response_code(200);
                            // echo json_encode(array("success"=>false,"message" => "affiliateCode is already taken" ));
                            
                        }
                        // $res = update_user_meta($userId, "nickname", $affiliateCode);
                        // if ($res)
                        // {
                        //     $wpdb->update($wpdb->users, array(
                        //         'user_login' => $affiliateCode
                        //     ) , array(
                        //         'ID' => $userId
                        //     ));
                        //     return array(
                        //         "success" => true,
                        //         "message" => "User is Updated",
                        //         "affiliateCode" => $affiliateCode
                        //     );
                        //     // http_response_code(200);
                        //     // echo json_encode(array("status"=>true,"message" => "User is Updated", "affiliateCode"=>$affiliateCode ));
                        // }
                        // else
                        // {
                        //     return array(
                        //         "success" => false,
                        //         "message" => "affiliateCode is already taken"
                        //     );
                        //     // http_response_code(200);
                        //     // echo json_encode(array("status"=>false,"message" => "affiliateCode is already taken" ));
                        // }
                        
                    }
                }
                else
                {
                    return array(
                        "success" => true,
                        "message" => "User is Updated",
                        "affiliateCode" => $affiliateCode
                    );
                    // http_response_code(200);
                    // echo json_encode(array("status"=>true,"message" => "User is Updated", "affiliateCode"=>$affiliateCode ));
                    
                }
            }
        }
    }

    public function deleteUserFromWp($sperseUserId)
    {
        require_once (ABSPATH . 'wp-admin/includes/user.php');
        global $wpdb;
        $rs = $wpdb->get_results($wpdb->prepare("SELECT {$wpdb->prefix}usermeta.user_id FROM {$wpdb->prefix}usermeta WHERE ({$wpdb->prefix}usermeta.meta_key = '_userId' AND {$wpdb->prefix}usermeta.meta_value =%s)", $sperseUserId));

        if (!empty($rs))
        {

            $userId = $rs[0]->user_id;

            if (wp_delete_user($userId))
            {

                $wpdb->delete('{$wpdb->prefix}affiliate_wp_affiliates', array(
                    'user_id' => $userId
                ));

                return array(
                    "success" => true,
                    "message" => "User is Deleted"
                );

                // $wpdb->query(
                //   "DELETE FROM wp_affiliate_wp_affiliates WHERE user_id = '".$userId."'"
                // );
                

                
            }
            else
            {

                return array(
                    "success" => false,
                    "message" => "User is not Deleted"
                );

            }
        }
        else
        {
            return array(
                "success" => false,
                "message" => "User Id not found in WP"
            );
        }

    }

    public function wpInsertUser($firstName, $lastName, $emailAddress)
    {
        $rFirstName = $this->removeDiacriticsAccents($firstName);
        $rLastName = $this->removeDiacriticsAccents($lastName);
        if (strlen($rFirstName) != mb_strlen($rFirstName, 'utf-8'))
        {
            $user_login = explode("@", $emailAddress) [0];
        }
        else
        {
            $user_login = $rFirstName . "." . $rLastName;
        }
        $random_password = wp_generate_password(8, false);
        $user_data = array(
            'user_login' => $user_login,
            // 'user_pass' => $random_password,
            'user_email' => $emailAddress,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role' => 'Subscriber'
        );
        $user_id = wp_insert_user($user_data);
        return $user_id;
    }

    public function removeDiacriticsAccents($str)
    {
        $a = array(
            'À',
            'Á',
            'Â',
            'Ã',
            'Ä',
            'Å',
            'Æ',
            'Ç',
            'È',
            'É',
            'Ê',
            'Ë',
            'Ì',
            'Í',
            'Î',
            'Ï',
            'Ð',
            'Ñ',
            'Ò',
            'Ó',
            'Ô',
            'Õ',
            'Ö',
            'Ø',
            'Ù',
            'Ú',
            'Û',
            'Ü',
            'Ý',
            'ß',
            'à',
            'á',
            'â',
            'ã',
            'ä',
            'å',
            'æ',
            'ç',
            'è',
            'é',
            'ê',
            'ë',
            'ì',
            'í',
            'î',
            'ï',
            'ñ',
            'ò',
            'ó',
            'ô',
            'õ',
            'ö',
            'ø',
            'ù',
            'ú',
            'û',
            'ü',
            'ý',
            'ÿ',
            'Ā',
            'ā',
            'Ă',
            'ă',
            'Ą',
            'ą',
            'Ć',
            'ć',
            'Ĉ',
            'ĉ',
            'Ċ',
            'ċ',
            'Č',
            'č',
            'Ď',
            'ď',
            'Đ',
            'đ',
            'Ē',
            'ē',
            'Ĕ',
            'ĕ',
            'Ė',
            'ė',
            'Ę',
            'ę',
            'Ě',
            'ě',
            'Ĝ',
            'ĝ',
            'Ğ',
            'ğ',
            'Ġ',
            'ġ',
            'Ģ',
            'ģ',
            'Ĥ',
            'ĥ',
            'Ħ',
            'ħ',
            'Ĩ',
            'ĩ',
            'Ī',
            'ī',
            'Ĭ',
            'ĭ',
            'Į',
            'į',
            'İ',
            'ı',
            'Ĳ',
            'ĳ',
            'Ĵ',
            'ĵ',
            'Ķ',
            'ķ',
            'Ĺ',
            'ĺ',
            'Ļ',
            'ļ',
            'Ľ',
            'ľ',
            'Ŀ',
            'ŀ',
            'Ł',
            'ł',
            'Ń',
            'ń',
            'Ņ',
            'ņ',
            'Ň',
            'ň',
            'ŉ',
            'Ō',
            'ō',
            'Ŏ',
            'ŏ',
            'Ő',
            'ő',
            'Œ',
            'œ',
            'Ŕ',
            'ŕ',
            'Ŗ',
            'ŗ',
            'Ř',
            'ř',
            'Ś',
            'ś',
            'Ŝ',
            'ŝ',
            'Ş',
            'ş',
            'Š',
            'š',
            'Ţ',
            'ţ',
            'Ť',
            'ť',
            'Ŧ',
            'ŧ',
            'Ũ',
            'ũ',
            'Ū',
            'ū',
            'Ŭ',
            'ŭ',
            'Ů',
            'ů',
            'Ű',
            'ű',
            'Ų',
            'ų',
            'Ŵ',
            'ŵ',
            'Ŷ',
            'ŷ',
            'Ÿ',
            'Ź',
            'ź',
            'Ż',
            'ż',
            'Ž',
            'ž',
            'ſ',
            'ƒ',
            'Ơ',
            'ơ',
            'Ư',
            'ư',
            'Ǎ',
            'ǎ',
            'Ǐ',
            'ǐ',
            'Ǒ',
            'ǒ',
            'Ǔ',
            'ǔ',
            'Ǖ',
            'ǖ',
            'Ǘ',
            'ǘ',
            'Ǚ',
            'ǚ',
            'Ǜ',
            'ǜ',
            'Ǻ',
            'ǻ',
            'Ǽ',
            'ǽ',
            'Ǿ',
            'ǿ',
            'Ά',
            'ά',
            'Έ',
            'έ',
            'Ό',
            'ό',
            'Ώ',
            'ώ',
            'Ί',
            'ί',
            'ϊ',
            'ΐ',
            'Ύ',
            'ύ',
            'ϋ',
            'ΰ',
            'Ή',
            'ή'
        );
        $b = array(
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'AE',
            'C',
            'E',
            'E',
            'E',
            'E',
            'I',
            'I',
            'I',
            'I',
            'D',
            'N',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'U',
            'U',
            'U',
            'U',
            'Y',
            's',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'ae',
            'c',
            'e',
            'e',
            'e',
            'e',
            'i',
            'i',
            'i',
            'i',
            'n',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'u',
            'u',
            'u',
            'u',
            'y',
            'y',
            'A',
            'a',
            'A',
            'a',
            'A',
            'a',
            'C',
            'c',
            'C',
            'c',
            'C',
            'c',
            'C',
            'c',
            'D',
            'd',
            'D',
            'd',
            'E',
            'e',
            'E',
            'e',
            'E',
            'e',
            'E',
            'e',
            'E',
            'e',
            'G',
            'g',
            'G',
            'g',
            'G',
            'g',
            'G',
            'g',
            'H',
            'h',
            'H',
            'h',
            'I',
            'i',
            'I',
            'i',
            'I',
            'i',
            'I',
            'i',
            'I',
            'i',
            'IJ',
            'ij',
            'J',
            'j',
            'K',
            'k',
            'L',
            'l',
            'L',
            'l',
            'L',
            'l',
            'L',
            'l',
            'l',
            'l',
            'N',
            'n',
            'N',
            'n',
            'N',
            'n',
            'n',
            'O',
            'o',
            'O',
            'o',
            'O',
            'o',
            'OE',
            'oe',
            'R',
            'r',
            'R',
            'r',
            'R',
            'r',
            'S',
            's',
            'S',
            's',
            'S',
            's',
            'S',
            's',
            'T',
            't',
            'T',
            't',
            'T',
            't',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'W',
            'w',
            'Y',
            'y',
            'Y',
            'Z',
            'z',
            'Z',
            'z',
            'Z',
            'z',
            's',
            'f',
            'O',
            'o',
            'U',
            'u',
            'A',
            'a',
            'I',
            'i',
            'O',
            'o',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'U',
            'u',
            'A',
            'a',
            'AE',
            'ae',
            'O',
            'o',
            'Α',
            'α',
            'Ε',
            'ε',
            'Ο',
            'ο',
            'Ω',
            'ω',
            'Ι',
            'ι',
            'ι',
            'ι',
            'Υ',
            'υ',
            'υ',
            'υ',
            'Η',
            'η'
        );
        return str_replace($a, $b, $str);
    }

    public function create_affiliate_account($user_id, $payment_email)
    {
        global $wpdb;
        $date = date("Y-m-d H:i:s");
        $rs = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}affiliate_wp_affiliates WHERE (user_id =%d )",$user_id ));

        if (empty($rs))
        {

            $wpdb->insert("{$wpdb->prefix}affiliate_wp_affiliates", array(
                'user_id' => $user_id,
                'rate' => '20',
                'rate_type' => 'percentage',
                'flat_rate_basis' => 'per_product',
                'payment_email' => $payment_email,
                'status' => 'active',
                'earnings' => '0',
                'date_registered' => $date
            ));

        }
        else
        {
            $affiliate_id = $rs[0]->affiliate_id;
            $userId = email_exists($payment_email);
            $wpdb->update("{$wpdb->prefix}affiliate_wp_affiliates", array(
                'user_id' => $userId
            ) , array(
                'affiliate_id' => $affiliate_id
            ));
        }
    }

    public function checkIfUserExists($emailAddress)
    {
        global $wpdb;
        $userId = email_exists($emailAddress);
        if (!is_wp_error($userId))
        {
            $user_info = get_userdata($userId);
            $user_login = $user_info->user_login;
            $rs = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}affiliate_wp_affiliates WHERE (payment_email = %s)", $emailAddress ));

            if (empty($rs))
            {
                $this->create_affiliate_account($userId, $emailAddress);

                // http_response_code(200);
                //      	echo json_encode(array("status"=>true,"message" =>  "Success", "userId" => $userId, "userNiceName"=> $user_login));
                return array(
                    "success" => true
                );
            }
            else
            {
                $affiliate_id = $rs[0]->affiliate_id;
                $wpdb->update($wpdb->affiliate_wp_affiliates, array(
                    'user_id' => $userId
                ) , array(
                    'affiliate_id' => $affiliate_id
                ));
                return array(
                    "success" => true
                );
                // http_response_code(200);
                //     	 echo json_encode(array("status"=>true,"message" =>  "Success", "userId" => $userId, "userNiceName"=> $user_login));
                
            }
        }
        else
        {
            return array(
                "success" => true
            );

            
        }
    }

    //////////////////adding affiliate rate-----------------------------------------
    public function create_customers($buyerEmailAddress, $buyerAccessCode, $resellerAccessCode, $affiliateRateRound)
    {
        $logMessage = '';
        $currentDate = date("Y-m-d H:i:s");
                      if (!empty($_SERVER['HTTP_CLIENT_IP']))
                {
                     $ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);                //ip from share internet
                }
                elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                {
                $ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);          //ip pass from proxy
                }
                else
                {
                $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
                }
                $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) :''; 
        global $wpdb;
        $resultsAc = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE user_login = %s",$buyerAccessCode ));
        $buyerWpUserId = $resultsAc[0]->ID;
        $buyerFirstName = get_user_meta($buyerWpUserId, 'first_name', true);
        $buyerLastName = get_user_meta($buyerWpUserId, 'last_name', true);

        // 	check if user id exists
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}affiliate_wp_customers WHERE user_id = %d",$buyerWpUserId));
        if (empty($results))
        {
            $wpdb->insert("{$wpdb->prefix}affiliate_wp_customers", array(
                'user_id' => $buyerWpUserId,
                'email' => $buyerEmailAddress,
                'first_name' => $buyerFirstName,
                'last_name' => $buyerLastName,
                'ip' => $ip,
                'date_created' => $currentDate
            ));

            $lastid = $wpdb->insert_id;
            // 		$wpdb->last_query.
            $logMessage .= "\n customer Id: " . $lastid . "\n New Customer Created \n Calling create_refs...";

            $create_refsResp = $this->create_refs($resellerAccessCode, $lastid, $currentDate, $buyerWpUserId, $affiliateRateRound);
            if ($create_refsResp['success'] === false)
            {
                $logMessage .= $create_refsResp['message'];
                return array(
                    "success" => false,
                    "message" => $logMessage,
                );
            }
            else
            {

                $logMessage .= $create_refsResp['message'];
                return array(
                    "success" => true,
                    "message" => $logMessage,
                );

            }

        }
        else
        {
            //echo "customerId: ";
            $customer_id = $results[0]->customer_id;
            //echo "<br>";
            // echo "Customer existed <br>";
            $logMessage .= "\n customerId: " . $customer_id . " \n Customer existed \n";
            $checkCusUpdate = $wpdb->update("{$wpdb->prefix}affiliate_wp_customers", array(
                'user_id' => $buyerWpUserId,
                'first_name' => $buyerFirstName,
                'last_name' => $buyerLastName,
                'email' => $buyerEmailAddress
            ) , array(
                'customer_id' => $customer_id
            ));
            if ($wpdb->last_error == '')
            {
                // echo "customer Updated: <br>";
                $logMessage .= "customer Updated: \n";
                $create_refsResp = $this->create_refs($resellerAccessCode, $customer_id, $currentDate, $buyerWpUserId, $affiliateRateRound);

                if ($create_refsResp['success'] === false)
                {
                    $logMessage .= $create_refsResp['message'];
                    return array(
                        "success" => false,
                        "message" => $logMessage,
                    );
                }
                else
                {

                    $logMessage .= $create_refsResp['message'];
                    return array(
                        "success" => true,
                        "message" => $logMessage,
                    );

                }

            }
            else
            {

                $logMessage .= "customer not Updated: " . $wpdb->last_query;
                return array(
                    "success" => false,
                    "message" => $logMessage,
                );

            }

        }

    }

    public function create_refs($resellerAccessCode, $lastid, $currentDate, $buyerWpUserId, $affiliateRateRound)
    {

        global $wpdb;
        $logMessage = '';
        $statusCh = false;

        $logMessage .= "resellerAccessCode: " . $resellerAccessCode . "\n";

        $results8 = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}users WHERE user_login =%s ",$resellerAccessCode));
        // echo "Reseller WP UserId: ";
        $resellerUserId = $results8[0]->ID;
        $logMessage .= "Reseller WP UserId: " . $resellerUserId . " \n";
        if (!empty($results8))
        {

            $resellerUserId = $results8[0]->ID;
            $wpdb->update("{$wpdb->prefix}affiliate_wp_affiliates", array(
                'rate' => $affiliateRateRound
            ) , array(
                'user_id' => $buyerWpUserId
            ));

            if (is_null($resellerAccessCode))
            {

                $logMessage .= "affiliateContactAffiliateCode is null \n";

                $statusCh = true;

            }
            else
            {

                $results2 = $wpdb->get_results($wpdb->prepare("SELECT affiliate_id, rate FROM {$wpdb->prefix}affiliate_wp_affiliates WHERE user_id = %d",$resellerUserId));

                $resellerAffiliateId = $results2[0]->affiliate_id;

                $resellerRate = $results2[0]->rate;

                $resellerRate = $resellerRate / 100;
                // $resellerRate  = $affiliateRateRound;
                $logMessage .= "resellerAffiliateId: " . $resellerAffiliateId . "/n " . "Buyer WP User Id: " . $buyerWpUserId . "\n ";

                $results1 = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_value = %s AND meta_key = '_customer_user' ORDER BY post_id ASC LIMIT 1",$buyerWpUserId));

                if (!empty($results1))
                {

                    $orderId = $results1[0]->post_id;
                    $logMessage .= "Parent Order Id: " . $orderId . "\n";

                    $results6 = $wpdb->get_results($wpdb->prepare("SELECT *  FROM {$wpdb->prefix}affiliate_wp_referrals WHERE reference =%d ",$orderId));

                    if (!empty($results6))
                    {

                        $referral_id = $results6[0]->referral_id;
                        //   echo "referral_id: ". $referral_id; echo "<br>";
                        $logMessage .= "referral_id: " . $referral_id . " \n";
                        $checkCrUpdate = $wpdb->update("{$wpdb->prefix}affiliate_wp_referrals", array(
                            'affiliate_id' => $resellerAffiliateId,
                            'customer_id' => $lastid,
                            'amount' => $resellerRate
                        ) , array(
                            'referral_id' => $referral_id
                        ));
                        if ($wpdb->last_error == '')
                        {

                            $logMessage .= "Updated Referrer \n";

                            $statusCh = true;

                            
                        }
                        else
                        {

                            $logMessage .= "Not Updated Referrer \n" . "Query: " . $wpdb->last_query;

                            $statusCh = false;

                        }

                    }
                    else
                    {

                        $wpdb->insert("{$wpdb->prefix}affiliate_wp_referrals", array(
                            'affiliate_id' => $resellerAffiliateId,
                            'visit_id' => 0,
                            'customer_id' => $lastid,
                            //  'description' => 'prodyctname',
                            'status' => 'unpaid',
                            'amount' => $resellerRate,
                            'currency' => 'USD',
                            'context' => 'woocommerce',
                            'type' => 'sale',
                            'reference' => $orderId,
                            'date' => $currentDate
                        ));

                        $lastid = $wpdb->insert_id;
                        if ($lastid)
                        {

                            $logMessage .= "Added Referrer \n";

                            $statusCh = true;

                        }
                        else
                        {

                            $logMessage .= "Not Added Referrer \n" . "Query: " . $wpdb->last_query;

                            $statusCh = false;

                        }

                    }

                }
                else
                {

                    $logMessage .= "Didn't find parent Order Id \n";

                    $statusCh = true;
                }

            }

        }
        else
        {
            $logMessage .= "Didn't find Reseller WP UserId \n";

            $statusCh = false;

        }

        return array(
            "success" => true,
            "successO" => $statusCh,
            "message" => $logMessage,
        );

    }

    //////////////////adding affiliate rate-----------------------------------------
  
    
}
$sperseRestApi = new sperseRestApi();
