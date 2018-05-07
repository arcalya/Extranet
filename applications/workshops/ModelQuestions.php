<?php
namespace applications\workshops;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;
use includes\Lang;

class ModelQuestions extends CommonModel {
    
    
    public function __construct() 
    {
        $this->_setTables(['workshops/builders/BuilderQuestions']);
    }

    
    
    
    public function beneficiaireWorkshopEval( $params = [] )
    {
        $orm = new Orm( 'coaching_evaluations' );
        
        $results = $orm ->select()
                        ->join([ 'coaching_evaluations'=>'IDQuestionEvaluation', 'coaching_evaluation_questions'=>'IDQuestion' ])
                        ->where( $params )
                        ->execute();
        
        return $results;
    }  
    
    
     
    /**
     * Select datas form the table "coaching_evaluation_questions"
     * 
     * @param array $params | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IDQuestion'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function questions( $params = [] ) {
    
        $orm = new Orm( 'coaching_evaluation_questions', $this->_dbTables['coaching_evaluation_questions'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'IDQuestion' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "coaching_evaluation_questions".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function questionsBuild( $id = null )
    {
        $orm = new Orm( 'coaching_evaluation_questions', $this->_dbTables['coaching_evaluation_questions'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDQuestion' => $id] : null;
            
        return $orm->build( $params );
    }
    
    /**
     * Updates datas in the database.
     * Do insert and update.
     * Figure errors and send back false in that case
     * 
     * @param string $action  | (optionnal) Action to do.
     *                          Default : does insert.
     *                          Defined by "insert" or "update". 
     * @param int $id         | (optional) Id of the content to update.
     *                          It is mandatory for updates.
     * @return boolean|object | false when errors are found 
     *                          (ex. empty fields, bad file format imported,...). 
     *                          Object with content datas when process went good. 
     */ 
    public function questionsUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'coaching_evaluation_questions', $this->_dbTables['coaching_evaluation_questions'] );
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IDQuestion' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }

    /**
     * Delete an entry in the database.
     * 
     * @param int $id   | Id of the content to delete.
     * @return boolean  | Return's true in all cases.    
     */
    public function questionsDelete( $id ) 
    {
        $orm = new Orm( 'coaching_evaluation_questions', $this->_dbTables['coaching_evaluation_questions'] );
            
        $orm->delete([ 'IDQuestion' => $id ]);
        
        return true;
    } 
    
    
    public function coaching_evaluation_questionsActiveUpdate( $id = null )
    {
        return $this->_updateActive( $id, 'questions', 'coaching_evaluation_questions', 'coaching_evaluation_questions', 'IDQuestion', 'Question', 'StatutCoaching_evaluation_questions');
       
    }
    
    
}