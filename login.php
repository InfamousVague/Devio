<?php
    require 'init.php';
    
    $returnto = (isset($_REQUEST['returnto'])) ? urldecode($_REQUEST['returnto']) : "./index";
	if ( $user->rank_id > 0) {
		header("Location: " . $returnto);
		exit;
	}

	if ( isset( $_POST['username'] , $_POST['password'] ) ) {
		$pwd    = $_POST['password'];
		$output = $user->login( $_POST[ 'username' ] , $pwd );
		if( !is_error_obj( $output ) ) {
			header("Location: " . $returnto);
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
            background:url('assets/img/backgrounds/vlad2.jpg');
            background-size:cover;
        }
    </style>
    <div class="container" style="margin-top:5%;">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        		<form role="form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                    <img src="assets/img/devio_light.png" alt="Devio Logo" />
                    <br /><br />
                    <div class="row">
        				<div class="col-xs-12 col-sm-12 col-md-12">
        				    <?php
                                if( isset($output) && is_error_obj( $output ) ) {
                                    echo $output->display_errors( 1,true,true,1 );
                                }
                            ?>
        					<div class="form-group">
                                <input type="text" name="username" id="username" class="form-control input-lg" value="<?php echo (isset($_POST['username']))?$_POST['username']:"";?>" placeholder="<?php __("username"); ?>" tabindex="1">
        					</div>
        				</div>
        			</div>
        			<div class="row">
        				<div class="col-xs-12 col-sm-12 col-md-12">
        					<div class="form-group">
        						<input type="password" name="password" id="password" class="form-control input-lg" placeholder="<?php __("password"); ?>" tabindex="2">
        					</div>
        				</div>
        			</div>
        			<div class="row">
        				<div class="col-xs-12 col-sm-12 col-md-12">
        				    <?php if (isset($_REQUEST['returnto'])) { ?><input type="hidden" name="returnto" value="<?php echo urlencode($returnto);?>" /><?php } ?>
        				    <button type="submit" class="btn btn-success btn-block btn-lg">Sign In</button>
        				    <br />
        				    <p style="color:#fff;">
        				        New to Devio.tv? Let's get you <a href="register.php">signed up.</a>
        				    </p>
        				</div>
        				
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
        				<p>Full Terms &amp; Conditions pending.</p>
        				<p>This site is in early alpha, if you upload any inapropriot content or content that is illegal in the state of MD, you take full responsibility for any legal action that is taken.</p>
        				<p>Additionally this site stores cookies on your browser to save settings, and stores your geolocation and other information on uploads. This information is never shared and is only accessed in the event that legal issues arise.</p>
        			</div>
        			<div class="modal-footer">
        				<button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
        			</div>
        		</div><!-- /.modal-content -->
        	</div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
</div>

<?php include('includes/global/scripts.php'); ?>

</body>
</html>