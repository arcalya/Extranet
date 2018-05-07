<?php
namespace applications\timestamp;

use includes\components\Module;


class InterfaceModule extends Module
{
    protected $_tableheadpunchlist;
    protected $_tabs;
    
    public function __construct()
    {
        $this->_tableheadpunchlist = [ 'cells' => [
                [ 'title' => '#', 'colspan' => '1', 'class' => 'cell-mini' ],
                [ 'title' => 'punchitems', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'color', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'in_or_out', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'absence', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'sigle', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'lien', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'horairefixe', 'colspan' => '1', 'class' => 'cell-mini'],
                [ 'title' => 'Modifier', 'colspan' => '1', 'class' => 'cell-small', 'right' => 'update', 'rightmenu' => 'timestamp', 'rightaction' => '' ],
                [ 'title' => 'Supprimer','colspan' => '1', 'class' => 'cell-small', 'right' => 'delete', 'rightaction' => '' ]
            ] ];
        
        
        $this->_tabs = [
                'timestamp'    => [ 'title' => 'Timbreuse',             'action' => 'timestamp',        'url' => '/timestamp',              'class' => 'active' ], 
                'reports'      => [ 'title' => 'Rapport d\'heures',     'action' => 'reports',          'url' => '/timestamp/reports',      'class' => '' ], 
                'absencesgrid'  => [ 'title' => 'Absences',              'action' => 'absencesgrid',      'url' => '/timestamp/absencesgrid',  'class' => '' ], 
                'punchlist'   => [ 'title' => 'Configuration - pointeurs',  'action' => 'punchlist',  'url' => '/timestamp/punchlist',   'class' => '' ], 
                'confaccess'   => [ 'title' => 'Configuration - accès',      'action' => 'confaccess',  'url' => '/timestamp/confaccess',   'class' => '' ]
        ];
        
    }   
    
    
    public function getPunchlistTableHead()
    {
        return $this->_tableheadpunchlist;
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
    public function getInfoUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'timestamp/ModelPuchs/info', 'fullname', 'fullname' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'La rubrique <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajoutée.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'La rubrique <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mise à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Une rubrique vient d\'être supprimée.' : '';
            
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
    public function getInfoFormUpdatedDatas( $build )
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
    public function getpunchlistUpdatedDatas( $urlDatas )
    {
        $updatemessage  = '';

        $alert          = 'success'; // 'success', 'info', 'warning', 'danger' 
        
        $msgDatas = $this->_updatedMsgDatas( $urlDatas, 'punchlist', 'IDPunch', 'punchitems' );

        if( $msgDatas[ 'updated' ] )
        {
            $updatemessage .= ( $msgDatas[ 'action' ] === 'successinsert' ) ? 'La rubrique <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être ajoutée.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successupdate' ) ? 'La rubrique <strong><a href="#'.$msgDatas[ 'updatedid' ].'">'.$msgDatas[ 'updatedname' ] . '</a></strong> vient d\'être mise à jour.' : '';

            $updatemessage .= ( $msgDatas[ 'action' ] === 'successdelete' ) ? 'Une rubrique vient d\'être supprimée.' : '';
            
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
    public function getpunchlistFormUpdatedDatas( $build )
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

    
}