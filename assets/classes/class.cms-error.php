<?php
/**
 * cms-error class
 * 
 * This class handles all cms related errors.
 * 
 * @author Willem van Oort <willem.vanoort@live.nl> 
 * @copyright 2013 TwentySix Media 
 * @since 1.0.0
 */
 
 class cms_error {
     
     /**
      * 
      * 
      * 
      */
     public $errors = array( );
     
     /**
      * 
      * 
      * 
      */
     public function __construct( $message = null , $level = 1 ) {
         if ( $message !== null) {
              $this->add( $message , $level );
         }
     }
     
     /**
      * adds error messages to the $errors array
      * 
      * @param string $message, defines the message to be stored in the array $errors
      * @param optional int $level
      */
     public function add( $message , $level = 1 ) {
         $this->errors[ $level ][ ] = $message;
         //krsort($this->errors);
     }
     
     /**
      * returns a specific amount of errors. By default get_errors returns all errors ordered by their level (descending)
      * 
      * @param optional int $level. Defines at what level to pick the errors. Default is 1
      * @param optional bool $ceil. Defines whether or not to return errors with a level above param $level. Default is true.
      * @param optional bool $desc. Defines in what order the results gets returned (true for descending and false for ascending). Ordering goes by $level
      * @param optional int $count. Defines how many errors to pick. Default is zero, for zero it returns all errors.
      * 
      * @return array $errors. All errors with the param-filters applied
      */
     public function get_errors( $level = 1 , $ceil = true , $desc = true , $count = 0 ) {
         
         if ( $count !== 1 ) $return_array    = array( );
         $filtered_levels = array( );
         
         if ( $ceil === true ) {
             for ( $i = 1 ; $i < $level ; $i++) {
                 $filtered_levels[] = $i;
             }
         } else {
             foreach ( $this->errors as $value ) {
                 if ( $level !== $value ) $filtered_levels[] = $value;
             }
         }
         
         if ( $desc === true ) {
            krsort( $this->errors );
         } else {
            ksort ( $this->errors );
         }  

        $filtered_array = array_diff_key( $this->errors , array_flip( $filtered_levels ) );
         
         // make sure $count is an integer, otherwise make $count = 0
         $count = ( is_int( $count ) ) ? $count : 0 ;
         $i = 0;
         foreach ( $filtered_array as $level => $messages ) {
                
             // loop through all errors with $level, return their stored messages
             foreach ( $this->errors[ $level ] as $message ) {
                 $return_array[] = $message;
                 
                 // stops the foreach loop when $i reaches $count. $i++ will never be 0, therefore $count = 0 lets foreach finish its loop.
                $i++;
                if ( $i == $count ) break;
             }
             if ( $i == $count ) break;
         }
         
         return (isset($return_array)) ? (( $count === 1 ) ? (string) $return_array[0] : (array) $return_array) : _r("error.unknown");
     }
	 
	 public function display_errors( $level = 1 , $ceil = true , $desc = true , $count = 0 ) {
		$errors = (array) $this->get_errors( $level , $ceil , $desc , $count);
		
		// no errors found, so none to display
		if ( count($errors) === 0 ) return;
		
		// 1 error found, no need for a list
		if ( count($errors) === 1 ) { 
			$display_errors = (string) $errors[0]; 
		} else {
			$display_errors = "<ul>";
			foreach($errors as $error) {
				$display_errors .= "<li>".$error."</li>";
			}
			$display_errors .= "</ul>";
		}
		
		return alert( $display_errors , "warning" );
		
	 }
	 
	 public function is_fatal( $level = 4 ) {
		if ( max( array_keys($this->errors) ) >= $level ){
			return true;
		} else {
			return false;
		}
	 }
     
 }

/**
 * is_error_obj - function to check whether a value is an cms_error object
 * 
 * @param mixed $variable. The value you want to check against
 * @return bool true if $return is a "cms_error" object, false if its not.
 */
function is_error_obj( $variable ) {
    return ( is_object( $variable ) && is_a( $variable , "cms_error" ) );
}

/**************************************************************

function test() {
    $errors_in_test = new cms_error("initial error with lvl 3",3);
    $errors_in_test->add("level 1  4" ,4);
    $errors_in_test->add("New error");
    $errors_in_test->add("anther, level 2 4" ,4);
    $errors_in_test->add("anther, level 1 2" ,2);
    $errors_in_test->add("anther, level 1 7" ,7);
    $errors_in_test->add("anther, level 6" ,6);
    $errors_in_test->add("anther, level 2 7" ,7);
    $errors_in_test->add("anther, level 2 2" ,2);
    $errors_in_test->add("anther, level 3" ,3);
    
    return $errors_in_test;
}
$outcome = test ();

if (is_object($outcome) && is_a($outcome,"cms_error")){
    var_dump( is_error_obj ($outcome));
echo "<pre>";
print_r($outcome);
echo "</pre>";

echo "<pre>";
print_r($outcome->get_errors(3, true, false));
echo "</pre>";

} else {
    echo "no error detected";
}
echo $outcome->display_errors(3,true,false);
print_r($outcome->is_fatal());
*/
/*
echo "<pre>";
print_r($error);
echo "</pre>";
*/



?>