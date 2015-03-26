<?php
/**
 * Init file - Should be included on every page to initiate connections and settings
 * 
 * @since 1.0.0
 */

ini_set("display_errors", 1);
error_reporting (E_ALL);

require_once 'config.php';

define( "COREPATH"   , "assets"              );
define( "CLASSPATH"  , COREPATH . "/classes" );
define( "LANGPATH"   , COREPATH . "/lang"    );

// load translations
require_once ABSPATH . CLASSPATH . '/class.lang.php';

$translations = new Translation( LANGUAGE );

/**
 * alias of $translations->get_translation($string)
 */
function __( $string , $function = null ) { 
	global $translations; 
	echo $translations->get_translation( $string , $function );
}

function _r( $string , $function = null ) {
    global $translations;
    return $translations->get_translation( $string , $function );
}

// load other files
require_once ABSPATH . COREPATH . '/functions.php';

// start session
secure_session_start( );

// initiate database connection
$mysqli = new mysqli( MYSQL_HOST , MYSQL_USER , MYSQL_PASSWORD , MYSQL_DATABASE );

require_once ABSPATH . CLASSPATH . '/class.cms-error.php';
require_once ABSPATH . CLASSPATH . '/class.user.php';

//require_once ABSPATH . CLASSPATH . '/class.settings.php';

// new user Object
$user = new User( );

if ( LANGUAGE !== $user->language ) {
	$language = new Translation( $user->language );
}

if ( isset ( $_GET[ "logout" ] ) && ! isset( $_POST[ 'logout' ] ) && ! isset( $_COOKIE[ 'logout' ]) ) {
    if ( $user->logout( ) ){
        header("Location: /");
    }
    exit;
}

//$settings = new settings();
define( "INITIATED" , "true" );
?>