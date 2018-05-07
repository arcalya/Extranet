<?php
namespace applications\users;

use includes\components\CommonController;
use includes\Request;
use stdClass;

class Controller extends CommonController{
    
    private $_formDisplay = [];

    
    private function _setbeneficiaireForm( $formDisplay = 'global', $action = 'update' )
    {   
        $this->_setModels( [ 'users/ModelUsers', 'users/ModelManagers', 'contacts/ModelContacts', 'contacts/ModelContactStructures', 'system/ModelSystem' ] );

        $modelUsers             = $this->_models[ 'ModelUsers' ];
        $modelManagers          = $this->_models[ 'ModelManagers' ];
        $modelContacts          = $this->_models[ 'ModelContacts' ];
        $modelContactStructures = $this->_models[ 'ModelContactStructures' ];
        $modelSystem            = $this->_models[ 'ModelSystem' ];    
        
        $this->_datas = new stdClass;
        
        if( $formDisplay === 'global' )
        {
            $id = ( !empty( $this->_router ) ) ? $this->_router : null;
            $this->_formDisplay['user']   = true;
            $this->_formDisplay['detail'] = ( isset( $id ) ) ? false : true;
            $this->_formDisplay['formaction'] = SITE_URL.'/users/beneficiaireupdate/'.( ( isset( $id ) ) ? $id : '' );
            
            $this->_datas->form          = $modelUsers->beneficiaireBuild( $id );
            $this->_datas->form->details = ( !isset( $id ) ) ? $modelUsers->beneficiaire_detailsBuildBeneficiaire( $id ) : null;
        }
        else if( $formDisplay === 'detail' )
        {
            if( $action === 'insert' )
            {
                $idDetail           = null;
                $id                 = ( !empty( $this->_router ) ) ? $this->_router : null;
                $beneficiaireDetail = $modelUsers->beneficiaire_detailsBuild( null );
            }
            else
            {
                $idDetail = ( !empty( $this->_router ) ) ? $this->_router : null;
                $beneficiaireDetail          = $modelUsers->beneficiaire_detailsBuild( $idDetail );
                $id                          = $beneficiaireDetail[ 0 ]->IDBeneficiaire;
            }
            $this->_formDisplay['user']   = false;
            $this->_formDisplay['detail'] = true;
            $this->_formDisplay['formaction'] = SITE_URL.'/users/beneficiairedetailupdate/'.( ( isset( $idDetail ) ) ? $idDetail : '' );
            
            $this->_datas->form          = $modelUsers->beneficiaireBuild( $id );
            $this->_datas->form->details = $beneficiaireDetail;
            $this->_datas->form->details[0]->IDBeneficiaire = $id;
        }

        $this->_datas->formDisplay  = $this->_formDisplay;
        $this->_datas->tabs         = $this->_interface->getTabs( 'beneficiaire' );
        
        $this->_datas->structures   = $modelContactStructures->getContactstructuresByCantons([], ['contactstructure_type.IdTypeStructure'=>[1,5,10]]);
        $this->_datas->caisses      = $modelContactStructures->getContactstructures(['contactstructure_type.IdTypeStructure'=>3]);
        $this->_datas->conseillers  = $modelContacts->getContacts();
        $this->_datas->countries    = $modelSystem->getCountries();
        $this->_datas->employes     = $modelManagers->get_employeByOffices( [], $id );
        $this->_datas->groups       = $this->_interface->getGroups(['groupparticipant'=>1, 'fonction_corporate.IdCorporate'=>$_SESSION['adminOffice'] ]);
        $this->_datas->offices      = $modelUsers->getOffices( [], $id );
        $this->_datas->fonctions    = $this->_interface->getFonctionsByOffices( [], $id );
        $this->_datas->statuts      = $this->_interface->getStatuts( ['ActiveStatut'=>1], $id );
        $this->_datas->presences    = $this->_interface->getPresences();
        
        $this->_datas->response     = $this->_interface->getBeneficiaireFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'users/beneficiaire-form';
    }

    
    private function _setstatutsForm()
    {   
        $this->_setModels( [ 'users/ModelStatus' ] );

        $modelStatus    = $this->_models[ 'ModelStatus' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->form     = $modelStatus->statutsBuild( $id );

        $this->_datas->response = $this->_interface->getStatutsFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'users/statuts-form';
    }
    
    
    protected function _setDatasView()
    {
        $this->_setModels( [ 'users/ModelUsers', 'users/ModelDairy', 'users/ModelStatus' ] );

        $modelUsers     = $this->_models[ 'ModelUsers' ];
        $modelDairy     = $this->_models[ 'ModelDairy' ];
        $modelStatus    = $this->_models[ 'ModelStatus' ];
        
        
        switch( $this->_action )
        {        
            case 'beneficiaireform':
                
                $this->_setbeneficiaireForm();
                
            break;
        
            case 'beneficiaire-form-detail':
                
                $this->_setbeneficiaireForm( 'detail' );
                
            break;  
        
            case 'beneficiaire-form-detail-new':
                
                $this->_setbeneficiaireForm( 'detail', 'insert' );
                
            break;            

            
            case 'beneficiaireupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelUsers->beneficiaireUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/users/beneficiaire/success' . $action . '/' . $data->IDBeneficiaire );
                    
                    exit;
                }
                else 
                {
                    $this->_setbeneficiaireForm();
                }
            break;
            
            
            case 'beneficiairedetailupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelUsers->beneficiaireDetailUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/users/beneficiaire/success' . $action . '/' . $data->IDBeneficiaire );
                    
