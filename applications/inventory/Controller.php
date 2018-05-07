<?php
namespace applications\inventory;

use includes\components\CommonController;

use includes\Request;
use stdClass;

class Controller extends CommonController{
    
    private function _setinventoryForm()
    {
        /*
        $this->_setModels( ['inventory/ModelInventory' ] );
        
        $modelInventory     = $this->_models[ 'ModelInventory' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->tabs     = $this->_interface->getTabs( 'workshops' );

        $this->_datas->form     = $modelInventory->workshopsBuild( $id );

        $this->_datas->categories= $modelInventory->inventory_categoriesBuild( $id );

        $this->_datas->response = $modelInventory->getinventoryFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'inventory/-form';
        */ 
    }
    
    
    protected function _setDatasView()
    {
        $this->_setModels( ['inventory/ModelInventory' ] );
        
        $modelInventory     = $this->_models[ 'ModelInventory' ];
        
        
        switch( $this->_action )
        {
            case 'inventoryform':
                /*
                $this->_setworkshopForm();
                */ 
            break;           
        
               
            case 'menu':
                
                $this->_datas = new stdClass;
                
                $this->_datas->inventory    = $modelInventory->inventoryMenu( [ 'librairie_emprunts.IdBeneficiaireEmprunt' => $_SESSION['adminId'], 'StatutEmprunt' => '1' ] );
                
                $this->_view = 'inventory/inventory-menu';
                
            break;
                
            
            default :
                
                $this->_datas = new stdClass;
                
                $this->_datas->nom = 'Toto';
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'inventory' );
                
                $this->_datas->categories   = $modelInventory->categories( ['IdCorporateCategorie' => $_SESSION['adminOffice'] ] , true );
                
                $this->_datas->types        = $modelInventory->types();
                
                //$this->_datas->inv          = $modelInventory->inventory();      
                
                //$this->_datas->emp          = $modelInventory->emprunts();                              
                
                /*
                $this->_datas->tabs         = $this->_interface->getTabs( 'workshops' );
                
                $this->_datas->datas        = $modelInventory->inventory();
                
                $this->_datas->response     = $modelInventory->getinventoryUpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $modelInventory->getinventoryTableHead();
                */
                $this->_view = 'inventory/inventory-list';               
            break;
            
        } 
    }
}