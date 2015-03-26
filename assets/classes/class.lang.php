<?php
/**
 * language class
 * 
 * This class makes sure the right language is displayed.
 * 
 * @author Willem van Oort <willem.vanoort@live.nl> 
 * @copyright 2013 TwentySix Media 
 * @since 1.0.0
 */
 
class Translation {
    
	/**
	 * Stores the language
	 * 
	 * @var string $language
	 * @access public
	 */
    public $language;
	
    /**
	 * Stores default language, advised is to keep at en_GB
	 * 
	 * @var string $default_language
	 * @access public
	 */
    public $default_language = "en_US";
	
	/**
	 * Stores all translations of requested language
	 *
	 * @var array $language_array
	 */
	public $language_array = array( );
	
	/**
	 * Stores all translations of default language, only used when an item of the requested language cant be found
	 *
	 * @var array $language_array
	 */
	public $default_language_array = array( );
	
    public function __construct( $language = "en_GB" ) {

    	$this->language = $language;
		if ( ! file_exists( ABSPATH . LANGPATH . "/" . $this->language .".json") ) {
			$this->language = $this->default_language;
		}
		$file = file_get_contents( ABSPATH . LANGPATH . "/" . $this->language .".json" );
		$this->language_array = json_decode( $file , true );
    }
	
	/**
	 * Gets translation that goes with requested unique string
	 *
	 * @param string $unique_string unique abbreviation from requested string 
	 */
	public function get_translation( $unique_abbr , $function = null ) {
		
		if ( isset($this->language_array[ $unique_abbr ]) ) {
			$raw = $this->language_array[ $unique_abbr ];
		} else {
			if ( $this->default_language_array === array ( ) ) {
				if ( file_exists( ABSPATH . LANGPATH . "/" . $this->default_language .".json") ) {
					$file = file_get_contents( ABSPATH . LANGPATH . "/" . $this->default_language .".json" );
					$this->default_language_array = json_decode( $file , true );
				} else {
					return "!*!".$unique_abbr."!*!";
				}
			}
			$raw = (isset($this->default_language_array[ $unique_abbr ])) ? $this->default_language_array[ $unique_abbr ] : "!!!".$unique_abbr."!!!";
		}
		
		// call user defined function before output
		return ( isset( $function ) && is_callable( $function ) ) ? call_user_func( $function , $raw ) : $raw;
		
	}
}