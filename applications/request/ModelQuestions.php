<?php
namespace applications\request;

use includes\components\CommonModel;
use includes\tools\Orm;

class ModelQuestions extends CommonModel {

    public function __construct() {

        $this->_setTables(['request/builders/BuilderQuestions']);
       
    }
    
    
    public function choix($params = [], $isOffice = false)
    {
        $orm = new Orm( 'interventions_choix', $this->_dbTables['interventions_choix'], $this->_dbTables['relations'] );

        $results = $orm ->select()
                        ->where($params)
                        ->order([ 'interventions_choix.IdChoix' => 'ASC'])
                        ->execute();

        return $results;
    }
    
    
    public function questions($params = [], $isOffice = false)
    {
        $orm = new Orm('interventions_questions', $this->_dbTables['interventions_questions'], $this->_dbTables['relations']);

        $jointure = ( $isOffice ) ? ['interventions_questions'] : [];
        
        $group = ( $isOffice ) ? ['interventions_questions' => 'IdOffice'] : [];

        $results = $orm ->select()
                        ->joins($jointure)
                        ->where($params)
                        ->group($group)
                        ->order([ 'interventions_questions.OrderQuestion' => 'ASC'])
                        ->execute();
        
        return $results;
    }

   

    public function questionUpdate( $action = 'insert', $id = null )
    {
        $orm = new Orm('interventions_questions', $this->_dbTables['interventions_questions']);

        $datas = $orm->prepareGlobalDatas([ 'POST' => true]);

        if (!$orm->issetErrors()) 
        {
            if ($action === 'insert')
            {
                $data = $orm->insert();
            }
            else if ($action === 'update') 
            {
                $data = $orm->update([ 'IdQuestion' => $id]);
            }

            return $data;
        } 
        else 
        {
            return false;
        }
    }


    public function questionDelete( $id = null ) 
    {
        $orm = new Orm('interventions_questions', $this->_dbTables['interventions_questions']);

        $orm->delete(['IdQuestion' => $id]);

        return true;
    }

    /**
     * Prepare datas for the form
     * depending on the table "interventions_questions".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */
    public function questionBuild( $id = null ) {
        
        $orm = new Orm('interventions_questions', $this->_dbTables['interventions_questions']);

        $orm->prepareGlobalDatas([ 'POST' => true]);

        $params = ( isset($id) ) ? ['IdQuestion' => $id] : null;

        return $orm->build($params);
    }
   

}
