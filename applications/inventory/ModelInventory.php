<?php
namespace applications\inventory;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Date;
use includes\Request;

use stdClass;

class ModelInventory extends CommonModel {
 
    protected $_stateArticle;
    protected $_nameEmprunt;
    
    function __construct() 
    {
        $this->_setTables(['inventory/builders/BuilderInventory']);
        
        $this->_stateArticle = [
          '1' => 'En fonction', 
          '2' => 'Désuet',
          '3' => 'Hors d\'usage', 
          '4' => 'Perdu'  
        ];
        
        $this->_nameEmprunt = [
            '1' => 'Demande',
            '2' => 'Emprunt',
            '3' => 'Rendu'
        ];
    }
    
    public function getstateArticle( $key )
    {
        return $this->_nstateArticle[ $key ];
    }
    
    public function getNameEmprunt( $key )
    {
        return $this->_nameEmprunt[ $key ];
    }
    
    public function beneficiaireDisplayEmprunt( $params = [] ) {
    
        $orm = new Orm( 'librairie_emprunts' );
        
        $time = mktime( '0', '0', '0', date('m'), date('d'), date('Y') );
        
        $results = $orm ->select()
                        ->join([ 'librairie_emprunts'=>'IdArticle', 'librairie_articles'=>'IdArticle' ])
                        ->join([ 'librairie_articles'=>'IdTypeArticle', 'librairie_types'=>'IdType' ])
                        ->join([ 'librairie_articles'=>'IdCategorie', 'librairie_categories'=>'IdCategorie' ])
                        ->where( $params )
                        ->group([ 'librairie_emprunts' => 'IdArticle' ])
                        ->order([ 'librairie_emprunts.DateFinEmprunt' => 'ASC', 'librairie_emprunts.StatutEmprunt' => 'ASC' ])
                        ->execute();
        
        if( isset( $results ) )
        {
            foreach( $results as $result )
            {
                $dateDebutEmprunt       = new Date( $result->DateDebutEmprunt, 'YYYY-MM-DD' ); 
                $result->DebutEmprunt  = $dateDebutEmprunt->get_date();
                $dateFinEmprunt         = new Date( $result->DateFinEmprunt, 'YYYY-MM-DD' );
                $result->FinEmprunt    = $dateFinEmprunt->get_date();
                $result->empruntsToLate     = ( $result->StatutEmprunt === '1' && $dateFinEmprunt->get_timestamp() < $time ) ? true : false; 
                $result->empruntsOnGoing    = ( $result->StatutEmprunt === '1' ) ? true : false; 
            }
        }
        
        return $results;
    }
    
    
    
    public function inventoryMenu( $params = [] )
    {
        $inventory = new stdClass;
        
        $inventory->materials = $this->beneficiaireDisplayEmprunt( $params );
      
        if( isset( $inventory->materials ) )
        {
            $inventory->empruntsOnGoing  = [];
            $inventory->empruntsToLate   = [];
            foreach( $inventory->materials as $n => $emprunt ) {

                $emprunt->StatutEmpruntName = $this->_nameEmprunt[ $emprunt->StatutEmprunt ];

                if( $emprunt->empruntsOnGoing )
                {
                    $inventory->empruntsOnGoing[] = $inventory->materials[ $n ]; 
                }

                if( $emprunt->empruntsToLate )
                {
                    $inventory->empruntsToLate[] = $inventory->materials[ $n ];
                }
            }

            $inventory->nbEmpruntsToLate     = count( $inventory->empruntsToLate );
            $inventory->nbEmpruntsOnGoing    = count( $inventory->empruntsOnGoing );
            $inventory->nbEmprunts           = count( $inventory->materials );
        }
        else
        {
            $inventory->nbEmpruntsToLate     = '';
            $inventory->nbEmpruntsOnGoing    = '';
            $inventory->nbEmprunts           = '';
        }
        
        return $inventory;
    }
    
    
    
    public function inventoryEmprunts( $params = [] )
    {
        
    }
    
    
    public function inventoryDetail( $articles )
    {
        if( isset( $articles ) )
        {
            foreach( $articles as $article )
            {
                $article->Historic = $this->emprunts([ 'IdArticle'=>$article->IdArticle ]);

                $article->NameStatutEmprunt = ( isset( $article->Historic ) && isset( $this->_nameEmprunt[ $article->Historic[0]->StatutEmprunt ] ) ) ?  $this->_nameEmprunt[ $article->Historic[0]->StatutEmprunt ] : '';

                $article->nbEmprunts = ( isset( $article->Historic ) ) ? count( $article->Historic ) : '';
            }

            return $articles;
        }
    }
    
    
    
    
     
