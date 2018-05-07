<?php
namespace includes\tools;

/**
 * The string class  :
 *  - transform strings (ex. suppress accents) 
 *  - makes string format verification (ex. e-mail, url, addresses)
 *  - crop texts in a number of characters
 * 
 * 
 * Example of use :
 * 
    $mystring = new String();
    echo 'This text is in lowercase and with no space : ' . 
         $mystring->make_lowercase( 'Lowercase and no space string' ) . '<br />';
 * 
 * 
 * @author Olivier Dommange (add you name if you make implementations)
 * @copyright GPL
 * @version 0.1
 */
class String {
    
    
    function __contstruct(){
    }
    
    
    /**
     * Make alias and verify that it is unique in the DB
     * 
     * @param string $string // The string to insert in a field of the DB as an alias
     * @param string $tableBd // The table in DB that will be checked so the alias doesn't exist
     * @param string $fieldBd // The field of the table in DB that will be checked so the alias doesn't exist
     * @param string $valueNotToConsidere // A value that could be used by the alias if see ine the DB
     * @return string
     */
    public function make_alias_db( $string, $tableBd, $fieldBd, $valueNotToConsidere = null ){
	
        $db = DB::_db();
        
        $alias = $this->make_alias( $string );

        $other = false;
        $num = '';
        $aliasString = $alias;

        $whereNot = ( isset( $valueNotToConsidere ) ) ? ' AND '.$fieldBd.' <> \''.$valueNotToConsidere.'\'' : '';

        while( !$other ){
            if( $num == '' ) $suffix = ''; else $suffix = '-'.$num;
            $aliasString = $alias.$suffix;
            $sql = 'SELECT * FROM '.$tableBd.' 
                            WHERE '.$fieldBd.' = \''.$aliasString.'\''
                            .$whereNot;
            $result = $db->query( $sql );
            if( $result->num_rows == 0 ){
                        $other = true;	
            }else{
                        if( $num == '' ) $num = 1; else $num++;
            }
        }

        return $aliasString;
    }

    
    /**
     * 
     * @param string $string // The string to make an alias
     * @return type
     */
    public function make_alias( $string ){
        
        $string = utf8_decode( $this->make_lowercase( $string ) ); 
        $doubleDash = false;
        while( !$doubleDash ){
            $textbefore = $string;
            $string = str_replace("--", "-", $string );
            if( $textbefore === $string ) $doubleDash = true;
        }
        
        return utf8_encode( $string ); 
        
    }

    /**
     * 
     * @param string $string // The string to define characters to lowercase 
     * @return type
     */
    public function make_lowercase( $string ){
        
        $accents = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ+.?!$=()/&%*[]{}<>' ";
        $sans = "aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr--------------------";	
        $string = utf8_decode( $string );    
        $string = strtr( $string, utf8_decode( $accents ), $sans );
        $string = strtolower( $string );
        return utf8_encode( $string ); 
        
    }
    
