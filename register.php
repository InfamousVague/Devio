<?php
    ini_set("display_errors",1);
    error_reporting(E_ALL);
    require 'init.php';
    
    $returnto = (isset($_REQUEST['returnto'])) ? urldecode($_REQUEST['returnto']) : "./index";
	if ( $user->rank_id > 0) {
		header("Location: " . $returnto);
		exit;
	}
	
    if(isset($_POST['display_name'],$_POST['email'],$_POST['t_and_c'],$_POST['password'],$_POST['password_confirmation'],$_POST['first_name'],$_POST['last_name'])){
        if(preg_match("/^[a-zA-Z][a-zA-Z0-9_-]{4,25}$/",$_POST['display_name'])){
            if( $stmt = $mysqli->prepare("SELECT `id` FROM `" . TABLE_USERS . "` WHERE LOWER(`username`) = ?")){
	            $stmt->bind_param('s',$a=strtolower($_POST['display_name']));
                $stmt->execute();
	            $stmt->store_result();
	            
	            // username does not exist yet
                if($stmt->num_rows === 0){
                    if( $stmt2 = $mysqli->prepare("SELECT `id` FROM `" . TABLE_USERS ."` WHERE LOWER(`email`) = ?")){
                        $stmt2->bind_param('s',$b=strtolower($_POST['email']));
                        $stmt2->execute();
                        $stmt2->store_result();
                        
                        // email does not exist yet
                        if( $stmt2->num_rows === 0 ){
                            
                            // password has more than 5 chars
                            if(strlen($_POST['password']) > 5){
                                
                                // email is valid address
                                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                                    
                                    // password and password confirmation match
                                    if ($_POST['password'] == $_POST['password_confirmation']){
                                        
                                        // firstname and lastname both have more than 1 char
                                        if(strlen($_POST['first_name']) > 1 && strlen($_POST['last_name']) > 1){
                                            
                                            // terms and conditions was accepted
                                            if($_POST['t_and_c'] == "1"){
                                                
                                                // all good, prepare database insert
                                                if( $stmt3 = $mysqli->prepare("INSERT INTO `" . TABLE_USERS . "` (`username`,`email`,`password`,`salt`,`verification_key`,`firstname`,`lastname`,`rank`,`registered_since`) VALUES (?,?,?,?,?,?,?,?,?)")) {
                                                    $salt     = hash('sha512',rand_string(32));
                                                    $veri_key = rand_string(128);
                                                    $password = hash('sha512',$_POST['password'].$salt);
                                                    $rank     = 1;
                                                    $unix     = time();
                                                    $stmt3->bind_param('sssssssii',$_POST['display_name'],$_POST['email'],$password,$salt,$veri_key,$_POST['first_name'],$_POST['last_name'],$rank,$unix);
                                                    
                                                    // insert the new user
                                                    if($stmt3->execute()){
                                                        
                                                        // include classes required for validation mail
                                                        require ABSPATH . CLASSPATH . "/class.phpmailer.php";
                                                        require ABSPATH . CLASSPATH . "/class.smtp.php";
                                                        
                                                        $mail = new PHPMailer;

                                                        $mail->isSMTP();                                      // Set mailer to use SMTP
                                                        $mail->Host = 'smtp.gmail.com';                                     // Specify main and backup server
                                                        $mail->SMTPAuth = true;                               // Enable SMTP authentication
                                                        $mail->Username = 'deviotv@gmail.com';                            // SMTP username
                                                        $mail->Password = 'ZrFknvrC';                           // SMTP password
                                                        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
                                                        $mail->port = 465;
                                                        $mail->SetFrom('noreply@devio.tv', 'Devio.tv');
                                                        $mail->addAddress($_POST['email'], $_POST['first_name'] . " " . $_POST['last_name']);  // Add a recipient

                                                        $mail->isHTML(true);                                  // Set email format to HTML
                                                        
                                                        $mail->Subject = 'Please verify your email address';
                                                        $mail->Body    = '<img src="http://devio.tv/assets/img/devio.png" /><br><br><h1>Please verify your email address</h1><p>For full access to Devio.tv you must verify your email by clicking the link below.</p>' ." http://devio.tv/verify?username=".$_POST['display_name']."&veri_key=" . $veri_key . "<br><br><p>If you did not sign up for Devio.tv you can ignore and discard this email.</p>";
                                                        $mail->AltBody = 'Please verify your email address. For full access to Devio.tv you must verify your email by clicking the link below.' ." http://devio.tv/verify?username=".$_POST['display_name']."&veri_key=" . $veri_key . "If you did not sign up for Devio.tv you can ignore and discard this email.";
                                                        
                                                        // send mail
                                                        $mail->send();
                                                        
                                                        // redirect to registered page
                                                        header("Location: /?registered");
                                                    } else {
                                                        $error = new cms_error( _r("error.database") );
                                                    }
                                                } else {
                                                    $error = new cms_error( _r("error.database") );
                                                }
                                            } else {
                                                $error = new cms_error( _r("error.login.t_and_c") );
                                            }
                                        } else {
                                            $error = new cms_error( _r("error.login.firstlast") );
                                        }
                                    } else {
                                        $error = new cms_error( _r("error.login.passwordmatch") );
                                    }
                                } else {
                                    $error = new cms_error( _r("error.login.email") );
                                }
                            } else {
                                $error = new cms_error( _r("error.login.password") );
                            }
                        } else {
                            $error = new cms_error( _r("error.login.emailtaken") );
                        }
                    } else {
                        $error = new cms_error( _r("error.login.database") );
                    }
                } else {
                    $error = new cms_error( _r("error.login.usernametaken") );
                }
            }else {
                $error = new cms_error( _r("error.database") );
            }
        } else {
            $error = new cms_error( _r("error.login.username") );
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php __("logintitle"); ?></title>
</head>
<body>

<?php include('includes/global/styles.php'); ?>
    <style type="text/css">
        body{
            background:url('assets/img/backgrounds/vlad.jpg');
            background-size:cover;
        }
        .has-error{
            border:1px solid red;
            border-radius:7px;
            box-shadow:0px 0px 5px red;
        }
    </style>    
    <div class="container" style="margin-top:5%;">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        		<form role="form" id="form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        		    <div class="page-header">
                    <img src="assets/img/devio_light.png" alt="Devio Logo" />
                    <br /><br />
                    </div>
        			<div class="row">
        			    <div class="col-xs-12">
        			        <?php echo (isset($error)&&is_error_obj($error))?$error->display_errors(1,true,true,1):"";?>
        			    </div>
        				<div class="col-xs-12 col-sm-6 col-md-6">
        				    
        					<div class="form-group">
                                <input type="text" name="first_name" id="first_name" class="form-control input-lg" placeholder="<?php __("firstname","ucwords");?>" tabindex="1">
        					</div>
        					<span data-error="frstname" style="color:#fff;display:none;"><?php __("error.register.frstlast"); ?><br /><br /></span>
        				</div>
        				<div class="col-xs-12 col-sm-6 col-md-6">
        					<div class="form-group">
        						<input type="text" name="last_name" id="last_name" class="form-control input-lg" placeholder="<?php __("lastname","ucwords");?>" tabindex="2">
        					</div>
        					<span data-error="lastname" style="color:#fff;display:none;"><?php __("error.register.frstlast"); ?><br /><br /></span>
        				</div>
        			</div>
        			
        			<div class="form-group">
        				<input type="text" name="display_name" id="display_name" class="form-control input-lg" placeholder="<?php __("displayname","ucwords");?>" tabindex="3">
        			</div>
        			<span data-error="username" style="color:#fff;display:none;"><?php __("error.register.username"); ?><br><br /></span>
        			<span data-error="usernme2" style="color:#fff;display:none;"><?php __("error.register.usernme2"); ?><br><br /></span>
        			<div class="form-group">
        				<input type="email" name="email" id="email" class="form-control input-lg" placeholder="<?php __("emailaddress","ucwords");?>" tabindex="4">
        			</div>
        			<span data-error="mailaddr" style="color:#fff;display:none;"><?php __("error.register.mailaddr"); ?><br><br /></span>
        			<span data-error="mailadr2" style="color:#fff;display:none;"><?php __("error.register.mailadr2"); ?><br><br /></span>
        			<div class="row">
        				<div class="col-xs-12 col-sm-6 col-md-6">
        					<div class="form-group">
        						<input type="password" name="password" id="password" class="form-control input-lg" placeholder="<?php __("password","ucwords");?>" tabindex="5">
        					</div>
        				</div>
        				<div class="col-xs-12 col-sm-6 col-md-6">
        					<div class="form-group">
        						<input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-lg" placeholder="Confirm Password" tabindex="6">
        					</div>
        				</div>
        			</div>
        			<span data-error="password" style="color:#fff;display:none;"><?php __("error.register.password"); ?><br><br /></span>
        			<span data-error="passwrd2" style="color:#fff;display:none;"><?php __("error.register.passwrd2"); ?><br><br /></span>
        			<div class="row">
        				<div class="col-xs-4 col-sm-3 col-md-3">
        					<span class="button-checkbox" id="agree">
        						<button type="button" class="btn" data-color="info" tabindex="7">I Agree</button>
                                <input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
        					</span>
        				</div>
        				<div class="col-xs-8 col-sm-9 col-md-9" style="color:#fff;">
        					 By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a> set out by this site, including our Cookie Use.
        				</div>
        			</div>
        			
        			<hr >
        			<div class="row">
        				<div class="col-xs-12 col-md-12"><input type="submit" value="Register" id="register" disabled="disabled" class="btn btn-primary btn-lg btn-block" tabindex="7"></div>
        			</div>

        		</form>
        	</div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        	<div class="modal-dialog modal-lg">
        		<div class="modal-content">
        			<div class="modal-header">
        				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        				<h4 class="modal-title" id="myModalLabel">Terms &amp; Conditions</h4>
        			</div>
        			<div class="modal-body">
 <h1>Devio TV Terms of Service ("Agreement")</h1>
<p>This Agreement was last modified on May 06, 2014.</p>

<p>Please read these Terms of Service ("Agreement", "Terms of Service") carefully before using http://devio.tv ("the Site") operated by Devio TV ("us", "we", or "our"). This Agreement sets forth the legally binding terms and conditions for your use of the Site at http://devio.tv.</p>
<p>By accessing or using the Site in any manner, including, but not limited to, visiting or browsing the Site or contributing content or other materials to the Site, you agree to be bound by these Terms of Service. Capitalized terms are defined in this Agreement.</p>

<p><strong>Intellectual Property</strong><br />The Site and its original content, features and functionality are owned by Devio TV and are protected by international copyright, trademark, patent, trade secret and other intellectual property or proprietary rights laws.</p>

<p><strong>Termination</strong><br />We may terminate your access to the Site, without cause or notice, which may result in the forfeiture and destruction of all information associated with you. All provisions of this Agreement that by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity, and limitations of liability.</p>

<p><strong>Links To Other Sites</strong><br />Our Site may contain links to third-party sites that are not owned or controlled by Devio TV.</p>
<p>Devio TV has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party sites or services. We strongly advise you to read the terms and conditions and privacy policy of any third-party site that you visit.</p>

<p><strong>Governing Law</strong><br />This Agreement (and any further rules, polices, or guidelines incorporated by reference) shall be governed and construed in accordance with the laws of Maryland, without giving effect to any principles of conflicts of law.</p>

<p><strong>Illegal Content</strong><br /> You assume full responsibility for any content you upload. Your content should fill any and all legal requirements governed by the state of Maryland. Your information will be logged and in the event that local authorities contatct Devio.tv you agree that your information will be shared to help resolve any legal disputes.</p>

<p><strong>Pornography</strong><br /> Under no circumstances will any type of pornographic material be alloud on Devio.tv. If we find that you have uploaded content that voids this rule your account will be immediatly disabled.</p>

<p><strong>Changes To This Agreement</strong><br />We reserve the right, at our sole discretion, to modify or replace these Terms of Service by posting the updated terms on the Site. Your continued use of the Site after any such changes constitutes your acceptance of the new Terms of Service.</p>
<p>Please review this Agreement periodically for changes. If you do not agree to any of this Agreement or any changes to this Agreement, do not use, access or continue to access the Site or discontinue any use of the Site immediately.</p>

<p><strong>Contact Us</strong><br />If you have any questions about this Agreement, please contact us.</p>
        			</div>
        			<div class="modal-footer">
        				<button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
        			</div>
        		</div><!-- /.modal-content -->
        	</div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
</div>

<?php include('includes/global/scripts.php'); ?>
<script type="text/javascript">
    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    }
    $(document).ready(function(){
        
        var username = false,
            usernme2 = false,
            password = false,
            passwrd2 = false,
            frstname = false,
            lastname = false,
            mailaddr = false,
            mailadr2 = false,
            t_and_c  = false;
            
        var username_contcheck = false,
            password_contcheck = false,
            passwrd2_contcheck = false,
            frstname_contcheck = false,
            lastname_contcheck = false,
            mailaddr_contcheck = false,
            t_and_c_contcheck  = false;
            
        function handle_error( type , state ) {
            var $element = $("#form [data-error='"+type+"']");
            if(state === "show") $element.show();
            if(state === "hide") $element.hide();
        }
        
        $("#form [data-error]").hide();
        
        function check_existance( $field , type ) {
            var new_value = true;
            $.ajax({
                url: '/functions/check.php',
                dataType: 'json',
                data: { checkfor : type , value : $field.val() },
                type: 'POST',
                success: function(data) {
                    if (data.datasuccess == "1") {
                        if (data.success == "1") {
                            new_value = true;
                        } else {
                            new_value = false;
                        }
                    } else {
                        new_value = true;
                    } 
                    if ( type === "display_name" ) usernme2 = new_value;
                    if ( type === "email"        ) mailadr2 = new_value;
                    handle_error( (type==="display_name")?"usernme2":"mailadr2" , (new_value)?"hide":"show");
                }
            });
        }
        
        function check_frstname(handle_err) {
            frstname = (!(new RegExp(/\d/)).test( $("#first_name").val() ) && $("#first_name").val().length > 1);
            if (handle_err) handle_error( "frstname" , (frstname)?"hide":"show");
            frstname_contcheck = handle_err;
            return frstname;
        }
        function check_lastname(handle_err) {
            lastname = (!(new RegExp(/\d/)).test( $("#last_name").val() ) && $("#last_name").val().length > 1);
            if (handle_err) handle_error( "lastname" , (lastname)?"hide":"show");
            lastname_contcheck = handle_err;
            return lastname;
        }
        function check_username(handle_err) {
            username = (new RegExp(/^[a-zA-Z][a-zA-Z0-9_-]{5,25}$/)).test( $("#display_name").val() );
            if (handle_err) handle_error( "username" , (username)?"hide":"show");
            username_contcheck = handle_err;
            return username;
        }
        function check_mailaddr(handle_err) {
            mailaddr = (new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i))
                        .test( $("#email").val() );
            if (handle_err) handle_error( "mailaddr" , (mailaddr)?"hide":"show");
            mailaddr_contcheck = handle_err;
            return mailaddr;
        }
        function check_password(handle_err) {
            var passwordvalue = $("#password").val();
            password = ( (new RegExp(/[0-9]/)).test(passwordvalue) && (new RegExp(/[a-z]/)).test(passwordvalue) && (new RegExp(/[A-Z]/)).test(passwordvalue) && passwordvalue.length >= 6 );
            if (handle_err) handle_error( "password" , (password)?"hide":"show");
            password_contcheck = handle_err;
            return password;
        }
        function check_passwrd2(handle_err) {
            passwrd2 = ( $("#password").val() == $("#password_confirmation").val() );
            if (handle_err) handle_error( "passwrd2" , (passwrd2)?"hide":"show");
            passwrd2_contcheck = handle_err;
            return passwrd2;
        }
        function check_t_and_c(handle_err) {
            t_and_c  = ( $("#t_and_c").is(":checked") === true );
            if (handle_err) handle_error( "t_and_c" , (t_and_c)?"hide":"show");
            t_and_c_contcheck = handle_err;
            return t_and_c;
        }
        function check_all() {
            //check_existance( $("display_name") , "display_name" );
            //check_existance( $("email")        , "email"        );
            
            check_frstname( );
            check_lastname( );
            check_username( );
            check_mailaddr( );
            check_password( );
            check_passwrd2( );
            check_t_and_c ( );
        }
        
        $("#display_name").on("change",function(){ check_existance($("#display_name"), "display_name"); check_username(true); });
        $("#email"       ).on("change",function(){ check_existance($("#email"       ), "email"       ); check_mailaddr(true); });
        
        $("#password").on("change",function(){ check_password(true); });
        $("#t_and_c" ).on("change",function(){ check_t_and_c( true); });
        
        $("#password_confirmation").on("change",function(){ check_passwrd2(true); });
        
        $("#last_name" ).on("change",function(){ check_lastname(true); });
        $("#first_name").on("change",function(){ check_frstname(true); });
        
        $("#display_name").on("keyup",function(){ check_username(username_contcheck); });
        $("#email"       ).on("keyup",function(){ check_mailaddr(mailaddr_contcheck); });
        $("#password"    ).on("keyup",function(){ check_password(password_contcheck); });
        
        $("#first_name").on("keyup",function(){ check_frstname(frstname_contcheck); });
        $("#last_name" ).on("keyup",function(){ check_lastname(lastname_contcheck); });
        
        $("#password_confirmation,#password").on("keyup",function(){ check_passwrd2(passwrd2_contcheck); });
        
        setInterval(function(){ 
                        check_all(); 
                        if(username&&usernme2&&password&&passwrd2&&lastname&&frstname&&mailaddr&&mailadr2&&t_and_c) $("#register").removeAttr("disabled");
                            else $("#register").attr("disabled","disabled");
        }, 500);
        /*
        var step1 = false,
            step2 = false;
            
        var $reg_btn = $('#register');
        $('#password_confirmation, #password').keyup(function(){
            var orig_pass = $('#password').val(),
            veri_pass = $('#password_confirmation').val();
            if(orig_pass === veri_pass){
                step1 = true;
                $('#pass_error').hide();
            }else{
                $('#pass_error').show();
            }
            check_all();
        });
        $('#t_and_c').on("change",function(){
            if($(this).is(":checked") === true){
                step2 = true;
                check_all();
            } else {
                $reg_btn.attr("disabled","disabled");
                step2 = false;
            }
        });	        
        $('#username_error, #email_error, #pass_error').hide();
        var $email_error = $('#email_error'),
            $username_error = $('#username_error');
        
        function check_all() {
            if($("#pass_error").is(":visible") || $email_error.is(":visible") || $username_error.is(":visible") || !step2){
                $reg_btn.attr("disabled","disabled");
                return false;
            } else {
                $reg_btn.removeAttr("disabled");
                return true;
            }
        }
        
        $("form").on("keyup",function(){
            check_all();
        });
            
        $("#display_name").on("change",function(){
            var $self = $(this);
            $.ajax({
                url: '/functions/check.php',
                dataType: 'json',
                data: { checkfor : "display_name" , display_name : $self.val() },
                type: 'POST',
                success: function(data) {
                    if (data.datasuccess == "1") {
                        if (data.success == "1") {
                            if ( !(new RegExp(/^[a-zA-Z][a-zA-Z0-9_-]{5,25}$/)).test($self.val())){
                                $self.closest(".form-group").removeClass("has-success").addClass("has-error");
                                $username_error.html('Usernames must start with a letter, and be between 6 and 25 charicters long<br><br>').fadeIn();
                            } else {
                                $self.closest(".form-group").removeClass("has-error").addClass("has-success");
                                $username_error.fadeOut();
                            }
                        } else {
                            $self.closest(".form-group").removeClass("has-success").addClass("has-error");
                            $username_error.html('Usernames is already in use! Try Again.<br><br>').fadeIn();
                        }
                    }else{
                        $self.closest(".form-group").removeClass("has-error has-success");
                        $username_error.hide();
                    } 
                    check_all();
                }
            });
        });
        $()
        $("#email").on("change",function(){
            var $self = $(this);
            $.ajax({
                url: '/functions/check.php',
                dataType: 'json',
                data: { checkfor : "email" , email : $self.val() },
                type: 'POST',
                success: function(data) {
                    if (data.datasuccess == "1") {
                        if (data.success == "1") {
                            if( !isValidEmailAddress( $self.val() ) ) { 
                                $self.closest(".form-group").removeClass("has-success").addClass("has-error");
                                $email_error.html('Email address does not appear to be valid. <br><br>').fadeIn();
                            } else {
                                $self.closest(".form-group").removeClass("has-error").addClass("has-success");
                                $email_error.hide();
                            }
                        } else {
                            $self.closest(".form-group").removeClass("has-success").addClass("has-error");
                            $email_error.html('Email already in use <br><br>').fadeIn();
                        }
                    }else{
                        $self.closest(".form-group").removeClass("has-error has-success");
                        $email_error.hide();
                    } 
                    check_all();
                }, error: function(){
                    $self.closest(".form-group").removeClass("has-error has-success");
                    $email_error.hide();
                }
            });
        });
        $("#form").submit(function(e){
            //$("#email").trigger("change");
            //$("#username").trigger("change");
            if($(".form-control.has-error").length > 0 || $("#first_name").val().length < 2 || $("#last_name").val().length < 2 || !check_all()){
                return false;
            } else {
                return true;
            }
        });*/
    });
</script>
</body>
</html>