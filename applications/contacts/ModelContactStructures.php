<?php
namespace applications\contacts;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;

class ModelContactStructures extends CommonModel {
    
    
    public function __construct() 
    {
        $this->_setTables(['contacts/builders/BuilderContactStructures']);
    }
    
    
    public function getContactstructuresByCantons( $params = [], $paramsAndOr = [] )
    {
        $structureList = [];
        
        $this->_setModels(['contacts/ModelContactStructures', 'system/ModelSystem']);
        
        $modelContactStructures = $this->_models['ModelContactStructures'];
        $modelSystem            = $this->_models['ModelSystem'];
        
        $cantons = $modelSystem->cantons();
        
        if( isset( $cantons ) )
        {
            foreach( $cantons as $canton )
            {
                $params['contactstructures.IdCanton'] = $canton->IDCanton;
                
                $structures = $modelContactStructures->contactstructures( $params, $paramsAndOr );

                if( isset( $structures ) )
                {
                    $cantonStructures = [];
                    foreach( $structures as $structure )
                    {             
                        $cantonStructures[] = ['value' => $structure->IdStructure, 'label'=>$structure->NomStructure.( ( !empty( $structure->LocaliteStructure ) ) ? ' ('.$structure->LocaliteStructure.')' : '' ) ];
                    }
                    $structureList[] = ['options'=>$cantonStructures, 'name'=>$canton->NomCanton];
                }
            }
        }
        
        return $structureList;    
    }
    
    
    public function getContactstructures( $params = [], $paramsAndOr = [] )
    {
        $structureList = [];
        
        $this->_setModels(['contacts/ModelContactStructures']);
        
        $modelContactStructures = $this->_models['ModelContactStructures'];
        
        $structures = $modelContactStructures->contactstructures( $params, $paramsAndOr );

        if( isset( $structures ) )
        {
            foreach( $structures as $structure )
            {             
                $structureList[] = ['value' => $structure->IdStructure, 'label'=>$structure->NomStructure.( ( !empty( $structure->LocaliteStructure ) ) ? ' ('.$structure->LocaliteStructure.')' : '' ) ];
            }
        }
        return $structureList;    
    }
    
     public function getTypesStructures( $params = [], $paramsAndOr = [] ) //base relationnelle "contactstructure_type"
    {
        $typeStructureList = [];
        
        $typesStructures = $this->contactstructure_type( $params, $paramsAndOr );

        if( isset( $typesStructures ) )
        {
            foreach( $typesStructures as $typeStructure )
            {             
                $typeStructureList[] = ['value' => $typeStructure->IdTypeStructure, 'label'=>$typeStructure->TitreTypeStructure ];
            }
        }
        return $typeStructureList;    
    }
    
    
    public function getTypeStructureCategories( $params = [], $paramsAndOr = [] ) //base "contacttypestructure"
    {
        $typeStructureList = [];
        
        $typesStructures = $this->contactstructure_type( $params, $paramsAndOr );

        if( isset( $typesStructures ) )
        {
            foreach( $typesStructures as $typeStructure )
            {             
                $typeStructureList[] = ['value' => $typeStructure->IdTypeStructure, 'label'=>$typeStructure->TitreTypeStructure ];
            }
        }
        return $typeStructureList;    
    }
            
    
    public function contactstructures( $params = [], $paramsAndOr = [] ) //Requête de sélection des structures
    {
        $orm = new Orm( 'contactstructures', $this->_dbTables['contactstructures'], $this->_dbTables['relations'] );
        
        $results = $orm ->select()
                        ->joins([ 'contactstructures' ])
                        ->where( $params )
                        ->whereandor( $paramsAndOr )
                        ->order([ 'NomStructure' => 'ASC' ])
                        ->execute( true );
        
        return $results;        
    }
    
    
    public function contactstructure_type( $params = [] )  //Requête de sélection des relations entre type et structures
    {
        $orm = new Orm( 'contactstructure_type', $this->_dbTables['contactstructure_type'], $this->_dbTables['relations'] );
        
        $results = $orm ->select()
                        ->joins(['contactstructure_type'])
                        ->where( $params )
                        ->group(['contactstructure_type' => 'IdTypeStructure'])
                        ->order(['TitreTypeStructure' => 'ASC', 'NomStructure' => 'ASC'])
                        ->execute();

        return $results;        
    }
    
