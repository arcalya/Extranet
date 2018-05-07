<?php
namespace includes;

use includes\Login;

class Bootstrap{
    
    public static $page;
    public static $action;
    public static $router;
    
    public static $currentUrl = '';
    
    public static function url()
    {
        $router = ( empty( $_GET[ 'page' ] ) ) ? 'login' : $_GET[ 'page' ];

        if( !empty( $router ) )
        {
            $parts = explode( '/', $router );
            self::$page    = ( isset( $parts[0] ) ) ? $parts[0] : '';
            self::$action  = ( isset( $parts[1] ) ) ? $parts[1] : '';
            $router = '';
            if( isset( $parts[2] ) )
            {
                foreach( $parts as $n => $part )
                {
                    if( $n > 1 )
                    {
                        $router .= ( $n > 2 ) ? '/'.$part : $part;                        
                    }
                }
            }
            self::$router  = $router;
            
            if( !empty( self::$page ) && self::$page !== 'login' )
            {
                self::$currentUrl .= '/' . self::$page;

                if( !empty( self::$action ) )
                {
                    self::$currentUrl .= '/' . self::$action;

                    if( !empty( self::$router ) )
                    {
                        self::$currentUrl .= '/' . $parts[2];
                    }
                }
            }
            
        }
        
        if( !file_exists( SITE_PATH . '/applications/'  . self::$page . '/Controller.php' )  && self::$page !== 'login'  && self::$page !== 'home' )
        {
            header('HTTP/1.0 404 NOT FOUND');
            include SITE_PATH.'/public/views/404.php';
           
            exit;
        }
    }
}