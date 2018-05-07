<?php
namespace applications\workshops;

use includes\components\CommonController;

use stdClass;

class Controller extends CommonController{

    
    private function _setSubscribeForm( $isHistoric = false )
    {
        $this->_setModels( 'workshops/ModelWorkshops' );
        
        $modelWorkshops = $this->_models[ 'ModelWorkshops' ];
        
        $dateId = ( !empty( $this->_router ) ) ? explode( '/', $this->_router ) : [ null, null ];
        
        $this->_datas               = new stdClass;
        $this->_datas->tabs         = $this->_interface->getTabs( 'worshops' );  
        $this->_datas->formstep     = ( empty( $this->_router ) ) ? 'initialize' : 'update';
        $this->_datas->hoursList    = $this->_interface->getHoursList(); 
        $this->_datas->message      = $modelWorkshops->getMessage(); 
        $this->_datas->isHistoric   = $isHistoric;
        
        $period = ( $isHistoric ) ? '' : 'actual';
        
        $this->_datas->workshops    = $this->_interface->getWorkshops( $period );
        
        if( empty( $this->_router ) ) // Sets default value for date and workshop in case of a new planning wokshop. 
        {
            $modelWorkshops->set_defaultUseWorkshop( date( 'd.m.Y' ) , $this->_datas->workshops[ 0 ][ 'options' ][ 0 ][ 'value' ] );
        }
                 
        $this->_datas->form         = $modelWorkshops->workshopSubscribeBuild([ 'date'=>$dateId[0], 'id'=>$dateId[1] ]);
        $this->_datas->users        = $this->_interface->getUsersSubscribe( $this->_datas->form );
        $this->_datas->response     = $this->_interface->getSubscribeFormUpdatedDatas( $this->_datas->form );
        
        $this->_view = 'workshops/workshop-subscribe';
        
    }
    
    private function _setcoachForm()
    {   
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;
        
        $this->_setModels( 'workshops/ModelWorkshops' );
        $this->_setModels( 'workshops/ModelCoachs' );
        
        $modelWorkshops = $this->_models[ 'ModelWorkshops' ];
        $modelCoachs    = $this->_models[ 'ModelCoachs' ];

        $this->_datas = new stdClass;
        $this->_datas->tabs     = $this->_interface->getTabs( 'coachs' );
        $this->_datas->form     = $modelCoachs->coachBuild( $id );
        $this->_datas->response = $this->_interface->getCoachFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'Workshops/coach-form';
    }
    
    private function _setworkshopForm()
    {
        $this->_setModels( 'workshops/ModelWorkshops' );
        //$this->_setModels( 'workshops/ModelCoachs' );
        
        $modelWorkshops = $this->_models[ 'ModelWorkshops' ];
        //$modelCoachs    = $this->_models[ 'ModelCoachs' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;
        $this->_datas->tabs     = $this->_interface->getTabs( 'workshops' );
        $this->_datas->form     = $modelWorkshops->workshopBuild( $id );
        //$this->_datas->coachs   = $modelCoachs->workshop_coachBuild( $id );
        $this->_datas->response = $this->_interface->getWorkshopFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'workshops/workshop-form';
    }
    
    
    protected function _setDatasView()
    {      
        $this->_setModels([ 'workshops/ModelWorkshops', 'workshops/ModelCoachs', 'workshops/ModelQuestions', 'workshops/ModelStatistics', 'users/ModelUsers' ]);
        
        $modelWorkshops = $this->_models[ 'ModelWorkshops' ];
        $modelCoachs    = $this->_models[ 'ModelCoachs' ];
        $modelQuestions = $this->_models[ 'ModelQuestions' ];
        $modelUser      = $this->_models[ 'ModelUsers' ];
        $modelStatistics = $this->_models[ 'ModelStatistics' ];
        
        
        switch( $this->_action )
        {
               
            case 'menu':
        
                $this->_datas = new stdClass;
                
                //$this->_datas->workshops    = $modelWorkshops->workshopsMenu(['IDBeneficiaire' => $_SESSION['adminId']], ['start' => date('Y-m-d')], ['inscrit'] );
                $this->_datas->workshops    = $modelWorkshops->workshopsMenu(['IDBeneficiaire' => 2377], ['start' => date('Y-m-d')], ['inscrit'] );
                
                $this->_view = 'workshops/workshops-menu';
                
            break;
            
            case 'workshopform':
                
                $this->_setworkshopForm();
               
            break;           
        
            case 'workshopupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $this->_model->workshopUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/workshops/workshops/success' . $action . '/' . $data->IDCoaching );
                    
                    exit;
                }
                else 
                {
                    $this->_setworkshopForm();
                }
            break;
            
