<?php
namespace includes;

use includes\tools\Orm;

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
class Adm
{
    /**
     * All settings define in a array.
     *
     * @var.
     */
    private static $_headings  = false;
    
    
    private static $_rightsSymbol  = false;
    
    
    private static $_rights  = false;
    
    
    private static $_module  = false;
    
    
    private static $_action  = false;
    
    
    private static $_menu = null;

    
    private static $_activeMenu = false;



    /**
     * Class constructor (Multi-Singleton).
     */
    private function __construct()
    {	
    }
        
    public static function initSettings( $module, $action )
    {
        self::$_headings = [
            ['label'=>'Profil', 'value'=>'pages'],   
            ['label'=>'Suivi', 'value'=>'suivi'], 
            ['label'=>'Panification', 'value'=>'planification'], 
            ['label'=>'Rapports', 'value'=>'rapports'], 
            ['label'=>'Outils', 'value'=>'modules'],
            ['label'=>'Utilisateurs', 'value'=>'users'],
            ['label'=>'Paramètres', 'value'=>'params'],
        ];
        
        self::$_rightsSymbol = [
            'r' => ['title'=>'Lire', 'icon'=>'icon-eye-open'],
            'w' => ['title'=>'Ajouter', 'icon'=>'icon-plus-sign'],
            'm' => ['title'=>'Modifier', 'icon'=>'icon-edit'],
            'd' => ['title'=>'Supprimer', 'icon'=>'icon-remove-circle'],
            'v' => ['title'=>'Valider', 'icon'=>'icon-ok-circle'],
        ];
                
        self::$_module = self::module( $module );
        
        self::$_action = ( empty( $action ) ) ? '' : $action;
        
        self::_setRights();
        
        self::_setAdminmenu();
        
    }
    
    public static function resetRights( $params = [] )
    {
        self::$_menu    = ( isset( $params[ 'menu' ] ) )    ? $params[ 'menu' ]     : self::$_menu;
        self::$_action  = ( isset( $params[ 'action' ] ) )  ? $params[ 'action' ]   : self::$_action;
    }
    
    public static function getHeadings()
    {
        return self::$_headings;
    }
    
    
    public static function getRightsSymbol()
    {
        return self::$_rightsSymbol;
    }
    
    
    public static function getAdminmenu()
    {
        return self::$_menu;
    }
    
    
    private static function _setAdminmenu( $full = false )
    {
        if( isset( $_SESSION[ 'adminOK' ] ) && $_SESSION[ 'adminOK' ])
        { 
            self::$_menu = [];
            $adminRight = $_SESSION['adminRight'];
            
            $headings   = self::getHeadings();
            
            if( is_array( $headings ) )
            {
                foreach( $headings as $heading )
                {                    
                    $adminmenus = ( $full ) ? self::adminmenus( ['HeadingMenu'=>$heading[ 'value' ]] ) : self::adminmenusrights( ['IsActiveMenu' => 1, 'HeadingMenu'=>$heading[ 'value' ], 'IdGroup'=>$adminRight, 'Rights' => 'r' ] );
                    self::$_menu[ $heading[ 'value' ] ] = [ 'headings' => $heading, 'menus' => $adminmenus, 'menuactive' => self::$_activeMenu ];
                }
            }
        }  
    }
    
    
    private static function _setRights()
    {
        if( isset( $_SESSION[ 'adminOK' ] ) && $_SESSION[ 'adminOK' ])
        {
            self::$_rights = self::grouprightsModule( [ 'IdGroup' => $_SESSION['adminRight'], 'ModuleMenu' => ( ( isset( self::$_module ) ) ? self::$_module->IdModule : '' ), 'ActionMenu' => self::$_action ] );
        }
    }
            
    
    public static function getRights( $params = [] )
    {
        if( isset( $_SESSION[ 'adminOK' ] ) && $_SESSION[ 'adminOK' ])
        {
            $paramRight = [ 'IdGroup' => $_SESSION['adminRight'] ];
            $parameters = array_merge( $paramRight, $params );
            $groupRight = self::grouprights( $parameters );
        }
        else
        {
            $groupRight = false;
        }
                    
        return $groupRight;
    }
    
    
    public static function getAuthRights( $param, $mod, $act )
    {
        if( isset( $mod ) )
        {
            $params = ( isset( $act ) ) ? [ 'NameModule' => $mod, 'ActionMenu' => $act ] : [ 'NameModule' => $mod ];
            $IdMenu = self::adminmenu( $params );
            if( isset( $IdMenu ) &&  self::getRights( ['IdMenu'=>$IdMenu->IdMenu, 'Rights'=>$param] ) !== null )
            {
                return true;
            }
        }
        if( isset( $act ) )
        {
            $IdMenu = self::adminmenu( [ 'ModuleMenu' => self::$_module->IdModule, 'ActionMenu' => $act ] );
            if( isset( $IdMenu ) &&  self::getRights( ['IdMenu'=>$IdMenu->IdMenu, 'Rights'=>$param] ) !== null )
            {
                return true;
            }
        }
        if( isset( self::$_rights[ $param ] ) )
        {
            return self::$_rights[ $param ];
        }
        else 
        {
            return false;
        }
    }
    
    
    public static function grouprights( $params = [] )
    {
        $orm = new Orm( 'group_rights' );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->execute();
        
        return $result;
    }
    
    
    public static function grouprightsModule( $params = [] )
    {
        $orm = new Orm( 'group_rights' );
        
        $rights = $orm  ->select()
                        ->join([ 'group_rights' => 'IdMenu', 'adminmenus' => 'IdMenu' ])
                        ->where( $params )
                        ->execute();
        
        if( isset( $rights ) )
        {
            $r = [];
            foreach( $rights as $right )
            {
                $r[ $right->Rights ] = true;
            }
        }
        else 
        {
            $r = false;
        }
        return $r;
    }
    
