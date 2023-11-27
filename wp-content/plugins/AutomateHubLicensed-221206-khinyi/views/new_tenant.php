 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;500;600;700&display=swap" rel="stylesheet">
    <header>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <a class="navbar-brand" href="#">Sperse Hub</a><span class="navbar-brand">/ Dashboard</span>
                    </div>
                    <div class="col-md-6">
                        <ul class="menu">
                            <li><i class="fa fa-inbox" aria-hidden="true"></i><a href="<?php echo admin_url('admin.php?page=automate_hub'); ?>">Platforms</a></li>
                            <li><i class="fa fa-money"></i><a href="<?php echo admin_url('admin.php?page=my_integrations'); ?>">Integrations</a></li>

                            <li><i class="fa fa-share" aria-hidden="true"></i><a href="<?php echo admin_url('admin.php?page=automate_hub_log'); ?>">Logs</a></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i><a href="mailto:support@sperse.com">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <style>

#login form{
	width: 250px;
}
#login, .logo{
    display:inline-block;
    width:40%;
}

.logo{
color:#fff;
font-size:50px;
  line-height: 125px;
}

#login form span.fa {
	background-color: #fff;
	border-radius: 3px 0px 0px 3px;
	color: #000;
	display: block;
	float: left;
	height: 50px;
    font-size:24px;
	line-height: 50px;
	text-align: center;
	width: 50px;
}

#login form input {
	height: 50px;
}
fieldset{
    padding:0;
    border:0;
    margin: 0;

}
#login form input[type="text"], input[type="password"],input[type="email"] {
	background-color: #fff;
	border-radius: 0px 3px 3px 0px;
	color: #000;
	margin-bottom: 1em;
	padding: 0 16px;
    width: 200px;
    border: 0px;
}

#login form input[type="text"]:focus, input[type="password"]:focus,input[type="email"]:focus {
    box-shadow:none;
}


#login form input[type="submit"] {
  /*border-radius: 3px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  background-color: #000000;
  color: #eee;
  font-weight: bold;
  /* margin-bottom: 2em; 
  text-transform: uppercase;
  padding: 5px 10px;
  height: 30px;*/
  background-color: #32cb4a;
    color: white;

    font-weight: bold;
    font-size: 1.5em;
    border: none;
    outline: none;
}

.sperse_submit{
    background-color: #32cb4a;
    color: white;
    border-radius: 30px;
}

#login form input[type="submit"]:hover {
	background-color: #d44179;
}

#login > p {
	text-align: center;
}

#login > p span {
	padding-left: 5px;
}

.container_form {
    margin: 0 auto;
  text-align: center;
  padding: 40px 0;

}
        </style>
        <section class="main-section">
			<div class="pages-background"></div>
            <div class="container">
                <div class="row">
                    <div class="container_form">
                        <center>
                            <div class="middle">
                                <div id="login">
                                    <form name="sperse_tenant_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                                        <fieldset class="clearfix">
                                        <input type="hidden" name="action" value="save_sperse_tenant">
                                        <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('awp_sperse_settings'); ?>"/>

                                            <p><span class="fa fa-envelope"></span><input type="email" name="adminemailaddress"  Placeholder="Admin Email Address" required></p> 
                                            <p><span class="fa fa-lock"></span><input type="password" name="adminpassword"  Placeholder="Admin Password" required></p> 
                                            <p ><span class="fa fa-user"></span><input type="text" name="adminfirstname"  Placeholder="Admin First Name" required></p> 
                                            <p ><span class="fa fa-user"></span><input type="text" name="adminlastname"  Placeholder="Admin Last Name" required></p> 
                                            <p ><span class="fa fa-user"></span><input type="text" name="tenancyname"  Placeholder="Tenancy Name" required></p> 
                                            <p ><span class="fa fa-user"></span><input type="text" name="name"  Placeholder="name" required></p> 
                                            <p class="sperse_submit"> <input type="submit" name="submit" value="Create Account" /><i class="fa fa-arrow-right" aria-hidden="true"></i> </p>
                                        </fieldset>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>  
                        </center>
                    </div>
                </div>
            </div>
        </section>
    </main>
