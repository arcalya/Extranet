<?php
namespace applications\contacts;

use includes\components\CommonController;

use stdClass;

class Controller extends CommonController{

    
    private function _setcontactForm()
    {   
        $this->_setModels( ['contacts/ModelContacts', 'system/ModelSystem'] );

        $modelContacts          = $this->_models[ 'ModelContacts' ];
        $modelSystem            = $this->_models[ 'ModelSystem' ]; 
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;
        
        $this->_datas                       = new stdClass;
        
        $this->_datas->form                 = $modelContacts->contactsBuild( $id );
        
        $this->_datas->countries            = $modelSystem->getCountries();  
        
        $this->_datas->cantons              = $modelSystem->getCantons();  
        
        $this->_datas->contactStructures    = $this->_interface->getStructures();
        
        $this->_datas->response             = $modelContacts->getContacts();
        
        $this->_view = 'contacts/contact-form';
        
    }
    
    private function _setStructureForm($action = "update", $idTypeStructure = null) //formulaire d'ajout de structure : base "contactstructures" et relations "contactstructure_type"
    {
           
        $this->_setModels(['contacts/ModelContactStructures', 'system/ModelSystem']);
        
        $modelContactStructures     = $this->_models['ModelContactStructures'];
        $modelSystem                = $this->_models['ModelSystem'];
        
        $idStructure = ( (!empty($this->_router)) && ($action === "update") ) ? $this->_router : null;
        
        $this->_datas                       = new stdClass;
        
        $this->_datas->form                 = $modelContactStructures->contactStructuresBuild( $idStructure );
        
        
        $this->_datas->formTypes            = ($action === "update") ? $modelContactStructures->contactStructureTypeBuild( $idStructure ) : $modelContactStructures->contactTypesStructuresBuild($idTypeStructure);
        
        $this->_datas->countries            = $modelSystem->getCountries();  
        
        $this->_datas->cantons              = $modelSystem->getCantons();  
        
        $this->_datas->contactStructures    = $this->_interface->getTypeStructureCategories();
        
        $this->_datas->response             = $modelContactStructures->getContactstructures();
        
        $this->_view = 'contacts/structure-form';
        
        
        
    }
    
     private function _setTypeStructureForm() //formulaire d'ajout de type de structures: base "contacttypestructure"
    {
           
        $this->_setModels(['contacts/ModelContactStructures', 'system/ModelSystem']);
        
        $modelContactStructures     = $this->_models['ModelContactStructures'];
        $modelSystem                = $this->_models['ModelSystem'];
        
        $id = (!empty($this->_router)) ? $this->_router : null;
        
        $this->_datas                       = new stdClass;
        
        $this->_datas->form                 = $modelContactStructures->contactTypesStructuresBuild( $id );
                
        $this->_datas->contactStructures    = $this->_interface->getTypeStructureCategories();
        
        $this->_datas->response             = $modelContactStructures->getTypeStructureCategories();
        
        $this->_view = 'contacts/type-structure-form';
        
        
        
    }
    
    
    protected function _setDatasView()
    {   
        $this->_setModels( ['contacts/ModelContacts' ] );
        $this->_setModels( ['contacts/ModelContactStructures' ] );

        $modelContacts               = $this->_models[ 'ModelContacts' ];
        $modelContactStructures      = $this->_models[ 'ModelContactStructures' ];
        
        
        switch( $this->_action )
        {
        
            case 'contactform':
                
                $this->_setcontactForm();
                
               
            break;
        
            
            case 'contactupdate': /* Mise à jour d'un contact */   
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelContacts->contactUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/contacts/success' . $action . '/' . $data->IdContact );
                    
                    exit;
                }
                else 
                {
                    $this->_setcontactForm();
                }
                
            break;     
            
            
            case 'contactdeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelContacts->contactDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Un contact vient d\'être supprimé.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;
            
            
            case 'structures':
                
                $this->_datas = new stdClass();
                
                $this->_datas->tabs = $this->_interface->getTabs( 'structures' );
                
                $this->_datas->structures  = $modelContactStructures->contactstructures(); //Structures
                
                $this->_datas->typesstructures = $modelContactStructures->contactstructure_type(); //Relation structure - type
                
                $this->_datas->typesstructurescategories = $modelContactStructures->contacttypestructure(); //Relation structure - type
                                
                $this->_view = 'contacts/structures';
                
            break;
        
        
            case 'structureform':
                
                   $this->_setStructureForm();
                
            break;
        
        
            case 'structureforminsert':
                
                    $this->_setStructureForm('insert', $this->_router);
                
            break;
        
        
            case 'structureupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelContactStructures->structureUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/contacts/structures/success' . $action . '/' . $data->IdStructure );
                    
                    exit;
                }
                else 
                {
                    $this->_setStructureForm();
                }
                
            break;
            
            
            case 'structuredeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelContactStructures->structureDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une structure vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;
            
        
            case 'typestructureform':
                
                $this->_setTypeStructureForm();    
                
            break;
        
        
            case 'typestructureupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelContactStructures->typeStructureCategoryUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/contacts/structures/typesuccess' . $action . '/' . $data->IdTypeStructure );
                    
                    exit;
                }
                else 
                {
                    $this->_setStructureForm();
                }
                
            break;
            
            case 'typestructuredeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelContactStructures->typeStructureCategoryDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Un type de structure vient d\'être supprimé.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;
        
        
            default:
                
                $this->_datas = new stdClass();
                
                $this->_datas->tabs = $this->_interface->getTabs( 'contacts' );
                
                $this->_datas->contacts = $modelContacts->getContactsInfos();
                
                $this->_datas->response     = $this->_interface->getContactUpdatedDatas( $this->_action.'/'.$this->_router );
                
                $this->_datas->dropdownlist = $this->_interface->getStructureList();
                
                $this->_view = 'contacts/contacts';
                
            break;
            
            
        }
    }
    
}
    