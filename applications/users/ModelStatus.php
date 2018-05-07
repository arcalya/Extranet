<?php
namespace applications\users;

use includes\components\CommonModel;

use includes\tools\Orm;

class ModelStatus extends CommonModel {
    
    public function __construct() 
    {
        $this->_setTables( [ 'users/builders/BuilderStatus' ] );
    }
    
    
    public function prescripteurs( $params = [] )
    {
        $orm = new Orm( 'prescripteurs' );
        
        $results = $orm->select()
                        ->where( $params )
                        ->order([ 'NomPrescripteur' => 'ASC' ])
                        ->execute( true );
        
        return $results;
    }
    
    
    
    /**
     * Select datas form the table "statuts"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdStatut'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function statuts( $params = [], $statutsSets = [])
    {
        $orm = new Orm( 'statuts', $this->_dbTables['statuts'], $this->_dbTables['relations'] );
        
        $results = $orm ->select()
                        ->joins([ 'statuts' =>['prescripteurs'] ])
                        ->where( $params )
                        ->whereoror( $statutsSets )
                        ->group([ 'statuts'=>'IdStatut' ])
                        ->order([ 'TitreStatut' => 'ASC' ])
                        ->execute( true );
        
        return $results;
    }
    
    
      
    /**
     * Prepare datas for the formulas 
     * depending on the table "statuts".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function statutsBuild( $id = null )
    {
        $orm = new Orm( 'statuts', $this->_dbTables['statuts'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdStatut' => $id] : null;
            
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
    public function statutsUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'statuts', $this->_dbTables['statuts'] );
        $errors     = false;
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        if( $orm->issetErrors() )
        {
            $errors = true;
        }
        
        if( !$errors )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IdStatut' => $id ]);
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
    public function statutsDelete( $id ) 
    {
        $orm = new Orm( 'statuts', $this->_dbTables['statuts'] );
            
        $orm->delete([ 'IdStatut' => $id ]);
        
        return true;
    } 

    
    public function statutsActiveUpdate( $id = null )
    {
        return $this->_updateActive( $id, 'statuts', 'statuts', 'statuts', 'IdStatut', 'TitreStatut', 'IsActiveStatuts');
       
    }
    


}