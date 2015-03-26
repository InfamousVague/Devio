<?php
class settings
{
    
    public $settings_array = array();
    
    public function __construct() {
        
        $mysqli = new mysqli( MYSQL_HOST , MYSQL_USER , MYSQL_PASSWORD , MYSQL_DATABASE );
        
        if ($stmt = $mysqli->prepare("SELECT `value`, `abbr` FROM `" .TABLE_SETTINGS . "`")) {
            $stmt->execute();
            $stmt->bind_result($value, $abbr);
            $stmt->store_result();
            while ( $stmt->fetch() ) {
                $value = ($value == 'nee') ? false : $value;
                $value = ($value == 'ja')  ? true  : $value;
                $this->settings_array[$abbr] = $value;
            }
            $stmt->close();
        }
    }
    
    public function setting( $settingname ) {
        
        return $this->settings_array[$settingname];
        
    }

}