<?php
namespace applications\menus;

use includes\components\CommonModel;

use includes\tools\Orm;
use includes\Adm;
use stdClass;

/**
 * Description of Model
 *
 * @author admin
 */
class ModelGroups extends CommonModel {
    
    function __construct() {
        
        $this->_setTables(['menus/builders/BuilderGroups']);
    }
    
    
       
    public function getRights( $params = [] )
    {
        $orm = new Orm( 'group_rights' );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->execute();
        
        return $result;
    }
    
    public function getGroupsByTypes()
    {
        $groups = $this->groups();
        
        $types = [ 'participants' => [], 'managers' => [] ];
        
        foreach( $groups as $group )
        {
            if( $group->groupparticipant == 1 )
            {
                $types['participants'][] = $group->groupid;
            }
            else
            {
                $types['managers'][] = $group->groupid;
            }
        }
        
        return $types;        
    }

    
    public function groups( $params = [], $extend = false )
    {
        $orm = new Orm( 'groups', $this->_dbTables['groups'], $this->_dbTables['relations'] );
        
        $joint = ( $extend ) ? ['groups', 'fonction_group'] : [];
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->joins( $joint )
                        ->group([ 'groups' => 'groupid' ])
                        ->order([ 'groupname' => 'ASC' ])
                        ->execute();
        return $result;
    }
    
    public function group( $params = [] )
    {
        $orm = new Orm( 'groups', $this->_dbTables['groups'], $this->_dbTables['relations'] );
        
        $result = $orm  ->select()
                        ->where( $params )
                        ->first();
        return $result;
    }
    
    
    private function _groupMenuRights( $groupid )
    {
        $this->_setModels( ['menus/ModelMenus' ] );
        
        $modelMenus     = $this->_models[ 'ModelMenus' ];
        
        $rights = Adm::getRightsSymbol();
        $menus  = $modelMenus->getAdminmenu();
        
        $group = $this->group([ 'groupid' => $groupid ]);
        
        $h = 0;
        
        foreach( $menus as $headings )
        {
            if( isset( $headings[ 'menus' ] ) )
            {
                foreach( $headings[ 'menus' ] as $m => $menu )
                {
                    foreach( $rights as $r => $right )
                    {
                        $rightsVerdict = $this->getRights([ 'IdMenu'=>$menu->IdMenu, 'Rights'=>$r, 'IdGroup'=>$groupid ]);
                        $rights[ $r ] = ( isset( $rightsVerdict ) ) ? true : false;
                    }
                    $menu->landing = ( empty( $group->IdMenuLanding ) && $m === 0 && $h === 0 ) ? true : ( $group->IdMenuLanding  === $menu->IdMenu ) ? true : false;
                    $menu->rights = $rights;
                }
                $h++;
            }
        }
        return $menus;
    }
    
    public function groupsAndRights()
    {
        $datas = $this->groups();
        
        foreach( $datas as $n => $data )
        {
            $data->tableMenus = new stdClass;
            $data->tableMenus = $this->_groupMenuRights( $data->groupid );
        }
        
        return $datas;
    }
    
    
    
    public function groupActiveUpdate( $urlDatas )
    {
        $this->_setModels( ['menus/ModelMenus' ] );
        
        $modelMenus     = $this->_models[ 'ModelMenus' ];
        
        $urlStrs = explode( '-', $urlDatas );
        $nbStrs = count( $urlStrs );
        
        if( $nbStrs >= 3 )
        {
            $IdGroup    = $urlStrs[ ( $nbStrs - 1 ) ];
            $IdMenu     = $urlStrs[ ( $nbStrs - 2 ) ];
            $action     = $urlStrs[ ( $nbStrs - 3 ) ];
            
            $rights = Adm::getRightsSymbol();
            
            $actionIsValid = null;
            
            foreach( $rights as $r => $right )
            {
                if( $action === $r )
                {
                    $actionIsValid = true;
                }
            }
            
            $groupRights = $this->getRights( [ 'IdGroup'=>$IdGroup, 'IdMenu'=>$IdMenu, 'Rights'=>$action ] );
            
            $active = ( isset( $groupRights ) ) ? false : true;
            
            if( isset( $actionIsValid ) && $this->grouprights( [ 'IdGroup'=>$IdGroup, 'IdMenu'=>$IdMenu, 'Rights'=>$action ] ) )
            {
                $menu   = $modelMenus->adminmenus(['IdMenu'=>$IdMenu])[0];
                $group  = $this->groups(['groupid'=>$IdGroup])[0];
                
                return [ 'action'=>$action, 'menu'=>$menu, 'group'=>$group, 'active'=>$active ];
            }
        }
        
        return false;
       
    }
    
    
    public function groupBuild( $id = null )
    {
        $orm = new Orm( 'groups', $this->_dbTables['groups'] );
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        $params = ( isset( $id ) ) ? ['groupid' => $id] : null;
        return $orm->build( $params );
    } 
    
    
    public function groupUpdate( $action = 'insert', $id = null) 
    {
        $orm = new Orm( 'groups', $this->_dbTables['groups'] );
        
        $orm->prepareGlobalDatas( [ 'POST' => true ] );
        
        if( !$orm->issetErrors() )
        {
            if( $action === 'insert' )
            {
                $data = $orm->insert();
            }
            else if( $action === 'update' )
            {
                $data = $orm->update([ 'groupid' => $id ]);
            }

            return $data;
        }
        else
        {
            return false;
        }
    }
    
    public function groupDelete( $id ) 
    {
        $orm = new Orm( 'groups', $this->_dbTables['groups'] );
        $orm->delete([ 'groupid' => $id ] );
        
        $ormLangue = new Orm( 'group_rights', $this->_dbTables['grouprights'] );
        $ormLangue->delete([ 'IdGroup' => $id ] );
        
        return true;
    }
        
    public function grouprights( $params = [] ) 
    {
        $orm = new Orm( 'group_rights', $this->_dbTables['grouprights'] );
        
        $rights = $this->getRights( $params );
        
        if( isset( $rights ) )
        {
            $orm->delete( $params );

            return true; 
        }
        else
        {
            $orm->prepareDatas( $params );

            if( !$orm->issetErrors() )
            {
                $orm->insert();

                return true;
            }
            else
            {
                return false;
            } 
        }
    }
        
    
}