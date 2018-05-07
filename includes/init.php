<?php
use includes\Db;
use includes\Audit;
use includes\Lang;
use includes\Login;
use includes\Template;
use includes\Adm;
use includes\Bootstrap;


/* Load Config file
 */
$config = parse_ini_file( SITE_PATH . '/includes/config.ini' ); 

define( 'SITE_TITLE',     $config[ 'title' ] );
define( 'SITE_EMAIL',     $config[ 'email' ] );
define( 'SITE_LANG',      $config[ 'lang' ] );
define( 'SITE_CHARSET',   $config[ 'charset' ] );
define( 'SITE_VERSION',   $config[ 'version' ] );
define( 'SITE_DEBUG',     $config[ 'debug' ] );
define( 'SITE_COOKIE',    $config[ 'cookieName' ] );


/* Sets the default timezone as recommended by the strict standards ...
 * Because it might not have been set in the php.ini configuration file.
 * Note that this function is PHP >= 5.1.0,
 * that's why we first test if the function exists ...
 * Then, clears the configuration variable.
 */
date_default_timezone_set( $config['defaultTimezone'] );



include SITE_PATH . '/includes/Bootstrap.class.php';


/* Connects to DB
 */
include SITE_PATH . '/includes/Db.class.php';
Db::connect( $config );


/* Starts(or continues) a session using the session name found in cms/config.php
 * and the sessions save path found in cms/paths.const.php.
 * Then, clears the variable containing the session name.
 */
session_save_path( SITE_PATH . '/caches/sessions' );
session_name( $config['sessionName'] );
session_start();

/*  
 * Unset config variable. Useless.
 */
unset( $config );



/* Includes the Request singleton class.
 * Note that if you want to use it or implement it into a class,
 * you will need to use Request::getInstance()
 * which will return a reference to the only instance of the Request class.
 */
require_once( SITE_PATH . '/includes/Request.class.php' );


/* Includes usefull classes wich can be used by developers for common functions
 * SEE the documentation for explicit explications
 */
require_once( SITE_PATH . '/includes/tools/File.class.php' );
require_once( SITE_PATH . '/includes/tools/Date.class.php' );
require_once( SITE_PATH . '/includes/tools/String.class.php' );
require_once( SITE_PATH . '/includes/tools/Orm.class.php' );
require_once( SITE_PATH . '/includes/tools/Upload.class.php' );
require_once( SITE_PATH . '/includes/tools/Mail.class.php' );
require_once( SITE_PATH . '/includes/tools/Position.class.php' );

/* Includes usefull classes extensions wich are used by some tools for common functions
 * SEE the documentation for explicit explications
 */
require_once( SITE_PATH . '/includes/components/Common.class.php' );
require_once( SITE_PATH . '/includes/components/CommonController.class.php' );
require_once( SITE_PATH . '/includes/components/CommonModel.class.php' );
require_once( SITE_PATH . '/includes/components/Module.class.php' );


require_once( SITE_PATH . '/includes/Lang.class.php' );
Lang::initSettings( SITE_LANG, SITE_CHARSET );



/* Includes the system Audit singleton class.
 * Note that if you want to use it or implement it into a class,
 * you will need to use Audit::setAudit()
 * wich will archive an audit info in the system through the DB
 */
require_once( SITE_PATH . '/includes/Audit.class.php' );
Audit::initSettings();


/* Includes the Login singleton class.
 * Note that if you want to use it or implement it into a class,
 * you will need to use Login::getInstance()
 * which will return a reference to the only instance of the Login class.
 */
require_once( SITE_PATH . '/includes/Login.class.php' );
Login::initSettings();


/* Templates
 * Used in the Bootstrap process  
 */
include SITE_PATH . '/includes/Template.class.php';


/* Define page Info.
 */
Bootstrap::url();

Audit::initUrlSettings();

Login::loginByUrl( Bootstrap::$page, Bootstrap::$action, Bootstrap::$router );


/* Includes the Adm singleton class.
 * which will administrator tools for authorisation and define Main menu
 */
include SITE_PATH . '/includes/Adm.class.php';   

/* Check if login is to reset */
if( !Login::getDatas()->isLoguedIn )
{
    Login::checkCookieConnected( Bootstrap::$page, Bootstrap::$action );
}
else /* Rights */
{ 
    
    Adm::initSettings( Bootstrap::$page, Bootstrap::$action );

    function autorise_read($mod = null, $act = null){ return Adm::getAuthRights('r', $mod, $act); }
    function autorise_add($mod = null, $act = null){ return Adm::getAuthRights('w', $mod, $act); }
    function autorise_mod($mod = null, $act = null){ return Adm::getAuthRights('m', $mod, $act); }
    function autorise_del($mod = null, $act = null){ return Adm::getAuthRights('d', $mod, $act); }
    function autorise_valid($mod = null, $act = null){ return Adm::getAuthRights('v', $mod, $act); }
}

/* Page Render */
$urlInfos = array( 'page'=>Bootstrap::$page, 'action'=>Bootstrap::$action, 'router'=>Bootstrap::$router );
Template::page( $urlInfos );
unset( $urlInfos );

if( SITE_DEBUG )
{
    Audit::displayAudit();
}