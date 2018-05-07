<?php
namespace includes\components;

use includes\components\Common;
use includes\Request;

/**
 * Contains common Controller properties and methods
 *
 * @author Olivier Dommange
 * @license GPL
 * @
 */
class CommonController extends Common {

    protected $_action;
    protected $_router;
    protected $_datas;
    protected $_view;
    protected $_request;
    protected $_interface;


    public function __construct( $page, $action, $router )
    {           
        include_once SITE_PATH . '/applications/' . $page . '/InterfaceModule.php';
        
        $interface = '\applications\\' . $page . '\InterfaceModule';

        $this->_action      = $action; 
        $this->_router      = $router;  
        $this->_request     = Request::getInstance();
        $this->_interface   = new $interface;
        
        $this->_setDatasView();
    }
    
    public function datas()
    {
        return $this->_datas;
    }

    public function view()
    {
        return $this->_view;
    }
}
