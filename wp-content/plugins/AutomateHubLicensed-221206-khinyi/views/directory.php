<?php include AWP_INCLUDES.'/header.php'; ?>
<div class="app-directory">
        <div class="tabs-panel">
            <div class="pages-background"></div>
            <div class="tabs-panel__container">
                <h2 class="main-title"><?php esc_html_e( 'App Directory ', 'automate_hub' ); ?><span class="counter">(<span id="visibleCounter">0</span>)</span></h2>
                <div class="tabs-panel__inner">
                    <div class="tabs-panel__left">
                        <div class="tabs-panel__left-tab semitabactive" id="tab-all" data-type="all">
                            <span class="tabs-panel__left-icon">
                                <svg width="18" height="19" viewBox="0 0 18.126 19">
                                    <g id="Group_9182" data-name="Group 9182" transform="translate(2887 1544)">
                                        <g id="Group_9181" data-name="Group 9181" transform="translate(-2887 -1544)">
                                            <g id="Rectangle_4640" data-name="Rectangle 4640">
                                                <rect id="Rectangle_4647" data-name="Rectangle 4647" width="15" height="16" rx="3" transform="translate(0 3)" fill="none" />
                                                <path id="Round_6916" data-name="Round 6916" d="M12,19H3a3,3,0,0,1-3-3V6A3,3,0,0,1,3,3h9a3,3,0,0,1,3,3V16A3,3,0,0,1,12,19ZM3,4.5A1.5,1.5,0,0,0,1.5,6V16A1.5,1.5,0,0,0,3,17.5h9A1.5,1.5,0,0,0,13.5,16V6A1.5,1.5,0,0,0,12,4.5Z" fill="#616a6e" />
                                            </g>
                                            <path id="copy" d="M18.126,14.176V2.969A2.973,2.973,0,0,0,15.157,0H6.919a.742.742,0,1,0,0,1.484h8.238a1.486,1.486,0,0,1,1.484,1.484V14.176a.742.742,0,0,0,1.484,0Z" fill="#616a6e" />
                                            <path id="Round_6917" data-name="Round 6917" d="M10.646,8.989H4.378a.75.75,0,0,1,0-1.5h6.268a.75.75,0,0,1,0,1.5Z"  fill="#616a6e" />
                                            <path id="Round_6918" data-name="Round 6918" d="M10.646,11.714H4.378a.75.75,0,0,1,0-1.5h6.268a.75.75,0,0,1,0,1.5Z" fill="#616a6e" />
                                            <path id="Round_6919" data-name="Round 6919" d="M10.646,14.439H4.378a.75.75,0,0,1,0-1.5h6.268a.75.75,0,0,1,0,1.5Z" fill="#616a6e" />
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <span class="tabs-panel__left-text"><?php esc_html_e( 'All', 'automate_hub' ); ?></span>
                        </div>
                        <div class="tabs-panel__left-tab" id="tab-forms" data-type="forms">
                            <span class="tabs-panel__left-icon">
                                <svg width="17" height="17" viewBox="0 0 16.712 16.711">
                                    <g id="Group_9171" data-name="Group 9171" transform="translate(-800.484 -147)">
                                        <path id="reminders" d="M5.875,4.341a.653.653,0,0,1,.653-.653H12.8a.653.653,0,0,1,0,1.306H6.528A.653.653,0,0,1,5.875,4.341ZM8.094,11.75H6.528a.653.653,0,1,0,0,1.306H8.094a.653.653,0,0,0,0-1.306ZM6.528,8.91H10.02a.653.653,0,0,0,0-1.306H6.528a.653.653,0,1,0,0,1.306ZM3.917,7.6a.653.653,0,1,0,.653.653A.653.653,0,0,0,3.917,7.6Zm0-3.917a.65.65,0,1,1,0,.005Zm0,8.062a.65.65,0,1,1,0,.005ZM14.1,0H2.611A2.614,2.614,0,0,0,0,2.611V14.1a2.614,2.614,0,0,0,2.611,2.611H7.572a.653.653,0,0,0,0-1.306H2.611A1.307,1.307,0,0,1,1.306,14.1V2.611A1.307,1.307,0,0,1,2.611,1.306H14.1a1.307,1.307,0,0,1,1.306,1.306V8.486a.653.653,0,1,0,1.306,0V2.611A2.614,2.614,0,0,0,14.1,0Z" transform="translate(800.484 147)" fill="#616a6e" />
                                        <path id="reminders-2" data-name="reminders" d="M3.344,0H.686a.653.653,0,1,0,0,1.306H3.344A1.307,1.307,0,0,1,4.65,2.611V5.955c.034.866,1.153,1.009,1.306,0V2.611A2.614,2.614,0,0,0,3.344,0Z" transform="translate(817.195 157.755) rotate(90)" fill="#616a6e" />
                                    </g>
                                </svg>
                            </span>
                            <span class="tabs-panel__left-text "><?php esc_html_e( 'Forms', 'automate_hub' ); ?></span>
                            <div class="tabs-panel__left-popup triangle"> 
                                <span class='head'><strong><?php esc_html_e( 'List of Incoming Data Sources', 'automate_hub' ); ?></strong></span><br/><br/><p>                                
                                <?php esc_html_e( 'To create an integration you must specify a data source. Your input data can be received through the Receiver Webhook, or when using the Wordpress Plugin, from any of the supported Form sources actively installed in your Wordpress site.', 'automate_hub' ); ?>
                                </p>
                            </div>
                        </div>
                        <div class="tabs-panel__left-tab" id="tab-apps" data-type="apps">
                            <span class="tabs-panel__left-icon">
                                <svg width="17" height="17" viewBox="0 0 16.71 16.71">
                                    <path id="voice-message-app" d="M15.4,10.963V2.61A1.307,1.307,0,0,0,14.1,1.3H2.61A1.307,1.307,0,0,0,1.3,2.61V14.1A1.307,1.307,0,0,0,2.61,15.4H14.1a1.307,1.307,0,0,0,1.3-1.3.653.653,0,1,1,1.305,0,2.613,2.613,0,0,1-2.61,2.61H2.61A2.613,2.613,0,0,1,0,14.1V2.61A2.613,2.613,0,0,1,2.61,0H14.1a2.613,2.613,0,0,1,2.61,2.61v8.353a.655.655,0,1,1-1.31,0Zm-3.181.294a1.941,1.941,0,0,0,1.942-1.941V6.983a.653.653,0,0,0-1.305,0V9.315a.639.639,0,0,1-.636.636h0a.637.637,0,0,1-.636-.636V4.568A1.925,1.925,0,0,0,9.658,2.643h0A1.928,1.928,0,0,0,7.733,4.568v7.57a.62.62,0,0,1-.62.62h0a.621.621,0,0,1-.62-.62V7A1.941,1.941,0,1,0,2.61,7v3.525a.653.653,0,0,0,1.305,0V7A.636.636,0,1,1,5.188,7v5.139a1.927,1.927,0,0,0,1.925,1.925h0a1.925,1.925,0,0,0,1.925-1.925V4.568a.621.621,0,0,1,.619-.62h0a.62.62,0,0,1,.62.62V9.316a1.944,1.944,0,0,0,1.942,1.941Z" fill="#616a6e" />
                                </svg>
                            </span>
                            <span class="tabs-panel__left-text"><?php esc_html_e( 'Apps', 'automate_hub' ); ?></span>
                            <div class="tabs-panel__left-popup triangle"> 
                                <span class='head'><strong><?php esc_html_e( 'List of Outgoing Destinaton Apps ', 'automate_hub' ); ?></strong></span><br/><br/><p>
                                <?php esc_html_e( "You can send the received form data to any of the dozens of external applications listed below. You'll need to have valid account credentials with an active acccount for the platform provider in order to connect and send to it.", 'automate_hub' ); ?>
                                </p>
                            </div>
                        </div>
                        <div class="tabs-panel__left-tab" id="tab-enterprise" data-type="enterprises">
                            <span class="tabs-panel__left-icon">
                                <svg width="17" height="17" viewBox="0 0 16.366 16.366">
                                    <g id="Group_9170" data-name="Group 9170" transform="translate(0 0)">
                                        <g id="Group_9169" data-name="Group 9169">
                                            <path id="Round_6908" data-name="Round 6908" d="M279,279.474a1.139,1.139,0,0,0,1.137,1.137h1.336a1.139,1.139,0,0,0,1.137-1.137v-1.336A1.139,1.139,0,0,0,281.474,277h-1.336A1.139,1.139,0,0,0,279,278.137Zm1.137-1.336h1.336v1.336h-1.337Z" transform="translate(-269.954 -268.418)" fill="#616a6e" />
                                            <circle id="Ellipse_619" data-name="Ellipse 619" cx="0.71" cy="0.71" r="0.71" transform="translate(11.379 6.387)" fill="#616a6e" />
                                            <circle id="Ellipse_620" data-name="Ellipse 620" cx="0.71" cy="0.71" r="0.71" transform="translate(9.046 6.387)" fill="#616a6e" />
                                            <path id="Round_6909" data-name="Round 6909" d="M15.727,11.252a.639.639,0,0,0,.639-.639V3.836a2.56,2.56,0,0,0-2.557-2.557h-.831V.639a.64.64,0,0,0-1.279,0v.639H8.791V.639a.64.64,0,0,0-1.279,0v.639H4.635V.639a.639.639,0,1,0-1.279,0v.639h-.8A2.56,2.56,0,0,0,0,3.836v9.973a2.56,2.56,0,0,0,2.557,2.557H13.809a2.56,2.56,0,0,0,2.557-2.557.64.64,0,0,0-1.279,0,1.28,1.28,0,0,1-1.279,1.279H2.557a1.28,1.28,0,0,1-1.279-1.279V3.836A1.28,1.28,0,0,1,2.557,2.557h.8V3.2a.639.639,0,0,0,1.279,0V2.557H7.512V3.2a.64.64,0,1,0,1.279,0V2.557H11.7V3.2a.64.64,0,1,0,1.279,0V2.557h.831a1.28,1.28,0,0,1,1.279,1.279v6.777a.639.639,0,0,0,.638.639Z" fill="#616a6e" />
                                            <circle id="Ellipse_621" data-name="Ellipse 621" cx="0.71" cy="0.71" r="0.71" transform="translate(3.567 10.648)" fill="#616a6e" />
                                            <circle id="Ellipse_626" data-name="Ellipse 626" cx="0.71" cy="0.71" r="0.71" transform="translate(6.408 10.648)" fill="#616a6e" />
                                            <circle id="Ellipse_622" data-name="Ellipse 622" cx="0.71" cy="0.71" r="0.71" transform="translate(3.567 6.387)" fill="#616a6e" />
                                            <circle id="Ellipse_624" data-name="Ellipse 624" cx="0.71" cy="0.71" r="0.71" transform="translate(6.408 6.387)" fill="#616a6e" />
                                            <circle id="Ellipse_623" data-name="Ellipse 623" cx="0.71" cy="0.71" r="0.71" transform="translate(3.567 8.517)" fill="#616a6e" />
                                            <circle id="Ellipse_625" data-name="Ellipse 625" cx="0.71" cy="0.71" r="0.71" transform="translate(6.408 8.517)" fill="#616a6e" />
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <span class="tabs-panel__left-text"><?php esc_html_e( 'Enterprise', 'automate_hub' ); ?></span>
                            <div class="tabs-panel__left-popup triangle"> 
                                <span class='head'><strong><?php esc_html_e( 'Enterprise Apps', 'automate_hub' ); ?></strong></span><br/><br/><p>
                                </p>
                                <?php esc_html_e( 'Enterprise Apps require custom integration or access to 3rd-party software and support. Contact us for implementation details.', 'automate_hub' ); ?>
                            </div>
                        </div>
                        <div class="tabs-panel__left-tab" id="tab-disabled" data-type="disableds">
                            <span class="tabs-panel__left-icon">
                                <svg width="17" height="17" viewBox="0 0 16.71 16.71">
                                    <path id="voice-message-app" d="M15.4,10.963V2.61A1.307,1.307,0,0,0,14.1,1.3H2.61A1.307,1.307,0,0,0,1.3,2.61V14.1A1.307,1.307,0,0,0,2.61,15.4H14.1a1.307,1.307,0,0,0,1.3-1.3.653.653,0,1,1,1.305,0,2.613,2.613,0,0,1-2.61,2.61H2.61A2.613,2.613,0,0,1,0,14.1V2.61A2.613,2.613,0,0,1,2.61,0H14.1a2.613,2.613,0,0,1,2.61,2.61v8.353a.655.655,0,1,1-1.31,0Zm-3.181.294a1.941,1.941,0,0,0,1.942-1.941V6.983a.653.653,0,0,0-1.305,0V9.315a.639.639,0,0,1-.636.636h0a.637.637,0,0,1-.636-.636V4.568A1.925,1.925,0,0,0,9.658,2.643h0A1.928,1.928,0,0,0,7.733,4.568v7.57a.62.62,0,0,1-.62.62h0a.621.621,0,0,1-.62-.62V7A1.941,1.941,0,1,0,2.61,7v3.525a.653.653,0,0,0,1.305,0V7A.636.636,0,1,1,5.188,7v5.139a1.927,1.927,0,0,0,1.925,1.925h0a1.925,1.925,0,0,0,1.925-1.925V4.568a.621.621,0,0,1,.619-.62h0a.62.62,0,0,1,.62.62V9.316a1.944,1.944,0,0,0,1.942,1.941Z" fill="#616a6e" />
                                </svg>
                            </span>
                            <span class="tabs-panel__left-text"><?php esc_html_e( 'Disabled', 'automate_hub' ); ?></span>
                            <div class="tabs-panel__left-popup triangle"> 
                                <span class='head'><strong><?php esc_html_e( 'Disabled Apps ', 'automate_hub' ); ?></strong></span><br/><br/><p>
                                <?php esc_html_e( ' Disabled Apps are temporarily not in service by their respective providers. Please contact them for further status updates.', 'automate_hub' ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tabs-panel__right">
                        <div class="tabs-panel__right-tabs">
                            <span class="cat secondarytabsactive hide"></span>
                            <span class="cat" data-cat="favourites"><?php esc_html_e( 'Favorites', 'automate_hub' ); ?></span>
                            <span class="cat" data-cat="crm"><?php esc_html_e( 'CRM', 'automate_hub' ); ?></span>
                            <span class="cat" data-cat="esp"><?php esc_html_e( 'ESP', 'automate_hub' ); ?></span>
                            <span class="cat" data-cat="sms"><?php esc_html_e( 'SMS', 'automate_hub' ); ?></span>
                            <span class="cat" data-cat="webinars"><?php esc_html_e( 'Webinars', 'automate_hub' ); ?></span>
                            <span class="cat" data-cat="other"><?php esc_html_e( 'Other', 'automate_hub' ); ?></span>                    
                        </div>
                        <div class="tabs-panel__search-input is-active">
                            <div class="tabs-panel__input-icon">
                                <svg width="23" height="23" viewBox="0 0 19.438 19.766">
                                    <g id="Search" transform="translate(-65.009)">
                                        <g id="Group_2369" data-name="Group 2369" transform="translate(65.009)">
                                            <path id="Round_3164" data-name="Round 3164" d="M12.387,16.756a8.361,8.361,0,1,1,5.929-2.449,8.378,8.378,0,0,1-5.929,2.449Zm0-15.467a7.089,7.089,0,1,0,7.089,7.089,7.089,7.089,0,0,0-7.089-7.089Z" transform="translate(-4.009)" fill="#616a6e" />
                                            <path id="Round_3165" data-name="Round 3165" d="M334.213,339.358a.646.646,0,0,1-.473-.172l-5.113-5.113a.638.638,0,1,1,.9-.9l5.113,5.113a.623.623,0,0,1,0,.9A.5.5,0,0,1,334.213,339.358Z" transform="translate(-315.395 -319.594)" fill="#616a6e" />
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <input type="text" id="platform-filter" autocomplete="off" placeholder="Search Platform">
                        </div>
                        <div class="tabs-panel__search-button close-btn">
                            <svg version="1.1" id="Capa_1" x="30px" y="30px" viewBox="0 0 512.001 512.001" xml:space="preserve">
                                <g><g><path d="M284.286,256.002L506.143,34.144c7.811-7.811,7.811-20.475,0-28.285c-7.811-7.81-20.475-7.811-28.285,0L256,227.717
                    L34.143,5.859c-7.811-7.811-20.475-7.811-28.285,0c-7.81,7.811-7.811,20.475,0,28.285l221.857,221.857L5.858,477.859
                    c-7.811,7.811-7.811,20.475,0,28.285c3.905,3.905,9.024,5.857,14.143,5.857c5.119,0,10.237-1.952,14.143-5.857L256,284.287
                    l221.857,221.857c3.905,3.905,9.024,5.857,14.143,5.857s10.237-1.952,14.143-5.857c7.811-7.811,7.811-20.475,0-28.285
                    L284.286,256.002z" fill="#616a6e" /></g></g>
                            </svg>
                        </div>
                        <div class="tabs-panel__search-button is-hide">
                            <svg width="19.438" height="19.766" viewBox="0 0 19.438 19.766">
                                <g id="Search" transform="translate(-65.009)">
                                    <g id="Group_2369" data-name="Group 2369" transform="translate(65.009)">
                                        <path id="Round_3164" data-name="Round 3164" d="M12.387,16.756a8.361,8.361,0,1,1,5.929-2.449,8.378,8.378,0,0,1-5.929,2.449Zm0-15.467a7.089,7.089,0,1,0,7.089,7.089,7.089,7.089,0,0,0-7.089-7.089Z" transform="translate(-4.009)" fill="#616a6e" />
                                        <path id="Round_3165" data-name="Round 3165" d="M334.213,339.358a.646.646,0,0,1-.473-.172l-5.113-5.113a.638.638,0,1,1,.9-.9l5.113,5.113a.623.623,0,0,1,0,.9A.5.5,0,0,1,334.213,339.358Z" transform="translate(-315.395 -319.594)" fill="#616a6e" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <main>
        <section class="main-section">
        <div class="container-app">
        <div class="row-app">     
        <div class="to-center-screen">

        <?php 

            $app_directory_obj=new AWP_Updates_Manager();

            $apps_data=$app_directory_obj->get_app_directory_data();

            $special_handling=array(

                'campaignmonitor'=>[
                    'img'=>[
                        
                            'css'=>'campaignmonitor-img',
                    ]
                ],
                'autopilot'=>[
                    'img'=>[
                        
                            'css'=>'autopilot-img',
                    ]
                ],
            
            );

            foreach ($apps_data as $key => $app_data) {
                ?>
                <div class="col-app" data-type="<?php echo $app_data['app_type'] ?>" data-category="<?php echo $app_data['app_category'] ?>">
                <span class = "heart"><i class="fa fa-heart-o" aria-hidden="true" ></i> </span>
                <a href="admin.php?page=automate_hub&tab=<?php echo $app_data['slug'] ?> ">
                    <img src="<?php echo AWP_ASSETS_REMOTE; ?>/images/logos/<?php echo $app_data['slug'] ?>.png"   
                    class="<?php echo 

                    isset( $special_handling[ $app_data['slug'] ]['img']['css'] ) ? $special_handling[ $app_data['slug'] ]['img']['css'] : 'app-directory-app-img-default '?>"
                    alt="<?php echo $app_data['name'] ?>"></a><br/><h3 class="title-app"><?php esc_html_e( $app_data['name'], 'automate_hub' ); ?></h3>
                <div class="rating-app"><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span></div>
                <p class="app-desc"><?php esc_html_e( $app_data['app_desc'], 'automate_hub' ); ?></p> 
                <div class="visit-app" ><a href="https://sperse.io/go/<?php echo $app_data['slug'] ?>" target="_blank"><?php esc_html_e( 'View Website', 'automate_hub' ); ?></a> <i class="fa fa-external-link"></i></div>
                <div class="install-app" ><i class="fa fa-download" aria-hidden="true"></i><a href="admin.php?page=automate_hub&tab=<?php echo $app_data['slug'] ?>"><?php esc_html_e( 'Connect', 'automate_hub' ); ?></a></div>
                </div>
                <?php
            }
            
        ?>


    
         



       



    
                
        
              
    </div> <!-- end of to center -->    
    </div>
</div>
</section>      
<!-- Scroll to top -->
<div class="scrollup scrollup__hide"><?php esc_html_e('To Top', 'automate_hub'); ?></div>
</main>
</div>