    public function contacttypestructure($params = []) //Requête de sélection des types de structures uniquement
    {
        
        $orm =  new Orm( 'contacttypestructure', $this->_dbTables['contacttypestructure'], $this->_dbTables['relations'] );
       
        $results = $orm ->select()
                        ->joins(['contacttypestructure'])
                        ->where( $params )
                        ->order(['TitreTypeStructure' => 'ASC'])
                        ->execute( true );

        
        
        return $results;        
        
    }
     
     public function structureUpdate( $action = 'insert', $id = null) 
    {
        $orm = new Orm( 'contactstructures', $this->_dbTables['contactstructures'] );
       
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                
                
                
                //Insère les données dans contactstructures
                $data = $orm->insert(); 
                
                $id = $data->IdStructure;
                
                $ormType = new Orm( 'contactstructure_type', $this->_dbTables['contactstructure_type'] );
                
                $datasType = $ormType->prepareGlobalDatas([ 'POST' => true]); //Ne prend pas en compte IdStructure ??????????
                
                $ormType->prepareDatas([ 'IdStructure' => $id]);
                
                $dataType = $ormType->insert();
            }
            else if( $action === 'update' )
            {
                
                //Met à jour les données dans contactstructures
                $data = $orm->update([ 'IdStructure' => $id ]);
                
                //Met à jour les données liées (ID type de structure) dans contactstructure_type    
                $ormType = new Orm( 'contactstructure_type', $this->_dbTables['contactstructure_type'] );
                
                $datasType = $ormType->prepareGlobalDatas([ 'POST' => true ]);
                
                $ormType->prepareDatas([ 'IdStructure' => $id]);
                
                $dataType = $ormType->update([ 'IdStructure' => $id ]);
            }
            
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function structureDelete($id = null)
    {
        $orm = new Orm( 'contactstructures', $this->_dbTables['contactstructures'] );
        
        $orm->delete(['IdStructure' => $id]);
        
        $ormType = new Orm ('contactstructure_type', $this->_dbTables['contactstructure_type']);
        
        $ormType->delete(['IdStructure' => $id]);
        
        return true;
        
    }
    
    
     public function typeStructureCategoryUpdate( $action = 'insert', $id = null) 
    {
        $orm = new Orm( 'contacttypestructure', $this->_dbTables['contacttypestructure'] );
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IdTypeStructure' => $id ]);
            }
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function typeStructureCategoryDelete($id = null)
    {
        $orm = new Orm( 'contacttypestructure', $this->_dbTables['contacttypestructure'] );
        
        $orm->delete(['IdTypeStructure' => $id]);
        
        return true;
        
    }
    
    /**
     * Prepare datas for the formulas 
     * depending on the table "contacts".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function contactStructuresBuild( $id = null )
    {
        $orm = new Orm( 'contactstructures', $this->_dbTables['contactstructures'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdStructure' => $id] : null;
            
        return $orm->build( $params );
    }
    
    public function contactStructureTypeBuild( $idStructure = null ){ //Builder relations structures / types (FK)
        
        $orm = new Orm( 'contactstructure_type', $this->_dbTables['contactstructure_type'] );
        
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $idStructure ) ) ? ['IdStructure' => $idStructure] : null;
            
        return $orm->build( $params );
        
        
    }
    
    public function contactTypesStructuresBuild( $id = null )
    {
        $orm = new Orm( 'contacttypestructure', $this->_dbTables['contacttypestructure'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdTypeStructure' => $id] : null;
            
        return $orm->build( $params );
    }
    
    
}