            case 'historic':
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'workshops' );
                
                $this->_datas->datas        = $modelWorkshops->workshopsHistoric( [ 'coaching.IDCoaching' => $this->_router, 'coaching.IDCorporate' => $_SESSION['adminOffice'] ] );
                                
                $this->_view                = 'workshops/workshops-historic';
                
            break;
            
            case 'print':
                
                $this->_datas = new stdClass;
            
                $urlInfos = explode( '/', $this->_router );

                $this->_datas->urlBack     = SITE_URL . '/workshops/' . $urlInfos[0] . '/' . $urlInfos[1] . '/' . $urlInfos[2];
                
                $this->_datas->workshop    = $modelWorkshops->workshopSubscribeBuild([ 'date'=>$urlInfos[1], 'id'=>$urlInfos[2] ]); 
                
                $this->_datas->users       = $this->_interface->getUsersSubscribe( $this->_datas->workshop );
                                
                $this->_view  = 'workshops/workshop-print-sheet';
                
            break;
            
            case 'statistics':
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'statistics' );
                                
                $this->_datas->statistics   = $modelStatistics->statistics( $this->_router );
                                
                $this->_datas->yearsstats   = $modelStatistics->getYearsStats();
                
                $this->_datas->dropdownlist = $this->_interface->getYearsList( '/workshops/statistics', $this->_router );
                      
                $this->_view  = 'workshops/workshops-statistics';
                
            break;
            
            case 'calendar':
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'calendar' );
                
                $this->_view  = 'workshops/workshops-calendar';
                
            break;
                        
                        
                
            case 'coachs' :
                
                $period = $this->_interface->checkCoachPeriod( $this->_router ); // By Default choose current (actual)

                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'coachs' );
                                
                $this->_datas->dropdownlist = $this->_interface->getDropdownList( $period, '_listcoach' );
                
                $this->_datas->datas        = $modelCoachs->coachsDatas([ 'formateur.IDCorporate' => $_SESSION['adminOffice'] ]);
                
                $this->_datas->response     = $this->_interface->getCoachUpdatedDatas( $this->_router );
                
                $this->_view = 'workshops/coachs-list';
                
            break;     
        
            case 'coachform':
                
                $this->_setcoachForm();
                
            break;
        
            case 'coachupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelCoachs->coachUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/workshops/coachs/success' . $action . '/' . $data->IDFormateur );
                    
                    exit;
                }
                else 
                {
                    $this->_setcoachForm();
                }
            break;
                        
            case 'coachdeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelCoachs->coachDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Un formateur vient d\'être supprimé.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
                    
            
            
            
            
            case 'subscribe':
                
                $this->_setSubscribeForm();
                                
            break; 
            
            case 'subscribehistoric':
                
                $this->_setSubscribeForm( true );
                                
            break;      
        
            case 'subscribeAjax':
                
                $datas = new stdClass;
                
                if( $return = $modelWorkshops->subscribeActiveUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    $msg = '';
                    
                    if( $return[ 'action' ] === 'demande' )
                    {
                        $msg = $return['workshop']->user . '<strong>' . ' est en demande</strong> pour la formation &laquo;' . $return['workshop']->workshop .'&raquo;.';
                    }
                    else if( $return[ 'action' ] === 'inscrit' )
                    {
                        $msg = $return['workshop']->user . '<strong>' . ' est inscrit</strong> à la formation &laquo;' . $return['workshop']->workshop .'&raquo;.';
                    }
                    else if( $return[ 'action' ] === 'suivi' )
                    {
                        $msg = $return['workshop']->user . '<strong>' . ' a suivi</strong> la formation &laquo;' . $return['workshop']->workshop .'&raquo;.';
                    }
                    else if( $return[ 'action' ] === 'absent' )
                    {
                        $msg = $return['workshop']->user . '<strong>' . ' est absent</strong> pour la formation &laquo;' . $return['workshop']->workshop .'&raquo;.';
                    }
                    else if( $return[ 'action' ] === 'delete' )
                    {
                        $msg = $return['workshop']->user . ' n\'est plus inscrit à la formation &laquo;' . $return['workshop']->workshop .'&raquo;.';
                    }
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => $msg ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
            
            case 'subscribeabsenceAjax' :
                
                if( $modelWorkshops->subscribeAbsenceUpdate() )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK']);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL']);
                }
                
                exit;
                
            break;
            
            
            case 'subscribeconvocationAjax' : // TODO - Sends Email to users who are subscribed to a workshop
                
            break;
        
        
            case 'usersubscribe' :
                
                //$user = $_SESSION['adminId'];
                $user = 2340;
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'usersubscribe' );
                
                $this->_datas->datas        = $modelWorkshops->workshopsUserSubscribe( $user );
                
                $this->_datas->user         = $modelUser->beneficiaire([ 'beneficiaire.IDBeneficiaire'=>$user ])[0];
                
                $this->_datas->response     = $this->_interface->getWorkshopUpdatedDatas( $this->_router );
                
                $this->_view = 'workshops/usersubscribe-list';
                
            break;
        
            
            case 'usersubscribeAjax' :
                
                if( $data = $modelWorkshops->userSubscribe() )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'callback'=>['function'=>'refreshInfos', 'selector'=>'header.workshop_'.$data.' ul li:last-child', 'content'=>'<span class="info-number operation selected" style="cursor:default"><i class="mdi mdi-check"></i></span>' ]]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL']);
                }
                
                exit;
                
            break;
        
        
            
            default :
                
                $period = $this->_interface->checkPeriod( $this->_router ); // By Default choose current (actual)
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'workshops' );
                
                $this->_datas->dropdownlist = $this->_interface->getDropdownList( $period );
                
                $this->_datas->displayinfos = $this->_interface->getDisplayinfos( $period );
                
                $this->_datas->datas        = $modelWorkshops->workshopsAndDomains( $period );

                $this->_datas->response     = $this->_interface->getWorkshopUpdatedDatas( $this->_router );
                
                $this->_view = 'workshops/workshops-list';
                
            break;
            
        } 
    }
}