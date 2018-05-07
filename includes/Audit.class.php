<?php
namespace includes;

use includes\tools\Orm;
use includes\tools\String;
use includes\Bootstrap;

/**
 * Class Configuration (Multi-Singleton).
 * Used to load the configurations defined for the applications.
 *
 * PHP versions >= 5.0.0
 *
 * Copyright (c) 2016
 *
 * @package Extranet
 * @author 5D
 * @copyright Copyright (c) 2012, 5D. All Rights Reserved.
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GPL2.0
 * @version 1.0
 */
class Audit
{
    /**
     * All settings define in a array.
     *
     * @var.
     */
    private static $_systemaudits  = [
            'IdAudit'               =>[ 'type' => 'INT', 'autoincrement' => true, 'primary' => true ],
            'DateAudit'             =>[ 'type' => 'DATETIME', 'default' => 'NOW' ],
            'FirstnameUserAudit'    =>[ 'type' => 'STR', 'mandatory' => true ],
            'NameUserAudit'         =>[ 'type' => 'STR', 'mandatory' => true ],
            'LoginUserAudit'        =>[ 'type' => 'STR', 'mandatory' => true ],
            'EmailUserAudit'        =>[ 'type' => 'STR', 'mandatory' => true ],
            'IpUserAudit'           =>[ 'type' => 'STR', 'mandatory' => true ],
            'UrlSystemAudit'        =>[ 'type' => 'STR', 'mandatory' => true ],
            'ModuleSystemAudit'     =>[ 'type' => 'STR', 'mandatory' => true ],
            'ActionSystemAudit'     =>[ 'type' => 'STR', 'mandatory' => true ],
            'DescriptionAudit'      =>[ 'type' => 'STR', 'mandatory' => true ]
        ];
    
    private static $_datas = [ 
            'Date'          => '', 
            'Firstname'     => '', 
            'Name'          => '', 
            'Login'         => '', 
            'Email'         => '', 
            'Ip'            => '', 
            'Url'           => '', 
            'Module'        => '', 
            'Action'        => '', 
            'Description'   => '',
        ];
    
    private static $_datasSets = [];
    
    private static $_auditSequence = [];

    /**
     * Class constructor (Multi-Singleton).
     */
    private function __construct()
    {	
    }
        
    public static function initSettings()
    {
        self::_prepareIpAudit();
    }
        
    public static function initUrlSettings()
    {        
        self::_prepareUrlAudit();
    }
    
    public static function getIp()
    {
        return self::$_datas[ 'Ip' ];
    }
    
    public static function displayAudit()
    {  
        if( count( self::$_auditSequence ) > 0 )
        {
            $htmlStr = '';

            $htmlStr .= '<div style="position:fixed; box-shadow:0px 0px 3px 4px rgba(0,0,0,0.2); border:3px solid #f00; right:0px; bottom:0px; max-height:300px; overflow:auto; z-index:100000; width:25%; padding:20px; background:rgba(0,0,0,0.6); color:#fff; font-family:courier; font-size:10px;">';
            $htmlStr .= '<h4>Debug Audit</h4>';

                foreach( self::$_auditSequence as $seq ){
                    $htmlStr .= 'Date:' . $seq[ 'Date' ] . '<br />';
                    $htmlStr .= $seq[ 'Description' ];
                    $htmlStr .= '<hr />';
                }

            $htmlStr .= '</div>';

            echo $htmlStr;
        }
        
    }
    
