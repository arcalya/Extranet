<?php
namespace applications\menus;

use includes\components\CommonController;
use includes\Request;

use stdClass;

class Controller extends CommonController{

    private function _setmenuForm()
    {     
        $this->_setModels( ['menus/ModelMenus','menus/ModelModules' ] );
        
        $modelMenus     = $this->_models[ 'ModelMenus' ];
        $modelModules   = $this->_models[ 'ModelModules' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->form     = $modelMenus->adminmenuBuild( $id );

        $this->_datas->headers  = $modelMenus->getHeadings();

        $this->_datas->modules  = $modelModules->setModuleOptions();

        $this->_view = 'menus/menu-form';
    }

    private function _setmodulesForm()
    {   
        $this->_setModels( ['menus/ModelMenus','menus/ModelModules' ] );
        
        $modelMenus     = $this->_models[ 'ModelMenus' ];
        $modelModules   = $this->_models[ 'ModelModules' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->tabs     = $this->_interface->getTabs( 'modules' );

        $this->_datas->form     = $modelModules->modulesBuild( $id );

        $this->_datas->response = $this->_interface->getModulesFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'menus/modules-form';
    }
    
    
    private function _setgroupForm()
    {      
        $this->_setModels( ['menus/ModelGroups' ] );
        
        $modelGroups    = $this->_models[ 'ModelGroups' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->form = $modelGroups->groupBuild( $id );
        
        $this->_datas->menus = $this->_interface->getMenus();

        $this->_view = 'menus/group-form';
    }
       
    
    protected function _setDatasView()
    {
        $this->_setModels( ['menus/ModelMenus','menus/ModelModules','menus/ModelGroups' ] );
        
        $modelMenus     = $this->_models[ 'ModelMenus' ];
        $modelModules   = $this->_models[ 'ModelModules' ];
        $modelGroups    = $this->_models[ 'ModelGroups' ];
        
        switch( $this->_action )
        {
        
            case 'config':
                
                $this->_view = 'menus/configmenu';
                
            break;
        
        
            case 'menuform':
                
                $this->_setmenuForm();
                
            break;
            
        
            case 'menuactiveAjax':
                
                $datas = new stdClass;
                
                if( $return = $modelMenus->adminmenuActiveUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'La rubrique <strong>' . $return['name'] . '</strong> a été ' . ( ( $return['active'] === 1 ) ? 'activée.' : 'désactivée.') ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
            
        
            case 'menuorderAjax':
                
                $datas = new stdClass;
                
                if( $modelMenus->adminmenuPosition( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => '' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                exit;
                
            break;
            
            
            case 'menuupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelMenus->adminmenuUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/menus/menulist/success' . $action . '/' . $data->IdMenu );
                    
                    exit;
                }
                else 
                {
                    $this->_setmenuForm();
                }
            break;
            
            
            case 'menudeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelMenus->adminmenuDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une rubrique vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;
        
            
            
            // MODULES
               
            case 'modules':
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'modules' );
                
                $this->_datas->datas        = $modelModules->modules();
                
                $this->_datas->response     = $this->_interface->getModulesUpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $this->_interface->getModulesTableHead();
                
                $this->_view = 'menus/modules-list';
                
            break;
            
            case 'modulesform':
                
                $this->_setmodulesForm();
                
            break;
            
            case 'modulesupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelModules->modulesUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/menus/modules/success' . $action . '/' . $data->IdModule );
                    
                    exit;
                }
                else 
                {
                    $this->_setmodulesForm();
                }
            break;
            
            
            case 'modulesdeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelModules->modulesDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Un module vient d\'être supprimé.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
                    
            
            
            
            // GROUPS
            
            
            case 'groups':
                
                $this->_datas = new stdClass;
                
                $this->_datas->response     = $this->_interface->getUpdatedGroupDatas( $this->_router );
                
                $this->_datas->tableDatas   = $modelGroups->groupsAndRights();
                
                $this->_datas->tableHead    = $this->_interface->getGroupHead();
                
                $this->_view = 'menus/group-list';
                
            break;
            
        
            case 'groupform':
                
                $this->_setgroupForm();
                
            break;
        
        
            
            case 'groupupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelGroups->groupUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/menus/groups/success' . $action . '/' . $data->groupid );
                    
                    exit;
                }
                else 
                {
                    $this->_setgroupForm();
                }
            break;
            
            
            case 'groupdeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelGroups->groupDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'un groupe vient d\'être supprimé.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;
            
            
        
            case 'groupactiverightAjax':
                
                $datas = new stdClass;
                
                if( $return = $modelGroups->groupActiveUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    $msg = '';
                    
                    $active = ( $return[ 'active' ] ) ? ' a dorénavant ' : ' n\'a dorénavant plus ';
                    if( $return[ 'action' ] === 'r' )
                    {
                        $msg = 'Le groupe '.$return['group']->groupname . '<strong>' . $active . 'le droit de lecture</strong> pour la rubrique &laquo;' . $return['menu']->NameMenu .'&raquo;.';
                    }
                    else if( $return[ 'action' ] === 'w' )
                    {
                        $msg = 'Le groupe '.$return['group']->groupname . '<strong>' . $active . 'le droit d\'écriture</strong> pour la rubrique &laquo;' . $return['menu']->NameMenu .'&raquo;.';
                    }
                    else if( $return[ 'action' ] === 'm' )
                    {
                        $msg = 'Le groupe '.$return['group']->groupname . '<strong>' . $active . 'le droit de modification</strong> pour la rubrique &laquo;' . $return['menu']->NameMenu .'&raquo;.';
                    }
                    else if( $return[ 'action' ] === 'd' )
                    {
                        $msg = 'Le groupe '.$return['group']->groupname . '<strong>' . $active . 'le droit de suppression</strong> pour la rubrique &laquo;' . $return['menu']->NameMenu .'&raquo;.';
                    }
                    else if( $return[ 'action' ] === 'v' )
                    {
                        $msg = 'Le groupe '.$return['group']->groupname . '<strong>' . $active . 'le droit de validation</strong> pour la rubrique &laquo;' . $return['menu']->NameMenu .'&raquo;.';
                    }
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => $msg ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
            
        
            
            // MENU
        
            default:
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( '' );
                
                $this->_datas->response     = $this->_interface->getUpdatedDatas( $this->_router );
                
                $this->_datas->tableDatas   = $modelMenus->getAdminmenu();
                
                $this->_datas->tableHead    = $this->_interface->getTableHead();
                
                $this->_view = 'menus/menu-list';
            break;
        }
    }

}