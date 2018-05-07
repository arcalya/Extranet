<?php
namespace includes;


final class Lang
{

    /**
     * The current selected language.
     *
     * @var string.
     */
    private static $_language   = false;

    /**
     * The default behavior for the outputed strings coming from getLabel and getText.
     *
     * @var boolean.
     */
    private static $_isOutputUTF8  = true;

   
    /**
     * The list of translations for the labels for each loaded language (lists of SimpleXMLElement objects).
     *
     * @var array of SimpleXMLElement objects.
     */
    private static $_labels        = array();

    /**
     * The list of translations for the texts for each loaded language (lists of SimpleXMLElement objects).
     *
     * @var array of SimpleXMLElement objects.
     */
    private static $_texts         = array();


   
    private function __construct()
    {
    }
    
    public static function initSettings( $language, $charset )
    {
        self::$_language    = $language;
        
        $charsetLower = strtolower( $charset );
        
        self::$_isOutputUTF8     = ( $charsetLower === 'utf-8' || $charsetLower === 'utf8' ) ? true : false;
        
        self::_load();
    }

    
    public static function strUtf8Encode( $str )
    {
        if( self::$_isOutputUTF8 )
        {
            return utf8_encode( $str );
        }
        else
        {
            return $str;
        }        
    }

    /**
     * Sets the default text output encoding.
     * In fact, SimpleXMLElement stores the string as UTF-8 internally.
     * Therefore, by default the strings are outputed as UTF-8.
     * To allow outputting ISO, you can pass 'true' to this method.
     * The default value of this parameter is 'false'.
     *
     * @param  boolean $val ( optional ) if true, strings output will be ISO.
     *                      If false, strings output will be UTF-8 (the default configuration).
     *                      Otherwise, the method will not change the actual value of this parameter.
     * @return boolean      the actual value.
     */
    public static function utf8Decode( $val = NULL )
    {
        if( $val !== NULL ) 
        {
            self::$_outputISO = ( $val ) ? true : false;
        }

        return self::$_outputISO;
    }


    /**
     * Tries to load a translation file.
     *
     * @return boolean       true if the translation file has been successfully loaded.
     */
    private static function _load()
    {
        $lang   = self::$_language;
        $xml    = null;
        
        if( is_file( $file = SITE_PATH . '/public/languages/' . strtolower( $lang ) . '.xml' ) && is_readable( $file ) ) 
        {
            if( !( $xml = simplexml_load_file( $file ) ) ) 
            {
                self::$_labels[ $lang ] = [];
                self::$_texts[ $lang ]  = [];
            }

            self::$_labels[ $lang ] = $xml->labels;
            self::$_texts[ $lang ]  = $xml->texts;

            return true;
        }

        return false;
    }

    /**
     * Gets the translation of a label for the requested language, the specified language or the default language.
     * If no $name label is found, the string 'Label not found' will be returned.
     *
     * @param  string $name       the name of the label for which to find a translation.
     * @return string             the translated label or '[Label not found]'.
     */
    public static function getLabel( $name )
    {
        $res    = false;
        $lang   = self::$_language;

        if( isset( self::$_labels[ $lang ] ) ) 
        {
            if( isset( self::$_labels[ $lang ]->$name ) )
            {
                $res = ( self::$_isOutputUTF8 ) ? ( string )self::$_labels[ $lang ]->$name : utf8_decode( ( string )self::$_labels[ $lang ]->$name );
            }

        }

        return ( $res !== false ) ? $res : '[Label not found]';
    }

    /**
     * Gets the translation of a text for the requested language, the specified language or the default language.
     * If no $name text is found, the string 'Text not found' will be returned.
     *
     * @param  string $name       the name of the text for which to find a translation.
     * @return string             the translated text or '[Text not found]'.
     */
    public static function getText( $name )
    {
        $res    = false;
        $lang   = self::$_language;

        if( isset( self::$_texts[ $lang ] ) )
        {
            if( isset( self::$_texts[ $lang ]->$name ) )  // the current language has a $name text
            {
                $res = ( self::$_isOutputUTF8 ) ? ( string )self::$_texts[ $lang ]->$name : utf8_decode( ( string )self::$_texts[ $lang ]->$name );
            }
        } 
        return ( $res !== false ) ? $res : '[Text not found]';
    }

}