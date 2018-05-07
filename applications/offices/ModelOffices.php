<?php
namespace applications\offices;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\Adm;
use stdClass;
  
/**
 * class Model
 * 
 * Filters apps datas
 *
 * @param array $_fonction  | Table and fields structure "fonction".
 * @param array $_fonction_corporate  | Table and fields structure "fonction_corporate".
 * @param array $_adminmenu_office  | Table and fields structure "adminmenu_office".
 * @param array $_offices  | Table and fields structure "offices".
 *                  
 */
class ModelOffices extends CommonModel {     

            
    function __construct() 
    {
        $this->_setTables(['offices/builders/BuilderOffices']);
    
    }
           
        
    /**
     * Select datas form the table "offices"
     * 
     * @param array $param  | (optional)
     *                        Selection conditions depending on the field's name and it's value
     *                        Example : [ 'officename'=>1 ]
     * @return object       | Results of the selection in the database.
     */
    public function offices( $params = [], $extend = false ) {
    
        $orm = new Orm( 'offices', $this->_dbTables['offices'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'officename' => 'ASC' ])
                        ->execute( $extend );
        
        return $result;
    }    
         
    /**
     * Prepare datas for the formulas 
     * depending on the table "offices".
     * Manage sending. Returns settings datas and errors
     * 
     * @param int $id       | (optional) Id of the content. 
     * @return object       | Datas and errors.
     */   
    public function officesBuild( $id = null )
    {
        $orm = new Orm( 'offices', $this->_dbTables['offices'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['officeid' => $id] : null;
            
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
    public function officesUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'offices', $this->_dbTables['offices'] );
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
                $id = $data->officeid;
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'officeid' => $id ]);
            }
            
            $ormmenus = new Orm( 'adminmenu_office', $this->_dbTables['adminmenu_office'] );
            $datasmenus= $ormmenus->prepareGlobalDatas( [ 'POST' => true ] );
            $ormmenus->delete([ 'IdOffice' => $id ]);
            if( isset( $datasmenus[ 'IdMenu' ] ) && count( $datasmenus[ 'IdMenu' ] ) > 0 )
            {
                $ormmenus->prepareDatas([ 'IdOffice' => $id ]);
                $ormmenus->insert();
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
    public function officesDelete( $id ) 
    {
        $orm = new Orm( 'offices', $this->_dbTables['offices'] );
            
        $orm->delete([ 'officeid' => $id ]);
        
        $orm = new Orm( 'adminmenu_office' );
    
        $orm->delete([ 'IdOffice' => $id ]);
        
        $orm = new Orm( 'fonction_corporate' );
    
        $orm->delete([ 'IdCorporate' => $id ]);
        
        return true;
    } 

    
    public function officesActiveUpdate( $id = null )
    {
        return $this->_updateActive( $id, 'offices', 'offices', 'offices', 'officeid', 'officename', 'officeactif');
    }
    
    public function officesInterventionUpdate( $id = null )
    {
        return $this->_updateActive( $id, 'offices', 'offices', 'offices', 'officeid', 'officename', 'officeIntervention');
    }
    

    
    
    
    public function getHeadings()
    {
        return Adm::getHeadings();
    }
    
    
    
    public function adminmenu_officeBuild( $id = null )
    {
        $admenu = new stdClass();

        $orm = new Orm( 'adminmenu_office', $this->_dbTables['adminmenu_office'] );
            
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdOffice' => $id] : null;
            
        $officeBuild = $orm->builds( $params );
        
        $headings   = $this->getHeadings();
        if( is_array( $headings ) )
        {
            foreach( $headings as $head => $heading )
            {             
                $menus = [];
                $adminmenus = $this->adminmenus( ['HeadingMenu'=>$heading['value']] );
                if( isset( $adminmenus ) )
                {
                    foreach( $adminmenus as $adminmenu )
                    {
                        $checked = false;
                        
                        if( isset( $officeBuild ) )
                        {
                      
                            foreach( $officeBuild as $office )
                            {
                                if( isset( $office->IdMenu ) && $office->IdMenu === $adminmenu->IdMenu )
                                {
                                    $checked = true;
                                }
                            }
                        }
                        $menus[] = [ 'value'=>$adminmenu->IdMenu, 'label'=>$adminmenu->NameMenu, 'checked'=>$checked ];
                    }                    
                }
                
                $admenu->$head = [ 'label' => $heading['label'], 'menus' => $menus ];
            }
        }
        
        return $admenu;        
    }
    
    
    public function adminmenus( $params = [] )
    {
        $orm = new Orm( 'adminmenus' );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->order([ 'OrderMenu' => 'ASC' ])
                        ->execute();
        
        return $result;
    }
    
              
}