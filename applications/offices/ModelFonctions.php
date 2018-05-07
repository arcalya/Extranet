<?php
namespace applications\offices;

use includes\components\CommonModel;

use includes\tools\Orm;

class ModelFonctions extends CommonModel {
               
    function __construct() 
    {    
        $this->_setTables(['offices/builders/BuilderFonctions']);
    }
     
        
    /**
     * Select datas form the table "fonction"
     * 
     * @see Orm Class
     * @param array $params   | (optional) Conditions. ['FieldName'=>value]
     * @param array $paramsor | (optional) Or Conditions 
     * @param boolean $otherparams | (optional) [ 'joins' => false, 'extend' => true, 'limit' => [ 'num' => 0, 'nb' => 10 ] ]
     * 
     * 
     * @return object       | Results of the selection in the database.
     */
    public function fonctions( $params = [], $paramsor = [], $otherparams = [] ) {
    
        $orm = new Orm( 'fonction', $this->_dbTables['fonction'], $this->_dbTables['relations'] );
        
        $joint = ( isset( $otherparams['joins'] ) && $otherparams['joins'] ) ? ['fonction', 'fonction_group'] : [];
        
        $extend = ( isset( $otherparams['extend'] ) ) ? $otherparams['extend'] : false;
        
        $limit = ( !isset( $otherparams[ 'limit' ][ 'num' ] ) || !isset( $otherparams[ 'limit' ][ 'nb' ] ) ) ? [] : [ 'num' => $otherparams[ 'limit' ][ 'num' ], 'nb' => $otherparams[ 'limit' ][ 'nb' ] ];
        
        $result = $orm  ->select()
                        ->joins( $joint )
                        ->where( $params )
                        ->whereoror( $paramsor )
                        ->group(['fonction'=>'IDFonction'])
                        ->order([ 'fonction.NomFonction' => 'ASC' ])
                        ->limit( $limit )
                        ->execute( $extend );
        
        return $result;
    }    
    
    
    
    public function fullFonctions( $params = [], $paramsor = [], $otherparams = [ 'joins' => false, 'extend' => true, 'limit' => [ 'num' => 0, 'nb' => 10 ] ] )
    {
        $fonctions = $this->fonctions( $params, $paramsor, $otherparams );
        
        foreach( $fonctions as $fonction )
        {
            $fonction->offices = $this->fonction_corporate([ 'IdFonction' => $fonction->IDFonction ]);
        }
                
        return $fonctions;
    }
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "fonction".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function fonctionBuild( $id = null )
    {
        $orm = new Orm( 'fonction', $this->_dbTables['fonction'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDFonction' => $id] : null;
            
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
    public function fonctionUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'fonction', $this->_dbTables['fonction'] );
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
                
                $id = $data->IDFonction;
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IDFonction' => $id ]);
            }
            
            
            $ormcorporate  = new Orm( 'fonction_corporate', $this->_dbTables['fonction_corporate'] );
            $datascorporate= $ormcorporate->prepareGlobalDatas( [ 'POST' => true ] );
            $ormcorporate->delete([ 'IdFonction' => $id ]);
            if( isset( $datascorporate[ 'IdCorporate' ] ) && count( $datascorporate[ 'IdCorporate' ] ) > 0 )
            {
                $ormcorporate->prepareDatas([ 'IdFonction' => $id ]);
                $ormcorporate->insert();
            }
            
            $ormgroup  = new Orm( 'fonction_group', $this->_dbTables['fonction_group'] );
            $datasgroup= $ormgroup->prepareGlobalDatas( [ 'POST' => true ] );
            $ormgroup->delete([ 'IdFonction' => $id ]);
            if( isset( $datasgroup[ 'IdGroup' ] ) && count( $datasgroup[ 'IdGroup' ] ) > 0 )
            {
                $ormgroup->prepareDatas([ 'IdFonction' => $id ]);
                $ormgroup->insert();
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
    public function fonctionDelete( $id ) 
    {
        $orm = new Orm( 'fonction', $this->_dbTables['fonction'] );
            
        $orm->delete([ 'IDFonction' => $id ]);
        
        return true;
    } 

    
    public function fonctionActiveUpdate( $id = null )
    {
        return $this->_updateActive( $id, 'fonction', 'fonction', 'fonction', 'IDFonction', 'NomFonction', 'StatutFonction');
       
    }
    
    
    
    /**
     * Select datas form the table "fonction_corporate"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdFonction'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function fonction_corporate( $params = [] ) {
    
        $orm = new Orm( 'fonction_corporate', $this->_dbTables['fonction_corporate'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->join(['fonction_corporate'=>'IdCorporate', 'offices'=>'officeid'])
                        ->group(['fonction_corporate'=>'IdFonction'])
                        ->order([ 'IdFonction' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "fonction_corporate".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function fonction_corporateBuild( $id = null )
    {
        $this->_setModels( [ 'offices/ModelOffices' ] );
        
        $modelOffices   = $this->_models[ 'ModelOffices' ];
        
        $offices = $modelOffices->offices();
        
        $officeList = [];
        
        $orm = new Orm( 'fonction_corporate', $this->_dbTables['fonction_corporate'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdFonction' => $id] : null;
            
        $fonctionBuild = $orm->builds( $params );
        
        if( isset( $offices ) )
        {
            foreach ( $offices as $office )
            {
                $checked = false;
                if( isset( $fonctionBuild ) )
                {
                    // Check if checked is detected with $fonctionBuild
                    foreach( $fonctionBuild as $fonction )
                    {
                        //var_dump( $fonctionBuild );
                        if( isset( $fonction->IdCorporate ) && $fonction->IdCorporate === $office->officeid )
                        {
                            $checked = true;
                        }
                    }
                }
                
                $officeList[] = ['value' => $office->officeid, 'label'=>$office->officename, 'checked' => $checked ];
            }
        }
        
        return $officeList;
    }
    
    
    
    /**
     * Select datas form the table "fonction_group"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdFonction'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function fonction_group( $params = [] ) {
    
        $orm = new Orm( 'fonction_group', $this->fonction_group );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->join(['fonction_group'=>'IdGroup', 'groups'=>'groupid'])
                        ->order([ 'IdGroup' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "fonction_corporate".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function fonction_groupBuild( $id = null )
    {
        $ormGroup = new Orm( 'groups' );
        
        $groups = $ormGroup->select()->execute();
        
        $officeList = [];
        
        $orm = new Orm( 'fonction_group', $this->_dbTables['fonction_group'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
       
        $params = ( isset( $id ) ) ? ['IdFonction' => $id] : null;
            
        $fonctionBuild = $orm->builds( $params );
        
        if( isset( $groups ) )
        {
            foreach ( $groups as $group )
            {
                $checked = false;
                
                if( isset( $fonctionBuild ) )
                {
                    // Check if checked is detected with $fonctionBuild
                    foreach( $fonctionBuild as $fonction )
                    {
                        if( isset( $fonction->IdGroup ) && $fonction->IdGroup === $group->groupid )
                        {
                            $checked = true;
                        }
                    }
                }
                
                $officeList[] = ['value' => $group->groupid, 'label'=>$group->groupname, 'checked' => $checked ];
            }
        }
        
        return $officeList;
    }
        
    
}