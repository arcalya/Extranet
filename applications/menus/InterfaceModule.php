<?php
namespace applications\menus;

use includes\components\Module;

use stdClass;


class InterfaceModule extends Module
{
    protected $_tabs;
    private $_tablehead;
    private $_tableheadmodules;
    private $_grouphead;
        
    public function __construct()
    {
        
        $this->_tabs = [
            [ 'title' => 'Menus', 'action' => '', 'url' => '/menus', 'class' => 'active' ], 
            [ 'title' => 'Modules', 'action' => 'modules', 'url' => '/menus/modules', 'class' => '' ], 
        ];
        
        $this->_tablehead = [ 'cells' => [
            [ 'title' => '#',       'colspan' => '1', 'class' => 'cell-mini' ],
            [ 'title' => 'Libellé', 'colspan' => '1', 'class' => 'cell-large' ],
            [ 'title' => 'Titre',   'colspan' => '1', 'class' => 'cell-large' ],
            [ 'title' => 'Url',     'colspan' => '1', 'class' => 'cell-large' ],
            [ 'title' => 'Ordre',   'colspan' => '1', 'class' => 'cell-small' ],
            [ 'title' => 'Publier', 'colspan' => '1', 'class' => 'cell-small' ],
            [ 'title' => 'Modifier','colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'menus', 'rightaction' => '' ],
            [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
        ] ];
        
        $this->_tableheadmodules = [ 'cells' => [
            [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
            [ 'title' => 'Module', 'colspan' => '1', 'class' => 'cell-xlarge'],
            [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'modules', 'rightaction' => '' ],
            [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
        ] ];
        
        $this->_grouphead = [ 'cells' => [
            [ 'title' => '#',           'colspan' => '1', 'class' => 'cell-mini' ],
            [ 'title' => 'Groupe',      'colspan' => '1', 'class' => 'cell-small' ],
            [ 'title' => 'Description', 'colspan' => '1', 'class' => 'cell-large' ],
            [ 'title' => 'Type',        'colspan' => '1', 'class' => 'cell-small' ],
            [ 'title' => 'Droits',      'colspan' => '1', 'class' => 'cell-xxlarge' ],
            [ 'title' => 'Modifier',    'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'menus', 'rightaction' => '' ],
            [ 'title' => 'Supprimer',   'colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
        ] ];
        
    }   
    
    
    public function getTableHead()
    {
        return $this->_tablehead;
    }
    
    public function getModulesTableHead()
    {
        return $this->_tableheadmodules;
    }
    
    public function getGroupHead()
    {
        return $this->_grouphead;
    }
    
    
      
    public function getUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'menus/ModelMenus/adminmenus', 'IdMenu', 'NameMenu' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'La rubrique <strong>'.$msgDatas[ 'updatedname' ] . '</strong> vient d\'être ajoutée.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'La rubrique <strong>'.$msgDatas[ 'updatedname' ] . '</strong> vient d\'être mise à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Une rubrique vient d\'être supprimée.' : '';
            
        }
        
        return [ 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ] ];
    }
    
    
    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $urlDatas     | Last part of the Url (Router).
     * @return array            | Return's infos to display :
     *                            'updated'       | boolean   If an interaction has been made
     *                            'updatemessage' | str       Message content
     *                            'updatedid'     | int       Id of the content inserted, updated or deleted
     */
    public function getModulesUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'menus/ModelModules/modules', 'IdModule', 'NameModule' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'Le module <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajouté.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'Le module <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mis à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Un module vient d\'être supprimé.' : '';
            
        }
        
        return [ 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ], 'alert' => $alert ];
    }
    

    /**
     * Defines what info is sent back to the uset after an  
     * interaction (insert, update or delete) has been mad with the database.
     * 
     * @param str $build     | Build datas for froms.
     * @return array            | Return's infos to display :
     *                            'updated'       | boolean   If an interaction has been made
     *                            'updatemessage' | str       Message content
     *                            'updatedid'     | int       Id of the content inserted, updated or deleted
     */
    public function getModulesFormUpdatedDatas( $build )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $updated        = false;
        
        if( isset( $build->errors ) )
        {
            $updatemessage = 'Certains champs ont été mal remplis.';
            $updated        = true;            
        }
        
        return [ 'updated' => $updated, 'updatemessage' => $updatemessage, 'updateid' => null, 'alert' => $alert ];
    }

    
    public function getUpdatedGroupDatas( $urlDatas )
    {
        $updatemessage  = '';
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'menus/ModelGroups/groups', 'groupid', 'groupname' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'Le groupe <strong>'.$msgDatas[ 'updatedname' ] . '</strong> vient d\'être ajouté.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'Le groupe <strong>'.$msgDatas[ 'updatedname' ] . '</strong> vient d\'être mis à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Un groupe vient d\'être supprimée.' : '';
            
        }
        
        return [ 'updated' => $msgDatas[ 'updated' ], 'updatemessage' => $updatemessage, 'updateid' => $msgDatas[ 'updatedid' ] ];
    }

    
    public function getMenus()
    {
        $this->_setModels( ['menus/ModelMenus' ] );
        
        $modelMenus     = $this->_models[ 'ModelMenus' ];
        
        $menus = $modelMenus->getAdminmenu();
        
        $menusList = [];
        
        foreach( $menus as $heading )
        {
            
            if( isset( $heading[ 'menus' ] ) )
            {
                $menusItems = [];
                
                foreach( $heading[ 'menus' ] as $n => $menu )
                {
                    $menusItems[] = [ 'label' => $menu->NameMenu, 'value' => $menu->IdMenu ];
                }
                
                $menusList[] = [ 'name' => $heading[ 'label' ], 'options' => $menusItems ];
            }
        }
            
        return $menusList;
    }   
    
  
    
   
}