    /**
     * Crop sentences by an number words
     * 
     * @param string $string // The string to crop
     * @param string $nbWords [optional] // Number of words to get. 18 by default
     * @return string
     */
    public function crop_word( $string, $nbWords = 18 ){
		
      $newString = explode( ' ', $string );
      $endString = '';

      $length = count($newString);

      if ( $length > $nbWords ) {

          for( $i = 0; $i <= $nbWords; $i++ ){
              if( $i != 0 ) $endString .= ' ';
              $endString .= $newString[ $i ];
          }	
          $endString .= '...';
          return $endString;
      } else {
          return $string;
      }
    }

    
    /**
     * @
     * Checks the format of a string so it corresponds
     *   email              : (no $param) Email address
     *   url                : (no $param) URL address
     *   user               : (no $param) alphanumeric user name within 5 to 20 characters and underscore symbol
     *   creditCard         : (no $param) credit card number
     *   ip                 : (no $param) IP address
     *   textExist          : ($param string // Text to check) Check if a string is present into another one
     *   nbCharactersMin    : ($param int    // minimum number of characters) Checks the number is over or equal
     *   nbCharactersMax    : ($param int    // maximum number of characters) Checks the number is below or equal
     *   nbCharactersBetween: ($param string // minimum and maximum number of characters seperated be a dash (ex. 2-9)
     *   nbCharacters       : ($param int    // Number of characters) Checks the number is equal 
     * 
     * 
     * @param string $string                    // String to check
     * @param string $formatToCheck             // Defined as (email, url, user, creditCard, ip, textExist, nbCharactersMin, nbCharactersMax, nbCharactersBetween, nbCharacters)
     * @param string or int $param [optional]   // Depends if the format need a parameter to evaluate
     * @return boolean
     */
    public function check_format_string( $string, $formatToCheck = 'email', $param = null ){
        
        $process = false;
        
        if( $formatToCheck == 'email' ){
            
            if( filter_var( $string, FILTER_VALIDATE_EMAIL ) ) $process = true;            
            
        }else if( $formatToCheck == 'url' ){
            
            $format = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
            if( preg_match( $format, $string ) ) $process = true;            
            
        }else if( $formatToCheck == 'user' ){
            
            $format = "/^[a-z\d_]{5,20}$/i";
            if( preg_match( $format, $string ) ) $process = true;            
            
        }else if( $formatToCheck == 'creditCard' ){
            
            $format = "/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})$/";
            if( preg_match( $format, $string ) ) $process = true;            
            
        }else if( $formatToCheck == 'ip' ){
            
            $format = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
            if( preg_match( $format, $string ) ) $process = true;            
            
        }else if( $formatToCheck == 'textExist' ){
            
            $format =  "/\b(". $param . "\w+)\b/";
            if( preg_match( $format, $string ) ) $process = true;            
            
        }else if( $formatToCheck == 'nbCharactersMin' ){
            
            if( strlen( $string ) >= $param ) $process = true;            
            
        }else if( $formatToCheck == 'nbCharactersMax' ){
            
            if( strlen( $string ) <= $param ) $process = true;            
            
        }else if( $formatToCheck == 'nbCharactersBetween' ){
            
            list( $min, $max ) = explode( '-', $param );
            $nbChars = strlen( $string );
            if( $nbChars >= $min && $nbChars <= $max ) $process = true;            
            
        }else if( $formatToCheck == 'nbCharacters' ){
            
            if( strlen( $string ) == $param ) $process = true;            
            
        }
        
        return $process;
        
    }
    
    
    /**
     * Creates a word with random characters.
     * Usefull for captcha and random passwords.
     * 
     * @param int $nbCharacters [optional] // Number of Characters
     * @return string
     */
    public function getRandomCharacters( $nbCharacters = 6 ){
        
        $characters = str_split( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' );
        $nbCharactersToShuffle = count( $characters );
        shuffle( $characters) ;
        $newWord = '';
        for( $i = 0; $i < $nbCharacters; $i++ ){
            $val = rand( 0, ( $nbCharactersToShuffle - 1 ) );
            $newWord .= $characters[ $val ];
        }
        
        return $newWord;
    }
    
    
    /**
     * Creates a captcha by generating an image with the captcha text into it.
     * The method returns the text to store in a session variable.
     *   'captcha.jpg' The image used as the captcha background
     *   'captcha-text.jpg' The image rendered by the method
     * 
     * By default those images are stored in the folder 'modules/admin/images/captcha'
     * The fonts used are in the folder 'modules/admin/files/captcha-fonts'
     * 
     * 
     * @param string $folderSaveImg [optional]  // Defines to folder that will receive the image generated
     * @return string                           // The captcha text
     */
    public function createCaptcha( $folderSaveImg = 'modules/admin/images/captcha/' ){
        
        $folderImg      = CMS5D_ROOT_PATH . $folderSaveImg;
        $folderFonts    = CMS5D_MOD_PATH . 'admin/files/captcha-fonts/';
        $captchaWidth   = 220;
        $captchaHeight  = 40;
        $fonts          = array( 
                            $folderFonts . 'tahoma.ttf',  
                            $folderFonts . 'GARAIT.TTF',  
                            $folderFonts . 'georgia.ttf',  
                            $folderFonts . 'arial.ttf' 
                            );
        
        $text = $this->getRandomCharacters();
        
        $bkg    = imagecreatefromjpeg( $folderImg . 'captcha.jpg' );
        $image  = ImageCreate( $captchaWidth, $captchaHeight );
        
        ImageCopy( $image, $bkg, 0, 0, 0, 0, $captchaWidth, $captchaHeight );
        ImageDestroy( $bkg );
        
        $font       = $fonts[ rand( 0, count( $fonts ) - 1 ) ];
        $textColor  = imagecolorallocate( $image, 255, 255, 255 );
        
        ImageTTFText( $image, 23, -2, 10, ( $captchaHeight - ( $captchaHeight / 2 ) + 10 ), $textColor, $font, $text );

        imagejpeg( $image, $folderImg . 'captcha_text.jpg' );
        imagedestroy( $image );
        
        return $text;
        
    }
    
    /**
     * Creates an image containing a defined text.
     * The method create a png image file
     *  
     * 
     * @param string	$text					// The string to convert in an image
     * @param string	$folderImg				// The folder in wich the image will be saved
     * @param string	$fontFile [optional]	// The font file (path and file)
     * @param int		$fontSize [optional]	// The font size
     * @param array		$fontColor [optional]	// The color of the text
     * @param array		$bkgColor [optional]	// The color of the basckground of the image
     * @return boolean                          // if it went right
     */
	function createImgFromTxt( $text, $folderfileImg, $fontFile = 'admin/files/captcha-fonts/arial.ttf', $fontSize = 10, $fontColor = array( 102, 102, 102 ), $bkgColor = array( 255, 255, 255 ) ){

		$dims	= imagettfbbox( $fontSize, 0, CMS5D_MOD_PATH.$fontFile, $text );
		$width	= $dims[4] - $dims[6] + ( ( $dims[4] - $dims[6] ) / 50 ); //Width
		$height	= $dims[3] - $dims[5]; // Height

		$image = imagecreatetruecolor( $width, $height );

		$color	= imagecolorallocate( $image, $fontColor[ 0 ],  $fontColor[ 1 ],  $fontColor[ 2 ] );
		$colorBkg	= imagecolorallocate( $image, $bkgColor[ 0 ],  $bkgColor[ 1 ],  $bkgColor[ 2 ] );
		imagefilledrectangle( $image, 0, 0, $width, $height, $colorBkg );
		$x = 0;
		$y = $fontSize;
		imagefttext( $image, $fontSize, 0, $x, $y, $color, CMS5D_MOD_PATH.$fontFile, $text );

		imagepng( $image, $folderfileImg ); 
		imagedestroy( $image ); 
		
		if( file_exists( $folderfileImg ) )
			return true;
		else
			return false;
	}
	

	
}

?>
