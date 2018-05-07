<?php
namespace applications\offices;

use includes\components\CommonController;

use includes\Bootstrap;
use stdClass;

class Controller extends CommonController{
        
    private function _setfonctionForm()
    {
        $this->_setModels( [ 'offices/ModelOffices', 'offices/ModelFonctions' ] );
        
        $modelOffices   = $this->_models[ 'ModelOffices' ];
        $modelFonctions = $this->_models[ 'ModelFonctions' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->tabs     = $this->_interface->getTabs( 'fonction' );

        $this->_datas->form     = $modelFonctions->fonctionBuild( $id );

        $this->_datas->offices  = $modelFonctions->fonction_corporateBuild( $id );

        $this->_datas->groups   = $modelFonctions->fonction_groupBuild( $id );

        $this->_datas->response = $this->_interface->getFonctionFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'offices/fonction-form';
    }
    
    
    private function _setofficeForm()
    {
        $this->_setModels( [ 'offices/ModelOffices' ] );
        
        $modelOffices   = $this->_models[ 'ModelOffices' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->tabs     = $this->_interface->getTabs( 'offices' );

        $this->_datas->form     = $modelOffices->officesBuild( $id );

        $this->_datas->menus    = $modelOffices->adminmenu_officeBuild( $id );

        $this->_datas->response = $this->_interface->getOfficesFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'offices/offices-form';
    }
    
    
    
    protected function _setDatasView()
    {
        $this->_setModels( [ 'offices/ModelOffices', 'offices/ModelFonctions' ] );
        
        $modelOffices    = $this->_models[ 'ModelOffices' ];
        $modelFonctions  = $this->_models[ 'ModelFonctions' ];
        
        switch( $this->_action )
        {
            case 'fonctions':
                
                $page = 1;
                
                $nResult = 50;
                
                if( !empty( $this->_router ) )
                {
                    $pages = explode( '/', $this->_router );
                    
                    $page = ( count( $pages ) === 2 && $pages[ 0 ] === 'page' && is_numeric( $pages[ 1 ] ) ) ? $pages[ 1 ] : 1;
                }
                
                $fonctions = $modelFonctions->fonctions();
                
                //var_dump( ['page' => $page, 'nbperpage' => $nResult, 'nbresults' => count( $fonctions )] );
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'fonctions' );
                
                $this->_datas->datas        = $modelFonctions->fullFonctions( [], [], [ 'joins' => false, 'extend' => true, 'limit' => [ 'num' => ( ( $page - 1 ) * $nResult ), 'nb' => $nResult ] ] );
                
                $this->_datas->pagination   = ['page' => $page, 'nbperpage' => $nResult, 'nbresults' => count( $fonctions )];
                
                $this->_datas->response     = $this->_interface->getFonctionUpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $this->_interface->getFonctionTableHead();
                
                $this->_view = 'offices/fonction-list';
                
            break;    
        
        
            case 'fonctionform':
                
                $this->_setfonctionForm();
                
            break;           
        
            
            case 'fonctionupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelFonctions->fonctionUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/offices/fonction/success' . $action . '/' . $data->IDFonction );
                    
                    exit;
                }
                else 
                {
                    $this->_setfonctionForm();
                }
            break;
            

            case 'fonctionactiveAjax':
                
                $datas = new stdClass;
                if( $return = $modelFonctions->fonctionActiveUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'La rubrique <strong><a href="#'.$this->_request->getVar( 'id' ).'">' . ( $this->_encodeCharSet( $return['name'] ) ) . '</a></strong> a été ' . ( ( $return['active'] === 1 ) ? 'activée.' : 'désactivée.') ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
            
            
            case 'fonctiondeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelFonctions->fonctionDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une fonction vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
                    
                       
            case 'menu':
                
                $this->_datas = new stdClass;
                
                $this->_datas->offices  = $modelOffices->offices(['officeactif'=>'1']);
                                
                $this->_datas->current  = $modelOffices->offices([ 'officeid'=>$_SESSION['adminOffice' ] ])[0];
                
                $this->_datas->currentUrl = Bootstrap::$currentUrl;
                
                $this->_view = 'offices/offices-menu';
                
            break;
        
                       
            case 'printheader':
                
                $this->_datas = new stdClass;
                
                $this->_datas->current  = $modelOffices->offices([ 'officeid'=>$_SESSION['adminOffice' ] ])[0];
                
                $this->_view = 'offices/print-header';
                
            break;
                
            case 'officesform':
                
                $this->_setofficeForm();
                
            break;            
            
            
            case 'officesupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelOffices->officesUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/offices/offices/success' . $action . '/' . $data->IdMenu );
                    
                    exit;
                }
                else 
                {
                    $this->_setofficeForm();
                }
            break;
            
            
            case 'officesdeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelOffices->officesDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Un bureau vient d\'être supprimé.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;     
            
            

            case 'officesactiveAjax':
                
                $datas = new stdClass;
                if( $return = $modelOffices->officesActiveUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Le bureau <strong>' . ( $this->_encodeCharSet( $return['name'] ) ) . '</strong> a été ' . ( ( $return['active'] === 1 ) ? 'activée.' : 'désactivée.') ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
            
            

            case 'officesinterventionAjax':
                
                $datas = new stdClass;
                if( $return = $modelOffices->officesInterventionUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Le bureau <strong>' . ( $this->_encodeCharSet( $return['name'] . '</strong> a été ' ) ) . ( ( $return['active'] === 1 ) ? 'activée.' : 'désactivée.') ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
                    
                
            default :
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'offices' );
                
                $this->_datas->datas        = $modelOffices->offices( [], true );
                
                $this->_datas->response     = $this->_interface->getOfficesUpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $this->_interface->getOfficesTableHead();
                
                $this->_view = 'offices/offices-list';
                
            break;
            
        } 
    }
}