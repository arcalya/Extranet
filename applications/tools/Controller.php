<?php
namespace applications\tools;

use includes\components\CommonController;
use stdClass;

class Controller extends CommonController{

    protected function _setDatasView()
    {
        $this->_setModels( [ 'tools/ModelTools' ] );

        $modelTools= $this->_models[ 'ModelTools' ];
        
        switch( $this->_action )
        {        
            case 'documentation' :
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs = $this->_interface->getTabs( $this->_router );
                
                $this->_datas->doc  = $this->_interface->checkDoc( $this->_router );
                
                $this->_view = 'tools/documentation';
                
            break;
        
        
            case 'audit' :
                
                $this->_datas = new stdClass;
                
                $this->_datas->datas        = $modelTools->auditSystem();
                
                $this->_datas->tableHead    = $this->_interface->getAuditTableHead();
                
                $this->_view = 'tools/audit';
                
            break;
            
        
            default :
                
                $id = ( !empty( $this->_router ) ) ? $this->_router : null;
                
                $this->_datas = new stdClass;
                
                $this->_datas->form         = $modelTools->createAppBuild();
                
                $this->_datas->response     = $modelTools->getUpdatedDatas( $this->_router );

                $this->_view = 'tools/create-app';
                
            break;
        }
    }
    
    
}
