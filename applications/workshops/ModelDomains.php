<?php
namespace applications\workshops;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;
use includes\Lang;

class ModelDomains extends CommonModel {
    
    public function __construct() 
    {
        $this->_setTables(['workshops/builders/BuilderDomains']);
    }

    
         
        
    /**
     * Select datas form the table "domaine_ateliers"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IDDomaineAtelier'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function domaine_ateliers( $params = [] ) {
    
        $orm = new Orm( 'domaine_ateliers', $this->_dbTables['domaine_ateliers'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->join(['domaine_ateliers' => 'IDDomaineAtelier', 'domaine_atelier_office' => 'IDDomaineAtelier'])
                        ->order([ 'NomDomaineAtelier' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
    
    
    
    /**
     * Select datas form the table "domaine"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IDDomaine'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function domaine( $params = [] ) {
    
        $orm = new Orm( 'domaine', $this->_dbTables['domaine'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'IDDomaine' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
         
}