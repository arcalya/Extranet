<?php
namespace includes;

use includes\Request;
use includes\Login;
use includes\Bootstrap;
use includes\Adm;

use stdClass;

class Template{

    private static $_userInfos      = null;
    private static $_jsFiles        = [];
    private static $_jsPluginsFiles = [];
    
    public static function page( $pageInfos )
    {
        $request    = Request::getInstance();
        
        if( $pageInfos[ 'router' ] === 'ajax' && $_SESSION['token'] === $request->getVar( 'token', false, 'GP' ) )              // 1. AJAX
        {   
            $_SESSION['token'] = $request->generateToken(); // Use for AJAX query security
            
            self::_includeInTemplate( $pageInfos['page'], $pageInfos['action'], $pageInfos['router'] );
        }
        else if( ( $pageInfos[ 'page' ] === 'login' && $pageInfos[ 'action' ] === 'disconnect' ) || 
                !Login::isVisitorAccess( $pageInfos[ 'page' ], $pageInfos[ 'action' ] )  )                                      // 2. User Disconnect or visitor url access attempt not allowed
        {
            Login::logout();
        }
        else if( $pageInfos[ 'page' ] === 'login' && !empty( $pageInfos[ 'action' ] ) && is_numeric( $pageInfos[ 'action' ] ) ) // 3. Change office
        {            
            if( Login::changeOffice( $pageInfos[ 'action' ] ) )
            {
                header( 'location:' . SITE_URL . '/' . $pageInfos[ 'router' ] );
                exit;
            }
            else 
            {
                header( 'location:' . SITE_URL );
                exit;
            }
        }
        else 
        {
            $session = isset( $_SESSION['token'] ) ? $_SESSION['token'] : '';
            
            $_SESSION['token'] = $request->generateToken(); // Use for AJAX query security
            
            if( $pageInfos[ 'router' ] === 'ajax' )                                                                               // 4. Ajax error (token not reconized
            {
                $jsonResponse = [ 
                        'status' => 'FAIL',
                        'tokenSentByPost' => $request->getVar( 'token', false, 'GP' ),
                        'tokenOldSession' => $session,   
                        'tokenNewSession' => $_SESSION['token'], 
                        'token' => $_SESSION['token'], 
                        'page' => $pageInfos[ 'page' ], 
                        'action' => $pageInfos[ 'action' ] 
                        ];
                
                Audit::setAudit([ 'Description' => 'AJAX' . ( implode( '; ', $jsonResponse ) ) ]);
                
                echo json_encode( $jsonResponse );
                
                exit;
            }  
             
            if( ( $isLoguedIn = Login::isLoguedIn() ) || $pageInfos[ 'page' ] === 'login' )                                // 5. Normal Loading Page
            {
                self::setCommonJSFiles(); 
                
                if( $pageInfos[ 'action' ] === 'print' )                                                                             // 5.1 Print page
                {
                    $pageInfos[ 'bodyClass' ] = ' print';
                    
                    self::_render('print', $pageInfos );
                }
                else                                                                                                                 // 5.2 Standard page
                {
                    $pageInfos[ 'bodyClass' ] = ( $isLoguedIn && !Login::isVisitor() ) ? '' : ' login';                              // also used for login form

                    self::$_userInfos = Login::userInfos();

                    self::_render('page', $pageInfos );
                }
            }
            else                                                                                                                    // 6. Something's strange... Then it redirects to homepage
            {
                header( 'location:' . SITE_URL );
            
                exit;
            }
        }
    }

    private static function _includeInTemplate( $page, $action = '', $router = '' )
    { 
        if( file_exists( SITE_PATH . '/applications/' .$page. '/Controller.php' ) )
        {            
            if( Login::getDatas()->isLoguedIn )
            {
                include_once SITE_PATH . '/applications/' .$page. '/Controller.php';

                $controllerPath = '\applications\\'.$page.'\Controller'; // Acceder à la classe Controller par l'espace de nom.

                $controller = new $controllerPath( $page, $action, $router );
                
                self::setJSFiles( $page, $action );
                
                $view = $controller->view();
                $datas = $controller->datas();
                self::_render($view, $datas);
            }
        }
        else if( $page === 'home' )
        {            
            Login::landingPage();            
        }
        else if( $page === 'menuadmin' )
        {
            if( Login::isLoguedIn() && !Login::isVisitor() )
            {
                self::_render( 'menuadmin', Adm::getAdminmenu() );
            }
        }
        else if( $page === 'topadmin' )
        {
            if( Login::isLoguedIn() && !Login::isVisitor() )
            {
                self::_render( 'topadmin', null );
            }		
        }
        else
        {
            if( $page === 'login' && $action === 'newpassAjax' )
            {
                Login::passrecovery();
            }
            else if( $page === 'login' && $action === 'changepassAjax' )
            {
                Login::passchange();
            }
            else if( Login::isLoguedIn() && !Login::isVisitor() )
            {
               Login::landingPage();	
            }
            else{
                $datas = Login::getDatas();
                
                self::_render( 'login', $datas );	
            }
        }

    }


    private static function _render( $view, $datas = '' )
    {
        $user = self::$_userInfos;
        
        // Effectue les rendus pour l'affichage à l'aide de la mémoire tampon
        ob_start();
        
                include SITE_PATH . '/public/views/' . $view . '.php'; 
                $template = ob_get_contents();
                
        ob_end_clean();

        echo $template;
    }
    
    
    private static function setCommonJSFiles()
    {
        if( file_exists( SITE_PATH . '/public/theme/json/settings.json' ) )
        {  
            $json   = file_get_contents( SITE_PATH . '/public/theme/json/settings.json' );
            $jsons  = json_decode($json, true);
            foreach( $jsons as $p => $plugin )
            {
                if( $p === 'default' )
                {
                    foreach ( $plugin['src'] as $plug )
                    {
                        if( !in_array( 'theme/js/'.$plug, self::$_jsPluginsFiles ) )
                        {
                            array_push( self::$_jsPluginsFiles, 'theme/js/'.$plug );
                        }
                    }
                }
            }
        }
    }
    
    private static function setJSFiles( $page, $action )
    {
        if( file_exists( SITE_PATH . '/public/theme/json/settings.json' ) )
        {  
            $json   = file_get_contents( SITE_PATH . '/public/theme/json/settings.json' );
            $jsons  = json_decode($json, true);
            foreach( $jsons as $p => $plugin )
            {
                if( $p === 'modules' )
                {
                    foreach ( $plugin as $m => $module )
                    {
                        if( $m === $page )
                        {
                            foreach( $module as $plugs )
                            {
                                foreach( $plugs as $plug )
                                {
                                    if( !in_array( 'theme/js/'.$plug, self::$_jsPluginsFiles ) )
                                    {
                                        array_push( self::$_jsPluginsFiles, 'theme/js/'.$plug );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
     
        if( file_exists( SITE_PATH . '/public/views/' .$page. '/js/' . $page . '.js' ) )
        {  
            $js   = 'views/' .$page. '/js/' . $page . '.js';
            if( ! in_array( $js, self::$_jsFiles ) )
            {
                array_push( self::$_jsFiles, $js );
            }
        }
        
    }
    
    private static function getJSFiles()
    {
        foreach( self::$_jsPluginsFiles  as $jsFile )
        {
            echo '<script src="' . SITE_URL . '/public/' . $jsFile . '"></script>
            ';
        }
        foreach( self::$_jsFiles  as $jsFile )
        {
            echo '<script src="' . SITE_URL . '/public/' . $jsFile . '"></script>
            ';
        }
    }

}

	