    public static function setAudit( $datas = [] )
    {
        self::$_datasSets = $datas;
                
        self::_prepareDatasAudit();
        
        self::$_datas[ 'Date' ] = date( 'Y-m-d h:i:s' );
        
        $orm = new Orm( 'systemaudits', self::$_systemaudits );
        
        $orm->prepareDatas([ 'FirstnameUserAudit'   => self::$_datas[ 'Firstname' ] ]);
        $orm->prepareDatas([ 'NameUserAudit'        => self::$_datas[ 'Name' ] ]);
        $orm->prepareDatas([ 'LoginUserAudit'       => self::$_datas[ 'Login' ] ]);
        $orm->prepareDatas([ 'EmailUserAudit'       => self::$_datas[ 'Email' ] ]);
        $orm->prepareDatas([ 'IpUserAudit'          => self::$_datas[ 'Ip' ] ]);
        $orm->prepareDatas([ 'UrlSystemAudit'       => self::$_datas[ 'Url' ] ]);
        $orm->prepareDatas([ 'ModuleSystemAudit'    => self::$_datas[ 'Module' ] ]);
        $orm->prepareDatas([ 'ActionSystemAudit'    => self::$_datas[ 'Action' ] ]);
        $orm->prepareDatas([ 'DescriptionAudit'     => self::$_datas[ 'Description' ] ]);
        
        $orm->insert();
        
        self::$_auditSequence[] = self::$_datas;
        
        self::_regulateAudit();
    }
    
    
    private static function _sanatizeIpAudit( $ip )
    {
        $ipString = new String();
        
        if( $ipString->check_format_string( $ip, 'ip' ) )
        {
            return $ip;
        }
        else
        {
            return null;
        }
    }
    
    
    private static function _sanatizeIpAndProxyAudit( $ip )
    {
        $is_ip = preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $ip, $regs);
        if( $is_ip && ( count( $regs ) > 0 ) )
        {
            return $regs[ 0 ];
        }
        else
        {
            return null;
        }
    }
    
    private static function _prepareIpAudit()
    { 
        if( isset( $_SERVER['REMOTE_ADDR'] ) )
        {
            $ip = self::_sanatizeIpAudit( $_SERVER['REMOTE_ADDR'] );
        }
        
        if ( !isset( $ip ) && isset( $_SERVER['HTTP_CLIENT_IP'] ) )
        {
            $ip = self::_sanatizeIpAndProxyAudit( $_SERVER['HTTP_CLIENT_IP'] );
        }
        
        if ( !isset( $ip ) && isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
        {
            $ip = self::_sanatizeIpAndProxyAudit( $_SERVER['HTTP_X_FORWARDED_FOR'] );
        }
           
        self::$_datas[ 'Ip' ] = $ip;
    }
    
    private static function _prepareUrlAudit()
    {      
        self::$_datas[ 'Url' ]      = Bootstrap::$currentUrl;
        self::$_datas[ 'Module' ]   = Bootstrap::$page;
        self::$_datas[ 'Action' ]   = Bootstrap::$action;
    }
        
    private static function _prepareDatasAudit()
    { 
        if( !is_array( self::$_datasSets ) )
        {
            self::$_datas[ 'Description' ] = self::$_datasSets;
        }
        else if( isset( self::$_datasSets[ 'Description' ] ) )
        {
            self::$_datas[ 'Description' ] = self::$_datasSets[ 'Description' ];
        }
        else
        {
            self::$_datas[ 'Description' ] = '';            
        }
        
        if( isset( self::$_datasSets[ 'Firstname' ] ) )
        {
            self::$_datas[ 'Firstname' ] = self::$_datasSets[ 'Firstname' ];
        }
        else
        {
            self::$_datas[ 'Firstname' ] = ( isset( $_SESSION[ 'adminFirstname' ] ) ) ? $_SESSION[ 'adminFirstname' ] : '';
        }
        
        if( isset( self::$_datasSets[ 'Name' ] ) )
        {
            self::$_datas[ 'Name' ] = self::$_datasSets[ 'Name' ];
        }
        else
        {
            self::$_datas[ 'Name' ] = ( isset( $_SESSION[ 'adminLastname' ] ) ) ? $_SESSION[ 'adminLastname' ] : '';
        }
        
        if( isset( self::$_datasSets[ 'Email' ] ) )
        {
            self::$_datas[ 'Email' ] = self::$_datasSets[ 'Email' ];
        }
        else
        {
            self::$_datas[ 'Email' ] = ( isset( $_SESSION[ 'adminEmail' ] ) ) ? $_SESSION[ 'adminEmail' ] : '';
        }
        
        if( isset( self::$_datasSets[ 'Login' ] ) )
        {
            self::$_datas[ 'Login' ] = self::$_datasSets[ 'Login' ];
        }
        else
        {
            self::$_datas[ 'Login' ] = ( isset( $_SESSION[ 'adminLogin' ] ) ) ? $_SESSION[ 'adminLogin' ] : '';
        }
    }
    
    
    private static function _regulateAudit()
    {
        $orm = new Orm( 'systemaudits', self::$_systemaudits );
        
        $results = $orm ->select()
                        ->order(['DateAudit'=>'Desc'])
                        ->limit(['num'=>1000, 'nb'=>10])
                        ->execute();
        
        if( isset( $results ) )
        {
            foreach( $results as $result )
            {
                $orm->delete([ 'IdAudit' => $result->IdAudit ]);
            }
        }
    }
	
}