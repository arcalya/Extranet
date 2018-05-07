<?php
namespace applications\mailbox;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;

use stdClass;

class ModelMailbox extends CommonModel {

    public function __construct() 
    {
        $this->_setTables(['mailbox/builders/BuilderMailbox']);
    }
    
    
    public function getMessages($period) // Get Sent Messages.
    {

        switch($period)
        {
            case 'sent':
                $messages = $this->_messagessent(['sendermessagerie' => $_SESSION['adminId'], 'sendmessagerie' => 1]);
            break;
        
            case 'saved':
                $messages = $this->_messagessaved(['sendermessagerie' => $_SESSION['adminId'], 'sendmessagerie' => 0]);
            break;
            
            default:
                
              $messages = $this->_messages(['receiversmessagerie' => $_SESSION['adminId']]);
              $messages = $this->_messages(['receiversmessagerie' => 'olivier.dommange@lausanne.ch']); //DEBUG
            break;
        }
        
        $this->_values = new stdClass();
        
        if( isset( $messages ) )
        {
            foreach( $messages as $m => $message )
            {
            
            }
        }
        
        return $messages;
    }
    
    public function getSingleMessage($id) //Get the content for a specific message
    {
        
        
        
        $message = $this->_messages(['idmessagerie' => $id]);
        
        
        
        $this->values = new stdClass();
        
        if( isset($message) /*&& (sizeof($message) == 1)*/ )
        {
            $message[0]->currentLoggedUser = $id;
            return $message[0];
        }
        
        return false;
    }
    
    public function getFieldValues( $id = null )
    {
        
        $orm = new Orm( 'messagerie', $this->_dbTables['messagerie'], $this->_dbTables['relations'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true, 'FILE' => true ] );
        
        $params = ( isset( $id ) ) ? ['idmessagerie' => $id] : null;
            
        $messageBuild = $orm->build( $params );
        
      
       /* $messageBuild = $orm->select()
                            ->joins([ 'messagerie'=>['beneficiaire']]) 
                            ->where($params)
                            ->execute(true);
        
        */
        
        if(isset($id)){
            
           
            $this->_setModels( ['users/ModelUsers'] );

            $modelUsers        = $this->_models['ModelUsers'];
            
            $user = $modelUsers->beneficiaire(['beneficiaire.IDBeneficiaire' => $messageBuild->sendermessagerie]);
            
                      
            $messageBuild->titremessagerie = 'RE:'.$messageBuild->titremessagerie;
            
            $messageBuild->receiversmessagerie = ($_SESSION['adminEmail'] === $user[0]->EmailBeneficiaire) ? $messageBuild->receiversmessagerie : $user[0]->EmailBeneficiaire;
            
           
            $messageBuild->messagemessagerie = '



------------------------------------------------------------------------------------------------

'.$messageBuild->messagemessagerie;
            
            
        }
        
        return $messageBuild;
        
       
    }
    
    
    private function _messagessent( $params )
    {
        $orm = new Orm( 'messagerie', $this->_dbTables['messagerie'], $this->_dbTables['relations'] );
        
                
        $messages = $orm->select()
                        ->joins(['messagerie'])
                        ->where($params)
                        ->order(['datemessagerie' => 'DESC'])
                        ->execute();
        
        return $messages;        
    }
    
    private function _messagessaved( $params )
    {
        $orm = new Orm( 'messagerie', $this->_dbTables['messagerie'], $this->_dbTables['relations'] );
        
                
        $messages = $orm->select()
                        ->joins(['messagerie'])
                        ->where($params)
                        ->order(['datemessagerie' => 'DESC'])
                        ->execute();
        
        
        return $messages;        
    }
    
    private function _messages( $params )
    {
        $orm = new Orm( 'messagerie', $this->_dbTables['messagerie'], $this->_dbTables['relations'] );
                
        $messages = $orm->select()
                        ->joins(['messagerie'])
                        ->where($params)
                        ->order(['datemessagerie' => 'DESC'])
                        ->execute();
        
        if($messages){
        
            foreach($messages as $message){
                $message->datemessagerie = date('d.m.Y H:i:s', strtotime($message->datemessagerie));
            }
        
        }
        
        return $messages;        
    }
    
    
    /**
     * Prepare datas for the formulas 
     * depending on the table "mailbox".
     * Manage sending. Returns settings datas and errors
     * 
     * @return object       | Datas and errors.
     */   
    public function mailboxBuildAndInsert($submitMode)
    {
        $orm = new Orm( 'messagerie', $this->_dbTables['messagerie'], $this->_dbTables['relations'] );
        
        //$mailboxFileParams = $this->mailboxFileParams();
        
        //$mailboxFileParams['forceupload'] = true;
        
        $datas = $orm->prepareGlobalDatas( ['POST'=> true, 'FILE' => [ 'upload' => true ] ] );
        
        if( !$orm->issetErrors() )
        {
            //$orm->build($params);
            if(isset($_POST['send-message-on-submit'])){
                $saveMark = 1;
            }
            else{
                $saveMark = 0;
            }
            
            $orm->prepareDatas(["sendermessagerie" => $_SESSION['adminId'], "officemessagerie" => $_SESSION['adminOffice'], "SizeDocument" => $datas["UrlDocument"]["size"], "sendmessagerie" => $saveMark]);

            
            return $orm->insert();
        }  
        else
        {
            //echo '<div class="alert-danger alert">Erreur fichier : ',print_r($orm->getErrors()),'</div>';
            return false;
        }
    }
   
    
    public function mailboxFileDelete( $filedelete )
    {
        $fileInfos = explode( '/', $filedelete );
        
        $file   = $fileInfos[ 0 ];
        
        $param  = isset( $fileInfos[ 1 ] ) ? ['idmessagerie' => $fileInfos[ 1 ]] : null;
        
        $orm = new Orm( 'messagerie', $this->_dbTables['messagerie'], $this->_dbTables['relations'] );
                
        $orm->deleteFile( $param, ['UrlDocument' => $file] );
    }
    
    
    public function mailboxFileParams()
    {
        return $this->_dbTables['messagerie']['UrlDocument']['file'];
    }

    
}
    