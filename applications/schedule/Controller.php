<?php
namespace applications\schedule;

use includes\components\CommonController;

use includes\Request;
use includes\Db;
use includes\tools\Date;
use stdClass;

class Controller extends CommonController{

    private $_datasCalendar = [];
    
    
    private function _setJsonDatasCalendar( $datasToSet )
    {
        if( isset( $datasToSet ) )
        {
            foreach ( $datasToSet as $i ) 
            {
                $this->_datasCalendar[] = $i;
            }
        }
    }
    
    
    private function _setactiviteForm()
    {   
        $this->_setModels( [ 'schedule/ModelActivities' ] );

        $modelActivities= $this->_models[ 'ModelActivities' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->form     = $modelActivities->activiteBuild( $id );

        $this->_datas->response = $this->_interface->getActiviteFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'schedule/activite-form';
    }

    private function _settypeactiviteForm()
    {   
        $this->_setModels( [ 'schedule/ModelActivities' ] );

        $modelActivities= $this->_models[ 'ModelActivities' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->form     = $modelActivities->typeactiviteBuild( $id );

        $this->_datas->response = $this->_interface->getTypeactiviteFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'schedule/typeactivite-form';
    }
    

    private function _settaches_alertForm()
    {   
        $this->_setModels( [ 'schedule/ModelTasks' ] );

        $modelTasks     = $this->_models[ 'ModelTasks' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->form     = $modelTasks->taches_alertBuild( $id );

        $this->_datas->response = $this->_interface->getTaches_alertFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'schedule/taches_alert-form';
    }
    
    
    protected function _setDatasView()
    {
        $this->_setModels( [ 'schedule/ModelActivities', 'schedule/ModelTasks', 'workshops/ModelWorkshops', 'timestamp/ModelPunchs' ] );

        $modelActivities= $this->_models[ 'ModelActivities' ];
        $modelTasks     = $this->_models[ 'ModelTasks' ];
        $modelWorkshops = $this->_models[ 'ModelWorkshops' ];
        $modelPunchs    = $this->_models[ 'ModelPunchs' ];
        
        switch( $this->_action )
        {
            case 'activiteform':
                
                $this->_setactiviteForm();
                
            break;
            
            case 'activiteupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelActivities->activiteUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/schedule/activite/success' . $action . '/' . $data->IDActivite );
                    
                    exit;
                }
                else 
                {
                    $this->_setactiviteForm();
                }
            break;
            
            case 'activite_addAjax': // Update activities from Ajax
                
                $datas = new stdClass;
                                
                if( $this->_request->getVar( 'date' ) !== null && $this->_request->getVar( 'IdUser' ) !== null && $datas = $modelActivities->activiteUpdate( $this->_request->getVar( 'date' ), $this->_request->getVar( 'IdUser' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'alertsuccess' => ['alert-success.alert-display-ajax'=>'Les activités du <strong>' . $datas->date . '</strong> ont été mises à jour.'], 'callback'=>['function'=>'refreshCalendar' ] ]);  
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'errors'=>['alert-danger.alert-display-ajax' => 'Les champs ne sont pas correctement remplis.' ] ]); 
                }
                
                exit;
                
            break;
        
            
            case 'activitedeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelActivities->activiteDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une rubrique vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break; 
            
            
            case 'reports' :
                                
                $this->_datas = new stdClass;
                
                $this->_datas->response = '';
                
                $this->_view = 'schedule/reports';
                                
            break;
            
            
            
                
            case 'typeactivite':
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'typeactivite' );
                
                $this->_datas->datas        = $modelActivities->typeactivite();
                
                $this->_datas->response     = $this->_interface->getTypeactiviteUpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $this->_interface->getTypeactiviteTableHead();
                
                $this->_view = 'schedule/typeactivite-list';
                
            break;
                
            case 'typeactiviteform':
                
                $this->_settypeactiviteForm();
                
            break;
            