    /**
     * Select datas form the table "librairie_articles"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdArticle'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function inventory( $params = [] ) {
    
        $orm = new Orm( 'librairie_articles', $this->_dbTables['librairie_articles'] );
    
        $result = $orm  ->select()
                        ->join([ 'librairie_articles'=>'IdTypeArticle', 'librairie_types'=>'IdType' ])
                        ->where( $params )
                        ->order([ 'NomArticle' => 'ASC' ])
                        ->execute( true );
        
        $result = $this->inventoryDetail( $result );
        
        return $result;
    }  
    
    
    
    
    
    
    
    
         
        
    /**
     * Prepare datas for the formulas 
     * depending on the table "librairie_articles".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function inventoryBuild( $id = null )
    {
        $orm = new Orm( 'librairie_articles', $this->_librairie_articles );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdArticle' => $id] : null;
            
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
    public function inventoryUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'librairie_articles', $this->_librairie_articles );
        $errors     = false;
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IdArticle' => $id ]);
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
    public function inventoryDelete( $id ) 
    {
        $orm = new Orm( 'librairie_articles', $this->_librairie_articles );
            
        $orm->delete([ 'IdArticle' => $id ]);
        
        return true;
    } 
    
            
        
    /**
     * Select datas form the table "librairie_categories"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdCategorie'=>1 ]
     * ¨@param boolean      | (optional)
     *                        Includes inventory items in each category
     * @return object       | Results of the selection in the database.
     */
    public function categories( $params = [], $wInventory = false ) {
    
        $orm = new Orm( 'librairie_categories', $this->_dbTables['librairie_categories'] );
        
        $results = $orm  ->select()
                        ->where( $params )
                        ->order([ 'NomCategorie' => 'ASC' ])
                        ->execute( true );
        
        if( $wInventory )
        {
            foreach( $results as $result )
            {
                $result->inventories = $this->inventory( ['IdCategorie' => $result->IdCategorie] );
            }
        }
        
        return $results;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "librairie_categories".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function categoriesBuild( $id = null )
    {
        $orm = new Orm( 'librairie_categories', $this->_librairie_categories );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdCategorie' => $id] : null;
            
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
    public function categoriesUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'librairie_categories', $this->_librairie_categories );
        $errors     = false;
        
        $orm->prepareGlobalDatas( [ 'POST' => true ] );

        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IdCategorie' => $id ]);
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
    public function categoriesDelete( $id ) 
    {
        $orm = new Orm( 'librairie_categories', $this->_librairie_categories );
            
        $orm->delete([ 'IdCategorie' => $id ]);
        
        return true;
    } 


        
    /**
     * Select datas form the table "librairie_editions"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdEdition'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function editions( $params = [] ) {
    
        $orm = new Orm( 'librairie_editions', $this->_dbTables['librairie_editions'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'IdEdition' => 'ASC' ])
                        ->execute( true );
        
        return $result;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "librairie_editions".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function editionsBuild( $id = null )
    {
        $orm = new Orm( 'librairie_editions', $this->_librairie_editions );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdEdition' => $id] : null;
            
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
    public function editionsUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'librairie_editions', $this->_librairie_editions );
        $errors     = false;
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IdEdition' => $id ]);
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
    public function editionsDelete( $id ) 
    {
        $orm = new Orm( 'librairie_editions', $this->_librairie_editions );
            
        $orm->delete([ 'IdEdition' => $id ]);
        
        return true;
    } 

        
    /**
     * Select datas form the table "librairie_emprunts"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdLivreEmprunt'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function emprunts( $params = [] ) {
    
        $orm = new Orm( 'librairie_emprunts', $this->_dbTables['librairie_emprunts'] );
        
        $result = $orm  ->select()
                        ->join([ 'librairie_emprunts'=>'IdBeneficiaireEmprunt', 'beneficiaire'=>'IDBeneficiaire' ])
                        ->where( $params )
                        ->order([ 'DateDebutEmprunt' => 'DESC' ])
                        ->execute( true );
        
        return $result;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "librairie_emprunts".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function empruntsBuild( $id = null )
    {
        $orm = new Orm( 'librairie_emprunts', $this->_librairie_emprunts );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdLivreEmprunt' => $id] : null;
            
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
    public function empruntsUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'librairie_emprunts', $this->_librairie_emprunts );
        $errors     = false;
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IdLivreEmprunt' => $id ]);
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
    public function empruntsDelete( $id ) 
    {
        $orm = new Orm( 'librairie_emprunts', $this->_librairie_emprunts );
            
        $orm->delete([ 'IdLivreEmprunt' => $id ]);
        
        return true;
    } 


            
        
    /**
     * Select datas form the table "librairie_types"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdType'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function types( $params = [] ) {
    
        $orm = new Orm( 'librairie_types', $this->_dbTables['librairie_types'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'IdType' => 'ASC' ])
                        ->execute( true );
        
        
        
        return $result;
    }   
    
    

    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "librairie_types".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function typesBuild( $id = null )
    {
        $orm = new Orm( 'librairie_types', $this->_librairie_types );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdType' => $id] : null;
            
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
    public function typesUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'librairie_types', $this->_librairie_types );
        $errors     = false;
        
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'IdType' => $id ]);
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
    public function typesDelete( $id ) 
    {
        $orm = new Orm( 'librairie_types', $this->_librairie_types );
            
        $orm->delete([ 'IdType' => $id ]);
        
        return true;
    } 

    
}