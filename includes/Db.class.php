<?php
namespace includes;

use Mysqli;

final class Db{
    
    private static $_connect;
    
    private function __construct(){}
    
    public static function connect( $config )
    {
        if( !isset( self::$_connect ) )
        {
            self::$_connect = new mysqli( $config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname'], $config['dbport'] );
            if( self::$_connect->connect_errno ) 
            {    
                header( 'location:'. SITE_URL . '/configcheck' ); exit;
            }
        }
        
        return self::$_connect;
    }
    
    public static function db()
    {
        return self::$_connect;
    }
}