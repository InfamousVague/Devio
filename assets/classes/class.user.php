<?php
/** 
 * User Class
 * 
 * This class handles all user-related methods and
 * variables.
 * 
 * @author Willem van Oort <willem.vanoort@live.nl> 
 * @copyright 2013 tinderlight 
 * @since 1.0.0
 */  
class User 
{
    /**
     * Stores the id
     * 
     * @var int $id
     * @access public
     */
    public $id;
    
    /**
     * Stores the username
     * 
     * @var string $username
     * @access public
     */
    public $username;
    
     /**
     * Stores the firstname
     * 
     * @var string $firstname
     * @access public
     */
    public $firstname;
    
     /**
     * Stores the lastname
     * 
     * @var string $lastname
     * @access public
     */
    public $lastname;
   
    /**
     * Stores the login_string
     * 
     * @var string $login_string
     * @access private
     */
    private $login_string;
    
    /**
     * Stores the rankID
     * 
     * @var int $rankID
     * @access public
     */
    public $rank_id;
    
    /**
     * Stores the name of the rank
     * 
     * @var string $rank
     * @access public
     */
    public $rank;
    
    /**
     * Stores the browser that the current user is using
     * 
     * @var string $browser
     * @access public
     */
    public $browser;
    
    /**
     * Stores all permissions that have been loaded
     * 
     * @var array $permission_array
     * @
     * access private
     */
    private $permission_array = array( );
    
    /**
     * Constructor - sets values for the user
     * 
     * @return Some default variables
     */
    public function __construct( ) {
        
        // define some variables
        $this->browser = $_SERVER[ 'HTTP_USER_AGENT' ];
        //$this->login_string = $_SESSION[ 'login_string' ];
        //$this->id = (int) $_SESSION[ 'id' ];
        
        // check if user is logged in
        if ( ! $this->logged_in( ) ) {
            
            $_SESSION['login_string'] = "";
            $_SESSION['userID'] = "";
            // if user is not logged in, use these variables instead
            $this->username = "guest";
            $this->id = 0;
			$this->language = LANGUAGE;
            $this->rank_id = 0;
            $this->rank = "guest";
            $this->login_string = 0;
        } else {

        }
    }
    
    /**
     * Checks if a user is logged in.
     * 
     * @return bool True, if a user is logged in. False if not logged in.
     */
    public function logged_in( ) {
        
        global $mysqli;
       
       // check if sessions login_string, id are set, these are required for a user to be logged in
        if ( isset( $_SESSION[ 'login_string' ] ) && isset($_SESSION[ 'userID' ]) ) {
            $this->login_string = $_SESSION[ 'login_string' ];
            $this->id = $_SESSION[ 'userID' ];
            // select user from database with id param $_SESSION['id']
            $sql = "SELECT `" . TABLE_USERS . "`.`username` , `" . TABLE_USERS . "`.`password` , `" . TABLE_USERS . "`.`language` , `" . TABLE_USERS ."`.`firstname` , `" . TABLE_USERS ."`.`lastname` , `" . TABLE_RANKS . "`.`name` , `" . TABLE_USERS . "`.`rank` " . 
                     "FROM `" . TABLE_USERS . "` " .
                     "LEFT JOIN `" . TABLE_RANKS . "` " . 
                     "ON `" . TABLE_USERS . "`.`rank` = `" . TABLE_RANKS . "`.`id` " .
                     "WHERE `" . TABLE_USERS . "`.`id` = ?";

            if ( $stmt = $mysqli->prepare( $sql ) ) {
                $stmt->bind_param( 'i' , $this->id );
                $stmt->execute( );                
                $stmt->store_result();

                // check if user exists at all
                if ( $stmt->num_rows !== 1 ) {
                    
                    // user does not exist, return bool false
                    return false;
                    
                } else {
                    
                    // user exists, bind database result to $password and @var $rank_id
                    $stmt->bind_result( $username , $password , $language, $firstname , $lastname , $rank , $rank_id );
                    $stmt->fetch( );
                    
                    $check_login = hash( 'sha512' , $password.$this->browser );
                    if ( $check_login == $this->login_string ) {
                        $this->username  = $username;
						$this->language  = $language;
						$this->firstname = $firstname;
						$this->lastname  = $lastname;
                        $this->rank      = $rank;
                        $this->rank_id   = $rank_id;
                        
                        return true;
                        
                    } else {
                        
                        // browser or password not corresponding, so invalid session
                        return false;
                        
                    }
                }
            } else {

                // failed connecting to the database, return bool false
                return false;
                
            }
        } else {

            // No session information found for username and/or login_string
            return false;   
            
        }
    }
    
