<?php            
namespace applications\timestamp;

use includes\components\CommonModel;

use includes\tools\Orm;
use stdClass;
  
/**
 * class Model
 * 
 * Filters apps datas
 *
 * @param array $_info  | Table and fields structure "info".
 * @param array $_punchlist  | Table and fields structure "punchlist".
 *                  
 */
class ModelPunchtypes extends CommonModel {     

    protected $_punchlist;
            
    function __construct() 
    {
        $this->_setTables(['timestamp/builders/BuilderPunchtypes']);
        
    }
    
    
            
        
    /**
     * Select datas form the table "punchlist"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'IDPunch'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function punchlist( $params = [] ) {
    
        $orm = new Orm( 'punchlist', $this->_dbTables['punchlist'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'IDPunch' => 'ASC' ])
                        ->execute();
        
        return $result;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "punchlist".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function punchlistBuild( $id = null )
    {
        $orm = new Orm( 'punchlist', $this->_dbTables['punchlist'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IDPunch' => $id] : null;
            
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
    public function punchlistUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'punchlist', $this->_dbTables['punchlist'] );
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
                $data = $orm->update([ 'IDPunch' => $id ]);
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
    public function punchlistDelete( $id ) 
    {
        $orm = new Orm( 'punchlist', $this->_dbTables['punchlist'] );
            
        $orm->delete([ 'IDPunch' => $id ]);
        
        return true;
    } 


    public function punchlistPosition( $id ){

        $position = new Position( 'punchlist', 'OrderPunchlist' );

        $data = $this->punchlist([ 'IDPunch' => $id  ]);
            
        $position->moveUp([ 'id' => $id, 'dbFieldId' => 'IDPunch', 'dbFieldCat' => 'CategoryPunchlist', 'order' => $data[0]->OrderPunchlist ]);        

        return true;
    }
    
    
    
    public function punchlistActiveUpdate( $id = null )
    {
        return $this->_updateActive( $id, 'punchlist', 'punchlist', 'punchlist', 'IDPunch', 'punchitems', 'IsActivePunchlist');
       
    }
    
              
}