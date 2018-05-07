<?php
namespace applications\mailbox;

use includes\components\CommonController;

use stdClass;

class Controller extends CommonController{

    private function _setMailboxForm()
    {
         
        $this->_setModels( ['mailbox/ModelMailbox'/*, 'system/ModelSystem'*/] );
        
        $modelMailbox        = $this->_models[ 'ModelMailbox' ]; 
        //$modelSystem       = $this->_models[ 'ModelSystem' ];       
        
        
        $this->_datas = new stdClass;
                
        $this->_datas->users        = $this->_interface->getUsers();

        $this->_datas->values       = $modelMailbox->getFieldValues( ( !empty($this->_router) ? $this->_router : null ) );
        
        $this->_datas->values->field = '';
        
        $this->_datas->fileInfos    = $modelMailbox->mailboxFileParams();
          
        $this->_datas->response     = '';

        $this->_view = 'mailbox/mailbox-form';
       
    }
    
    
    protected function _setDatasView()
    {
        $this->_setModels(['mailbox/ModelMailbox']);
        
        $modelMailbox = $this->_models[ 'ModelMailbox' ];
        
        
        switch( $this->_action )
        {
          
            case 'form' : 
                
               $this->_setMailboxForm();
                              
            break;
        
            case 'send' :
                              
                if( ( $filedelete = $this->_request->getVar( 'filedelete' ) ) !== null ) // Supression de fichier
                {
                    $modelMailbox->mailboxFileDelete( $filedelete );
                    
                    $this->_setMailboxForm();
                }
                else
                {
                    
                    $submitMode = "";
                    
                    
                   
                    //SEND message on submit
                    if($this->_request->getVar('send-message-on-submit' ) !== null){
                        
                        $submitMode = "send";
                        
                        if( $data = $modelMailbox->mailboxBuildAndInsert($submitMode) )
                        {
                            header( 'location:' . SITE_URL . '/mailbox/success' . $action . '/' . $data->IdContact );
                            exit;
                        }
                        else 
                        {
                            $this->_setMailboxForm();
                        }

                        
                    }
                    
                    //SAVE message on submit
                    else{ 
                        
                        $submitMode = "save";
                        
                        if( $data = $modelMailbox->mailboxBuildAndInsert($submitMode) )
                        {
                            header( 'location:' . SITE_URL . '/mailbox/success' . $action . '/' . $data->IdContact );
                            exit;
                        }
                        else 
                        {
                            $this->_setMailboxForm();
                        }
                        
                    
                    }

                    
                }
                
            break;
        
            
            case 'user-list-formAjax' :
                
                $field = $this->_request->getVar( 'field' );
                
                $usersType = [ [ 'title'=>'Encadrement', 'value'=>'managers' ], [ 'title'=>'Participants', 'value'=>'participants' ] ];
                
                $users = [];
                
                foreach ( $usersType as $usersType )
                {
                    if( ( $usersSent = $this->_request->getVar( 'users_' . $usersType[ 'value' ] ) ) !== null )
                    {        
                        $users = array_merge( $users, $usersSent );
                    }
                }
                
                echo json_encode([ 'token' => $_SESSION[ 'token' ], 'status' => 'OK', 'callback'=>[
                                    'function'=>'appendDatas', 
                                    'selector'=>'#'.$field, 
                                    'content'=>implode( '; ', $users ) 
                                    ]]);
                exit;
                
            break;
        
           
            case 'sent':
            case 'saved':
            case 'received':
            default : 
                                
                $period = $this->_interface->checkTypeMessages( $this->_action ); // By Default choose current (received)
                $this->_datas = new stdClass;
                $this->_datas->messages         = $modelMailbox->getMessages( $period );
                
                $this->_datas->dropdownlist     = $this->_interface->getDropdownList( $period );
                
                $this->_datas->action = $period;
                
                $this->_datas->response         = '';
                
                //Case with router "message"
                
                if(!empty($this->_router)){
                    
                    $routerElements = explode('/', $this->_router);
                    $router["subAction"] = $routerElements[0];
                    $router["id"] = $routerElements[1];
                    
                    if($router["subAction"] == "message"){
                
                        $id = $router["id"];
                        $period = $this->_interface->checkTypeMessages( $this->_action ); // By Default choose current (received)

                        $this->_datas = new stdClass;

                        $this->_datas->messages = $modelMailbox->getMessages( $period );

                        $this->_datas->currentMessageRouter = $id;

                        $this->_datas->currentMessage = new stdClass;
                        $this->_datas->currentMessage = $modelMailbox->getSingleMessage($id);
                        $this->_datas->dropdownlist     = $this->_interface->getDropdownList( $period );

                        $this->_datas->action = $period;

                        $this->_datas->response         = '';

                        $this->_view = 'mailbox/mailbox';
                        
                    }
                
                }
                
                else{
                    
                
                }
                
                
                
                
                $this->_view = 'mailbox/mailbox';
                              
            break;
        } 
    }
}
