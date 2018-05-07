<?php
namespace applications\timestamp;
      
use includes\components\CommonController;
use includes\Request;
use stdClass;

class Controller extends CommonController{

    private function _setinfoForm()
    {   
        $this->_setModels( [ 'timestamp/ModelPunchs' ] );

        $modelPunchs= $this->_models[ 'ModelPunchs' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->tabs     = $modelPunchs->getTabs( 'info' );

        $this->_datas->form     = $modelPunchs->infoBuild( $id );

        $this->_datas->response = $modelPunchs->getinfoFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'timestamp/info-form';
    }
    

    private function _setpunchlistForm()
    {   
        $this->_setModels( [ 'timestamp/ModelPunchtypes', 'timestamp/ModelPunchtypes' ] );

        $modelPunchtypes= $this->_models[ 'ModelPunchs' ];
        
        $id = ( !empty( $this->_router ) ) ? $this->_router : null;

        $this->_datas = new stdClass;

        $this->_datas->tabs     = $this->_interface->getTabs( 'punchlist' );

        $this->_datas->form     = $modelPunchtypes->punchlistBuild( $id );

        $this->_datas->response = $modelPunchtypes->getpunchlistFormUpdatedDatas( $this->_datas->form );

        $this->_view = 'timestamp/punchlist-form';
    }
    
    protected function _setDatasView()
    {
        $this->_setModels( [ 'timestamp/ModelPunchs', 'timestamp/ModelPunchtypes', 'users/ModelUsers' ] );

        $modelPunchs= $this->_models[ 'ModelPunchs' ];

        $modelPunchtypes= $this->_models[ 'ModelPunchtypes' ];

        $modelUsers= $this->_models[ 'ModelUsers' ];
        
        switch( $this->_action )
        {
        
            case 'infoform':
                
                $this->_setinfoForm();
                
            break;
            

            case 'infoactiveAjax':
                
                $datas = new stdClass;
                if( $return = $modelPunchs->infoActiveUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'La rubrique <strong><a href="#'.$this->_request->getVar( 'id' ).'">' . $return['name'] . '</a></strong> a été ' . ( ( $return['active'] === 1 ) ? 'activée.' : 'désactivée.') ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
            
        
            case 'infoorderAjax':
                
                $datas = new stdClass;
                
                if( $modelPunchs->infoPosition( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => '' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                exit;
                
            break;
            
            
            case 'infoupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelPunchs->infoUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/timestamp/info/success' . $action . '/' . $data->fullname );
                    
                    exit;
                }
                else 
                {
                    $this->_setinfoForm();
                }
            break;
            
            
            case 'infodeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelPunchs->infoDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une rubrique vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;            
                    
                
            case 'punchlist': // Configuration of punchs
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'punchlist' );
                
                $this->_datas->datas        = $modelPunchtypes->punchlist();
                
                $this->_datas->response     = $this->_interface->getpunchlistUpdatedDatas( $this->_router );
                
                $this->_datas->tableHead    = $this->_interface->getPunchlistTableHead();
                
                $this->_view = 'timestamp/punchlist-list';
                
            break;
                
            case 'punchlistform':
                
                $this->_setpunchlistForm();
                
            break;
            

            case 'punchlistactiveAjax':
                
                $datas = new stdClass;
                if( $return = $this->_interface->punchlistActiveUpdate( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'La rubrique <strong><a href="#'.$this->_request->getVar( 'id' ).'">' . $return['name'] . '</a></strong> a été ' . ( ( $return['active'] === 1 ) ? 'activée.' : 'désactivée.') ]);
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]); 
                }
                exit;
                
            break;
            
        
            case 'punchlistorderAjax':
                
                $datas = new stdClass;
                