                    exit;
                }
                else 
                {
                    $this->_setbeneficiaireForm( 'detail' );
                }
            break;
            
            
            case 'dairyaddAjax':
                
                if( $data = $modelDairy->dairyUpdate() )
                {      
                    $user = $modelUsers->beneficiaire([ 'beneficiaire.IDBeneficiaire' => $data->IDClient ]);
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'callback' => [ 'function' => 'refreshInfos', 'initSelectors' => 'span', 'selector' => 'a.dairy-'.$data->IDClient.' span', 'content' => '0' ], 'status' => 'OK', 'alertsuccess' => ['alert-success.alert-display-ajax'=>'Une entrée vient d\'être ajoutée à <strong>'.$user[0]->PrenomBeneficiaire.' '.$user[0]->NomBeneficiaire.'</strong>.'] ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'errors'=> [ 'alert-danger.alert-display-ajax' => 'Les champs ne sont pas correctement remplis.' ] ]); 
                }
                
                exit;
                
            break;   
            
            
            case 'statutsform':
                
                $this->_setstatutsForm();
                
            break;
            

            case 'statutsactiveAjax':
                
                $datas = new stdClass;
                if( $return = $modelStatus->statutsActiveUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'La rubrique <strong><a href="#'.$this->_request->getVar( 'id' ).'">' . $return['name'] . '</a></strong> a été ' . ( ( $return['active'] === 1 ) ? 'activée.' : 'désactivée.') ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
            
            case 'statutsupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelStatus->statutsUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/status/statuts/success' . $action . '/' . $data->IdStatut );
                    
                    exit;
                }
                else 
                {
                    $this->_setstatutsForm();
                }
            break;
            
            
            case 'statutsdeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelStatus->statutsDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une rubrique vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
                    
            case 'statuts':
                
                $this->_datas = new stdClass;
                
                $this->_datas->datas        = $modelStatus->statuts();
                
                $this->_datas->response     = $this->_interface->getStatutsUpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $this->_interface->getStatutsTableHead();
                
                $this->_view = 'users/statuts-list';
                
            break;
            
        
            
            case 'beneficiairedeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelUsers->beneficiaireDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Un participant vient d\'être supprimé.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;  
            
            
            case 'beneficiairedetaildeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelUsers->beneficiaire_detailsDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une mesure vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
                    
             
            case 'passwordchangeproccess':
                                
                $this->_datas = new stdClass;
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                
                if( $data = $modelUsers->beneficiairePassUpdate( $id ) )
                {
                    header( 'location:' . SITE_URL . '/users/passwordchange/success' . $action . '/' . $data->IDBeneficiaire );
                    
                    exit;
                }
                else 
                {
                    $this->_setstatutsForm();
                }
                
            break;    
                    
             
            case 'passwordchange':
                                
                $this->_datas = new stdClass;
                
                $this->_datas->response     = $this->_interface->getBeneficiaireUpdatedDatas( $this->_router );
                
                $this->_view = 'users/beneficiaire-passform';
                
            break;
        
             
            case 'profile':
                                
                $this->_datas = new stdClass;
                
                $this->_datas->dairy        = $modelDairy->dairyBuild();
                
                $this->_datas->datas        = $modelUsers->beneficiaireDetails( [ 'beneficiaire.IDBeneficiaire' => $this->_router ], 'all', 'participants', ['infos'=>true, 'details'=>true, 'dairy'=>true, 'workshops'=>true, 'material'=>true] );
                
                $this->_datas->response     = $this->_interface->getBeneficiaireUpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $this->_interface->getBeneficiaireTableHead();
                
                $this->_view = 'users/beneficiaire-profile';
                
            break;
        
            case 'search' :
                
                $period = 'search'; // By Default choose current (actual) participant
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'all' );
                
                $this->_datas->displayinfos = $this->_interface->getDisplayinfos();
                
                $req = Request::getInstance();
                
                $this->_datas->searchfield  = ( $req->getVar( 'search' ) !== null ) ? $req->getVar( 'search' ) : '';
                
                $this->_datas->dairy        = $modelDairy->dairyBuild();
                
                $this->_datas->datas        = $modelUsers->beneficiaireDetails( [ 'beneficiaire_details.office' => $_SESSION['adminOffice'] ], $period, 'participants', $this->_datas->displayinfos );
                
                $this->_datas->response     = $this->_interface->getBeneficiaireUpdatedDatas( $this->_router );
                
                $this->_view = 'users/beneficiaire-list';
                
            break;
             
            default :
                
                $period = $this->_interface->checkTabPeriod( $this->_router ); // By Default choose current (actual) participant
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( $period );
                
                $this->_datas->displayinfos = $this->_interface->getDisplayinfos( $period );
                
                $this->_datas->searchfield  = '';
                
                $this->_datas->dairy        = $modelDairy->dairyBuild();
                
                $this->_datas->datas        = $modelUsers->beneficiaireDetails( [ 'beneficiaire_details.office' => $_SESSION['adminOffice'] ], $period, 'participants', $this->_datas->displayinfos );
                
                $this->_datas->response     = $this->_interface->getBeneficiaireUpdatedDatas( $this->_action.'/'.$this->_router );
                
                $this->_datas->tableHead    = $this->_interface->getBeneficiaireTableHead();
                
                $this->_view = 'users/beneficiaire-list';
                
            break;
            
        } 
    }
}