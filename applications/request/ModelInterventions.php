<?php
namespace applications\request;

use includes\components\CommonModel;
use includes\tools\Orm;

class ModelInterventions extends CommonModel {

    private $_etatIntervention;

    public function __construct() {

        $this->_setTables(['request/builders/BuilderInterventions']);
        
        $this->_etatIntervention = [
            '0'=>'Tous',
            '1'=>'En demande',
            '2'=>'En cours',
            '3'=>'Terminé',
        ];
    }
    
    
    public function getEtatIntervention()
    {
        $list = [];

        foreach( $this->_etatIntervention as $i => $etat )
        {
            $list[] = [ 'value' => $i, 'label' => $etat ];
        }

        return $list;    
    }
    
    public function getEtatInterventionInfos( $nEtat )
    {
        if($nEtat > count($this->_etatIntervention) || $nEtat < count( $this->_etatIntervention )){
            return $this->_etatIntervention[ '0' ];
        }
            return $this->_etatIntervention[ $nEtat ];
       
    }
    
        
    public function interventions($params = [])
    {
        $orm = new Orm('interventions', $this->_dbTables['interventions'], $this->_dbTables['relations']);
        
        $results = $orm->select()
                ->where($params)
                ->order([ 'DateDemandeIntervention' => 'DESC'])
                ->execute(true);
        
        return $results;
    }
    
    public function interventionsReponse($params = []){
        $orm = new Orm('interventions_reponses', $this->_dbTables['interventions_reponses'], $this->_dbTables['relations']);
        
        $results = $orm ->select()
                        ->where($params)
                        ->execute();

        return $results;
    }
    
/*
    public function interventionUpdate($action = 'insert', $id = null) {
        $orm = new Orm('interventions', $this->_dbTables['interventions']);

        $datas = $orm->prepareGlobalDatas([ 'POST' => true]);

        if (!$orm->issetErrors()) {
            if ($action === 'insert') {
                $data = $orm->insert();
            } else if ($action === 'update') {
                $data = $orm->update([ 'IdIntervention' => $id]);
            }

            return $data;
        } else {
            return false;
        }
    }
 * 
 */

    public function interventionDelete($id = null) 
    {
        $orm = new Orm('interventions', $this->_dbTables['interventions']);

        $orm->delete(['IdIntervention' => $id]);

        return true;
    }

    /**
     * Prepare datas for the form
     * depending on the table "interventions".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */
    public function interventionBuild( $id = null ) {
        
        $orm = new Orm('interventions', $this->_dbTables['interventions']);

        $orm->prepareGlobalDatas([ 'POST' => true]);

        $params = ( isset($id) ) ? ['IdIntervention' => $id] : null;

        return $orm->build($params);
    }
   

}