    /**
     * Logs in the user
     * 
     * @param string $username Submitted username
     * @param string $password Submitted password
     * @return bool True if user login was successfull. False if user login failed
     */
    public function login( $username , $password ) {
        
        global $mysqli;
        
        // checks if either param $username or param $password are empty
        if ( empty( $username ) || empty ( $password ) ) {
            
            // param $username or param $password was empty, so login is not possible, thus return bool false
              return new cms_error( _r( "error.login.fieldempty" , "ucfirst" ) , 3 );
            
        } else {
            
            // get contents that correspond with the submitted var $username
            $sql = "SELECT `id`, `email`, `password`, `salt`, `rank` , `language` FROM `" . TABLE_USERS . "` WHERE `username` = ?";
            if ( $stmt = $mysqli->prepare( $sql ) ) {
                $stmt->bind_param( 's' , $username );
              	$stmt->execute( ); 
              	$stmt->store_result( );
                $stmt->bind_result( $userID , $email , $databasePassword , $salt , $rank , $language );
                $stmt->fetch( );

                // hash param $password with the $salt from the database
                $password = hash( 'sha512' , $password.$salt );
                
                // check if a user exists with the submitted var $username
                if ( $stmt->num_rows == 1 ) {
                    
                    // check if user is denied from logging in
                    if ( $this->checktries( $userID ) === true) {
                       
                        // if user is denied, login fails so return bool false
                        return new cms_error( _r( "error.login.toomanyattempts" , "ucfirst" ) , 3 );
                        
                    } else {
                        
                        // check if submitted password corresponds with the password from the database
                        if ($databasePassword == $password) {
                            
                            // assigning some values
                            $this->browser  = $_SERVER['HTTP_USER_AGENT']; 
                            $this->id       = preg_replace("/[^0-9]+/", "", $userID);
                            $this->rank     = preg_replace("/[^0-9]+/", "", $rank);
							$this->language = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $language);
                            $this->username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                            
                            // setting session values
                            $_SESSION['userID'] = $this->id; 
                            $_SESSION['login_string'] = hash('sha512', $password.$this->browser);
                            
                            // if thats all done, the user is logged in so return bool true
                            if ( $this->logged_in( ) === true ) {
                                $this->permission_array = array( );
                                return true;
                            } else {
                                return false;
                            }
                            
                        } else {
                            
                            // get current time
                            $now = time();
                            
                            // failed login attempt, as passwords did not match. Record this to the database
                            $mysqli->query("INSERT INTO `" . TABLE_LOGINATTEMPTS . "` (`userID`, `time`) VALUES ('$userID', '$now')");
                            
                            // as the login failed, return bool false
                            return new cms_error( _r( "error.login.falsepassword" , "ucfirst" ) );
                            
                        }
                        
                    }
                } else {
                     return new cms_error( _r("error.login.unknownusername" , "ucfirst" ) , 3 );
                }
            } else {
                return new cms_error( _r("error.dbconnection" , "ucfirst" ) , 4 );
            }
        }
    } // end login( )
    
    public function logout ( ) {
        
        $this->permission_array = array( );
        $_SESSION = array( );
		$cParams = session_get_cookie_params( );
		setcookie( session_name( ) , '' , time( ) - 42000 , $cParams[ "path" ] , $cParams[ "domain" ] , $cParams[ "secure" ] , $cParams[ "httponly" ] );
		session_destroy( );
		
		return true;
		
    }
    
    public function checktries( $user_id ) {
           global $mysqli;
           $now = time( );

           $valid_attempts = $now - ( 2 * 60 * 60 ); 

           if ( $stmt_check = $mysqli->prepare( "SELECT `time` FROM `" . TABLE_LOGINATTEMPTS . " WHERE `user_id` = ? AND `time` > '$valid_attempts'" ) ) { 
              
              $stmt_check->bind_param( 'i' , $user_id ); 
              $stmt_check->execute( );
              $stmt_check->store_result( );
              if ( $stmt_check->num_rows > 5 ) {
                 $stmt_check->close( );
                 return true;
              } else {
                 $stmt_check->close( );
                 return false;
              }
           } else {
               return false;
           }
    }
    
    /**
     * Selects or sets a set of permissions for the user
     * 
     * @param int|string optional $plugin
     * @param int|string optional $type
     * @return array $permission_array[$plugin][$type], returns bool true if both $plugin and $type are defined
     */
    public function permission( $plugin = 'all' , $type = 'all' ) {
        
        global $mysqli;
        
        // superuser has all permissions at any time, so always return true
        if ( $this->rank_id === SUPER_USER_ID) return true;

        if ( isset( $this->permission_array[ $plugin ][ $type ] ) ) {
            
            // if the permission has been fetched earlier, theres no need to fetch it again, so just return the value (after checking if the value is an actual boolean)
            return $this->permission_array[ $plugin ][ $type ];
       
        } else {
            $any  = ( $type === 'any' );
            $sql  = "SELECT `plugin` , `type` , `allowed_ranks` , `denied_users` FROM `" . TABLE_PERMISSIONS . "`";
            $type = ( $plugin === 'all' || $type === 'any' ) ? 'all' : $type; // type is 'any' =>'all' to check against all rights
            
            $sql_suffix  = ( $plugin == 'all' ) ? "" : " WHERE `plugin` = ?"; 
            $sql_suffix .= ( $type   == 'all' ) ? "" : " AND `type` = ?";
            
            // fetch all permissions from the database
            if ( $stmt_permission = $mysqli->prepare( $sql . $sql_suffix ) ) {
                
                 if ( $type === 'all' && $plugin !== 'all' ) $stmt_permission->bind_param( 's'  , $plugin );
                 if ( $type !== 'all' && $plugin !== 'all' ) $stmt_permission->bind_param( 'ss' , $plugin , $type );
                 
                 $stmt_permission->execute( );
                 $stmt_permission->store_result( );
                 $stmt_permission->bind_result( $db_plugin , $db_type , $db_allowed_ranks , $db_denied_users );
                
                // loop through all permissions, setting the correct values to them
                while ( $stmt_permission->fetch( ) ) {
                    
                    // split up the results, as they are stored like 1;2;3;...;n
                    $allowed_ranks_array = explode( ";" , $db_allowed_ranks );
                    $denied_users_array  = explode( ";" , $db_denied_users  );
                    
                    // check if user has appropriate rank for the permission and if the user is not denied access from the permission
                    $this->permission_array[ $db_plugin ][ $db_type ] = ( in_array( (string) $this->rank_id , $allowed_ranks_array ) 
                                                                    &&  ! in_array( (string) $this->id      , $denied_users_array  ) ) 
                                                                        ? true : 0;
                }
                
                if ( $stmt_permission->num_rows == 0 && $type != 'all' ) $this->permission_array[ $plugin ][ $type ] = 0;
                if ( $type === 'all' && $plugin !== 'all' ) $this->permission_array[ $plugin ][ $type ] = ( in_array( 0 , $this->permission_array[ $plugin ] ) ) ? 0 : 1;                
                $stmt_permission->close( );

            } else {
                
                $stmt_permission->close( );
                // database fetch failed, dont set permission to false, as its not determined by the database
                return false;
                
            }
        }
		if ( $any ) {
			$this->permission_array[ $plugin ][ 'any' ] = false;
			foreach( $this->permission_array[ $plugin ] as $value){
				if ( $value ) $this->permission_array[ $plugin ][ 'any' ] = true;
			}
			return $this->permission_array[ $plugin ][ 'any' ];
		}
        return $this->permission_array[ $plugin ][ $type ];
    }
    
} // end class User

?>