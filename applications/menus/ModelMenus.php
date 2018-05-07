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
class ModelMenus extends CommonModel {
    
    
    function __construct() {
        
        $this->_setTables(['menus/builders/BuilderMenus']);
        
    }
    
    
    public function getHeadings()
    {
        return Adm::getHeadings();
    }
    
    
    public function getAdminmenu()
    {
        $admenu = new stdClass();

        $headings   = $this->getHeadings();
        
        if( is_array( $headings ) )
        {
            foreach( $headings as $head => $heading )
            {             
                $admenu->$head = [ 'label' => $heading['label'], 'menus' => $this->adminmenus( ['HeadingMenu'=>$heading['value']] ) ];
            }
        }
        
        return $admenu;        
    }
    
    public function adminmenus( $params = [] )
    {
        $this->_setModels( ['menus/ModelModules' ] );
        
        $modelModules   = $this->_models[ 'ModelModules' ];
        
        $orm = new Orm( 'adminmenus', $this->_dbTables['adminmenus'] );
        
        $results = $orm  ->select()
                        ->where( $params )
                        ->order([ 'OrderMenu' => 'ASC' ])
                        ->execute();
        
        if( isset( $results ) )
        {
            foreach( $results as $result )
            {
                $module = $modelModules->modules([ 'IdModule'=>$result->ModuleMenu ]);
                $result->UrlMenu = ( isset( $module ) ) ? $module[ 0 ]->NameModule : '';
                $result->UrlMenu .= ( ( !empty( $result->ActionMenu ) && ( !empty( $result->UrlMenu ) ) ) ? '/'.$result->ActionMenu : '' );
            }
        }
        
        return $results;
    }
    
    
    public function adminmenuBuild( $id = null )
    {
        $orm = new Orm( 'adminmenus', $this->_dbTables['adminmenus'] );
        
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['IdMenu' => $id] : null;
                
        return $orm->build( $params );
    }
    
    
    public function adminmenuPosition( $id ){

        $position = new Position( 'adminmenus', 'OrderMenu' );

        $menu = $this->adminmenus([ 'IdMenu' => $id  ]);
            
        $position->moveUp([ 'id' => $id, 'dbFieldId' => 'IdMenu', 'dbFieldCat' => 'HeadingMenu', 'order' => $menu[0]->OrderMenu ]);        

        return true;
    }
    
    public function adminmenuActiveUpdate( $id = null )
    {
        return $this->_updateActive( $id, 'adminmenus', 'adminmenus', 'adminmenus', 'IdMenu', 'NameMenu', 'IsActiveMenu');
       
    }
    
    public function adminmenuUpdate( $action = 'insert', $id = null) 
    {
        $orm        = new Orm( 'adminmenus', $this->_dbTables['adminmenus'] );
        $position   = new Position( 'adminmenus', 'OrderMenu' );
        
        $datas = $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $ordre  = $position->getNextPosition([ 'IdSectionregion' => $datas[ 'HeadingMenu' ] ]);

                $orm->prepareDatas([ 'OrderMenu' => $ordre ]);
                $new = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $new = $orm->update([ 'IdMenu' => $id ]);
            }

            $position->updatePositions([ 'dbFieldId' => 'IdMenu', 'dbFieldCat' => 'HeadingMenu' ]);

            return $new;
        }
        else
        {
            return false;
        }
    }
    
    public function adminmenuDelete( $id ) 
    {
        $orm = new Orm( 'adminmenus', $this->_dbTables['adminmenus'] );
        $orm->delete([ 'IdMenu' => $id ] );
        
        $ormLangue = new Orm( 'group_rights' );
        $ormLangue->delete([ 'IdMenu' => $id ] );
        
        return true;
    }
    
    
}