            case 'typeactiviteupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelActivities->typeactiviteUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/schedule/typeactivite/success' . $action . '/' . $data->IDTypeActivite );
                    
                    exit;
                }
                else 
                {
                    $this->_settypeactiviteForm();
                }
            break;
            
            
            case 'typeactivitedeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelActivities->typeactiviteDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une rubrique vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
             
            
            
            /*    
            case 'tache_beneficiaire':
                
                $this->_datas = new stdClass;
                
                $this->_datas->datas        = $modelTasks->tache_beneficiaire();
                
                $this->_datas->response     = $this->_interface->getTache_beneficiaireUpdatedDatas( $this->_router );
                
                $this->_view = 'schedule/tache_beneficiaire-list';
                
            break;
                
            case 'tache_beneficiaireform':
                
                $this->_settache_beneficiaireForm();
                
            break;
            
            case 'tache_beneficiaireupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelTasks->tache_beneficiaireUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/schedule/tache_beneficiaire/success' . $action . '/' . $data->IdTache );
                    
                    exit;
                }
                else 
                {
                    $this->_settache_beneficiaireForm();
                }
            break;
            
            
            case 'tache_beneficiairedeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelTasks->tache_beneficiaireDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une rubrique vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
                    
                
            case 'taches_alert':
                
                $this->_datas = new stdClass;
                
                $this->_datas->datas        = $modelTasks->taches_alert();
                
                $this->_datas->response     = $this->_interface->getTaches_alertUpdatedDatas( $this->_router );
                
                $this->_view = 'schedule/taches_alert-list';
                
            break;
                
            case 'taches_alertform':
                
                $this->_settaches_alertForm();
                
            break;
            
            
            case 'taches_alertupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelTasks->taches_alertUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/schedule/taches_alert/success' . $action . '/' . $data->IdTache );
                    
                    exit;
                }
                else 
                {
                    $this->_settaches_alertForm();
                }
            break;
            
            
            case 'taches_alertdeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelTasks->taches_alertDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une rubrique vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
                    */
            
            
            
            case 'tache_addAjax': // Update tasks from Ajax through the calendar
                
                $DateDebutTache = $this->_request->getVar( 'DateDebutTache' );
                
                $DateFinTache   = $this->_request->getVar( 'DateFinTache' );
                
                $_POST['DateDebutTache']    = $DateDebutTache[0] . ' ' . $modelTasks->hourFormat( $DateDebutTache[1], false );
                
                $_POST['DateFinTache']      = $DateFinTache[0] . ' ' . $modelTasks->hourFormat( $DateFinTache[1], false );
                
                if( $this->_request->getVar( 'IdBeneficiaire' ) !== null && $this->_request->getVar( 'TitreTache' ) !== null && $datas = $modelTasks->taches_alertUpdate() )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'alertsuccess' => ['alert-success.alert-display-ajax'=>'La tâche du <strong>' . $datas->TitreTache . '</strong> a été mise à jour.'], 'callback'=>['function'=>'refreshCalendar' ] ]);  
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'errors'=>['alert-danger.alert-display-ajax' => 'Les champs ne sont pas correctement remplis.' ] ]); 
                }
                
                exit;
                
            break;
            
            
            case 'tache_deleteAjax': // Delete tasks from Ajax through the calendar
                
                $deleteid = $this->_request->getVar( 'deleteid' );
                
                if( $modelTasks->taches_alertDelete( $deleteid ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'alertsuccess' => ['alert-success.alert-display-ajax'=>'La tâche a été supprimée.'], 'callback'=>['function'=>'refreshCalendar' ] ]);  
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'errors'=>['alert-danger.alert-display-ajax' => 'Les champs ne sont pas correctement remplis.' ] ]); 
                }
                
                exit;
                
            break;
            
            
            
            case 'calendareventsAjax' :
                
                $request = Request::getInstance();
                
                $startDate  = $request->getVar('start');
                $endDate    = $request->getVar('end');
                $events     = $request->getVar('events');   // Modules to display in the calendar : activities/tasks/workshops/timestamp/appointments
                $type       = $request->getVar('type');     // Datas to get from modules : "generic", "currentuser", "INTERGER"(user ID)
                
                $modules = explode( '/', $events );
               
                foreach( $modules as $module )
                {
                    if( $module === 'timestamp' )
                    {
                        $this->_setJsonDatasCalendar( $modelPunchs->puchsCalendar( [], ['start'=>$startDate, 'end'=>$endDate] ) );
                        //$this->_setJsonDatasCalendar( $modelPunchs->puchsCalendar( [ 'fullname' => 1133 ], ['start'=>$startDate, 'end'=>$endDate] ) );
                    }
                    else if( $module === 'workshops' )
                    {
                        if( is_numeric( $type ) )
                        {
                            $params = ['IDBeneficiaire'=>$type];
                        }
                        else if( $type === 'currentuser' )
                        {
                            $params = ['IDBeneficiaire'=>$_SESSION['adminId']];
                        }
                        else
                        {
                            $params = [];
                        }
                        $this->_setJsonDatasCalendar( $modelWorkshops->workshopsCalendar( $params, ['start'=>$startDate, 'end'=>$endDate], ['inscrit', 'suivi'], [0, 1] ) );
                    }
                    else if( $module === 'activities' )
                    {
                        //$this->_setJsonDatasCalendar( $modelActivities->activitiesCalendar( [], ['start'=>$startDate, 'end'=>$endDate] ) );
                        $this->_setJsonDatasCalendar( $modelActivities->activitiesCalendar( [ 'IDBeneficiaire' => 2700 ], ['start'=>$startDate, 'end'=>$endDate] ) );
                    }
                    else if( $module === 'tasks' )
                    {
                        $this->_setJsonDatasCalendar( $modelTasks->tasksCalendar( [], ['start'=>$startDate, 'end'=>$endDate] ) );
                    }
                    else if( $module === 'appointments' ) 
                    {
                        //$this->_setJsonDatasCalendar( $modelPunchs->appointmentsCalendar( [], ['start'=>$startDate, 'end'=>$endDate] ) );
                        $this->_setJsonDatasCalendar( $modelPunchs->appointmentsCalendar( [ 'fullname' => 2700 ], ['start'=>$startDate, 'end'=>$endDate] ) );
                    }
                }
                
                
                if( count( $this->_datasCalendar ) === 0 )
                {
                    $this->_setJsonDatasCalendar( [['title' => '', 'className'=>'hide', 'start' => $startDate, 'color' => 'white', 'token' => $_SESSION[ 'token' ]]] );    
                }
                
                //var_dump( $this->_datasCalendar );
                
                echo json_encode( $this->_datasCalendar );    
                
                exit;
                
            break;
                
        
            default :
                
                $this->_datas = new stdClass;
                
                $this->_datas->calendarevents= ( !empty( $this->_action ) && $this->_action !== 'all' ) ? $this->_action : 'timestamp/activities/tasks/workshops/appointments';
                
                $this->_datas->displayinfos = $this->_interface->setEvents( $this->_datas->calendarevents );
                
                $this->_datas->calendartype = ( !empty( $this->_router ) ) ? $this->_router : 'currentuser';
                
                if( $this->_datas->displayinfos['tasks'] )
                {
                    $this->_datas->formtask = new stdClass; 
                    
                    $this->_datas->formtask->task      = $modelTasks->taches_alertBuild();
                    
                    $this->_datas->formtask->users     = $modelTasks->getUsers( $this->_datas->formtask->task->IdTache );
                    
                    $this->_datas->formtask->periods   = $modelTasks->getPeriod();
                    
                    $this->_datas->formtask->time      = $this->_interface->getHoursList( 10 );
                }
                if( $this->_datas->displayinfos['activities'] )
                {
                    $this->_datas->formactivity = new stdClass;
                    
                    $this->_datas->formactivity->datas = $modelActivities->activiteBuild();

                    $this->_datas->formactivity->typeactivities = $this->_interface->getTypeactivities(); 

                    $this->_datas->formactivity->durees = $this->_interface->getDurees(); 
                }

                $this->_datas->response     = $this->_interface->getActiviteUpdatedDatas( $this->_router );
                                
                $this->_view = 'schedule/calendar';
                
            break;
            
        } 
    }
}