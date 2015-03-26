<?php
/**
 * config.php
 */

// Set name of the website
define( "WEBSITE_NAME" , "Devio" );
define( "WEBSITE_URL"  , "http://devio.tv");

// id of user that is superuser on the server (usually website-owner)
define ( "SUPER_USER_ID" , 1 );

// default language used on devio.tv
define ( "LANGUAGE"  , "en_US" );

// set vars for the MySQL connection
define ( "MYSQL_HOST"     , "localhost" );
define ( "MYSQL_USER"     , "deviouser" );
define ( "MYSQL_DATABASE" , "devio"     );
define ( "MYSQL_PASSWORD" , "8uzUMXRJ"  );

// set absolute path to includes
if ( ! defined ( "ABSPATH" ) )
	define ( "ABSPATH" , dirname ( __FILE__ ) . "/" );

define ( "TABLE_PREFIX" , "ddb_");

define ( "CORE_PREFIX" , "" );

define ( "TABLE_ACTIONS"       , TABLE_PREFIX . CORE_PREFIX . "actions" );
define ( "TABLE_USERS"         , TABLE_PREFIX . CORE_PREFIX . "users" );
define ( "TABLE_RANKS"         , TABLE_PREFIX . CORE_PREFIX . "ranks" );
define ( "TABLE_PERMISSIONS"   , TABLE_PREFIX . CORE_PREFIX . "permissions" );
define ( "TABLE_PLUGINS"       , TABLE_PREFIX . CORE_PREFIX . "plugins" );
define ( "TABLE_LOGINATTEMPTS" , TABLE_PREFIX . CORE_PREFIX . "loginattempts" );

?>