    public static function module( $NameModule )
    {
        $orm = new Orm( 'adminmenumodules');
        
        $result = $orm  ->select()
                        ->where([ 'NameModule' => $NameModule ])
                        ->first();
        return $result; 
    }
    
    
    public static function adminmenu( $params = [] )
    {
        $orm = new Orm( 'adminmenus');
        
        $params[ 'IdOffice' ] = $_SESSION[ 'adminOffice' ];
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->join([ 'adminmenus' => 'ModuleMenu', 'adminmenumodules' => 'IdModule' ])
                        ->join([ 'adminmenus' => 'IdMenu', 'adminmenu_office' => 'IdMenu' ])
                        ->first();
        
        return $result;
    }
    
    
    public static function adminmenus( $params = [] )
    {
        $orm = new Orm( 'adminmenus');
        
        $params[ 'IdOffice' ] = $_SESSION[ 'adminOffice' ];
        
        $results = $orm  ->select()
                        ->where( $params )
                        ->join([ 'adminmenus' => 'ModuleMenu', 'adminmenumodules' => 'IdModule' ])
                        ->join([ 'adminmenus' => 'IdMenu', 'adminmenu_office' => 'IdMenu' ])
                        ->order([ 'OrderMenu' => 'ASC' ])
                        ->execute();
        
        return self::setActivemenu( $results );
    }
    
    
    public static function adminmenusrights( $params = [] )
    {
        $orm = new Orm( 'adminmenus');
        
        $params[ 'IdOffice' ] = $_SESSION[ 'adminOffice' ];
        
        $results = $orm  ->select()
                        ->join([ 'adminmenus' => 'IdMenu', 'group_rights' => 'IdMenu' ])
                        ->join([ 'adminmenus' => 'ModuleMenu', 'adminmenumodules' => 'IdModule' ])
                        ->join([ 'adminmenus' => 'IdMenu', 'adminmenu_office' => 'IdMenu' ])
                        ->where( $params )
                        ->group([ 'adminmenus' => 'IdMenu' ])
                        ->order([ 'OrderMenu' => 'ASC' ])
                        ->execute();
        
        return self::setActivemenu( $results );
    }
    
    
    private static function setActivemenu( $results )
    {
        $activeSet = false;
        if( isset( $results ) )
        {
            foreach( $results as $result )
            {
                $result->ActiveMenu = ( self::$_module && self::$_module->IdModule == $result->ModuleMenu && self::$_action == $result->ActionMenu ) ? true : false;
                if( $result->ActiveMenu )
                {
                    $activeSet = $result->ActiveMenu;
                }
            }
            self::$_activeMenu = $result->ActiveMenu;
        }
        
        return $results;
    }
    
}