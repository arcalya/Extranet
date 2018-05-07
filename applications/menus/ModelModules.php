<?php
namespace applications\menus;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\tools\Position;
use includes\Adm;
use stdClass;

/**
 * Description of Model
 *
 * @author admin
 */
class ModelModules extends CommonModel {
 
    public function __construct() 
    {    
        $this->_setTables(['menus/builders/BuilderModules']);
    }
    
           
        
    /**
     * Select datas form the table "adminmenumodules"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IdModule'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function modules( $params = [] ) {
    
        $orm = new Orm( 'adminmenumodules', $this->_dbTables['adminmenumodules'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'NameModule' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
    
    
    public function setModuleOptions()
    {    
        return $this->_setValueOptions( $modules = $this->modules(), 'IdModule', 'NameModule' );   
    }
    
    
    /**
     * Prepare datas for the formulas 
     * depending on the table "adminmenumodules".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function modulesBuild( $id = null )
    {
        $orm = new Orm( 'adminmenumodules', $this->_dbTables['adminmenumodules'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdModule' => $id] : null;
            
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
    public function modulesUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'adminmenumodules', $this->_dbTables['adminmenumodules'] );
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
                $data = $orm->update([ 'IdModule' => $id ]);
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
    public function modulesDelete( $id ) 
    {
        $orm = new Orm( 'adminmenumodules', $this->_dbTables['adminmenumodules'] );
            
        $orm->delete([ 'IdModule' => $id ]);
        
        return true;
    } 


        
}