                if( $modelPunchs->punchlistPosition( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => '' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                exit;
                
            break;
            
            
            case 'punchlistupdate':
                
                $id     = ( !empty( $this->_router ) ) ? $this->_router : null;
                $action = ( !empty( $this->_router ) ) ? 'update' : 'insert';
                
                if( $data = $modelPunchs->punchlistUpdate( $action, $id ) )
                {
                    header( 'location:' . SITE_URL . '/timestamp/punchlist/success' . $action . '/' . $data->IDPunch );
                    
                    exit;
                }
                else 
                {
                    $this->_setpunchlistForm();
                }
            break;
            
            
            case 'punchlistdeleteAjax':
                
                $datas = new stdClass;

                if( $this->_datas = $modelPunchs->punchlistDelete( $this->_request->getVar( 'id' ) ) )
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'data' => $datas, 'msg' => 'Une rubrique vient d\'être supprimée.' ]); 
                }
                else
                {
                    echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'FAIL', 'data' => $datas, 'msg' => '' ]);   
                }
                
                exit;
                
            break;      
            
            
            case 'menu' : // Used in the top menu -> public/views/topadmin.php
                
                $this->_datas = new stdClass;
                
                //$this->_datas->appointments    = $modelPunchs->appointmentsMenu(['fullname' => $_SESSION['adminId']], ['start' => date('Y-m-d').' 00:00:00'] );
                $this->_datas->appointments    = $modelPunchs->appointmentsMenu(['fullname' => 2327], ['start' => date('Y-m-d').' 00:00:00'] );
                
                $this->_view = 'timestamp/timestamp-menu';
            
            break;
                    
        
        
            case 'absencesgrid' :
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'absencesgrid' );
                
                $this->_view = 'timestamp/absences-grid';
                
            break;
        
            case 'confaccess' :
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'confaccess' );
                
                $this->_view = 'timestamp/confaccess';
                
            break;
        
             case 'reports' :
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'reports' );
                
                $this->_view = 'timestamp/reports';
                
            break;
            
            default :
                
                $this->_datas = new stdClass;
                
                $this->_datas->tabs         = $this->_interface->getTabs( 'timestamp' );
                
                
                $users = $modelUsers->beneficiaireDetails( [ 'beneficiaire_details.office' => $_SESSION['adminOffice'] ], 'actual', 'participants' );
                
                $beneficiaires = [];
                
                foreach( $users as $user )
                {
                    $beneficiaires[] = ['value'=>$user->LoginBeneficiaire, 'label'=>$user->PrenomBeneficiaire . ' ' . $user->NomBeneficiaire];
                }
                
               /* $this->_datas->beneficiaires = [ 
                                            [ 'value'=>'Login@maill.com', 'label'=>'Sonja Guicheux' ], 
                                            ['value'=>'adress@mail.com', 'label'=>'Olivier Dommange'] 
               
                */
               
                $punchlist = $modelUsers->beneficiaireDetails( [ 'beneficiaire_details.office' => $_SESSION['adminOffice'] ], 'actual', 'participants' );
                
                $punchlist = [];
                
                foreach( $punchlist as $user )
                {
                    $punchlist[] =  [ 'value'=>'punchitems', 'label'=>$user->PrenomBeneficiaire . ' ' . $user->NomBeneficiaire ];
                                      
                }
                /*$this->_datas->beneficiaires = [ 
                                                 [ 'value'=>'Login@maill.com', 'label'=>'Sonja Guicheux' ], 
                                                 ['value'=>'adress@mail.com', 'label'=>'Olivier Dommange'] 
                                                      ];*/
                
        /* ?><pre><?php var_dump( $punchitems ); ?></pre><?php*/
                /* $punchlist = $punchitems->IDpuch( ['$punchlist->IDPunch.'>'.( $n + 1 ).'] );
                 
                 $punchlist = [];

                 foreach( $punchlist as $punchitems )
                  {
                     $punchlist[] =    ['value'=>'punchitems', 'label'=>'Arrivée matin' ];
                                       ['value'=>'punchitems', 'label'=>'Départ matin']; 
                                       ['value'=>'punchitems', 'label'=>'Arrivée apres-midi'];
                                       ['value'=>'punchitems', 'label'=>'Départ apres-midi'];
                  }
*/
               
                
                $this->_datas->punchlist= [ 
                                            [ 'value'=>'EP', 'label'=>'Arrivée matin' ], 
                                            ['value'=>'B', 'label'=>'Départ matin'] 
                                                      ];
                              
                
                
                   $this->_datas->beneficiaires = $beneficiaires;
                $this->_datas->formValues = new stdClass;
                
               $this->_datas->formValues->PeriodFrom  = 'EP';
               $this->_datas->formValues->Meeting     = 'B';
               
                $this->_view = 'timestamp/timestamp';
                
                
                
                
                
                
                
                
                
                
                
                
            break;
        
        
           
            
        } 
    }
}