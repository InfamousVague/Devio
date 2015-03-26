<?php
/**
 * functions.php
 */

function secure_session_start ( ) {
	ini_set( 'session.use_only_cookies' , 1 );
	$cParams = session_get_cookie_params( );
	session_set_cookie_params( $cParams["lifetime"] , $cParams["path"] , $cParams["domain"] , false , true ); // set one-to-last to true if using https
	session_name( "SecureSession" );
	session_start( );
	session_regenerate_id( );
}

function read_r( $thingy ) {
    echo "<pre>";
    print_r( $thingy );
    echo "</pre>";
}

function time_ago ( $timestamp , $rcs = 0 ) {
	$cur_tm = time(); 
	$diff   = $cur_tm - $timestamp;
	$pds    = array( _r('second',null) ,_r('minute',null) ,_r('hour',null) ,_r('day',null) ,_r('week',null) ,_r('month',null) ,_r('year',null) ,_r('decade',null) );
	$pds_m  = array( _r('multi.second',null) , _r('multi.minute',null) , _r('multi.hour',null) , _r('multi.day',null) , _r('multi.week',null) , _r('multi.month',null) , _r('multi.year',null) , _r('multi.decade',null) );
	$lngh   = array(1,60,3600,86400,604800,2630880,31570560,315705600);
	
	for ($v = count($lngh) - 1; ($v >= 0) && (($no = $diff / $lngh[$v]) <= 1); $v--);
		if ($v < 0)
			$v = 0;
	$_tm = $cur_tm - ($diff % $lngh[$v]);
	
	$no = ($rcs ? floor($no) : round($no)); // if last denomination, round

	if ($no != 1)
		$pds[$v] = $pds_m[$v];
	$x = $no . ' ' . $pds[$v];
	
	if (($rcs > 0) && ($v >= 1))
		$x .= ' ' . time_ago($_tm, $rcs - 1);
	
	return $x;
}

function singlequote( $string ) {
	return "&lsquo;" . $string . "&rsquo;";
}

function doublequote( $string ) {
	return "&ldquo;" . $string . "&rdquo;";
}

function rand_string( $length ) {
    $str = "";
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}
	return $str;
}

function alert( $message , $type = "info") {
    $types = array("info","success","warning","danger");
    $type = (in_array($type,$types)) ? $type : "info";
    
    return "<div class='alert alert-$type'>".$message."</div>";
}
?>